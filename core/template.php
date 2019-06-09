<?php
	if (!defined('_PLUGSECURE_'))
		die('Прямой вызов модуля запрещен!');

	class template 
	{
		private static $className = 'Шаблоны';
		
		private static $template;
		private static $template_dir;
		private static $template_page;
		private static $template_parts = array();
		private static $template_vars = array();

		public static function getClassName(){		
			return self::$className;	
		}
		
		private static function getTemplate(){
			$template_file = self::$template_dir . self::$template_page . '_template.tpl';
			if(!file_exists($template_file)){
				return;
			}
			else
				self::$template = file_get_contents($template_file);
		}
		
		private static function getTemplatePart($part){
			$template_part = '';
			$part_file = self::$template_dir . '__' . $part . '.tpl';
			if(!file_exists($part_file)) {
				echo 'krr';
				return;
			} 
			else 
				$template_part = file_get_contents($part_file);
			return $template_part;
		}
		
		private static function cmp($operator, $op1, $op2){
			switch($operator){
				case ">=":
					if($op1 >= $op2)
						return true;
					else
						return false;
					break;
					
				case "<=":
					if($op1 <= $op2)
						return true;
					else
						return false;
					break;
				case ">":
					if($op1 > $op2)
						return true;
					else
						return false;
					break;
				case "<":
					if($op1 < $op2)
						return true;
					else
						return false;
					break;
				case "==":
					if($op1 == $op2)
						return true;
					else
						return false;
					break;
				case "!=":
					if($op1 != $op2)
						return true;
					else
						return false;
					break;
			}	
		}
		
		private static function parseTemplate()
		{
			foreach(self::$template_parts as $replace) {
				self::$template = preg_replace("/\{(|FILE=)\"$replace\"\}/", self::getTemplatePart($replace), self::$template);
			}

			foreach (self::$template_vars as $var => $replace) {
				self::$template = preg_replace("/\{(|VAR=)\"$var\"\}/", $replace, self::$template);
			}

			while (preg_match("/\{(|CONFIG=)\"([A-Za-z]*)\"\}/",self::$template, $a)){
				$conf_file = file_get_contents("./core/conf.ini");
				preg_match("/$a[2]\s*(|=)\s*\"([\-A-Za-z0-9\s]*)\"/", $conf_file, $value);
				self::$template = preg_replace("/\{(|CONFIG=)\"([A-Za-z]*)\"\}/", $value[2], self::$template);
			}
			
			
			$cmps = array('<', '>', '>=', '<=', '==', '!=');
			
			foreach($cmps as $el){
				$str = "/\{(|IF)\"([0-9]+)\"(|".$el.")\"([0-9]+)\"\}([A-Za-z\s]*)\{(|ELSE)\}([A-Za-z\s]*)\{(|ENDIF)\}/";
				while (preg_match($str,self::$template, $a)){
					//var_dump($a);
					if(self::cmp($a[3], $a[2], $a[4]))
						self::$template = preg_replace($str,$a[5],self::$template);
					else
						self::$template = preg_replace($str,$a[7],self::$template);
				}
			}
			
			foreach($cmps as $el){
				$str = "/\{(|IF)\"([0-9]+)\"(|".$el.")\"([0-9]+)\"\}([A-Za-z\s]*)\{(|ENDIF)\}/";
				while (preg_match($str,self::$template, $a)){
					var_dump($a);
					if(self::cmp($a[3], $a[2], $a[4]))
						self::$template = preg_replace($str,$a[5],self::$template);
					else
						self::$template = preg_replace($str, " ",self::$template);
				}
			}
			

			if (preg_match("/\{(|DB=)\"([A-Za-z0-9]*)\"\}/",self::$template, $a)){
				require_once './core/registry.php';
				$registry = Registry::singleton();
				$db = $registry->getObject('database');

				$c = $db->connect($registry->getObject('config')::$database);  

				preg_match_all("/\{(|DB=)\"([0-9]+)\"\}/",self::$template, $b);
				$i = 0;
				while ( @$b[0][$i] != null){
					$result = $c->query("SELECT `text` from `test` WHERE `id` = {$b[2][$i]}");
					$res = $result->fetch_assoc();
					self::$template = preg_replace("/\{(|DB=)\"([0-9]+)\"\}/", $res['text'], self::$template, 1);
					$i++;
				}
			}

			return self::$template;
		}
		
		public static function loadTemplate($data, $page, $vars)
		{
			if(!empty($data['dir']) && !empty($data['name']))
			{

				if(!file_exists('./' . $data['dir'] . '/' . $data['name'] . '/' . $data['name'] . '.php'))
				{
					echo 'template_not_found';
				}
				else
				{
					self::$template_dir = './' . $data['dir'] . '/' . $data['name'] . '/';
					require_once self::$template_dir . $data['name'] . '.php';
					self::$template_page = $page;
					self::$template_parts = $t_files;
					self::$template_vars = $vars;
					self::getTemplate();
					self::parseTemplate();
					return self::$template;
				}
			}
		}
	}