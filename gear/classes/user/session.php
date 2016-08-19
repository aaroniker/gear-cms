<?php

class userSession extends user {

    public static function init() {

        if(session_id() == '') {
            session_start();
        }

    }

    public static function destroy() {

        session_destroy();

    }

    public static function update($userID = 0, $sessionID = null) {

        if($userID) {

            $model = new UserModel($userID);

            $vars = [
                'session_id' => $sessionID
            ];

            $model->save($vars);

        }

    }

    public static function isConcurrentExists() {

        $sessionID = session_id();
        $userID = type::session('userID', 'int', 0);

        if($userID && isset($sessionID)) {

            $model = new UserModel($userID);

            return $sessionID !== $model->session_id;

        }

        return false;

    }

    public static function loggedIn() {

        return type::session('user_logged_in', 'bool', false);

    }

}

?>
