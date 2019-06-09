<?php
	if (!defined('_PLUGSECURE_'))
	{
	  die('Прямой вызов модуля запрещен!');
	}

	class database 
	{
		
		private static $className = 'Класс MySQL';
		private static $connection;
		
		public static function getClassName()
		{		
			return self::$className;	
		}
		
		public static function connect($data)
		{
			$connect = new mysqli($data['host'], $data['user'], $data['pass'], $data['base'], $data['port']);
			if($connect->connect_errno)
			{
				throw new Exception('При подключении к базе данных произошла ошибка: ' . $connect->connect_error);
			}
			else
			{
			self::$connection = $connect;
			unset($connect);
			return self::$connection;
			}
		}
		
		public function closeConnect()
		{
			$closedb = self::$connection->close();
			if($closedb)
			{
				return "0";
			}
			else
			{
				return "1";
			}
		}
		
		public static function dbPing()
		{
			$count = count(self::$connection);
			if(self::$connection->ping())
			{
				return "Соединение с MySQL установлено!";
			}
			else
			{
				return "Соединение с MySQL не установлено. Ошибка: " . self::$connection->error;
			}
		}
		
	}