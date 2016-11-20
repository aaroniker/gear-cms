<?php

class systemController extends controller {

  public function __construct() {

  }

  public function index($action = '', $id = 0) {

    include(dir::view('system/settings.php'));

  }

  public function theme($action = '', $id = 0) {

    include(dir::view('system/theme.php'));

  }

}

?>
