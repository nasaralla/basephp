<?php

final class Core_Engine
{
	static private $_project;

	public static function init($project_name)
 	{
		self::$_project = $project_name;
	}

	public static function setProjectName($name)
	{
		self::$_project = $name;
	}

	public static function _loadClass($class)
    {
	    $files = array(
			$class . '.php',
			str_replace('_', '/', $class) . '.php',
			__DIR__.'/../Local/'. str_replace('_', '/', $class) . '.php'
		);

	    foreach ($files as $file)
		{								
			if (file_exists($file) && is_readable($file))
			{
			    include_once $file;
			    return;
			}
		}
	}

	public static function loadClass($class)
	{		
		self::_loadClass(self::$_project.'_'.$class);

		return self::$_project.'_'.$class;
	}

	public static function getModel($model)
	{
		$class_name = str_replace("/", "_Model_", $model);		

		$class = self::loadClass($class_name);
	
        if( class_exists($class) ) {
            return new $class();
        }
        
		return new $class();
	}
	
	public static function getController($controller)
	{
		$class_name = str_replace("/", "_Controller_", $controller);		

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
        
		return new $class();
	}
	
	public static function run()
	{
        $is404 = FALSE;
        
		if( isset($_SERVER['PATH_INFO']) ) {
			$path = $_SERVER['PATH_INFO'];		
			
			$parts = explode("/",$path);		
			
			if( isset($parts[1]) && isset($parts[2]) && isset($parts[3]) ) {			
			
				$action = $parts[1].'/'.$parts[2].'Controller';
				
				$controller = self::getController($action);	
				
				if( $controller ) {
                    $method = $parts[3].'Action';
                    
                    if(method_exists($controller, $method) ) {                    
                        $controller->$method();
                    } else {
                        $is404 = TRUE;
                    }                    
				} else {
					$is404 = TRUE;
				}
				
			} else {
				$is404 = TRUE;
			}			
		} else {
			//load the index page
			echo "Home";
		}
        
        if( $is404 === TRUE ) {
            echo "404";
        }
	}
}

?>
