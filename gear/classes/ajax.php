<?php

class ajax {

    static $return = [];

    public static function is() {

        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

    }

    public static function addReturn($text) {
        self::$return[] = $text;

    }

    public static function getReturn() {

        return implode('<br />', self::$return);

    }

}

?>
