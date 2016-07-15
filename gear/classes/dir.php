<?php

class dir {
	
	static $base = '';
	
	public function __construct($dir = '') {
		self::$base = realpath($dir);
	}
	
	public static function base($file = '') {
		return self::$base.DIRECTORY_SEPARATOR.$file;
	}
	
	public static function admin($file = '') {
		return self::base('admin'.DIRECTORY_SEPARATOR.$file);
	}
	
	public static function gear($file = '') {
		return self::base('gear'.DIRECTORY_SEPARATOR.$file);
	}
	
	public static function assets($file = '') {
		return self::admin('assets'.DIRECTORY_SEPARATOR.$file);
	}
	
	public static function less($file = '') {
		return self::assets('less'.DIRECTORY_SEPARATOR.$file);
	}
	
	public static function css($file = '') {
		return self::assets('css'.DIRECTORY_SEPARATOR.$file);
	}
	
	public static function components($file = '') {
		return self::admin('components'.DIRECTORY_SEPARATOR.$file);
	}
	
	public static function controller($file = '') {
		return self::admin('controller'.DIRECTORY_SEPARATOR.$file);
	}

	public static function model($file = '') {
		return self::admin('model'.DIRECTORY_SEPARATOR.$file);
	}
	
	public static function view($file = '') {
		return self::admin('view'.DIRECTORY_SEPARATOR.$file);
	}
	
	public static function storage($file = '') {
		return self::base('storage'.DIRECTORY_SEPARATOR.$file);
	}
	
	public static function tmp($file = '') {
		return self::base('tmp'.DIRECTORY_SEPARATOR.$file);
	}
	
	public static function plugins($file = '', $plugin = false) {
		if($plugin)
			return self::gear('plugins'.DIRECTORY_SEPARATOR.$plugin.DIRECTORY_SEPARATOR.$file);
		else
			return self::gear('plugins'.DIRECTORY_SEPARATOR.$file);
	}
	
	public static function classes($file = '') {
		return self::gear('classes'.DIRECTORY_SEPARATOR.$file);
	}
	
	public static function vendor($file = '') {
		return self::gear('vendor'.DIRECTORY_SEPARATOR.$file);
	}
	
	public static function functions($file = '') {
		return self::gear('functions'.DIRECTORY_SEPARATOR.$file);
	}
	
	public static function cache($file = '') {
		return self::tmp('cache'.DIRECTORY_SEPARATOR.$file);
	}
	
	public static function language($file = '') {
		return self::gear('language'.DIRECTORY_SEPARATOR.$file);
	}
	
}

?>