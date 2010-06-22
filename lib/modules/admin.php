<?php

namespace naptime\modules;

require_once('lib/MainNavItem.php');

class admin extends \PASL\Web\Simpl\Page
{
	private static $instance = null;
	
	public $mainNav = null;
	public $subNav = null;
	
	public function __construct()
	{
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
	
	public function run()
	{
		switch(\naptime::GetInstance()->getPath(1))
		{
			case "settings":
				$this->body = join('/', \naptime::GetInstance()->getPath(0,2));
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