<?php

class type {

    static protected function cast($var, $type = '') {

        switch($type) {

            case 'bool': case 'boolean':
                return (boolean) $var;

            case 'int': case 'interger':
                return (int) $var;

            case 'double':
                return (double) $var;

            case 'float':
                return (float) $var;

            case 'string':
                return (string) $var;

            case 'object':
                return (object) $var;

            case 'array':
                return (empty($var)) ? [] : (array) $var;

            case '':
                return $var;

            default:

        }

    }

    static public function get($var, $type = '', $default = null) {

        return self::checkVar($_GET, $var, $type, $default);

    }

    static public function post($var, $type = '', $default = null) {

        return self::checkVar($_POST, $var, $type, $default);

    }

    static public function files($var, $type = '', $default = null) {

        return self::checkVar($_FILES, $var, $type, $default);

    }

    static public function super($var, $type = '', $default = null) {

        return self::post($var, $type, self::get($var, $type, $default));

    }

    static public function cookie($var, $type = '', $default = null) {

        return self::checkVar($_COOKIE, $var, $type, $default);

    }

    static public function server($var, $type = '', $default = null) {

        return self::checkVar($_SERVER, $var, $type, $default);

    }

    static public function session($var, $type = '', $default = null) {

        return self::checkVar($_SESSION, $var, $type, $default);

    }

    static public function addSession($name, $value) {

        $_SESSION[$name] = $value;

    }

    static public function deleteSession($name) {

        unset($_SESSION[$name]);

    }

    static public function setCookie($name, $value, $time) {

        setcookie($name, $value, $time);

    }

    static public function deleteCookie($name) {

        unset($_COOKIE[$name]);
        setcookie($name, null, -1);

    }

    static private function checkVar($global, $var, $type = '', $default = null) {

        if(isset($global[$var]) || array_key_exists($var, $global)) {
            return self::cast($global[$var], $type);
        }

        return $default;

    }

}

?>
