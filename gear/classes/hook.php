<?php

class hook {

    protected $hooks = [];

    public function clear($hook = false) {
        if(!$hook) {
            foreach($this->hooks as $key => $value) {
                $this->clear($key);
            }
        } else {
            $hooks = $this->process($hook);
            foreach($hooks as $h) {
                unset($this->hooks[$h]);
            }
        }
    }

    public function bind($hook, $callback, $priority = 10) {
        if(!is_callable($callback)) {
            throw new \InvalidArgumentException("Callback is not callable: $hook.");
        }
        $hooks = $this->process($hook);
        foreach($hooks as $hook) {
            if(!isset($this->hooks[$hook])) {
                $this->hooks[$hook] = [];
            }
            if(!isset($this->hooks[$hook][$priority])) {
                $this->hooks[$hook][$priority] = [];
            }
            $this->hooks[$hook][$priority][] = $callback;
        }
        return true;
    }

    public function run($hook, $params = []) {
        $hooks = $this->process($hook);
        foreach($hooks as $hook) {
            $count = 0;
            $this->sort($hook);
            if(isset($this->hooks[$hook])) {
                if(!is_array($params)) {
                    $params = func_get_args();
                    array_shift($params);
                }
                foreach($this->hooks[$hook] as $priority => $hooks) {
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

    public function filter($hook, $value, $params = []) {
        $hooks = $this->process($hook);
        foreach($hooks as $hook) {
            $count = 0;
            $this->sort($hook);
            if(isset($this->hooks[$hook])) {
                if(!is_array($params)) {
                    $params = func_get_args();
                    array_shift($params);
                    array_shift($params);
                }
                foreach($this->hooks[$hook] as $priority => $hooks) {
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

    protected function process($hook) {
        if(is_object($hook) || $hook === null) {
            throw new Exception("Invalid hook.");
        }
        if(is_array($hook)) {
            return $hook;
        }
        return [$hook];
    }

    protected function sort($hook = null) {
        if($hook === null) {
            foreach($this->hooks as $key=>$value) {
                $this->sort($key);
            }
            return $this->hooks;
        } else {
            if(isset($this->hooks[$hook])) {
                ksort($this->hooks[$hook]);
            } else {
                return [];
            }
            return $this->hooks[$hook];
        }
    }

}

?>
