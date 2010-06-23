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
				return new MainNavItem($title, $link, $alt, $parent);
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
		$db = db::GetInstance();
		
		if ($db->selectDB("nav_menus")) {
			try {
				$res = $db->getDoc($id);
				if ($res) {
					$menu = self::buildMenuFromArray($res->_id,$res->menuItems);
					$menu->_rev = $res->_rev;
					return $menu;
				}
			} catch (\CouchdbClientException $e) {
				//error_log('couchdb exception caught');
				return null;
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
