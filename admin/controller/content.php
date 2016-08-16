<?php

class contentController extends controller {

    public function __construct() {

    }

    public function index() {

    }

    public function media() {

        if(ajax::is()) {

            $path = type::post('path', 'string', '');

            $array = file_list($path);

            ajax::addReturn(json_encode(array_merge($array)));

        }

        include(dir::view('content/media/list.php'));

    }

}

?>
