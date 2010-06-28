<?php

namespace naptime\views;

class auth extends \PASL\Web\Simpl\Page
{
	private static $instance = null;
	
	public function __construct()
	{
		\naptime::GetInstance()->PageTemplate = 'login_template.html';
	}
	
	public function run()
	{
		\naptime::GetInstance()->pageDescription = 'Please login to access the management features';
				
		if (isset($_POST['username']) && isset($_POST['password'])) {
			if ($_POST['username'] == 'root' && $_POST['password'] == 'password') {
				$_SESSION['logged_in'] = true;
				
				header("Location: /admin");
				exit;
			}
			else \naptime\notices::GetInstance()->addNotice('Nope, wrong. Try again.', 'error');
		}
		
		$this->body = $this->loadAndParse('login.html');
	}
	
	public static function GetInstance()
	{
		if (is_null(self::$instance)) self::$instance = new self;
		return self::$instance;
	}
}

?>
