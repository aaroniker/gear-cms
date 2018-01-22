<?php

class application {

    public $content;

    public function __construct($config) {

        $this->config = $config;

        ob_start();

        if($this->config->get('system')['debug']) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        }

        $this->hook = new hook();
        $this->route = new route($this);
        $this->modules = new moduleManager($this);

        $this->content = ob_get_contents();

        ob_end_clean();

    }

    public function boot() {

        $this->hook->do_action('boot', $this);

        if($this->admin) {
            echo $this->route->includeController();
        }

        echo $this->content;

    }

}

?>
