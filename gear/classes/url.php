<?php

class url {

    public static $base;

    public static function base($params = []) {

        $return = rtrim(self::$base, '/');

        if(count($params)) {
            $return .= implode('/', $params);
        }

        return $return;

    }

    public static function admin($page = '', $params = []) {

        $return = self::$base.'admin';

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
            return self::$base.'media'.$file;
        }

        return false;

    }

    public static function refresh($admin = true) {

        $url = self::$base;
        $url .= ($admin) ? 'admin/' : '';
        $url .= type::get('url', 'string', '');

        header('location: '.$url);

        exit();

    }

}

?>
