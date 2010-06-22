<?php

namespace naptime;

require_once("PASL/Web/Simpl/NavMenu.php");

class navMenu extends \PASL\Web\Simpl\NavMenu
{
	public $display = true;
	
	public function show()
	{
		$this->display = true;
	}
	
	public function hide()
	{
		$this->display = false;
	}
	
	public function __toString()
	{
		$html = ($this->display) ? '<ul class="navmenu">' : '<ul class="navmenu hidden">';
		foreach($this->menuItems as $item)
		{
			$html .= (string) $item;
		}
		$html .= '</ul>';

		return $html;
	}
}

?>
