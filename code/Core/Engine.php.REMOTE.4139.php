<?php

final class Core_Engine {

    static private $_project;
    static private $_config;
    static private $_db;

    public static function init($project_name) {
        self::$_project = $project_name;
    }

    public static function setProjectName($name) {
        self::$_project = $name;
    }

    public static function _loadClass($class) {
        $files = array(
            $class . '.php',
            str_replace('_', '/', $class) . '.php',
            __DIR__ . '/../Local/' . str_replace('_', '/', $class) . '.php',
            __DIR__ . '/../Core/' . str_replace('_', '/', $class) . '.php'
        );

        foreach ($files as $file) {
            
            echo $file."<br/>";
            
            if (file_exists($file) && is_readable($file)) {
                include_once $file;
                return;
            }
        }
    }

    public static function loadClass($class) {
        self::_loadClass(self::$_project . '_' . $class);

        return self::$_project . '_' . $class;
    }

    public static function getModel($model) {
        $class_name = str_replace("/", "_Model_", $model);

		$class = self::loadClass($class_name);
	
        if( class_exists($class) ) {
            return new $class();
        }
        
        return FALSE;
	}
	
	public static function getBlock()
	{
		$class_name = str_replace("/", "_Block_", $model);		

		$class = self::loadClass($class_name);
	
        if( class_exists($class) ) {
            return new $class();
        }
        
    }
    
    
    
    public static function getConfig()
    {
        if( !(self::$_config instanceof Core_Config) ) {
            self::_loadClass("Config");
        
            self::$_config = new Core_Config();                
        }
        
        return self::$_config;
    }
    
    public static function getDb()
    {
        if( !(self::$_db instanceof DBConnect) ) {
            self::_loadClass("DBConnect");
        
            $config = self::getConfig();
            
            self::$_db = new DBConnect($config->db_config['host'],
                                       $config->db_config['dbase'],
                                       $config->db_config['user'],
                                       $config->db_config['pass']                                     
                                      );
            
            self::$_db->setDb();
        }
        
        return self::$_db;
    }

}

?>
