<?php

require_once('PASL/Web/Simpl/Page.php');
require_once('PASL/Web/Simpl/MainNav.php');
require_once('lib/MainNavItem.php');

use PASL\Web\Simpl as Web;

class naptime extends Web\Page
{
	private static $instance = null;
	
	public $MainNav = null;
	
	public function __construct()
	{
		$this->body = 'Body';
		
		$this->MainNav = new Web\MainNav();
		$this->MainNav->addMenuItem(new naptime\MainNavItem('API', '#'));
		$this->MainNav->addMenuItem(new naptime\MainNavItem('Developer Guide', '#'));
		$this->MainNav->addMenuItem(new naptime\MainNavItem('Integration Examples', '#'));
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
