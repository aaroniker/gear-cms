<?php

class extension {

    protected static $extensions = [];

    public static function add($name, $function, $position = -1) {

        try {

            if(!is_callable($function)) {

                throw new Exception(sprintf(lang::get('extension_callable_func'), _CLASS__));

                return false;

            }

            if(!isset(self::$extensions[$name]))
                self::$extensions[$name] = [];

            if($position < 0)
                $position = count(self::$extensions[$name]);

            array_splice(self::$extensions[$name], $position, 0, $function);

            return true;


        } catch (Exception $e) {

            echo message::error($e->getMessage());

        }

    }

    public static function has($name, $function = false) {

        if(!$function)
            return isset(self::$extensions[$name]);

        if(!function_exists($function))
            return false;

        return isset(self::$extensions[$name][$function]);

    }

    public static function get($name, $object = false, $this = false) {

        if(!self::has($name))
            return $object;

        $extension = self::$extensions[$name];

        foreach($extension as $function)
            $object = $function($object, $this);

        return $object;

    }

}

?>
