<?php

namespace naptime;

class notices extends \PASL\Web\Simpl\Page
{
	private static $instance = null;
	
	public $notices = Array();
	
	public function __construct()
	{
	}
	
	public function addNotice($string, $type="notice")
	{
		$theme = \naptime::GetInstance()->Theme;
		
		switch($type)
		{
			case "alert":
				$notice = '<div class="alert"><img src="/themes/'.$theme.'/images/icons/dialog-warning.png"/>'.$string.'</div>';
			break;
			case "notice":
				$notice = '<div class="notice"><img src="/themes/'.$theme.'/images/icons/dialog-information.png"/>'.$string.'</div>';
			break;
			case "error":
				$notice = '<div class="error"><img src="/themes/'.$theme.'/images/icons/dialog-error.png"/>'.$string.'</div>';
			break;
			default:
				$notice = '<div>'.$string.'</div>';
		}
		
		array_push($this->notices,$notice);
	}
	
	public function __toString()
	{
		if (!count($this->notices)) return '';
		
		$this->TOKENS['body'] = join("\n",$this->notices);
		return $this->loadAndParse('notices.html');
	}
	
	public static function GetInstance()
	{
		if(is_null(self::$instance)) self::$instance = new self;
		return self::$instance;
	}
}

?>
