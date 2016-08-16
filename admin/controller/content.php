<?php

class contentController extends controller {

    public function __construct() {

    }

    public function index() {

    }

    public function media() {

        if(ajax::is()) {

            $path = type::post('path', 'string', '');

            ajax::addReturn(json_encode(file_list($path)));

        }

        include(dir::view('content/media/list.php'));

    }

}

?>
