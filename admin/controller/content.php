<?php

class contentController extends controller {

    public function __construct() {

    }

    public function index() {

    }

    public function storage() {

        if(ajax::is()) {

            $dirs = [];
            $files = [];

            $array = array_diff(scandir(dir::storage(type::post('path', 'string', '/'))), array('.', '..'));

            foreach($array as $name) {

                $path = dir::storage($name);

                if(is_dir($path)) {

                    $dirs[] = [
                        'id' => $name,
                        'name' => $name,
                        'size' => ''
                    ];

                } else {

                    $files[] = [
                        'id' => $name,
                        'name' => $name,
                        'size' => file_size($path)
                    ];

                }

            }

            ajax::addReturn(json_encode(array_merge($dirs, $files)));
        }

        theme::addJS('admin/view/content/storage/list.js', true);

        include(dir::view('content/storage/list.php'));

    }

}

?>
