<?php

class pluginsController extends controller {

    public function __construct() {

    }

    public function index($action = '', $id = 0) {

        include(dir::view('plugins/list.php'));

    }

}

?>
