<?php

namespace naptime;

require_once('PASL/Web/Simpl/MainNav.php');
require_once('PASL/Web/Simpl/SubNav.php');
require_once('PASL/Web/Simpl/SubNavItem.php');
require_once('lib/MainNavItem.php');
require_once('lib/DB.php');

class NavFactory
{
	
	public static function itemFactory($navType, $item=null)
	{
		$title = (is_null($item)) ? '' : $item->title;
		$link = (is_null($item)) ? '' : $item->link;
		$alt = (is_null($item)) ? '' : $item->caption;
		$parent = (is_null($item)) ? '' : $item->parent;
		
		switch($navType)
		{
			case "MainNav":
				return new MainNavItem($title, $link, $alt, $parent);
			break;
			case "SubNav":
				return new MainNavItem($title, $link, $alt, $parent);
			break;
		}
	}
	
	public static function menuFactory($navType)
	{
		switch($navType)
		{
			case "MainNav":
				$menu = new \PASL\Web\Simpl\MainNav();
			break;
			case "SubNav":
				$menu = new \PASL\Web\Simpl\SubNav();
			break;
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
		$db = db::GetInstance();
		$res = $db->listDatabases();
		
		if ($db->selectDB("nav_menus")) {
			$res = $db->getDoc($id);
			if ($res) {
				$menu = self::buildMenuFromArray($res->_id,$res->menuItems);
				$menu->_rev = $res->_rev;
				return $menu;
			}
		}
		
		return null;
	}
	
	public static function storeNav($id, \PASL\Web\Simpl\NavMenu $menu)
	{
		$db = db::GetInstance();
		
		// Let's make sure the database is there
		if (!in_array('nav_menus',$db->listDatabases())) {
			$db->createDatabase('nav_menus');
		}
		
		$db->selectDB("nav_menus");
		
		if (!$menu->_id) $menu->_id = $id;
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
