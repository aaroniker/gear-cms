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
            'parentID',
            'pageID',
            'link',
            'typeLink',
            'order'
        ];

        if($id) {
            $this->load($id);
        }

        return $this;

    }

    public static function getAllMenu($menuID = 0) {

        $return = [];

        $getAllFromDb = self::getAllFromDb();

        if(is_array($getAllFromDb)) {
            foreach($getAllFromDb as $key => $val) {

                $item = new MenuItemModel($val->id);

                if($item->menuID == $menuID) {

                    $page = new PageModel($item->pageID);

                    if($item->typeLink == 'pageID') {
                        $return[$item->id] = [
                            'id' => $item->id,
                            'order' => $item->order,
                            'name' => $item->name,
                            'pageName' => $page->name,
                            'pageURL' => PageModel::getFullURL($page->id)
                        ];
                    } elseif($item->typeLink == 'link') {
                        $return[$item->id] = [
                            'id' => $item->id,
                            'order' => $item->order,
                            'name' => $item->name,
                            'pageName' => '',
                            'pageURL' => $item->link
                        ];
                    }

                }

            }
        }

        return $return;

    }

    public static function getAll($menuID = 0, $parentID = 0) {

        $return = [];

        $getAllFromDb = self::getAllFromDb();

        if(is_array($getAllFromDb)) {
            foreach($getAllFromDb as $key => $val) {

                $item = new MenuItemModel($val->id);

                if($item->parentID == $parentID && $item->menuID == $menuID) {

                    $page = new PageModel($item->pageID);

                    if($item->typeLink == 'pageID') {
                        $return[] = [
                            'id' => $item->id,
                            'order' => $item->order,
                            'name' => $item->name,
                            'pageName' => $page->name.' -',
                            'pageURL' => PageModel::getFullURL($page->id),
                            'children' => self::getAll($menuID, $item->id)
                        ];
                    } elseif($item->typeLink == 'link') {
                        $return[] = [
                            'id' => $item->id,
                            'order' => $item->order,
                            'name' => $item->name,
                            'pageName' => '',
                            'pageURL' => $item->link,
                            'children' => self::getAll($menuID, $item->id)
                        ];
                    }

                    usort($return, function($a, $b) {
                        return $a['order'] - $b['order'];
                    });

                }

            }
        }

        return $return;

    }

}

?>
