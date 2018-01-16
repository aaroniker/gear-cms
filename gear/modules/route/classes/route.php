<?php

class route {

    public $controller = false;
    public $action = false;
    public $params = [];

    public function __construct() {

        $this->splitUrl();

    }

    public function splitUrl() {

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
