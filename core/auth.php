<?php
	define( "_PLUGSECURE_", true); 
	require_once './core/registry.php';	
	$registry = Registry::singleton();	
	$registry->addObject('config', './core/config.php');
	$registry->addObject('database', './core/database.php');
	$registry->addObject('template', './core/template.php');

	
	
	 
	 
