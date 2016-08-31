<?php

class userLogin extends user {

    public function __construct() {

        self::loginCookie(type::cookie('remember_me'));

        if(!userSession::loggedIn()) {
            userSession::destroy();
        } elseif(userSession::isConcurrentExists()) {
            self::logout();
        } else {
            parent::setUser(type::session('userID'));
        }

        if(type::get('logout', 'int', 0) && !type::post('login', 'string', '')) {
            self::logout();
        }

        if(type::post('login', 'string', '')) {
            self::loginPost();
        }

    }

    protected static function setSession($userID) {

        userSession::init();

        session_regenerate_id(true);

        type::addSession('userID', $userID);
        type::addSession('user_logged_in', true);

        userSession::update($userID, session_id());

    }

    protected static function setCookie($userID) {

        $random_token = hash('sha256', mt_rand());

        $model = new UserModel($userID);

        $vars = [
            'cookie_token' => $random_token
        ];

        $model->save($vars);

        $cookie_first_part = encryption::encrypt($userID).':'.$random_token;
        $cookie_hash = hash('sha256', $userID.':'.$random_token);
        $cookie = $cookie_first_part.':'.$cookie_hash;

        type::setCookie('remember_me', $cookie, time() + 3600 * 24 * 7);

    }

    protected static function loginPost() {

        $remember = type::post('remember', 'int', 0);

        $is_valid = validate($_POST);

        if($is_valid[0]) {

            $query = db()->from('user')->where('email', type::post('email'))->fetch();

            if(!$query) {
                message::error(lang::get('email_not_found'));
                return;
            }

            if(!self::checkPassword(type::post('password'), $query->password)) {
                message::error(lang::get('wrong_pw'));
                return;
            }

            if($query->status != 1) {
                echo message::error(lang::get('user_blocked'));
                return;
            }

            parent::setUser($query->id);

            self::setSession($query->id);

            if($remember) {
                self::setCookie($query->id);
            }

        } else {

            echo $is_valid[1];

        }

    }

    protected static function loginCookie($cookie) {

        if($cookie) {

            if(count(explode(':', $cookie)) !== 3) {
                return false;
            }

            list($userID, $token, $hash) = explode(':', $cookie);

            $userID = encryption::decrypt($userID);

            if($hash !== hash('sha256', $userID.':'.$token) || empty($token) || empty($userID)) {
                return false;
            }

            $model = new UserModel($userID);

            if($model->cookie_token == $token) {

                self::setSession($userID);

                return true;

            }

        }

        return false;

    }

    protected static function delCookie($userID) {

        $model = new UserModel($userID);

        $vars = [
            'cookie_token' => null
        ];

        $model->save($vars);

        type::deleteCookie('remember_me');

    }

    public static function logout() {

        $userID = type::session('userID');

        userSession::destroy();
        userSession::update($userID);

        self::delCookie($userID);

        theme::addJSCode('
            $.session.clear();
        ');

        header('location: '.url::admin());

        exit();

    }

    public static function checkPassword($password, $hash) {

        return password_verify($password, $hash);

    }

}

?>
