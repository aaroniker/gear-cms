<?php

class admin {
	
	public static $menu = [];
	public static $submenu = [];
	
	public static $page = '';
	public static $url = '';
	public static $subpage = '';
	
	public static function addMenu($name, $url, $type = 'menu') {
		
		$active = application::getUrl();
		
		$activeUrl = explode('/', $url);
		
		if($active[0] == $activeUrl[0]) {
			
			$class = 'active';
			
			self::$page = $name;
			self::$url = $activeUrl[0];
			
		} else
			$class = '';
		
		self::$menu[$type][$url] = [
			'name' => $name,
			'class' => $class
		];
		
	}
	
	public static function addSubmenu($name, $url, $parentUrl) {
		
		$url = ($url) ? $parentUrl.'/'.$url : $parentUrl;
		
		$active = application::getUrl();
		
		if(count($active) > 2)
			$active = [$active[0], $active[1]];
		
		if($parentUrl == $url || (is_array($active) && implode('/', $active) == $url)) {
			
			$class = 'active';
			
			self::$subpage = $name;
			
		} else
			$class = '';
		
		self::$submenu[$parentUrl][$url] = [
			'name' => $name,
			'class' => $class
		];
		
	}
	
	public static function getSubmenu($parentUrl = false) {
		
		$parentUrl = ($parentUrl) ? $parentUrl : self::$url;
		
		if(isset(self::$submenu[$parentUrl]))
			return self::$submenu[$parentUrl];
		
		return false;
		
	}
	
	public static function getMenu($type = 'menu') {
		
		if(isset(self::$menu[$type]))
			return self::$menu[$type];
			
		return false;
		
	}
	
	public static function vue($file) {
		
		theme::addJS('admin/view/'.$file, true, 'vue');
			
	}
	
}

?>