<?php

class contentController extends controller {

    public function __construct() {

    }

    public function index() {

    }

    public function media($action = '') {

        if(ajax::is()) {

            $path = type::post('path', 'string', '');

            if($action == 'get') {

                ajax::addReturn(json_encode(file_list($path)));

            } elseif($action == 'addDir') {

                $name = type::post('name', 'string', '');

                $path = dir::media($path.$name);

                if($name) {

                    if(!file_exists($path)) {
                        mkdir($path, 0777, true);
                        message::success(lang::get('dir_added'));
                    } else {
                        message::error(lang::get('dir_exists'));
                    }

                }

            }

        }

        include(dir::view('content/media/list.php'));

    }

}

?>
