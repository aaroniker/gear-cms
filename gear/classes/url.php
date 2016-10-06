<?php

class url {

    public static function base($params = []) {

        $return = config::get('url');

        if(count($params)) {
            $return .= '/'.implode('/', $params);
        }

        return $return;

    }

    public static function admin($page = '', $params = []) {

        $return = config::get('url').'admin';

        if($page != '') {
            $return .= '/'.$page;
        }

        if(count($params)) {
            $return .= '/'.implode('/', $params);
        }

        return $return;

    }

    public static function assets($file = '') {

        if($file) {
            return self::admin('assets', explode('/', $file));
        }

        return self::admin('assets');

    }

    public static function media($file = '') {

        if($file) {
            return config::get('url').'media'.$file;
        }

        return false;

    }

    public static function refresh($admin = true) {

        $url = config::get('url');
        $url .= ($admin) ? 'admin/' : '';
        $url .= type::get('url', 'string', '');

        header('location: '.$url);

        exit();

    }

}

?>
