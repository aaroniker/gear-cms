<?php

class ajax {

    protected static $return = [];
    protected static $data = [];

    public static function is() {
        $json = json_decode(file_get_contents('php://input'), true);
        self::$data = (isset($json) && is_array($json)) ? $json : [];
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    public static function get($key) {
        return (isset(self::$data[$key])) ? self::$data[$key] : null;
    }

    public static function addReturn($text) {
        self::$return[] = $text;
    }

    public static function getReturn() {
        return implode('<br>', self::$return);
    }

}

?>
