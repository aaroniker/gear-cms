<?php

class userSession {

    public static function init() {

        if(session_id() == '') {
            session_start();
        }

    }

}

?>
