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
            'siteURL',
            'content'
        ];

        if($id) {
            $this->load($id);
        }

        return $this;

    }

    public function getLink() {

        $siteURL = '/'.$this->siteURL;

        if($this->parentID) {
            $siteURL = self::getFullURL($this->parentID, $siteURL);
        }

        $siteURL = explode('/', $siteURL);

        return url::base($siteURL);

    }

    public function getByURL($url) {

        $url = (!is_array($url)) ? [] : $url;

        $pages = [];

        $getAllFromDb = self::getAllFromDb();

        if(is_array($getAllFromDb)) {
            foreach($getAllFromDb as $key => $val) {
                $page = new PageModel($val->id);
                $pages[self::getFullURL($val->id, '')] = $page;
            }
        }

        if(empty($url)) {
            $page = new PageModel(option::get('home'));
            if(isset($page->id)) {
                return $page;
            }
        }

        $url = '/'.implode('/', $url);

        if(!isset($pages[$url])) {
            return false;
        }

        return $pages[$url];

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
