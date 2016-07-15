<?php

class autoload {
	
	static $classes = [];
	static $dirs = [];
	static $registered = false;
	static $isNewCache = false;
	
	protected static $composer;
	
	static public function register() {
		
		if(self::$registered) {
			return;	
		}
		
		self::$composer = include dir::vendor('autoload.php');
		self::$composer->unregister();
		
		if(spl_autoload_register([__CLASS__, 'autoloader']) === false) {
			
		}
		
		self::loadCache();		
		
        register_shutdown_function([__CLASS__, 'saveCache']);
		
		self::$registered = true;
		
	}
	
	static public function unregister() {
	
		spl_autoload_unregister([__CLASS__, 'autoloader']);
		
		self::$registered = false;
		
	}
	
	static public function autoloader($class) {
		
		
		if(self::classExists($class)) {
			return true;
		}
		
		preg_match_all("/(?:^|[A-Z])[a-z]+/", $class, $treffer);
		
		$classPath = implode(DIRECTORY_SEPARATOR, array_map('strtolower', $treffer[0]));
		
		if(isset(self::$classes[$class])) {
			if(is_readable(self::$classes[$class])) {
				self::addClass($class, self::$classes[$class]);
				
				if(self::classExists($class)) {
					return true;
				}				
			}
			
			unset(self::$classes[$class]);
			self::$isNewCache = true;
		}
		
		if(is_readable(dir::classes($classPath.'.php'))) {
			self::addClass($class, dir::classes($classPath.'.php'));
		}
		
		if(self::classExists($class)) {
			return true;	
		}
		
		$classPath = self::$composer->findFile($class);
		if(!is_null($classPath)) {
			self::addClass($class, $classPath);	
		}
		
		return self::classExists($class);
		
	}
	
	public static function classExists($class) {
		return class_exists($class, false) || trait_exists($class, false) || interface_exists($class, false);
	}
	
	static public function saveCache() {
		
		if(self::$isNewCache) {
			
			$cacheFile = cache::getFileName(0, 'autoloader');
			
			cache::write(json_encode([self::$classes, self::$dirs]), $cacheFile);
			self::$isNewCache = false;
			
		}
		
	}
	
	static protected function loadCache() {
		
		$cacheFile = cache::getFileName(0, 'autoloadcache');
		
		if(!cache::exist($cacheFile, 3600))
			return;
				
		list(self::$classes, self::$dirs) = json_decode(cache::read($cacheFile), true);
		
	}
	
	static public function addClass($class, $path) {
		
		self::$classes[$class] = $path;
		
		if($path)
			include($path);
		
	}
	
	static public function addDir($dir) {
		
		if(!is_dir($dir)) {
		}
		
		if(in_array($dir, self::$dirs)) {
			return;	
		}
		
		self::$dirs[] = $dir;
		
		$files = scandir($dir);
		
		foreach($files as $file) {
				
			if(strrchr($file, '.') != '.php')
				continue;
			
			self::addClass($dir.'_'.$file, $dir.DIRECTORY_SEPARATOR.$file);
			self::$isNewCache = true;
			
		}
		
	}
	
}

?>