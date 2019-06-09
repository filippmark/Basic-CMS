<?php

	if (!defined('_PLUGSECURE_'))
	{
	  die('Прямой вызов модуля запрещен!');
	}

	interface StorableObject
	{
			public static function getClassName();
	}
	
class Registry implements StorableObject
{
	private static $className = 'Реестр';
	
	private static $instance;
	
	private static $objects = array();	
	
	public static function singleton()
	{
		if( !isset( self::$instance ) )
			{
				$obj = __CLASS__;
				self::$instance = new $obj;
			}
			  
			return self::$instance;
	}
	
	public function addObject($key, $object)
	{
		require_once($object);
		self::$objects[$key] = new $key(self::$instance);	
	}
	
	public function getObject($key)
	{
		if ( is_object(self::$objects[$key]))
		{
			return self::$objects[$key];	
		}
 	}
	
	public static function getClassName()
	{		
		return self::$className;	
	}
	
	public function getObjectsList()
	{
		$names = array();
		foreach(self::$objects as $obj) 
		{
				$names[] = $obj->getClassName();
			}
		array_push($names, self::getClassName());
		return $names;
	}
}