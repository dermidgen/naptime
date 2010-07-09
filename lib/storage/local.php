<?php

namespace naptime\storage;

require_once('lib/storage/iStorageProvider.php');

class local implements iStorageProvider
{
	private static $instance = null;
	
	public function getDoc($filename)
	{
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
	
	public function saveDoc($filename, $content)
	{
	}
	
	public function saveDocs(array $files)
	{
	}
	
	public static function GetInstance()
	{
		if(is_null(self::$instance)) self::$instance = new self;
		return self::$instance;
	}
}

?>
