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
        $user = $this->app->db->from($this->module->config('table'))->where('email', $email)->fetch();
        if(!$user) {
            return false;
        }
        return $user->id;
    }

    public function getUser($id) {
        $user = $this->app->db->from($this->module->config('table'))->where('id', $id)->fetch();
        if(!$user) {
            return false;
        }
        return $user;
    }

    public function addUser($email, $hash) {

        $return['error'] = true;

        $email = htmlentities(strtolower($email));

        //check some things

        $return['error'] = false;

        return $this->app->db->insertInto($this->module->config('table'))->values([
            'email' => $email,
            'password' => $hash
        ])->execute();

    }

}

?>
