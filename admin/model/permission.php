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

}

?>
