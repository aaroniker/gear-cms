<?php

class user {

    protected $app;
    protected $module;

    public function __construct($app, $module) {

        $this->app = $app;
        $this->module = $module;

        return $this;

    }

    public function getID($email) {
        $userID = $this->app->db->get($this->module->config('table'), 'id', [
            'email' => $email
        ]);
        if(!$userID) {
            return false;
        }
        return $userID;
    }

    public function getUser($id) {
        $user = $this->app->db->get($this->module->config('table'), '*', [
            'id' => $id
        ]);
        if(!$user) {
            return false;
        }
        return $user;
    }

    public function getUsers() {
        return $this->app->db->get($this->module->config('table'), '*');
    }

    public function addUser($username, $email, $hash) {

        $return['error'] = true;

        $email = htmlentities(strtolower($email));

        //check some things

        $return['error'] = false;

        return $this->app->db->insert($this->module->config('table'), [
            'email' => $email,
            'username' => $username,
            'password' => $hash
        ]);

    }

}

?>
