<?php

namespace naptime\views;

require_once('lib/MainNavItem.php');
require_once('lib/auth.php');
require_once('markdown.php');

class admin extends \PASL\Web\Simpl\Page
{
	private static $instance = null;
	
	public $mainNav = null;
	public $subNav = null;
	
	public function __construct()
	{
		$auth = (isset($_SESSION['logged_in'])) ? $_SESSION['logged_in'] : false;
		if ($auth !== true) { header("Location: /login"); }
		
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
		$naptime = \naptime::GetInstance();
		$notices = \naptime\notices::GetInstance();
		
		if ($_POST) {
			$module = $naptime->loadModule('admin');
			$configOptions = $naptime->clean($_POST);
			
			if ($configOptions['project_name'] == 'Fuck You') $notices->addNotice('Really? That project name is pretty offensive, dude.','alert');
			
			if($module->saveSettings($configOptions))
				$notices->addNotice('Your settings have been saved');
			else
				$notices->addNotice('Doh! Something went wrong and your changes could not be saved! - (verify config file permissions)','error');
		}

		$this->subNav->hide();

		$naptime->pageDescription = 'Manage Naptime! Settings';

		$this->TOKENS['projectName'] = $naptime->config->project->name;
		$this->TOKENS['projectTitle'] = $naptime->config->project->title;
		$this->TOKENS['projectDescription'] = $naptime->config->project->description;
		$this->TOKENS['companyName'] = $naptime->config->company->name;
		$this->TOKENS['storagePath'] = $naptime->config->storage->path;
		$this->TOKENS['provider.google.username'] = $naptime->config->provider->google->username;
		$this->TOKENS['provider.google.password'] = $naptime->config->provider->google->password;
		

		$this->body = $this->loadAndParse('admin/settings.html');
		
		unset($naptime, $notices);
		return null;
	}
	
	private function docs()
	{
		$module = \naptime::GetInstance()->loadModule('admin');

		if ($_POST) {
			if (isset($_POST['docName'])) $docName = \naptime::GetInstance()->clean($_POST['docName']);
			else return null;
			
			$docBody = $_POST['docBody'];
			$res = $module->saveDoc($docName, $docBody);
			if ($res) \naptime\notices::GetInstance()->addNotice('Saved!');
			else \naptime\notices::GetInstance()->addNotice('Doh! Something went wrong and your changes could not be saved! - (verify filesystem permissions)','error');
		}
		
		if ($actn = \naptime::GetInstance()->getPath(2) == 'edit') {
			switch($actn)
			{
				case 'edit':
					
					list($provider, $uri) = explode(':', \naptime::GetInstance()->getPath(3),2);
					
					$doc = $module->getDoc($uri, $provider);
					
					if ($doc) {
						$title = ($provider == 'local') ? $uri : $doc->title;
						$this->TOKENS['docName'] = $title;
						$this->TOKENS['docBody'] = $doc;
						$this->TOKENS['docPreview'] = Markdown($doc);
					}
					else \naptime\notices::GetInstance()->addNotice('Sorry, that doc does not exist.','error');
				break;
			}
		} 

		$this->subNav->hide();
		\naptime::GetInstance()->pageDescription = 'Manage Documents';
		
		$docs = $module->getDocs();
		
		$this->TOKENS['docs'] = '<li class="label">Local Files:</li>';
		foreach($docs['local'] as &$doc)
		{
			$doc = '<li><a href="/admin/docs/edit/local:'.$doc.'">'.$doc.'</a></li>';
		}
		$this->TOKENS['docs'] .= join("\n",$docs['local']);
		$this->TOKENS['docs'] .= '<li class="label">Google Docs Files:</li>';
		
		foreach($docs['google'] as $doc)
		{
			$docLink = null;
			foreach($doc->extensionElements as $em)
			{
				if ($em->rootElement === 'resourceId') $docLink = $em;
			}
			$this->TOKENS['docs'] .= '<li><a href="/admin/docs/edit/google:'.$docLink.'">'.$doc->title.'</a></li>';
		}
				
		$this->body = $this->loadAndParse('admin/docs.html');
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
			case "docs":
				$this->docs();
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
