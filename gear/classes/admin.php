<?php

class admin {

    protected $app;
    protected $menus = [];
    protected $order = 10;

    protected $patterns = [
        '{a}' => '([^/]+)',
        '{d}' => '([0-9]+)',
        '{i}' => '([0-9]+)',
        '{s}' => '([a-zA-Z]+)',
        '{w}' => '([a-zA-Z0-9_]+)',
        '{u}' => '([a-zA-Z0-9_-]+)',
        '{*}' => '(.*)',
        '{/*}' => '(/.*)?'
    ];

    function __construct($app) {
        $this->app = $app;
    }

    public function addMenuItem($menu, $name, $item = []) {

        $item['order'] = (isset($item['order'])) ? $item['order'] : $this->order;
        $url = isset($item['active']) ? $item['active'] : $item['url'];
        $url = str_replace(array_keys($this->patterns), array_values($this->patterns), $url);
        $item['activeClass'] = ((bool)preg_match('#^'.$url.'$#', $this->app->route->route)) ? 'active' : '';

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
