<?php

class user {

    static protected $userID = null;
    static protected $perms = [];

    public static function setUser($id) {
        self::$userID = $id;
        self::setPerms();
    }

    public static function current() {
        return new UserModel(self::$userID);
    }

    public static function getUser() {
        return self::$userID;
    }

    public static function setPerms() {
        self::$perms = explode('|', self::current()->perms);
    }

    public static function hasPerm($perm) {

        if(self::current()->admin)
            return true;

        return in_array($perm, self::$perms);

    }

}

?>
