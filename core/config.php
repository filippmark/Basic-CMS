<?php

	if (!defined('_PLUGSECURE_'))
	{
	  die('Прямой вызов модуля запрещен!');
	}

	class Config 
	{
		private static $className = 'Конфиг';
		
		public static $database = array(
			'host' => 'localhost', 	
			'port' => '3306',	
			'user' => 'fill',	
			'pass' => '123',		
			'base' => 'lr4ext'	
		);
		
		public static $template = array(
			'dir'	=> 'templates',
			'name'	=> 'Conversion'
		);
		
		public static $global_cms_vars	= array('USER','PAGE_TITLE', 'USER_NAME', 'SITE_NAME', 'CONTENT', 'USER_LIST');
		
		public static function getClassName()
		{		
			return self::$className;	
		}
		
		function __construct()
		{	self::$global_cms_vars['USER'] = 'll';
			self::$global_cms_vars['PAGE_TITLE'] = 'ManchesterUnited.com';
			self::$global_cms_vars['DATE'] = date("F d Y H:i:s.");
			$date_now = new DateTime();
			$date_match = new DateTime('2019-04-28 18:00');
			$interval = $date_match->diff($date_now);
			self::$global_cms_vars['LEFT']  = $interval->format("%d суток %h часов %i минут(а\ы)");
			self::$global_cms_vars['CONTENT'] = '';
			if ((date("H") > 6) && (date("H") < 12)){
				self::$global_cms_vars['PART']  = "Доброе утро";
			}
			elseif ((date("H") >= 12) && (date("H") <= 18)){
				self::$global_cms_vars['PART']  = "Доброе день";
			}
			elseif ((date("H") > 18) && (date("H") <= 24)){
				self::$global_cms_vars['PART']  = "Добрый вечер";
			}
			else
				self::$global_cms_vars['PART']  = "Доброй ночи";
			
			}
	}