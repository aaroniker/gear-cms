<?php

class option {

    public static function has($name) {

        $option = db()->from('options')->where('option_key', $name)->fetch();

        if($option && $option->option_value) {
            return true;
        }

        return false;

    }

    public static function get($name, $default = null) {

        $option = db()->from('options')->where('option_key', $name)->fetch();

        if($option && $option->option_value) {
            return $option->option_value;
        }

        return $default;

    }

    public static function set($name, $value) {

        if(!$value) {
            return self::del($name);
        }

        $values = [
            'option_value' => $value
        ];

        if(self::has($name)) {
            return db()->update('options')->set($values)->where('option_key', $name)->execute();
        } else {
            return db()->insertInto('options')->values($values)->execute();
        }

    }

    public static function del($name) {

        if(self::has($name)) {
            return db()->deleteFrom('options')->where('option_key', $name)->execute();
        }

        return false;

    }

}

?>
