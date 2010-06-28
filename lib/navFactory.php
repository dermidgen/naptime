<?php

namespace naptime;

/*
require_once('PASL/Web/Simpl/MainNav.php');
require_once('PASL/Web/Simpl/SubNav.php');
require_once('PASL/Web/Simpl/SubNavItem.php');
*/

require_once('lib/MainNavItem.php');
require_once('lib/navMenu.php');
require_once('lib/db.php');

class navFactory
{
	
	public static function itemFactory($navType, $item=null)
	{
		$title = (is_null($item)) ? '' : $item->title;
		$link = (is_null($item)) ? '' : $item->link;
		$alt = (is_null($item)) ? '' : $item->caption;
		$parent = (is_null($item)) ? '' : $item->parent;
		
		switch($navType)
		{
			default:
				return new MainNavItem($title, $alt, $link, $parent);
		}
	}
	
	public static function menuFactory($navType)
	{
		switch($navType)
		{
			default:
				$menu = new navMenu();
		}
		
		return $menu;
	}
	
	public static function buildMenuFromArray($navType, $itemArray)
	{
		$menu = self::menuFactory($navType);
		
		foreach($itemArray as $item) {
			$menu->addMenuItem(self::itemFactory($navType, $item));
		}
		
		return $menu;
	}
	
	public static function fetchNav($id)
	{
		$dbFetch = function($menuName)
		{
			$db = db::GetInstance();
			
			if ($db->selectDB("nav_menus")) {
				try {
					$res = $db->getDoc($menuName);
					if ($res) {
						$menu = \naptime\navFactory::buildMenuFromArray($res->_id,$res->menuItems);
						$menu->_rev = $res->_rev;
						return $menu;
					}
				} catch (\CouchdbClientException $e) {
					error_log('couchdb exception caught fetching: '.$menuName);
					return null;
				}
			}
			
			return null;
		};
		
		// Some menus are stored as persistent objects - others are built on the fly
		switch($id)
		{
			case 'mainNav':
				return $dbFetch($id);
			break;
			case 'subNav':
				return $dbFetch($id);
			break;
			case 'userNav':
				$menu = self::menuFactory($id);
				
				if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
					$menu->addMenuItem(self::itemFactory($id, (object)
									array(
										'title'	  => 'Logout',
										'link'	  => '/logout',
										'caption' => 'Logout',
										'parent'  => null
										)));
				else
					$menu->addMenuItem(self::itemFactory($id, (object)
									array(
										'title'	  => 'Login',
										'link'	  => '/login',
										'caption' => 'Login',
										'parent'  => null
										)));
				
				return $menu;
			break;
			default:
				return null;
		}
	}
	
	public static function storeNav($id, \PASL\Web\Simpl\NavMenu $menu)
	{
		$db = db::GetInstance();
		
		// Let's make sure the database is there
		if (!in_array('nav_menus',$db->listDatabases())) {
			$db->createDatabase('nav_menus');
		}
		
		$db->selectDB("nav_menus");
		
		if (!isset($menu->_id)) @$menu->_id = $id;
		else if (!isset($menu->_rev) && @self::fetchNav($id))
			$menu->_rev = self::fetchNav($id)->_rev; // if we have an _id let's throw in _rev so we can update it
		
		$db->storeDoc(json_encode($menu));
	}
	
	public static function deleteNav($id)
	{
		$db = db::GetInstance();
		$menu = self::fetchNav($id);
		if ($menu) $db->deleteDoc($id,$menu->_rev);
	}
}

?>
