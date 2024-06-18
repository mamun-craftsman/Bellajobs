<?php
namespace App\controllers;
use Framework\Database;
use Framework\Validator;
use Framework\Session;
class ListingControllers {
	protected $db;
	protected $id;
	public function __construct() {
		$config = require basePath('config/db_config.php');
		$this->db = new Database($config);
	}
	public function index() {
		$query = "SELECT * FROM listings";
		$listings = $this->db->query($query)->fetchAll();
		loadView('listings/index', [
			'listings' => $listings
		]);
	}
	public function details($params) {
		$query = "SELECT *FROM listings WHERE id=:id";
		$listing = $this->db->query($query, $params)->fetch();
		if(!$listing) {
			ErrorControllers::notFound();
			return;
		}
		loadView('listings/details', [
			'listing' => $listing
		]);
	}
	public function create(){
		loadView('listings/create');
	}
	public function storeData() {
		$listingData = $_POST;
		$currUser = Session::get('user')['id'];
		$listingData['user_id']=$currUser;
		$listingData= array_map('dataFilter', $listingData);
		// $listingData = array_merge(array('user_id' => $listingData['user_id']), $listingData);
		$errors=[];

		$field_required = [
			'title',
			'email',
			'description',
			'city',
			'state'
		];
		// debugDie($listingData);
		foreach($field_required as $field) {
			if(empty($listingData[$field]) || !Validator::String($listingData[$field])) {
				$errors[$field] = ucfirst($field)." is required";
			}
		}
		if (empty($errors)) {
			$fields = [];
			$values = [];
			foreach ($listingData as $key => $value) {
				$fields[] = $key;
				if ($value === '') {
					$listingData[$key] = null;
				}
				$values[] = ':' . $key;
			}
			$fields = implode(', ', $fields);
			$values = implode(', ', $values);
			debug($values);
		
			$query = "INSERT INTO listings ({$fields}) VALUES ({$values})";
			$messages=null;
			try {
				$this->db->query($query, $listingData);
				$messages = "Job added Successfully";
			} catch (Exception $e) {
				echo 'Error: ' . $e->getMessage();
				$messages = "Job added Failed";
			}
			$_SESSION['session_message']=$messages;
			redirect("\listings");
		}
		else {
			loadView('listings/create', 
			[
				'errors'=>$errors,
				'listingData'=>$listingData
			]);
		}

	}



	public function delete($params) {
		$currUser = strval(Session::get('user')['id']??-1);

		$jobUser=$this->db->query("SELECT user_id FROM listings WHERE id=:id",$params)->fetch();
		if(strval($jobUser['user_id'])!==$currUser) {
			ErrorControllers::unauthorized();
			return;
		}
		$query="DELETE FROM listings where id=:id";
		$this->db->query($query, $params);
		$_SESSION['session_message']='Job deleted successfully';
		redirect('\listings');
		
	}


	public function edit($params) {
		$currUser = (int) (Session::get('user')['id'] ?? -1);
		$jobUserId=$this->db->query("SELECT user_id FROM listings WHERE id=:id",$params)->fetch();
		if($jobUserId['user_id']!==$currUser) {
			ErrorControllers::unauthorized();
			return;
		}
		$params['currUser'] = $currUser;
		$query = "SELECT *FROM listings WHERE id=:id && user_id=:currUser";
		$listing = $this->db->query($query, $params)->fetch();
		if(!$listing) {
			ErrorControllers::notFound();
			return;
		}
		loadView('listings/edit', [
			'listing' => $listing
		]);
	}


	public function update($params) {
		$listingData = $_POST;
		$currUser = (int) (Session::get('user')['id'] ?? -1);
		$jobUserId=$this->db->query("SELECT user_id FROM listings WHERE id=:id",$params)->fetch();
		if($jobUserId['user_id']!==$currUser) {
			ErrorControllers::unauthorized();
			return;
		}
		$listingData['user_id']=$currUser;
		$listingData['id'] = $params['id'];
		$listingData= array_map('dataFilter', $listingData);
		// $listingData = array_merge(array('user_id' => $listingData['user_id']), $listingData);
		$errors=[];

		$field_required = [
			'title',
			'email',
			'description',
			'city',
			'state'
		];
		// debugDie($listingData);
		foreach($field_required as $field) {
			if(empty($listingData[$field]) || !Validator::String($listingData[$field])) {
				$errors[$field] = ucfirst($field)." is required";
			}
		}
		$updatedFields=[];
		if (empty($errors)) {
			$fields = [];
			foreach ($listingData as $key => $value) {
				if($key==='_method')continue;
				if ($value === '') {
					$listingData[$key] = null;
				}
				$fields[] = "{$key}=:{$key}";
				$updatedFields[$key] = $value;
			}
			$fields = implode(', ', $fields);
			
			$query = "UPDATE listings SET $fields where id=:id";
			// debugDie($query);
			$messages=null;
			try {
				$this->db->query($query, $updatedFields);
				$messages = "Job Updated Successfully";
			} catch (Exception $e) {
				echo 'Error: ' . $e->getMessage();
				$messages = "Job added Failed";
			}
			$_SESSION['session_message']=$messages;
			$this->details($params);

		}
		else {
			loadView('listings/create', 
			[
				'errors'=>$errors,
				'listingData'=>$listingData
			]);
		}

	}


	public function search() {
		$params = [];
		$keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : null;
		$location = isset($_GET['location']) ? trim($_GET['location']) : null;
		$params=[
			'keywords'=> "%{$keywords}%",
			'location'=>$location
		];
		$query = "SELECT * FROM listings WHERE title LIKE :keywords OR tags LIKE :keywords OR city = :location OR state = :location";
		$newlistings = $this->db->query($query, $params)->fetchAll();
		loadView('listings/index', [
			'listings'=>$newlistings,
			'keywords'=>$keywords,
			'location'=>$location

		]);
		return;

	}
}