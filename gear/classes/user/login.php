<?php

class userLogin extends user {

    public function __construct() {

        if(parent::loggedIn()) {
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

        type::addSession('userID', $userID);
        type::addSession('user_logged_in', true);

    }

    protected static function loginPost() {

        $is_valid = validate($_POST);

        if($is_valid[0]) {

            $query = sql::run()->from('user')->where('email', type::post('email'))->fetch();

            if(!$query) {
                message::error(lang::get('email_not_found'));
                return;
            }

            if(!password_verify(type::post('password'), $query->password)) {
                message::error(lang::get('wrong_pw'));
                return;
            }

            if($query->status != 1) {
                echo message::error(lang::get('user_blocked'));
                return;
            }

            parent::setUser($query->id);

            self::setSession($query->id);

        } else {

            echo $is_valid[1];

        }

    }

    public static function logout() {

        type::deleteSession('userID');
        type::deleteSession('user_logged_in');

        header('location: '.url::admin());

        exit();

    }

}

?>
