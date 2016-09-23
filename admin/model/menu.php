<?php

class MenuModel extends model {

    protected $id;
    protected $type;
    protected $name;

    public function __construct($id = 0) {

        $this->model = 'entry';

        $this->type = 'menu';

        $this->metaData = [
        ];

        if($id) {
            $this->load($id);
        }

        return $this;

    }

    public function addItem($name, $pageID) {

        $model = new MenuItemModel();

        $insert = [
            'name' => $name,
            'menuID' => $this->id,
            'pageID' => $pageID
        ];

        return $model->insert($insert);

    }

    public function deleteAllItems() {

        $items = MenuItemModel::getAll($this->id);

        if(is_array($items)) {
            foreach($items as $id => $item) {
                $model = new MenuItemModel($id);
                $model->delete();
            }
        }

        return $this;

    }

}

?>
