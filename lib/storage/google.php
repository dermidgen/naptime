<?php

namespace naptime\storage;

require_once('lib/storage/iStorageProvider.php');
require_once('Zend/Loader.php');

\Zend_Loader::loadClass('Zend_Gdata');
\Zend_Loader::loadClass('Zend_Gdata_AuthSub');
\Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
\Zend_Loader::loadClass('Zend_Gdata_Docs');
\Zend_Loader::loadClass('Zend_Gdata_Docs_Query');

class google implements iStorageProvider
{
	private static $instance = null;
	
	private $client = null;
	private $docs = null;

	public function __construct()
	{
		$config = \naptime\config::GetInstance();
		
		$service = \Zend_Gdata_Docs::AUTH_SERVICE_NAME;
  		$this->client = \Zend_Gdata_ClientLogin::getHttpClient($config->provider->google->username, $config->provider->google->password, $service);
  		$this->docs = new \Zend_Gdata_Docs($this->client);
	}

	public function getDoc($filename){}
	
	public function getDocs()
	{
		$query = new \Zend_Gdata_Docs_Query();
		$query->setQuery(".doc.md");
		$feed = $this->docs->getDocumentListFeed($query);
		$docs = Array();
		
		foreach($feed as $item)
		{
			array_push($docs, $item->title);
		}
		
		return $docs;
	}
	
	public function saveDoc($filename, $content){}
	public function saveDocs(array $files){}
	
	public static function GetInstance()
	{
		if(is_null(self::$instance)) self::$instance = new self;
		return self::$instance;
	}
}

?>
