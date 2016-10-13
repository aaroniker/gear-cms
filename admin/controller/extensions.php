<?php

class extensionsController extends controller {

    public function __construct() {

    }

    public function index($action = '', $id = 0) {

        include(dir::view('extensions/plugins.php'));

    }

    public function blocks($action = '', $block = '') {

        include(dir::view('extensions/blocks/list.php'));

    }

}

?>
