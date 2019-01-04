<?php

class contentController extends controller {

    public function index() {

        echo 'entries';

    }

    public function test() {

        var_dump(func_get_args());

    }

}

?>
