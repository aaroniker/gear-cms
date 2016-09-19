<?php

class PageModel extends model {

    protected $id;
    protected $type;
    protected $name;

    public function __construct($id = 0) {

        $this->model = 'entry';

        $this->type = 'page';

        $this->metaData = [
            'parentID',
            'siteURL'
        ];

        if($id) {
            $this->load($id);
        }

        return $this;

    }

    public static function getAll($parentID = 0) {

        $return = [];

        $getAllFromDb = self::getAllFromDb();

        if(is_array($getAllFromDb)) {
            foreach($getAllFromDb as $key => $val) {
                $page = new PageModel($val->id);
                if($page->parentID == $parentID) {
                    $return[$page->id] = [
                        'id' => $page->id,
                        'name' => $page->name,
                        'children' => self::getAll($page->id)
                    ];
                }
            }
        }

        return $return;

    }

}

?>
