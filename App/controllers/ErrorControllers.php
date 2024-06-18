<?php 
namespace App\Controllers;
class ErrorControllers {
	public static function unauthorized($message="You are not authorized to access this resource") {
		http_response_code (403);
		loadView('error', [
			'status'=>'403',
			'message' => $message
		]);
	}

	public static function notFound($message="Resource Not Found!") {
		http_response_code (404);
		loadView('error', [
			'status'=>'404',
			'message' => $message
		]);
	}
}
?>