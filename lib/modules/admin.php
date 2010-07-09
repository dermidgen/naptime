<?php

namespace naptime\modules;

require_once('lib/storage.php');

class admin
{
	private static $instance = null;
	
	public function __construct()
	{
	}
	
	public function saveDoc($file, $content, $providerName='local')
	{
		$provider = \naptime\storage::providerFactory($providerName);
		return $provider->saveDoc($file, $content);
		//return file_put_contents(\naptime::GetInstance()->config->storage->path . '/' . $file, $content);
	}
	
	public function getDoc($file, $providerName='local')
	{
		if (file_exists(\naptime::GetInstance()->config->storage->path . '/' . $file))
			return file_get_contents(\naptime::GetInstance()->config->storage->path . '/' . $file);
		else return false;
	}
	
	public function getDocs($providerName=null)
	{
		if (is_null($providerName)) { // We'll assume all providers
		
			$provider = \naptime\storage::providerFactory('local');
			$docs = $provider->getDocs();
			
			$provider = \naptime\storage::providerFactory('google');
			$docs = array_merge($docs, $provider->getDocs());
			
			return $docs;			
		}
		else 
			$provider = \naptime\storage::providerFactory($providerName);
			
		return $provider->getDocs();
	}
	
	public function saveSettings($configOptions)
	{
		$config = \naptime\config::GetInstance();

		// Project configs		
		if (key_exists('project_name',$configOptions)) $config->project->name = $configOptions['project_name'];
		if (key_exists('project_title',$configOptions)) $config->project->title = $configOptions['project_title'];
		if (key_exists('project_description',$configOptions)) $config->project->description = $configOptions['project_description'];
		if (key_exists('company_name',$configOptions)) $config->company->name = $configOptions['company_name'];
		
		// Storage configs
		if (key_exists('storage_path',$configOptions)) $config->storage->path = $configOptions['storage_path'];
		
		// Storage provider configs
		if (key_exists('provider_google_username',$configOptions)) $config->provider->google->username = $configOptions['provider_google_username'];
		if (key_exists('provider_google_password',$configOptions)) $config->provider->google->password = $configOptions['provider_google_password'];
		
		return $config->saveConfig();
	}
	
	public static function GetInstance()
	{
		if(is_null(self::$instance)) self::$instance = new self;
		return self::$instance;
	}
}

?>
