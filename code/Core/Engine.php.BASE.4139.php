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
	
		return new $class();
	}
	
	public static function getController($controller)
	{
		$class_name = str_replace("/", "_Controller_", $controller);		

		$class = self::loadClass($class_name);
	
		return new $class();
	}
	
	public static function getBlock()
	{
		$class_name = str_replace("/", "_Block_", $model);		

		$class = self::loadClass($class_name);
	
		return new $class();
	}
	
	public static function run()
	{
		print_r($_SERVER['PATH_INFO']);
	}
}

?>
