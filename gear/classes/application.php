<?php

class application {

    public $content;

    public function __construct($config) {

        $this->config = $config;

        if($this->config->get('system')['debug']) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        }

        $this->lang = new lang($this);

        $app = $this;

        function __($name, $values = []) {
            global $app;
            return $app->lang->get($name, $values);
        }

        $this->message = new message();
        $this->admin = new admin($this);
        $this->hook = new hook();
        $this->route = new route($this);
        $this->assets = new assets($this);
        $this->view = new viewManager($this);
        $this->controller = new controller($this);
        $this->modules = new moduleManager($this);


    }

    public function boot() {

        $this->hook->do_action('application.boot', $this);

        $this->view->register($this->route->includeController(), 'content');

        if(ajax::is()) {
            echo ajax::getReturn();
            exit();
        }

        echo $this->hook->apply_filters('application.show', $this->view->get('content'));

    }

}

?>
