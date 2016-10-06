<?php

class application {

    private $class = false;

    public $admin = false;

    private $controller = false;
    private $action = false;
    private $params = [];

    public static $url;

    public function __construct($env) {

        $this->splitUrl();

        if($env == 'admin') {

            $this->admin = true;

            if(config::get('dev') && !ajax::is()) {
                admin::generateLess();
            }

            $this->admin();

        } else {

            $this->frontend();

        }

    }

    private function frontend() {

        echo 'front';

    }

    private function admin() {
        if(userSession::loggedIn()) {

            $controller = $this->controller;

            $this->class = $controller.'Controller';

            if(!$controller) {
                $this->error404();
            } elseif(file_exists(dir::controller($this->controller.'.php'))) {

                include(dir::controller($this->controller.'.php'));

                $this->class = new $this->class();

                if(method_exists($this->class, $this->action)) {

                    if(!empty($this->params)) {

                        if(user::hasPerm($this->controller.'['.$this->action.']['.$this->params[0].']')) {
                            call_user_func_array([$this->class, $this->action], $this->params);
                        } else {
                            $this->permissionDenied();
                        }

                    } else {

                        if(user::hasPerm($this->controller.'['.$this->action.']')) {
                            $this->class->{$this->action}();
                        } else {
                            $this->permissionDenied();
                        }

                    }

                } else {

                    if(strlen($this->action) == 0) {

                        header('location: '.url::admin($this->controller, ['index']));

                        exit();

                    } else {
                        $this->error404();
                    }

                }

            } else {
                $this->error404();
            }

        } else {

            include(dir::controller('login.php'));

            $page = new loginController();
            $page->index();

        }
    }

    public function error404() {

        $path = ($this->admin) ? url::admin('dashboard') : '';

        if(!ajax::is()) {
            header('location: '.$path);
            exit();
        }

    }

    public function permissionDenied() {

        echo message::getMessage(lang::get('permission_denied'), 'error');

    }

    private function splitUrl() {

        if(type::get('url', 'string', false)) {

            $url = self::getUrl();

            $this->controller = isset($url[0]) ? $url[0] : '';
            $this->action = isset($url[1]) ? $url[1] : '';

            unset($url[0], $url[1]);

            $delete = type::get('delete', 'string', '');

            if($delete) {
                $url[0] = 'delete';
                $url[1] = $delete;
            }

            $this->params = (is_array($url)) ? array_values($url) : false;

        }

    }

    public static function getUrl() {

        if(type::get('url', 'string', false)) {

            $url = trim(type::get('url'), '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);

            return explode('/', $url);

        }

        return false;

    }

}

?>
