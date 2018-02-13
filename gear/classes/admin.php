<?php

class admin {

    protected $app;
    protected $menus = [];
    protected $order = 10;

    function __construct($app) {
        $this->app = $app;
    }

    public function addMenuItem($menu, $name, $item = []) {

        $item['order'] = (isset($item['order'])) ? $item['order'] : $this->order;
        $item['activeClass'] = ((bool)preg_match('#^'.str_replace('*', '.*', (isset($item['active'])) ? $item['active'] : $item['url']).'$#', $this->app->route->route)) ? ' class="active"' : '';

        if(isset($item['parent'])) {
            $this->menus[$menu][$item['parent']]['sub'][$name] = $item;
        } else {
            $this->menus[$menu][$name] = $item;
        }

        $this->order = $this->order + 10;

    }

    public function getMenus($menu) {
        function sort_by_order($a, $b) {
            return $a['order'] - $b['order'];
        }
        usort($this->menus[$menu], 'sort_by_order');
        return $this->menus[$menu];
    }

}

?>
