<?php

class contentController extends controller {

    public function __construct() {

    }

    public function index() {

    }

    public function media() {

        if(ajax::is()) {

            $dirs = [];
            $files = [];

            $path = type::post('path', 'string', '');

            $array = array_diff(scandir(dir::media($path)), array('.', '..'));

            foreach($array as $name) {

                $file = $path.$name;

                if(is_dir(dir::media($file))) {
                    $dirs[] = [
                        'name' => $name,
                        'path' => str_replace(dir::media(), '', $file."/"),
                        'size' => '',
                        'type' => 'dir'
                    ];
                } else {
                    $files[] = [
                        'name' => $name,
                        'size' => file_size(dir::media($file)),
                        'type' => 'file'
                    ];
                }

            }

            ajax::addReturn(json_encode(array_merge($dirs, $files)));
            
        }

        include(dir::view('content/media/list.php'));

    }

}

?>
