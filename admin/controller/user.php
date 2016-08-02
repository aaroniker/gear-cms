<?php

class userController extends controller {

    public function __construct() {
        $this->model = new UserModel;
    }

    public function index($action = '', $id = 0) {

        if($action == 'add') {

            include(dir::view('user/add.php'));

        } elseif($action == 'edit') {

            $id = ($id) ? $id : user::current()->id;

            $this->model = new UserModel($id);

            include(dir::view('user/edit.php'));

        } else {

            if(ajax::is()) {
                ajax::addReturn(json_encode(UserModel::getAllFromDb()));
            }

            include(dir::view('user/list.php'));

        }

    }

    public function permissions() {

        $this->model = new PermissionModel;

        if(ajax::is()) {

            $method = type::post('method', 'string', '');

            if($method == 'listPerm') {

                ajax::addReturn(json_encode(userPerm::getAll()));

            } elseif($method == 'savePerm') {

                $id = type::post('id');
                $perms = type::post('perms');

                $save = [
                    'permissions' => ''
                ];

                ajax::addReturn(json_encode($perms));

            } else {

                ajax::addReturn(json_encode(PermissionModel::getAllFromDb()));

            }

        }

        include(dir::view('user/permissions/list.php'));

    }

}

?>
