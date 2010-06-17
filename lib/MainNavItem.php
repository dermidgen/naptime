<?php

namespace naptime;

require_once("PASL/Web/Simpl/MainNavItem.php");


class MainNavItem extends \PASL\Web\Simpl\MainNavItem
{
	/**
	 * @return String
	 */
	function __toString()
	{
		$requestURI = ltrim($_SERVER['REQUEST_URI'],"/");

		if ($this->selected && $requestURI != $this->link) return "<li class=\"selected navitem\"><a href=\"{$this->link}\" alt=\"{$this->caption}\">{$this->title}</a></li>";
		else if ($this->selected) return "<li class=\"selected navitem\">{$this->title}</li>";
		else return "<li class=\"navitem\"><a href=\"{$this->link}\" alt=\"{$this->caption}\">{$this->title}</a></li>";
	}
}


?>
