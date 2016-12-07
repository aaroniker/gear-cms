<?php

class GridModel extends model {

    protected $id;
    protected $type;
    protected $name;

    public function __construct($id = 0) {

        $this->model = 'entry';
        $this->type = 'grid';

        $this->metaData = [
            'content'
        ];

        if($id) {
            $this->load($id);
        }

        return $this;

    }

    public static function getAll() {

        $return = [];

        $getAllFromDb = self::getAllFromDb();

        if(is_array($getAllFromDb)) {
            foreach($getAllFromDb as $key => $val) {
                $grid = new GridModel($val->id);
                $return[] = [
                    'id' => $grid->id,
                    'name' => $grid->name,
                    'content' => $grid->content
                ];
            }
        }

        return $return;

    }

}

?>
