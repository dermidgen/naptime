<?php

namespace naptime;

// Nothin fancy here - just a quick singleton to our couchdb client
class db
{
	private static $instance = null;
	
	/**
	 * @return CouchdbClient
	 */
	public static function GetInstance()
	{
		if(is_null(self::$instance)) self::$instance = new \CouchdbClient("http://localhost:5984");
		return self::$instance;
	}
}

?>
