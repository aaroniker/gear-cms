<?php

class user {

    static protected $userID = null;

    public static function setUser($id) {
        self::$userID = $id;
    }

    public static function current() {
        return new UserModel(self::$userID);
    }

    public static function hasPerm($perm) {

        if(self::current()->admin) {
            return true;
        }

        $allPerms = userPerm::getAll();

        if(!isset($allPerms[$perm])) {
            return true;
        }

        if(self::current()->permissions) {
            return in_array($perm, self::current()->permissions);
        }

        return false;

    }

}

?>
