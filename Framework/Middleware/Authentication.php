<?php
namespace Framework\Middleware;
use Framework\Session;

class Authentication {
	public function isAuthorized() {
		return Session::has('user');
	}
	public function handle($role) {
		if($role==='guest' && $this->isAuthorized()) {
			redirect('/');
			exit;
		}
		if($role==='auth' && $this->isAuthorized()===false) {
			echo "here";
			// debugDie($this->isAuthorized()===false);
			redirect('/auth/login');
			exit;
		}
	}
	
}
?>