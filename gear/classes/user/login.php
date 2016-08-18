<?php

class userLogin extends user {

    static protected $isLogin = false;

    public function __construct() {

        if(type::get('logout', 'int', false) && !type::post('login', 'int', false)) {

            self::logout();

        } elseif(self::checkLogin()) {

            self::loginSession();

        } elseif(type::post('login', 'string', false)) {

            self::loginPost();

        }

    }

    protected static function loginSession() {
        self::$isLogin = true;
    }

    protected static function checkLogin() {

        $session = type::session('login', 'int', '');
        $cookie = type::cookie('remember', 'int', '');

        if(!$session && !$cookie)
            return false;

        self::loginSession();

        parent::setUser(($session) ? $session : $cookie);

        return true;

    }

    protected static function loginPost() {

        $remember = type::post('remember', 'int', '');

        $is_valid = validate($_POST);

        if($is_valid[0]) {

            $query = db()->from('user')->where('email', type::post('email'))->fetch();

            if(!$query) {

                echo message::error(lang::get('email_not_found'));
                return;

            }

            if(!self::checkPassword(type::post('password'), $query->password)) {

                echo message::error(lang::get('wrong_pw'));
                return;

            }

            if($query->status != 1) {

                echo message::error(lang::get('user_blocked'));
                return;

            }

            self::loginSession();
            parent::setUser($query->id);

            type::addSession('login', $query->id);

            if($remember) {
                type::setCookie("remember", $query->id, time() + 3600 * 24 * 7);
            }

        } else {

            echo $is_valid[1];

        }

    }

    public static function logout() {

        self::$isLogin = false;

        type::deleteSession('login');
        type::setCookie('remember', '', time() - 3600);

        echo message::success(lang::get('logged_out'));

    }

    public static function checkPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public static function isLogged() {
        return self::$isLogin;
    }

}

?>
