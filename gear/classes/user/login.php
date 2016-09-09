<?php

class userLogin extends user {

    public function __construct() {

        if(!userSession::loggedIn()) {
            userSession::destroy();
        } elseif(!userSession::exists()) {
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

    protected static function setSession($userID, $remember) {

        userSession::init();

        session_regenerate_id(true);

        type::addSession('userID', $userID);
        type::addSession('user_logged_in', true);

        userSession::update($userID, session_id(), $remember);

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

            if(!password_verify(type::post('password'), $query->password)) {
                message::error(lang::get('wrong_pw'));
                return;
            }

            if($query->status != 1) {
                echo message::error(lang::get('user_blocked'));
                return;
            }

            parent::setUser($query->id);

            self::setSession($query->id, $remember);

        } else {

            echo $is_valid[1];

        }

    }

    public static function logout() {

        $userID = type::session('userID');

        userSession::delete($userID, session_id());
        userSession::destroy();

        header('location: '.url::admin());

        exit();

    }

}

?>
