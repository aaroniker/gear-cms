<?php

class user {

    static protected $userID = null;
    static protected $avatarType = 'wavatar';
    static protected $avatarRate = 'g';

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

    public static function getAvatar($size = 80, $img = false) {

        $url = "https://www.gravatar.com/avatar/";
        $url .= md5(strtolower(self::current()->email));
        $url .= "?s=$size&d=".self::$avatarType."&r=".self::$avatarRate;

        if($img) {
            return "<img src='".$url."'>";
        }

        return $url;

    }

}

?>
