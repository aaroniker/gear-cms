<?php

class plugin {

    protected static $allPlugins = [];

    protected $config = [];

    public function __construct($name) {

        $file = dir::plugins('plugin.json', $name);

        if(file_exists($file)) {
		    $this->config = json_decode(file_get_contents($file), true);
        }

	}

    public function getConfig() {
        return $this->config;
    }

	public static function getAll() {

        if(!count(self::$allPlugins)) {

            $plugins = array_diff(scandir(dir::plugins()), ['.', '..']);

            foreach($plugins as $dir) {

                $plugin = new plugin($dir);

                self::$allPlugins[] = $plugin->getConfig();

            }

        }

		return self::$allPlugins;

	}

}

?>
