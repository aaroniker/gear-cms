<?php

class PermissionModel extends model {

    protected $id;
    protected $type;
    protected $name;

    public function __construct($id = 0) {

        $this->model = 'entry';

        $this->type = 'permission';

        $this->metaData = [
            'permissions'
        ];

        if($id) {
            $this->load($id);
        }

        return $this;

    }

    public function countUser() {
        return count(sql::run()->from('user_meta')->where([
            'meta_key' => 'permissionID',
            'meta_value' => $this->id
        ])->fetchAll());
    }

    public static function getAll() {

        $getAllFromDb = self::getAllFromDb();

        if(is_array($getAllFromDb)) {
            foreach($getAllFromDb as $key => $val) {
                $permission = new PermissionModel($val->id);
                $getAllFromDb[$key]->countUser = $permission->countUser();
            }
        }

        return $getAllFromDb;
    }

}

?>
