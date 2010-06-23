<?php

namespace naptime;

/**
 * We're going to use a json based config here since we're
 * already using couchdb.  This allows us to treat the config
 * the same whether it's on the filesystem or an object
 * persistant in the database
 */

class config
{
	private static $instance = null;

	/**
	 * @type Object
	 */
	private $configObject = null;
	
	public function __get($var)
	{
		if (isset($this->configObject->$var)){
			return $this->configObject->$var;
		}
		else if (isset($this->$var)) return $this->$var;
		else return null;
	}
	
	public function __set($var,$val)
	{
		if (isset($this->$var)) $this->$var = $val;
		else @$this->configObject->$var = $val;
	}
	
	public function __construct()
	{
		$this->loadConfig();
	}

	private function loadConfig()
	{
		$config = file_get_contents('./config.js');
		$this->configObject = json_decode($config);
	}
	
	public function saveConfig()
	{
		return file_put_contents('./config.js', json_encode($this->configObject));
	}
	
	public function reload()
	{
		$this->loadConfig();
	}
	
	//TODO: add db persistance and loading

	/**
	 * @return config
	 */
	public static function GetInstance()
	{
		if (is_null(self::$instance)) self::$instance = new self;
		return self::$instance;
	}
}

?>
