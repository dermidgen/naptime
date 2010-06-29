<?php

namespace naptime\modules;

class admin
{
	private static $instance = null;
	
	public function __construct()
	{
	}
	
	public function getDoc($file)
	{
		if (file_exists(\naptime::GetInstance()->config->storage->path . '/' . $file))
			return file_get_contents(\naptime::GetInstance()->config->storage->path . '/' . $file);
		else return false;
	}
	
	public function getDocs()
	{
		$config = \naptime\config::GetInstance();
		$basepath = $config->storage->path . '/';
		$files = (scandir($basepath));
		
		if (!$files) return null;
		
		$txtFiles = array();
		foreach($files as $file)
		{
			if(is_file($basepath . $file) && strtolower(pathinfo($basepath . $file, PATHINFO_EXTENSION))) {
				array_push($txtFiles, $file);
			}
		}
		
		return $txtFiles;
	}
	
	public function saveSettings($configOptions)
	{
		$config = \naptime\config::GetInstance();
		
		if (key_exists('project_name',$configOptions)) $config->project->name = $configOptions['project_name'];
		if (key_exists('project_title',$configOptions)) $config->project->title = $configOptions['project_title'];
		if (key_exists('project_description',$configOptions)) $config->project->description = $configOptions['project_description'];
		if (key_exists('company_name',$configOptions)) $config->company->name = $configOptions['company_name'];
		if (key_exists('storage_path',$configOptions)) $config->storage->path = $configOptions['storage_path'];
		
		return $config->saveConfig();
	}
	
	public static function GetInstance()
	{
		if(is_null(self::$instance)) self::$instance = new self;
		return self::$instance;
	}
}

?>
