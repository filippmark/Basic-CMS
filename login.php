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
		if(isset($_POST["GO"]) && ($_POST["login"] != '') && ($_POST["password"] != '')){
			$mysqli = new mysqli("127.0.0.1", "fill", "123", "lr6ext");
			$login = htmlspecialchars($_POST['login']);
			$query = "SELECT * FROM `users` where `login` = '".$login."'";
			$res = $mysqli->query($query);
			if($res->num_rows == 1){
				$password = $_POST['password'];
				$res = $res -> fetch_assoc();
				$salt = $res['salt'];
				$password = md5(md5($password).$salt);
				if($password == $res['passw']){
					$_SESSION['s'] = $res['hash5'];
					setcookie('name', $login, '/');
					if (isset($_POST["lik"]))
						setcookie('hash5', $res['hash5'],time()+3600, '/');
					else
						setcookie('hash5', $res['hash5'], '/');
					$config = $registry->getObject('config');
					$config::$global_cms_vars['USER'] = $login;
					if(is_superuser($res)){
						$users = '<table><tr><th>login</th><th>Дата регистрации</th><th>Возможности</th></tr>';
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
						echo template::loadTemplate(config::$template, 'authorized', config::$global_cms_vars);
					}
				}
				else{
					echo template::loadTemplate(config::$template, 'login', config::$global_cms_vars);
				}	
			}else{
				echo template::loadTemplate(config::$template, 'login', config::$global_cms_vars);
			}
		}else{
			echo template::loadTemplate(config::$template, 'login', config::$global_cms_vars);
		}
	}
	 
	function is_superuser($res){
			if (($res['rules'] != null) && ($res['rules'] == 'admin'))
				return true;
			return false;
	}	