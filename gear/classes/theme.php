<?php

class theme {
	
	public static $cssFiles = [];
	public static $jsFiles = [];
	
	public static function addCSS($css_file, $local = false) {
		self::$cssFiles[] = [
			'file' => $css_file,
			'local' => $local
		];
	}
	
	public static function addJs($js_file, $local = false, $type = 'footer') {
		self::$jsFiles[$type][] = [
			'file' => $js_file,
			'local' => $local
		];;
	}
	
	public static function getCSS() {
	
		$return = '';
	
		foreach(self::$cssFiles as $css) {
			
			$path = ($css['local']) ? config::get('url') : '';
				
			$return .= '<link rel="stylesheet" href="'.$path.$css['file'].'">'.PHP_EOL;
		
		}
	
		return $return;
	
	}
	
	public static function getJS($type = 'footer') {
	
		$return = '';
		
		if(isset(self::$jsFiles[$type]))
			foreach(self::$jsFiles[$type] as $js) {
			
				$path = ($js['local']) ? config::get('url') : '';
			
				$return .= '<script src="'.$path.$js['file'].'"></script>'.PHP_EOL;
				
			}
		
		if($type == 'footer')
			if(isset(self::$jsFiles['vue']))
				foreach(self::$jsFiles['vue'] as $js) {
				
					$path = ($js['local']) ? config::get('url') : '';
				
					$return .= '<script src="'.$path.$js['file'].'"></script>'.PHP_EOL;
					
				}
	
		return $return;
	
	}
	
	public static function getTitle() {
		
		return admin::$page;
			
	}
	
}

?>