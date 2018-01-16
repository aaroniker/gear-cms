<?php

class hook {

    protected static $hooks = [];

    public static function clear($hook = false) {
        if(!$hook) {
            foreach(self::$hooks as $key => $value) {
                self::clear($key);
            }
        } else {
            $hooks = self::process($hook);
            foreach($hooks as $h) {
                unset(self::$hooks[$h]);
            }
        }
    }

    public static function bind($hook, $callback, $priority = 10) {
        if(!is_callable($callback)) {
            throw new \InvalidArgumentException("Callback is not callable: $hook.");
        }
        $hooks = self::process($hook);
        foreach($hooks as $hook) {
            if(!isset(self::$hooks[$hook])) {
                self::$hooks[$hook] = [];
            }
            if(!isset(self::$hooks[$hook][$priority])) {
                self::$hooks[$hook][$priority] = [];
            }
            self::$hooks[$hook][$priority][] = $callback;
        }
        return true;
    }

    public static function run($hook, $params = []) {
        $hooks = self::process($hook);
        foreach($hooks as $hook) {
            $count = 0;
            self::sort($hook);
            if(isset(self::$hooks[$hook])) {
                if(!is_array($params)) {
                    $params = func_get_args();
                    array_shift($params);
                }
                foreach(self::$hooks[$hook] as $priority => $hooks) {
                    foreach($hooks as $cb) {
                        if(@call_user_func_array($cb, $params) !== false) {
                            $count++;
                        }
                    }
                }
            }
        }
        return $count;
    }

    public static function filter($hook, $value, $params = []) {
        $hooks = self::process($hook);
        foreach($hooks as $hook) {
            $count = 0;
            self::sort($hook);
            if(isset(self::$hooks[$hook])) {
                if(!is_array($params)) {
                    $params = func_get_args();
                    array_shift($params);
                    array_shift($params);
                }
                foreach(self::$hooks[$hook] as $priority => $hooks) {
                    foreach($hooks as $cb) {
                        array_unshift($params, $value);
                        $value = @call_user_func_array($cb, array_values($params));
                        $count++;
                    }
                }
            }
            return $value;
        }
    }

    protected static function process($hook) {
        if(is_object($hook) || $hook === null) {
            throw new Exception("Invalid hook.");
        }
        if(is_array($hook)) {
            return $hook;
        }
        return [$hook];
    }

    protected static function sort($hook = null) {
        if($hook === null) {
            foreach(self::$hooks as $key=>$value) {
                self::sort($key);
            }
            return self::$hooks;
        } else {
            if(isset(self::$hooks[$hook])) {
                ksort(self::$hooks[$hook]);
            } else {
                return [];
            }
            return self::$hooks[$hook];
        }
    }

}

?>
