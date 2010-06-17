<?php

namespace naptime;

require_once('PASL/Web/Simpl/MainNav.php');
require_once('PASL/Web/Simpl/SubNav.php');
require_once('PASL/Web/Simpl/SubNavItem.php');
require_once('lib/MainNavItem.php');

class NavFactory
{
	public static function itemFactory($navType, $item)
	{
		switch($navType)
		{
			case "MainNav":
				return new MainNavItem($item->title, $item->link);
			break;
			case "SubNav":
				return new \PASL\Web\Simpl\SubNavItem();
			break;
		}
	}
	
	public static function buildMenuFromArray($navType, $itemArray)
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
		
		foreach($itemArray as $item) {
			$menu->addMenuItem(self::itemFactory($navType, $item));
		}
	}
}

?>
