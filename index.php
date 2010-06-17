<?php

require_once('PASL/Web/Simpl/Page.php');

class naptime extends PASL\Web\Simpl\Page
{
	private static $instance = null;
	
	public function __construct()
	{
		$this->body = 'Body';
	}
	
	public static function Main()
	{
		$app = naptime::GetInstance();
		$app->display();
	}
	
	/**
	 * @return naptime
	 */
	public static function GetInstance()
	{
		if (is_null(self::$instance)) self::$instance = new self();
		return self::$instance;
	}
}

naptime::Main();
?>
