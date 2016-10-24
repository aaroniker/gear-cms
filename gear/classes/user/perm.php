<?php

class userPerm {

    public static $perms = [];

    public static function add($name, $value) {

        self::$perms[$name] = $value;

    }

    public static function delete($name) {

        unset(self::$perms[$name]);

    }

    public static function is($name) {

        return isset(self::$perms[$name]);

    }

    public static function getAll($onlyKey = false) {

        if($onlyKey) {
            $return = [];
            foreach(self::$perms as $key => $val) {
                $return[] = $key;
            }
            return $return;
        }

        return self::$perms;

    }

}

?>
