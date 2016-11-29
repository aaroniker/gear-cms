<?php

class config {

    public $cfg = true;

    static $params = [];
    static $fileParams = [];
    static $isChange = false;

    static $newEntrys = [];

    public function __construct() {

        self::$params = json_decode(file_get_contents(dir::gear('config.json')), true);
        self::$fileParams = self::$params;

        if(self::get('debug')) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }

    }

    public static function has($name) {

        return isset(self::$params[$name]) || array_key_exists($name, self::$params);

    }

    public static function get($name, $default = null) {

        if(self::has($name)) {
            return self::$params[$name];
        }

        return $default;

    }

    public static function add($name, $value, $toSave = false) {

        self::$params[$name] = $value;

        if($toSave) {
            self::$isChange = true;
            self::$newEntrys[$name] = $value;
        }

    }

    public static function save() {

        if(!self::$isChange) {
            return true;
        }

        $newEntrys = array_merge(self::$fileParams, self::$newEntrys);

        return file_put_contents(dir::gear('config.json'), json_encode($newEntrys, JSON_PRETTY_PRINT));

    }

    public static function getBrowser() {

        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $name = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        if(preg_match('/linux/i', $userAgent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $userAgent)) {
            $platform = 'windows';
        }

        if(preg_match('/MSIE/i',$userAgent) && !preg_match('/Opera/i',$userAgent)) {
            $name = 'Internet Explorer';
            $ub = "MSIE";
        } elseif(preg_match('/Firefox/i',$userAgent)) {
            $name = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif(preg_match('/Chrome/i',$userAgent)) {
            $name = 'Google Chrome';
            $ub = "Chrome";
        } elseif(preg_match('/Safari/i',$userAgent)) {
            $name = 'Apple Safari';
            $ub = "Safari";
        } elseif(preg_match('/Opera/i',$userAgent)) {
            $name = 'Opera';
            $ub = "Opera";
        }

        $known = ['Version', $ub, 'other'];
        $pattern = '#(?<browser>'.join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        preg_match_all($pattern, $userAgent, $matches);

        $i = count($matches['browser']);

        if($i != 1) {

            if(strripos($userAgent,"Version") < strripos($userAgent,$ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }

        } else {
            $version= $matches['version'][0];
        }

        if($version == null || $version == "") {
            $version = "?";
        }

        return [
            'userAgent' => $userAgent,
            'name' => $name,
            'version' => $version,
            'platform' => $platform,
            'pattern' => $pattern
        ];

    }

}

?>
