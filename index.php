<?php


error_reporting(E_ALL);
ini_set('display_errors', '1');

include ("code/Core/Engine.php");

Core_Engine::init('Test');

//echo get_class(Core_Engine::getModel('Module/Model'));

$db = Core_Engine::getDb();

print_r($db->fetchItem($db->makeQuery("show processlist;")));

?>
