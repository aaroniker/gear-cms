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

    public function addItem($name, $parentID, $pageID, $link) {

        $model = new MenuItemModel();

        $insert = [
            'name' => $name,
            'menuID' => $this->id,
            'parentID' => $parentID
        ];

        if($pageID) {
            $insert['typeLink'] = 'pageID';
            $insert['pageID'] = $pageID;
        } else {
            $insert['typeLink'] = 'link';
            $insert['link'] = $link;
        }

        return $model->insert($insert);

    }

    public function deleteAllItems() {

        $items = MenuItemModel::getAllMenu($this->id);

        if(is_array($items)) {
            foreach($items as $id => $item) {
                $model = new MenuItemModel($item['id']);
                $model->delete();
            }
        }

        return $this;

    }

}

?>
