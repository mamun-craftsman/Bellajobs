<?php
namespace App\controllers;
use Framework\Database;
class HomeControllers {
	protected $db;
	public function __construct() {
		$config = require BasePath('config/db_config.php');
		$this->db = new Database($config);
	}
	public function home() {
		$query = "SELECT * FROM listings ORDER BY created_at DESC limit 3";
		$listings = $this->db->query($query)->fetchAll();
		loadView('home', [
			'listings' => $listings
		]);
	}
}
?>