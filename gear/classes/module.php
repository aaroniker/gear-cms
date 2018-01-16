<?php

class module {

    public $name;
    public $path;

    protected $config = [];
    protected $options = [];

    public function __construct($args = []) {

        $this->name = $args['name'];
        $this->path = $args['path'];

        $this->config = $args['config'];
        $this->options = $args;

    }

    public function main($app) {

        $main = $this->options['main'];

        if(is_callable($main)) {
            return call_user_func($main, $app);
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
