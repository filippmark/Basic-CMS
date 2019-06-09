<?php
	if (!defined('_PLUGSECURE_'))
		die('Прямой вызов модуля запрещен!');

	class users{
		
		private static $className = "Пользователи";

		private static $user_id;

		public function __construct(){
			$check_user = self::checkUser();
			if($check_user)
				self::$user_id = $check_user;
			else
				self::$user_id = -1;
		}

		public static function getClassName()
		{		
			return self::$className;	
		}
		
		public static function checkUserByName($name){
			$result = database::prepareQuery(
					"SELECT COUNT(`name`) as `count` FROM `userlist` WHERE `name`='s:name';",
					array('name'=>$name)
						  );
			$result = $result->fetch_assoc();
			if($result['count'] > 0)
				return true;
			else
				return false;
		}
		
		public static function getUserNameById($id){
			$result = database::prepareQuery(
					"SELECT `name` FROM `userlist` WHERE `id`='i:id';",
					array('id'=>$id)
				);
			if($result->num_rows){
				$result = $result->fetch_assoc();
				return $result['name'];
			}
			else
				return false;
		}
		
		private static function isThisUser($name){
			if(self::$user_id == self::getUserIdByName($name))
				return true;
			else
				return false;
		}
		
		public static function getUserData($cols = null){		
				if($cols){
					if(is_array($cols))
						$cols_str = implode(',', $cols);
					else
						$cols_str = $cols;
				}
				else
					$cols_str = '*';
					
				$user_data = database::query("SELECT ".$cols_str." FROM `userlist` WHERE `id`='".(int)self::$user_id."' LIMIT 1");
				if($user_data->num_rows){
					if(strpos($cols_str, ',') === false){
						$result = $user_data->fetch_assoc();
						return $result[$cols_str];
					}
					else
						return $user_data->fetch_assoc();
				}
				else
					return false;
		}
		
		public static function getId(){
			return self::$user_id;
		}
		
		private static function getUserIdByName($name){
			$result = database::prepareQuery(
						"SELECT `id` FROM `userlist` WHERE `name`='s:name';",
						array('name'=>$name)
				);
			if($result->num_rows){
				$result = $result->fetch_assoc();
				return $result['id'];
			}
			else
				return false;
		}
		
		public static function checkUser(){
			if($_SESSION['user_id'] && $_SESSION['user_hash']){
				$check_data = database::prepareQuery("SELECT `id` FROM `userlist` WHERE `id`='i:user_id' AND `secret`='s:user_hash'",
							array(
								'user_id'=>$_SESSION['user_id'],
								'user_hash'=>$_SESSION['user_hash']
							)
						);
				if($check_data->num_rows){
					$result = $check_data->fetch_assoc();
							return $result['id'];
				}
				else 
					return false;
			}
			else
				return false;
		}
	}