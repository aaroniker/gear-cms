<?php

class UserModel extends model {

    protected $id;
    protected $password;
    protected $email;
    protected $status;
    protected $admin;
    protected $permissions;

    public function __construct($id = 0) {

        $this->model = 'user';

        $this->metaData = [
            "username",
            "permissionID"
        ];

        if($id) {
            $this->load($id);
        }

        if($this->permissionID == 0) {

            $this->admin = true;
            
        } else {

            $permGroup = new PermissionModel($this->permissionID);

            $this->permissions = unserialize($permGroup->permissions);

        }

        return $this;

    }

}

?>
