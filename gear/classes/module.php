<?php

class module {

    protected $app;

    public $name;
    public $path;

    protected $config = [];
    public $options = [];

    public function __construct($app, $args = []) {

        $this->app = $app;

        $this->name = $args['name'];
        $this->path = $args['path'];

        $this->config = $args['config'];
        $this->options = $args;

    }

    public function run() {

        $run = $this->options['run'];

        if($run instanceof \Closure) {
            $run = $run->bindTo($this, $this);
        }

        if(is_callable($run)) {
            return call_user_func($run, $this->app);
        }

    }

    public function autoload() {

        if(isset($this->options['autoload'])) {
            if(is_string($this->options['autoload'])) {
                $this->options['autoload'] = (array)$this->options['autoload'];
            }
            foreach((array)$this->options['autoload'] as $dir) {
                autoload::addDir($this->path.'/'.$dir);
            }
        }

    }

    public function config($key = null) {

        if($key) {
            if(isset($this->config[$key])) {
                return $this->config[$key];
            }
            return false;
        }

        return $this->config;

    }

    public function menu() {
        if(isset($this->options['menu']) && is_array($this->options['menu'])) {
            foreach($this->options['menu'] as $name => $item) {
                if(isset($item['icon'])) {
                    $item['icon'] = $this->app->assets->getIcon($item['icon']);
                } else {
                    $item['icon'] = '';
                }
                $this->app->admin->addMenuItem('main', $name, $item);
            }
        }
    }

    public function action() {

        if(isset($this->options['action'])) {
            if(is_string($this->options['action'])) {
                $this->options['action'] = (array)$this->options['action'];
            }
            foreach((array)$this->options['action'] as $hook => $callback) {
                if($callback instanceof \Closure) {
                    $callback = $callback->bindTo($this, $this);
                }
                $hook = $this->getNamePriority($hook);
                $this->app->hook->add_action($hook['name'], $callback, $hook['priority']);
            }
        }

    }

    public function filter() {

        if(isset($this->options['filter'])) {
            if(is_string($this->options['filter'])) {
                $this->options['filter'] = (array)$this->options['filter'];
            }
            foreach((array)$this->options['filter'] as $hook => $callback) {
                if($callback instanceof \Closure) {
                    $callback = $callback->bindTo($this, $this);
                }
                $hook = $this->getNamePriority($hook);
                $this->app->hook->add_filter($hook['name'], $callback, $hook['priority']);
            }
        }

    }

    protected function getNamePriority($name) {
        if(strpos($name, '-') !== false) {
            $name = explode('-', $name);
            $priority = (is_int($name[1])) ? $name[1] : 10;
            return [
                'name' => $name[0],
                'priority' => $priority
            ];
        } else {
            return [
                'name' => $name,
                'priority' => 10
            ];
        }
    }

}

?>
