<?php

namespace naptime\views;

require_once('lib/MainNavItem.php');
require_once('lib/auth.php');

class admin extends \PASL\Web\Simpl\Page
{
	private static $instance = null;
	
	public $mainNav = null;
	public $subNav = null;
	
	public function __construct()
	{
		$auth = true;
		if ($auth == false) { header("Location: /login"); }
		
		$this->mainNav = \naptime::GetInstance()->mainNav;
		$this->subNav = \naptime::GetInstance()->subNav;
		
		$basepath = '/'.\naptime::GetInstance()->getPath(0);
		
		$this->mainNav->menuItems = $this->subNav->menuItems = Array();
		$this->mainNav->addMenuItem(new \naptime\MainNavItem('Settings', 'Manage Naptime! Settings', $basepath.'/settings', null));
		$this->mainNav->addMenuItem(new \naptime\MainNavItem('Pages', 'Manage Pages', $basepath.'/pages', null));
		$this->mainNav->addMenuItem(new \naptime\MainNavItem('Documents', 'Manage Documents', $basepath.'/docs', null));
		$this->mainNav->addMenuItem(new \naptime\MainNavItem('Navigation', 'Manage Menus &amp; Links', $basepath.'/navigation', null));
		
		$this->mainNav->selectItemByAttribute('link',$_SERVER['REQUEST_URI']);
	}
	
	private function settings()
	{
		if ($_POST) {
			$module = \naptime::GetInstance()->loadModule('admin');
			$configOptions = \naptime::GetInstance()->clean($_POST);
			
			if ($configOptions['project_name'] == 'Fuck You') \naptime\notices::GetInstance()->addNotice('Really? That project name is pretty offensive, dude.','alert');
			
			if($module->saveSettings($configOptions))
				\naptime\notices::GetInstance()->addNotice('Your settings have been saved');
			else
				\naptime\notices::GetInstance()->addNotice('Doh! Something went wrong and your changes could not be saved! - (verify config file permissions)','error');
		}

		$this->subNav->hide();

		\naptime::GetInstance()->pageDescription = 'Manage Naptime! Settings';

		$this->TOKENS['projectName'] = \naptime::GetInstance()->config->project->name;
		$this->TOKENS['projectTitle'] = \naptime::GetInstance()->config->project->title;
		$this->TOKENS['projectDescription'] = \naptime::GetInstance()->config->project->description;
		$this->TOKENS['companyName'] = \naptime::GetInstance()->config->company->name;

		$this->body = $this->loadAndParse('admin/settings.html');
				
	}
	
	public function run()
	{
		switch(\naptime::GetInstance()->getPath(1))
		{
			case "settings":
				$this->settings();
			break;
			case "pages":
				$this->body = join('/', \naptime::GetInstance()->getPath(0,2));
			break;
			case "docs" || "documents":
				$this->body = join('/', \naptime::GetInstance()->getPath(0,2));
			break;
			case "navigation":
				$this->body = join('/', \naptime::GetInstance()->getPath(0,2));
			break;
			default:
				$this->body = 'admin';
		}
	}
	
	public static function GetInstance()
	{
		if(is_null(self::$instance)) self::$instance = new self;
		return self::$instance;
	}
}

?>
