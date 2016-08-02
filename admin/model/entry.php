<?php

class EntryModel extends model {

    protected $id;
    protected $type;
    protected $name;

    public function __construct($type, $id = 0) {

        $this->model = 'entry';
        $this->type = $type;

        $this->metaData = [
        ];

        if($id) {
            $this->load($id);
        }

        return $this;

    }

}

?>
