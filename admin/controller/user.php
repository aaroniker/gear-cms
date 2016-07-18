<?php

class userController extends controller {

    public function __construct() {
        $this->model = new UserModel;
    }

    public function index() {

        if(ajax::is()) {
            ajax::addReturn(json_encode(UserModel::getAllFromDb()));
        }

        admin::vue('user/list.js');

        include(dir::view('user/list.php'));

    }

    public function edit($id = 0) {

        if ($id > 0) {

            $model = new UserModel($id);

            include(dir::view('user/edit.php'));

        }

    }

    public function add() {

        include(dir::view('user/add.php'));

    }

}

?>
