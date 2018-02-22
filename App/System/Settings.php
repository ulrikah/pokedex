<?php
namespace App\System;

use Symfony\Component\Yaml\Yaml;

class Settings {

    public static $config       = null;
    private static $environment = null;

    public static function getConfig() {
        if(self::$config === null) {
            $config_file  = Yaml::parse((file_get_contents(dirname(__DIR__) . '/config.yml')));
            self::$environment = $config_file['environment'];
            self::$config = $config_file[self::$environment];
            if(!self::$config) throw new Exception("Config file could not be loaded!");
        }

        return self::$config;
    }
    
}
