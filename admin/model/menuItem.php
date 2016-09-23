<?php

class MenuItemModel extends model {

    protected $id;
    protected $type;
    protected $name;

    public function __construct($id = 0) {

        $this->model = 'entry';

        $this->type = 'menu_item';

        $this->metaData = [
            'menuID',
            'pageID'
        ];

        if($id) {
            $this->load($id);
        }

        return $this;

    }

    public static function getAll($menuID = 0) {

        $return = [];

        $getAllFromDb = self::getAllFromDb();

        if(is_array($getAllFromDb)) {
            foreach($getAllFromDb as $key => $val) {

                $item = new MenuItemModel($val->id);

                if($item->menuID == $menuID) {

                    $page = new PageModel($item->pageID);

                    $return[$item->id] = [
                        'id' => $item->id,
                        'name' => $item->name,
                        'pageName' => $page->name,
                        'pageURL' => PageModel::getFullURL($page->id),
                        'children' => []
                    ];

                }

            }
        }

        return $return;

    }

}

?>
