<?php

class auth {

    protected $app;
    protected $module;

    protected $isLogged = false;
    protected $user = null;

    public function __construct($app, $module) {
        $this->app = $app;
        $this->module = $module;
    }

    public function login($email, $password, $remember = 0) {

        $return['error'] = true;

        if($this->isBlocked()) {
            $return['message'] = 'user blocked for '.$this->module->config('attempts')['ban'];
            return $return;
        }

        $validateEmail = $this->validateEmail($email);

        if($validateEmail['error']) {
            $this->addAttempt();
            $return['message'] = $validateEmail['message'];
            return $return;
        }

        $userID = $this->getID(strtolower($email));

        if(!$userID) {
            $this->addAttempt();
            $return['message'] = 'email not found';
            return $return;
        }

        $user = $this->getBaseUser($userID);

        if(!$this->passwordRehash($password, $user['password'], $userID)) {
            $this->addAttempt();
            $return['message'] = 'incorrect password';
            return $return;
        }

        $session = $this->addSession($user['id'], $remember);

        $this->app->hook::run('auth.login', $this->app, $this);

        $return['error'] = false;

        return $return;

    }

    public function getID($email) {
        $user = sql::run()->from($this->module->config('table'))->where('email', $email)->fetch();
        if(!$user) {
            return false;
        }
        return $user['id'];
    }

    protected function addSession($userID, $remember) {

        $user = $this->getBaseUser($userID);
        if(!$user) {
            return false;
        }

        $data['hash'] = sha1($this->module->config('session')['key'].microtime());
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $this->deleteExistingSessions($userID);

        if($remember == true) {
            $data['expire'] = strtotime($this->module->config('session')['remember']);
        } else {
            $data['expire'] = strtotime($this->module->config('session')['time']);
        }

        $data['cookie'] = sha1($data['hash'].$this->module->config('session')['key']);

        $session = sql::run()->insertInto($this->module->config('session')['table'])->values([
            'userID' => $userID,
            'hash' => $data['hash'],
            'expire' => date("Y-m-d H:i:s", $data['expire']),
            'ip' => $this->getIP(),
            'agent' => $agent,
            'cookie' => $data['cookie']
        ])->execute();

        if(!$session) {
            return false;
        }

        setcookie($this->module->config('session')['cookieName'], $data['hash'], $data['expire'], '/');
        $_COOKIE[$this->module->config('session')['cookieName']] = $data['hash'];

        return $data;
    }

    protected function deleteExistingSessions($userID) {
        return sql::run()->deleteFrom($this->module->config('session')['table'])->where('userID', $userID)->execute();
    }

    protected function deleteSession($hash) {
        return sql::run()->deleteFrom($this->module->config('session')['table'])->where('hash', $hash)->execute();
    }

    public function checkSession($hash) {
        if($this->isBlocked()) {
            $return['message'] = 'user blocked for '.$this->module->config('attempts')['ban'];
            return false;
        }
        if(strlen($hash) != 40) {
            return false;
        }
        $session = sql::run()->from($this->module->config('session')['table'])->where('hash', $hash)->fetch();
        if(!$session) {
            return false;
        }
        $sessionID = $session['id'];
        $userID = $session['userID'];
        $expire = strtotime($session['expire']);
        $current = strtotime(date("Y-m-d H:i:s"));
        if($current > $expire) {
            $this->deleteExistingSessions($userID);
            return false;
        }
        if($this->getIP() != $session['ip']) {
            return false;
        }
        if($session['cookie'] == sha1($hash.$this->module->config('session')['key'])) {
            return true;
        }
        return false;
    }

    public function getSessionID($hash) {
        $session = sql::run()->from($this->module->config('session')['table'])->where('hash', $hash)->fetch();
        if(!$session) {
            return false;
        }
        return $session['userID'];
    }

    public function getUser($id) {
        $user = sql::run()->from($this->module->config('table'))->where('id', $id)->fetch();
        if(!$user) {
            return false;
        }
        unset($user['password']);
        return $user;
    }

    public function addUser($email, $password) {

        $return['error'] = true;

        $email = htmlentities(strtolower($email));
        $password = $this->getHash($password);

        //check some things

        $return['error'] = false;

        return sql::run()->insertInto($this->module->config('table'))->values([
            'email' => $email,
            'password' => $password
        ])->execute();

    }

    protected function getProtectedUser($id) {
        $user = sql::run()->from($this->module->config('table'))->where('id', $id)->fetch();
        if(!$user) {
            return false;
        }
        return $user;
    }

    public function isLogged() {
        if(!$this->isLogged) {
            $this->isLogged = $this->checkSession($this->getSessionHash());
        }
        return $this->isLogged;
    }

    protected function getIP() {
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
           return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
           return $_SERVER['REMOTE_ADDR'];
        }
    }

    public function isBlocked() {
        $ip = $this->getIP();
        $this->deleteAttempts($ip, false);
        $attempts = sql::run()->from($this->module->config('attempts')['table'])->where('ip', $ip)->fetchAll();
        if(count($attempts) < intval($this->module->config('attempts')['count'])) {
            return false;
        }
        return true;
    }

    protected function validateEmail($email) {
        $return['error'] = true;
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $return['message'] = 'invalid email';
            return $return;
        }
        $return['error'] = false;
        return $return;
    }

    protected function addAttempt() {
        $ip = $this->getIP();
        $expire = date("Y-m-d H:i:s", strtotime($this->module->config('attempts')['ban']));
        return sql::run()->insertInto($this->module->config('attempts')['table'])->values([
            'ip' => $ip,
            'expire' => $expire
        ])->execute();
    }

    protected function deleteAttempts($ip, $all = false) {
        if($all) {
            return sql::run()->deleteFrom($this->module->config('attempts')['table'])->where('ip', $ip)->execute();
        }
        $attempts = sql::run()->from($this->module->config('attempts')['table'])->where('ip', $ip)->fetchAll();
        foreach($attempts as $attempt) {
            $expiredate = strtotime($attempt['expire']);
            $currentdate = strtotime(date("Y-m-d H:i:s"));
            if($currentdate > $expiredate) {
                sql::run()->deleteFrom($this->module->config('attempts')['table'])->where('id', $attempt['id'])->execute();
            }
        }
    }

    public function getSessionHash(){
        $cookieName = $this->module->config('session')['cookieName'];
        return isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : false;
    }

    public function getCurrentUser() {
        if(is_null($this->user)) {
            $hash = $this->getSessionHash();
            if($hash === false) {
                return false;
            }
            $id = $this->getSessionID($hash);
            if($id === false) {
                return false;
            }
            $this->user = $this->getUser($id);
        }
        return $this->user;
    }

    public function getHash($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    public function comparePassword($id, $password) {
        $user = sql::run()->from($this->module->config('table'))->where('id', $id)->fetch();
        if(!$user) {
            return false;
        }
        return password_verify($password, $user['password']);
    }

    public function passwordRehash($password, $hash, $id) {
        if(!password_verify($password, $hash)) {
            return false;
        }
        if(password_needs_rehash($hash, PASSWORD_DEFAULT, ['cost' => 10])) {
            $hash = $this->getHash($password);
            sql::run()->update($this->module->config('table'))->set('password', $hash)->where('id', $id)->execute();
        }
        return true;
    }

}

?>
