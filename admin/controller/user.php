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

            $this->model->load($id);

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
            $id = type::post('id', 'int', 0);

            if($method == 'getPerm') {

                $this->model->load($id);

                $perms = ($this->model->permissions) ? unserialize($this->model->permissions) : [];

                ajax::addReturn(json_encode($perms));

            } elseif($method == 'savePerm') {

                $perms = type::post('perms');
                $perms = ($perms) ? serialize($perms) : null;

                $this->model->load($id);

                $save = [
                    'permissions' => $perms
                ];

                $this->model->save($save);

            } else {

                ajax::addReturn(json_encode(PermissionModel::getAllFromDb()));

            }

        }

        include(dir::view('user/permissions/list.php'));

    }

}

?>
