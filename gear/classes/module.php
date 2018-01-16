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

    public function main() {

        $main = $this->options['main'];

        if(is_callable($main)) {
            return call_user_func($main);
        }

    }

}

?>
