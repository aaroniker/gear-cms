<?php

class module {

    public $name;
    public $path;

    protected $config = [];
    public $options = [];

    public function __construct($args = []) {

        $this->name = $args['name'];
        $this->path = $args['path'];

        $this->config = $args['config'];
        $this->options = $args;

    }

    public function run($app) {

        $run = $this->options['run'];

        if(is_callable($run)) {
            return call_user_func($run, $app);
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

}

?>
