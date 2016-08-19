<?php

class userSession {

    public static function init() {
        if(session_id() == '') {
            session_start();
        }
    }

    public static function destroy() {
        session_destroy();
    }

}

?>
