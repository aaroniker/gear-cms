<?php

class application {

    private $class = false;

    private $admin = false;

    private $controller = false;
    private $action = false;
    private $params = [];

    public static $url;

    public function __construct($env) {

        $this->splitUrl();

        if($env == 'admin') {
            $this->admin = true;
            $this->admin();
        } else {
            $this->frontend();
        }

    }

    private function frontend() {

        echo 'front';

    }

    private function admin() {
        if(userLogin::isLogged()) {

            $controller = $this->controller;

            $this->class = $controller.'Controller';

            if(!$controller) {

                include(dir::controller('dashboard.php'));

                $page = new dashboardController();
                $page->index();

            } elseif(file_exists(dir::controller($this->controller.'.php'))) {

                include(dir::controller($this->controller.'.php'));

                $this->class = new $this->class();

                if(method_exists($this->class, $this->action)) {

                    if(!empty($this->params)) {
                        call_user_func_array([$this->class, $this->action], $this->params);
                    } else {
                        $this->class->{$this->action}();
                    }

                } else {

                    if(strlen($this->action) == 0) {
                        $this->class->index();
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

        $path = ($this->admin) ? 'admin/' : '';

        header('location: '.config::get('url').$path);

        exit();

    }

    public function permissionDenied() {

        echo message::error('permission_denied');

    }

    private function splitUrl() {

        if(type::get('url', 'string', false)) {

            $url = self::getUrl();

            $this->controller = isset($url[0]) ? $url[0] : '';
            $this->action = isset($url[1]) ? $url[1] : '';

            unset($url[0], $url[1]);

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
