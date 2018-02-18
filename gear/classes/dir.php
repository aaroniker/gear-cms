<?php

class dir {

    static $base = '';

    public function __construct($dir = '') {
        self::$base = realpath($dir);
    }

    public static function base($file = '') {
        return self::$base.DIRECTORY_SEPARATOR.$file;
    }

    public static function gear($file = '') {
        return self::base('gear'.DIRECTORY_SEPARATOR.$file);
    }

    public static function language($file = '') {
        return self::gear('language'.DIRECTORY_SEPARATOR.$file);
    }

    public static function classes($file = '') {
        return self::gear('classes'.DIRECTORY_SEPARATOR.$file);
    }

    public static function vendor($file = '') {
        return self::gear('vendor'.DIRECTORY_SEPARATOR.$file);
    }

    public static function tmp($file = '') {
        return self::base('tmp'.DIRECTORY_SEPARATOR.$file);
    }

    public static function cache($file = '') {
        return self::tmp('cache'.DIRECTORY_SEPARATOR.$file);
    }

    public static function extensions($extension = false, $file = '') {
        if($extension) {
            return self::base('extensions'.DIRECTORY_SEPARATOR.$extension.DIRECTORY_SEPARATOR.$file);
        } else {
            return self::base('extensions'.DIRECTORY_SEPARATOR.$file);
        }
    }

    public static function themes($theme = false, $file = '') {
        if($theme) {
            return self::base('themes'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.$file);
        } else {
            return self::base('themes'.DIRECTORY_SEPARATOR.$file);
        }
    }

}

?>
