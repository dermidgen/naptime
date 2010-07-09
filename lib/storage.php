<?php

namespace naptime;

class storage
{
	public static function providerFactory($providerName)
	{
		$filename = $providerName.'.php';
		require_once('lib/storage/'.$filename);
		
		switch($providerName)
		{
			case "local":
				return new \naptime\storage\local;
			break;
			case "google":
				return \naptime\storage\google::GetInstance();
			default: return null;
		}
	}
}

?>
