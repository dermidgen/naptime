<?php

namespace naptime\modules;

class admin
{
	private static $instance = null;
	
	public function __construct()
	{
	}
	
	public function saveSettings($configOptions)
	{
		$config = \naptime\config::GetInstance();
		
		if (key_exists('project_name',$configOptions)) $config->project->name = $configOptions['project_name'];
		if (key_exists('project_title',$configOptions)) $config->project->title = $configOptions['project_title'];
		if (key_exists('project_description',$configOptions)) $config->project->description = $configOptions['project_description'];
		if (key_exists('company_name',$configOptions)) $config->company->name = $configOptions['company_name'];
		
		return $config->saveConfig();
	}
	
	public static function GetInstance()
	{
		if(is_null(self::$instance)) self::$instance = new self;
		return self::$instance;
	}
}

?>
