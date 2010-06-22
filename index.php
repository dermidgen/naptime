<?php

require_once('PASL/Web/Simpl/Page.php');
require_once('PASL/Web/Simpl/MainNav.php');
require_once('lib/MainNavItem.php');

require_once('lib/NavFactory.php');

require_once('lib/Config.php');

use PASL\Web\Simpl as Web;

class naptime extends Web\Page
{
	private static $instance = null;
	
	public $config = null;
	
	public $MainNav = null;
	
	public $Project = null;
	public $Company = null;
	public $Title = null;
	public $Description = null;
	
	public function __construct()
	{
		$this->config = naptime\config::GetInstance();
		
		$this->Project = $this->config->project->name;
		$this->Company = $this->config->company->name;
		$this->Title = $this->config->project->title;
		$this->Description = $this->config->project->description;
		
		/* Example code for creating the menu entries in the DB - we'll move 
		 * this stuff into a more dynamic location later once there is an
		 * admin for managing menus.  Enable this block add menu's to the 
		 * database -->* /
		$this->MainNav = new Web\MainNav();
		$this->MainNav->addMenuItem(new naptime\MainNavItem('API', '#', 'API', null));
		$this->MainNav->addMenuItem(new naptime\MainNavItem('Developer Guide', '#', 'Developer Guide', null));
		$this->MainNav->addMenuItem(new naptime\MainNavItem('Integration Examples', '#', 'Integration Examples', null));
		naptime\NavFactory::storeNav('MainNav',$this->MainNav);
		

		$this->SubNav = new Web\SubNav();
		$this->SubNav->addMenuItem(new naptime\MainNavItem('Getting Started', '#', 'Getting Started', null));
		$this->SubNav->addMenuItem(new naptime\MainNavItem('API Methods', '#', 'API Methods', null));
		$this->SubNav->addMenuItem(new naptime\MainNavItem('HTTP Status Codes', '#', 'HTTP Status Codes', null));
		$this->SubNav->addMenuItem(new naptime\MainNavItem('Authentication', '#', 'Authentication', null));
		naptime\NavFactory::storeNav('SubNav', $this->SubNav);
		/**/
		
		$this->MainNav = naptime\NavFactory::fetchNav('MainNav');
		$this->SubNav = naptime\NavFactory::fetchNav('SubNav');
	}
	
	public function getPath($depth=null, $limit=1)
	{
		$path = explode('/',$_SERVER['REQUEST_URI']);
		array_shift($path); // Kill leading slash
		
		if (is_null($depth)) return $path; // return the full array
		else { // give them a sliced array back
			if($limit === 1) return $path[$depth];
			else return array_slice($path, $depth, $limit);
		}
	}
	
	public function run()
	{
		switch($this->getPath(0))
		{
			case "admin":
				$this->body = "Admin";
			break;
			default:
				$this->body = "Home";
		}
	}
	
	public static function Main()
	{
		$app = naptime::GetInstance();
		$app->run();
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
