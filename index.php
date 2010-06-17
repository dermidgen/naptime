<?php

require_once('PASL/Web/Simpl/Page.php');
require_once('PASL/Web/Simpl/MainNav.php');
require_once('lib/MainNavItem.php');

require_once('lib/NavFactory.php');

use PASL\Web\Simpl as Web;

class naptime extends Web\Page
{
	private static $instance = null;
	
	public $MainNav = null;
	
	public function __construct()
	{
		$this->body = 'Body';
		
		$this->MainNav = new Web\MainNav();
		$this->MainNav->addMenuItem(new naptime\MainNavItem('API', '#', 'API', null));
		$this->MainNav->addMenuItem(new naptime\MainNavItem('Developer Guide', '#', 'Developer Guide', null));
		$this->MainNav->addMenuItem(new naptime\MainNavItem('Integration Examples', '#', 'Integration Examples', null));
		//naptime\NavFactory::storeNav('MainNav',$this->MainNav);

		$this->SubNav = new Web\SubNav();
		$this->SubNav->addMenuItem(new naptime\MainNavItem('Getting Started', '#', 'Getting Started', null));
		$this->SubNav->addMenuItem(new naptime\MainNavItem('API Methods', '#', 'API Methods', null));
		$this->SubNav->addMenuItem(new naptime\MainNavItem('HTTP Status Codes', '#', 'HTTP Status Codes', null));
		$this->SubNav->addMenuItem(new naptime\MainNavItem('Authentication', '#', 'Authentication', null));
		//naptime\NavFactory::storeNav('SubNav', $this->SubNav);
		
		$this->MainNav = naptime\NavFactory::fetchNav('MainNav');
		$this->SubNav = naptime\NavFactory::fetchNav('SubNav');
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
