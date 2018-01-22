<?php

// https://github.com/bainternet/PHP-Hooks

class hook {

    protected $filters = [];
    protected $actions = [];

    protected $merged = [];
    protected $current = [];

    public function add_filter($tag, $callback, $priority = 10, $accepted_args = 1) {
        $id = $this->filter_unique_id($tag, $callback, $priority);
        $this->filters[$tag][$priority][$id] = ['function' => $callback, 'accepted_args' => $accepted_args];
        unset($this->merged[$tag]);
        return true;
    }

    public function remove_filter($tag, $callback, $priority = 10) {
        $callback = $this->filter_unique_id($tag, $callback, $priority);
        $check = isset($this->filters[$tag][$priority][$callback]);
        if($check) {
            unset($this->filters[$tag][$priority][$callback]);
            if(empty($this->filters[$tag][$priority])) {
                unset($this->filters[$tag][$priority]);
            }
            unset($this->merged[$tag]);
        }
        return $check;
    }

    public function remove_all_filters($tag, $priority = false) {
        if(isset($this->filters[$tag])) {
            if(false !== $priority && isset($this->filters[$tag][$priority])) {
                unset($this->filters[$tag][$priority]);
            } else {
                unset($this->filters[$tag]);
            }
        }
        if(isset($this->merged[$tag])) {
            unset($this->merged[$tag]);
        }
        return true;
    }

    public function has_filter($tag, $callback = false) {
        $has = !empty($this->filters[$tag]);
        if(!$callback || !$has) {
            return $has;
        }
        if(!$id = $this->filter_unique_id($tag, $callback, false)) {
            return false;
        }
        foreach((array)array_keys($this->filters[$tag]) as $priority) {
            if(isset($this->filters[$tag][$priority][$id])) {
                return $priority;
            }
        }
        return false;
    }

    public function apply_filters($tag, $value) {
        $args = [];
        if(isset($this->filters['all'])) {
            $this->current[] = $tag;
            $args = func_get_args();
            $this->call_all($args);
        }
        if(!isset($this->filters[$tag])) {
            if(isset($this->filters['all'])) {
                array_pop($this->current);
            }
            return $value;
        }
        if(!isset($this->filters['all'])) {
            $this->current[] = $tag;
        }
        if(!isset( $this->merged[ $tag ])) {
            ksort($this->filters[$tag]);
            $this->merged[$tag] = true;
        }
        reset($this->filters[$tag]);
        if(empty($args)) {
            $args = func_get_args();
        }
        do {
            foreach((array)current($this->filters[$tag]) as $val) {
                if(!is_null($val['function'])) {
                    $args[1] = $value;
                    $value = call_user_func_array($val['function'], array_slice($args, 1, (int)$val['accepted_args']));
                }
            }
        } while(next($this->filters[$tag]) !== false);
        array_pop($this->current);
        return $value;
    }

    public function apply_filters_array($tag, $args) {
        if(isset($this->filters['all']) ) {
            $this->current[] = $tag;
            $all_args = func_get_args();
            $this->call_all($all_args);
        }
        if(!isset($this->filters[$tag])) {
            if(isset($this->filters['all'])) {
                array_pop($this->current);
            }
            return $args[0];
        }
        if(!isset($this->filters['all'])) {
            $this->current[] = $tag;
        }
        if(!isset($this->merged[$tag])) {
            ksort($this->filters[$tag]);
            $this->merged[$tag] = true;
        }
        reset($this->filters[$tag]);
        do {
            foreach( (array) current($this->filters[$tag]) as $val) {
                if(!is_null($val['function'])) {
                    $args[0] = call_user_func_array($val['function'], array_slice($args, 0, (int) $val['accepted_args']));
                }
            }
        } while(next($this->filters[$tag]) !== false);
        array_pop($this->current);
        return $args[0];
    }

    public function add_action($tag, $callback, $priority = 10, $accepted_args = 1) {
        return $this->add_filter($tag, $callback, $priority, $accepted_args);
    }

    public function has_action($tag, $callback = false) {
        return $this->has_filter($tag, $callback);
    }

    public function remove_action($tag, $callback, $priority = 10) {
        return $this->remove_filter($tag, $callback, $priority);
    }

    public function remove_all_actions($tag, $priority = false) {
        return $this->remove_all_filters($tag, $priority);
    }

    public function do_action($tag, $arg = '') {
        if(!isset($this->actions)) {
            $this->actions = [];
        }
        if(!isset($this->actions[$tag])) {
            $this->actions[$tag] = 1;
        } else {
            ++$this->actions[$tag];
        }
        if(isset($this->filters['all'])) {
            $this->current[] = $tag;
            $all_args = func_get_args();
            $this->call_all($all_args);
        }
        if(!isset($this->filters[$tag])) {
            if(isset($this->filters['all'])) {
                array_pop($this->current);
            }
            return;
        }
        if(!isset($this->filters['all'])) {
            $this->current[] = $tag;
        }
        $args = [];
        if(is_array($arg) && 1 == count($arg) && isset($arg[0]) && is_object($arg[0])) {
            $args[] =& $arg[0];
        } else {
            $args[] = $arg;
        }
        for($a = 2; $a < func_num_args(); $a++) {
            $args[] = func_get_arg($a);
        }
        if(!isset( $this->merged[$tag])) {
            ksort($this->filters[$tag]);
            $this->merged[$tag] = true;
        }
        reset($this->filters[$tag]);
        do {
            foreach((array)current($this->filters[$tag]) as $val) {
                if(!is_null($val['function']) ) {
                    call_user_func_array($val['function'], array_slice($args, 0, (int) $val['accepted_args']));
                }
            }
        } while(next($this->filters[$tag]) !== false);
        array_pop($this->current);
    }

    public function do_action_array($tag, $args) {
        if(!isset($this->actions)) {
            $this->actions = [];
        }
        if(!isset($this->actions[$tag])) {
            $this->actions[$tag] = 1;
        } else {
            ++$this->actions[$tag];
        }
        if(isset($this->filters['all'])) {
            $this->current[] = $tag;
            $all_args = func_get_args();
            $this->call_all($all_args);
        }
        if(!isset($this->filters[$tag])) {
            if(isset($this->filters['all'])) {
                array_pop($this->current);
            }
            return;
        }
        if(!isset($this->filters['all'])) {
            $this->current[] = $tag;
        }
        if(!isset($merged[$tag])) {
            ksort($this->filters[$tag]);
            $merged[$tag] = true;
        }
        reset($this->filters[$tag]);
        do {
            foreach((array)current($this->filters[$tag]) as $val) {
                if(!is_null($val['function'])) {
                    call_user_func_array($val['function'], array_slice($args, 0, (int) $val['accepted_args']));
                }
            }
        } while(next($this->filters[$tag]) !== false);
        array_pop($this->current);
    }

    public function did_action($tag) {
        if(!isset($this->actions) || !isset($this->actions[$tag])) {
            return false;
        }
        return $this->actions[$tag];
    }

    public function current_filter() {
        return end($this->current);
    }

    function current_action() {
        return $this->current_filter();
    }

    function doing_filter($filter = null) {
        if(is_null($filter)) {
            return !empty($this->current);
        }
        return in_array($filter, $this->current);
    }

    function doing_action($action = null) {
        return $this->doing_filter($action);
    }

    private function filter_unique_id($tag, $callback, $priority) {
        static $filter_id_count = 0;
        if(is_string($callback)) {
            return $callback;
        }
        if(is_object($callback)) {
            $callback = [$callback, ''];
        } else {
            $callback = (array)$callback;
        }
        if(is_object($callback[0])) {
            if(function_exists('spl_object_hash')) {
                return spl_object_hash($callback[0]) . $callback[1];
            } else {
                $obj_id = get_class($callback[0]).$callback[1];
                if(!isset($callback[0]->filter_id)) {
                    if(false === $priority) {
                        return false;
                    }
                    $obj_id .= isset($this->filters[$tag][$priority]) ? count((array)$this->filters[$tag][$priority]) : $filter_id_count;
                    $function[0]->filter_id = $filter_id_count;
                    ++$filter_id_count;
                } else {
                    $obj_id .= $callback[0]->filter_id;
                }
                return $obj_id;
            }
        } elseif(is_string($callback[0])) {
            return $callback[0].$callback[1];
        }
    }

    public function call_all($args) {
        reset($this->filters['all']);
        do {
            foreach((array)current($this->filters['all']) as $val)
            if(!is_null($val['function'])) {
                call_user_func_array($val['function'], $args);
            }
        } while(next($this->filters['all']) !== false);
    }

}

?>
