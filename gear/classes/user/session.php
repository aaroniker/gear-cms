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

    public static function update($userID = 0, $sessionID = null, $remember) {

        if($userID && !ajax::is()) {

            $model = new UserModel($userID);

            $sessions = unserialize($model->session_ids);

            if(!is_array($sessions)) {
                $sessions = [];
            }

            $modify = ($remember) ? config::get('session_remember') : config::get('session_expiration');

            $expiration = (new DateTime('now'))->modify($modify)->format('U');

            $browser = config::getBrowser();

            $sessions[$sessionID] = [
                'expiration' => $expiration,
                'info' => $browser['name']." ".$browser['version']."(".$browser['platform'].")",
                'ua' => $browser['userAgent']
            ];

            $vars = [
                'session_ids' => serialize($sessions)
            ];

            $model->save($vars);

        }

    }

    public static function delete($userID = 0, $sessionID = null) {

        if($userID && !ajax::is()) {

            $model = new UserModel($userID);

            $sessions = unserialize($model->session_ids);

            unset($sessions[$sessionID]);

            $vars = [
                'session_ids' => serialize($sessions)
            ];

            $model->save($vars);

        }

    }

    public static function exists() {

        $sessionID = session_id();
        $userID = type::session('userID', 'int', 0);

        if($userID && isset($sessionID)) {

            $model = new UserModel($userID);

            $sessions = unserialize($model->session_ids);

            if(!isset($sessions[$sessionID])) {
                return false;
            }

            $now = (new DateTime('now'))->format('U');

            if($sessions[$sessionID]['expiration'] < $now) {
                return false;
            }

            return true;

        }

        return false;

    }

    public static function loggedIn() {

        return type::session('user_logged_in', 'bool', false);

    }

}

?>
