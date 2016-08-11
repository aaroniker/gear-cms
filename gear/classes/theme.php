<?php

class theme {

    public static $cssFiles = [];
    public static $jsFiles = [];
    public static $jsCode = [
        'jquery' => [],
        'all' => []
    ];

    public static function addCSS($css_file) {
        self::$cssFiles[] = [
            'file' => $css_file
        ];
    }

    public static function addJs($js_file, $type = 'footer') {
        self::$jsFiles[$type][] = [
            'file' => $js_file
        ];;
    }

    public static function addJsCode($code, $jquery = true) {
        self::$jsCode[($jquery) ? 'jquery' : 'all'][] = $code;
    }

    public static function getCSS() {

        $return = '';

        foreach(self::$cssFiles as $css) {
            $return .= '<link rel="stylesheet" href="'.$css['file'].'">'.PHP_EOL;
        }

        return $return;

    }

    public static function getJS($type = 'footer') {

        $return = '';

        if(isset(self::$jsFiles[$type])) {
            foreach(self::$jsFiles[$type] as $js) {
                $return .= '<script src="'.$js['file'].'"></script>'.PHP_EOL;
            }
        }

        if($type == 'footer') {
            if(isset(self::$jsFiles['vue'])) {
                foreach(self::$jsFiles['vue'] as $js) {
                    $return .= '<script src="'.$js['file'].'"></script>'.PHP_EOL;
                }
            }
        }

        return $return;

    }

    public static function getJSCode() {

        $return = '<script>';
        $return .= '$(document).ready(function() {';

        foreach(self::$jsCode['jquery'] as $code) {
            $return .= $code;
        }

        $return .= '});';

        foreach(self::$jsCode['all'] as $code) {
            $return .= $code;
        }

        $return .= '</script>';

        return $return;

    }

}

?>
