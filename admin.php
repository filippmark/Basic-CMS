<?php

	define( "_PLUGSECURE_", true); 
	require_once './core/registry.php';	
	$registry = Registry::singleton();	
	$registry->addObject('config', './core/config.php');
	$registry->addObject('database', './core/database.php');
	$registry->addObject('template', './core/template.php');

	if (!empty($_POST['name']) && !empty($_POST['rulse'])){
		$mysqli = new mysqli("127.0.0.1", "fill", "123", "lr6ext");
		$result = $mysqli -> query("SELECT * FROM `users` where `login` = '".$_POST['name']."'");
		if ($result -> num_rows > 0){
			$mysqli -> query("UPDATE `users` SET `rules` = '".$_POST['rulse']."' WHERE `login` = '".$_POST['name']."'");
			$users = '<table><tr><th>login</th><th>Дата регистрации</th><th>Возможности</th></tr>';
			$login = 'filimon777';
			$config = $registry->getObject('config');
			$result = $mysqli -> query("SELECT * FROM `users` WHERE `login` <> '".$login."';");
			for($i = 0; $i < $result -> num_rows; $i++){
				$result -> data_seek($i);
				$res = $result -> fetch_assoc();
				$users = $users."<tr><td>".$res['login']."</td><td>".$res['reg_date']."</td><td>".$res['rules']."</td></tr>";	
			}	
			$users = $users."</table>";
			$config::$global_cms_vars['USER_LIST'] = $users;
			echo template::loadTemplate(config::$template, 'admin', config::$global_cms_vars);
		}else{
			echo template::loadTemplate(config::$template, 'admin', config::$global_cms_vars);
		}	
	}
	else{
		echo template::loadTemplate(config::$template, 'admin', config::$global_cms_vars);
	}	