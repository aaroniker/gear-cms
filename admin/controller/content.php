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

            $path = type::post('path', 'string', dir::storage());

            $array = array_diff(scandir($path), array('.', '..'));

            foreach($array as $name) {

                $file = $path.$name;

                if(is_dir($file)) {
                    $dirs[] = [
                        'name' => $name,
                        'path' => $file."/",
                        'size' => '',
                        'type' => 'dir'
                    ];
                } else {
                    $files[] = [
                        'name' => $name,
                        'size' => file_size($file),
                        'type' => 'file'
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
