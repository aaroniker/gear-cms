<?php

class systemController extends controller {

    public function __construct() {

    }

    public function index($action = '', $id = 0) {

        include(dir::view('system/settings.php'));

    }

    public function theme($action = '', $theme = '') {

        if(ajax::is()) {

            $theme = type::post('theme', 'string', $theme);

            if($action == 'setActive') {
                if($theme) {
                    if(option::set('theme', $theme)) {
                        message::success(lang::get('theme_active'));
                    }
                }
            } elseif($action == 'get') {
                ajax::addReturn(json_encode(theme::getAll()));
            }

        }

        include(dir::view('system/theme.php'));

    }

}

?>
