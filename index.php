<?php

	define( "_PLUGSECURE_", true); 
	require_once './core/registry.php';	
	$registry = Registry::singleton();	
	$registry->addObject('config', './core/config.php');
	$registry->addObject('database', './core/database.php');
	$registry->addObject('template', './core/template.php');

	session_start();
	if ((isset($_COOKIE['hash6'])) && (isset($_COOKIE['name']))){
		
		$config = $registry->getObject('config');
		$config::$global_cms_vars['USER'] = $_COOKIE['name'];
		echo template::loadTemplate(config::$template, 'authorized', config::$global_cms_vars);
	}else{
		if(isset($_POST["GO"])){
			$mysqli = new mysqli("127.0.0.1", "fill", "123", "lr6ext");
			$login = htmlspecialchars($_POST['login']);
			echo $login;
			$query = "SELECT * FROM `users` where `login` = '".$login."'";
			$res = $mysqli->query($query);
			if(($res->num_rows == 0) && (registrationCorrect())){
				$password = $_POST['password'];
				$mail = htmlspecialchars($_POST['mail']);
				$salt = mt_rand(100, 999);
				$password = md5(md5($password).$salt);
				$date =  date("F j, Y, g:i a");
				$hash = md5(md5($password).$salt.$date);
				$query = "INSERT INTO `users` (login,passw,salt,email,reg_date,hash5) VALUES ('".$login."','".$password."','".$salt."','".$mail."','".$date."','".$hash."')";
				if($mysqli->query($query)){
					setcookie('hash5', $hash, '/');
					setcookie('name', $login, '/');
					$config = $registry->getObject('config');
					$config::$global_cms_vars['USER'] = $login;
					echo template::loadTemplate(config::$template, 'authorized', config::$global_cms_vars);
				}
			}else{
				echo template::loadTemplate(config::$template, 'reg', config::$global_cms_vars);
			}
		}else{
			echo template::loadTemplate(config::$template, 'reg', config::$global_cms_vars);
		}
	}
	
	function registrationCorrect() {
		if ($_POST['login'] == "") return false;
		if ($_POST['password'] == "") return false;
		if ($_POST['password2'] == "") return false;
		if ($_POST['mail'] == "") return false;
		if (!preg_match('/^([a-z0-9])(\w|[.]|-|_)+([a-z0-9])@([a-z0-9])([a-z0-9.-]*)([a-z0-9])([.]{1})([a-z]{2,4})$/is', $_POST['mail'])) return false;
		if (!preg_match('/^([a-zA-Z0-9])(\w|-|_)+([a-z0-9])$/is', $_POST['login'])) return false;
		if (strlen($_POST['password']) < 5) return false; 
		if ($_POST['password'] != $_POST['password2']) return false;
		$login = $_POST['login'];
		return true;
	}
?>	 	 