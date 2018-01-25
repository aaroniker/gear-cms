<?php

class application {

    public $content;

    public function __construct($config) {

        $this->config = $config;

        if($this->config->get('system')['debug']) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        }

        $this->hook = new hook();
        $this->route = new route($this);
        $this->view = new view($this);
        $this->controller = new controller($this);
        $this->modules = new moduleManager($this);

    }

    public function boot() {

        ob_start();

        $this->hook->do_action('boot', $this);

        $controller = $this->route->includeController();

        echo $this->view->render($controller);

        $this->content = ob_get_contents();

        ob_end_clean();

        echo $this->content;

    }

}

?>
