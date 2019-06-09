<?php

	define( "_PLUGSECURE_", true); 
	require_once './core/registry.php';	
	include("sniffer.php");
	$registry = Registry::singleton();	
	$registry->addObject('config', './core/config.php');
	$registry->addObject('database', './core/database.php');
	$registry->addObject('template', './core/template.php');

	echo template::loadTemplate(config::$template, 'index', config::$global_cms_vars);