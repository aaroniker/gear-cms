<?php

class dashboardController extends controller {

    public function index() {

        include(dir::view('dashboard/index.php'));

    }

}

?>
