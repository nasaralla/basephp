<?php


error_reporting(E_ALL);
ini_set('display_errors', '1');

include ("code/Core/Engine.php");

Core_Engine::init('Test');

//echo get_class(Core_Engine::getModel('Module/Model'));

echo Core_Engine::run();

?>
