<?php

class PageModel extends model {

    protected $id;
    protected $type;
    protected $name;

    public function __construct($id = 0) {

        $this->model = 'entry';

        $this->type = 'page';
        $this->log = 'page';

        $this->metaData = [
            'parentID',
            'siteURL'
        ];

        if($id) {
            $this->load($id);
        }

        return $this;

    }

    public static function getFullURL($id, $base = '') {

        $page = new PageModel($id);

        $siteURL = '/'.$page->siteURL.$base;

        if($page->parentID) {
            $siteURL = self::getFullURL($page->parentID, $siteURL);
        }

        return $siteURL;

    }

    public static function getAll($parentID = 0) {

        $return = [];

        $getAllFromDb = self::getAllFromDb();

        if(is_array($getAllFromDb)) {
            foreach($getAllFromDb as $key => $val) {
                $page = new PageModel($val->id);
                if($page->parentID == $parentID) {
                    $home = (option::get('home') == $page->id) ? true : false;
                    $siteURL = ($home) ? '/' : self::getFullURL($page->id);
                    $return[$page->id] = [
                        'id' => $page->id,
                        'name' => $page->name,
                        'siteURL' => $siteURL,
                        'home' => $home,
                        'children' => self::getAll($page->id)
                    ];
                }
            }
        }

        return $return;

    }

}

?>
