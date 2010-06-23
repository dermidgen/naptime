<?php

require_once('PASL/Web/Simpl/Page.php');
require_once('PASL/Web/Simpl/MainNav.php');
require_once('lib/MainNavItem.php');
require_once('lib/navMenu.php');
require_once('lib/navFactory.php');
require_once('lib/notices.php');
require_once('lib/config.php');

use PASL\Web\Simpl as Web;

class naptime extends Web\Page
{
	private static $instance = null;
	
	public $config = null;
	
	public $mainNav = null;
	public $subNav = null;
	
	public $pageDescription = null;
	public $notices = null;
	
	public $project = null;
	public $company = null;
	public $title = null;
	public $description = null;
	
	public $body = null;
	
	public function __construct()
	{
		$this->config = &naptime\config::GetInstance();
		
		$this->project = &$this->config->project->name;
		$this->company = &$this->config->company->name;
		$this->title = &$this->config->project->title;
		$this->description = &$this->config->project->description;
		$this->notices = &naptime\notices::GetInstance();
		
		/* Example code for creating the menu entries in the DB - we'll move 
		 * this stuff into a more dynamic location later once there is an
		 * admin for managing menus.  Enable this block add menu's to the 
		 * database -->* /
		require_once('lib/db.php');
		$db = naptime\db::GetInstance();
		$db->deleteDatabase('nav_menus');
		
		$this->mainNav = new naptime\navMenu();
		$this->mainNav->addMenuItem(new naptime\MainNavItem('API', '#', 'API', null));
		$this->mainNav->addMenuItem(new naptime\MainNavItem('Developer Guide', '#', 'Developer Guide', null));
		$this->mainNav->addMenuItem(new naptime\MainNavItem('Integration Examples', '#', 'Integration Examples', null));
		naptime\navFactory::storeNav('mainNav',$this->mainNav);
		

		$this->subNav = new naptime\navMenu();
		$this->subNav->addMenuItem(new naptime\MainNavItem('Getting Started', '#', 'Getting Started', null));
		$this->subNav->addMenuItem(new naptime\MainNavItem('API Methods', '#', 'API Methods', null));
		$this->subNav->addMenuItem(new naptime\MainNavItem('HTTP Status Codes', '#', 'HTTP Status Codes', null));
		$this->subNav->addMenuItem(new naptime\MainNavItem('Authentication', '#', 'Authentication', null));
		naptime\navFactory::storeNav('subNav', $this->subNav);
		/**/
		
		$this->mainNav = naptime\navFactory::fetchNav('mainNav');
		$this->subNav = naptime\navFactory::fetchNav('subNav');
	}
	
	public function loadModule($name)
	{
		if (file_exists('lib/modules/'.$name.'.php')) require_once('lib/modules/'.$name.'.php');
		if (class_exists('naptime\\modules\\'.$name)) return call_user_func(array('naptime\\modules\\'.$name,'GetInstance'));
	}
	
	public function loadView($name)
	{
		if (file_exists('lib/views/'.$name.'.php')) require_once('lib/views/'.$name.'.php');
		if (class_exists('naptime\\views\\'.$name)) return call_user_func(array('naptime\\views\\'.$name,'GetInstance'));
	}
	
	public function clean($object, $strip_tags=true)
	{
		$clean = function(&$s, $key, $strip_tags=true)
		{
			htmlentities(($strip_tags === true) ? strip_tags($s) : $s);
		};
		
		if(is_array($object)) array_walk($object, $clean, $strip_tags);
		else if(is_string($obejct)) $clean($object);
		
		return $object;
	}

	public function getPath($depth=null, $limit=1)
	{
		$path = explode('/',$_SERVER['REQUEST_URI']);
		array_shift($path); // Kill leading slash
		
		if (is_null($depth)) return $path; // return the full array
		else { // give them a sliced array back
			if($limit === 1) return (isset($path[$depth])) ? $path[$depth] : null;
			else return array_slice($path, $depth, $limit);
		}
	}
	
	public function run()
	{
		switch($this->getPath(0))
		{
			case "admin":
				$view = $this->loadView('admin');
			break;
			case "login":
				$view = $this->loadView('auth');
			break;
			default:
				$this->body = "Home";
		}

		if (!isset($view) || is_null($view)) return;
		
		$view->run();
		
		/* We want to preserve any output that may have already been
		 * directly set during module runtime */
		$this->body = (is_null($this->body) || !isset($this->body)) ? $view->body : $this->body . $view->body;
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
