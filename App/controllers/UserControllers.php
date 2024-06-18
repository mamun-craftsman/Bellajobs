<?php
namespace App\controllers;
use Framework\Database;
use Framework\Session;
use Framework\Validator;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
basePath('vendor/phpmailer/phpmailer/src/PHPMailer.php');
basePath('vendor/phpmailer/phpmailer/src/Exception.php');
basePath('vendor/phpmailer/phpmailer/src/SMTP.php');
//Load Composer's autoloader
require basePath('vendor/autoload.php');
class UserControllers{
	protected $db;
	public function __construct() {
		$config = require BasePath('config/db_config.php');
		$this->db = new Database($config);
	}
	public function loginView() {
		loadView('user\login');
	}
	public function login() {
		$email = $_POST['email'];
		$password = $_POST['password'];
		$errors=[];
		if(!Validator::string($password)) {
			$errors['password']="Password Can't be empty";
		}
		if(!Validator::email($email)) {
			$errors['email']="Enter a valid email";
		}
		$params=[
			'email'=>$email
		];
		if(!empty($errors)) {
			loadView('user\login',[
				'errors'=>$errors,
				'value'=>$params
			]);
			exit;
		}
		$user = $this->db->query("SELECT * FROM users where email=:email", $params)->fetch();
		
		if(isset($user)){
			if($user==false || !Validator::match($user['email'], $email)||!password_verify($password, $user['password'])) {
				$errors['message']="Wrong Credintails";
			}
			elseif(!Validator::match($user['isVerified'], 'YES')) {
				$errors['message']="Your mail is not verified. Please check email and verify to log in";
			}
		}
		if(!empty($errors)) {
			loadView('user\login',[
				'errors'=>$errors,
				'value'=>$params
			]);
			exit;
		}
		Session::start();
		Session::set('user',[
			'id'=>$user['id'],
			'name'=>$user['name'],
			'email'=>$user['email'],
			'city'=>$user['city'],
			'state'=>$user['state']
		]);
		redirect('/');
	}
	public function register() {
		loadview('user/register');
	}
	public function regDB() {
		$userData=$_POST;
		array_filter($userData, 'dataFilter');
		$requiredField=[
			'name',
			'email',
			'password',
			'password_confirmation'
		];
		$error=[];
		foreach($requiredField as $field) {
			if(!isset($userData[$field])) {
				$error[$field]=ucfirst($field)."can not be blank";
			}
		}
		$email = $userData['email'];
		$name = $userData['name'];
		$password = $userData['password'];
		$confirmedPass = $userData['password_confirmation'];
		if(Validator::email($email)===false) {
			$error['email'] = "Enter a valid email";
		}
		if(Validator::string($name,2,26)===false) {
			$error['name'] = "Length of the name should be between 2 to 26";
		}
		if(Validator::string($password, 6)===false) {
			$error['password'] = "Password should be at least 6 characters";
		}
		if(Validator::match($password, $confirmedPass)===false) {
			$error['password_confirmation'] = "Password not confirmed or not matched";
		}
		$params=[
			'email'=>$email
		];
		$userExist = $this->db->query("SELECT * FROM users WHERE email=:email", $params)->fetch();
		if($userExist) {
			$error['email'] = "User already Exists";
		}
		if(!empty($error)) {
			loadview('user\register',[
				'data'=>$userData,
				'error'=>$error
			]);
			exit;
		}
		
		$mail = new PHPMailer(true); // Passing `true` enables exceptions
		try {
			//Server settings
			$mail->SMTPDebug = 0; // Enable verbose debug output
			$mail->isSMTP(); // Send using SMTP
			$mail->SMTPSecure = 'tls';
			$mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
			$mail->SMTPAuth   = true; // Enable SMTP authentication
			$mail->Username   = 'wolfhumi@gmail.com'; // SMTP username
			$mail->Password   = 'fbgy tkle romg wqch '; // SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption
			$mail->Port       = 465; // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
		
			// $mail->SMTPOptions = array(
			// 	'ssl' => array(
			// 		'verify_peer' => false,
			// 		'verify_peer_name' => false,
			// 		'allow_self_signed' => true
			// 	)
			// );

			//Recipients
			$mail->setFrom('wolfhumi@gmail.com', 'Md. Mamun'); // Sender's email address and name
			$mail->addAddress($email); // Recipient's email address
			$code = hash('sha256', random_bytes(32)); // Generate verification code
		
			//Content
			$mail->isHTML(true); // Set email format to HTML
			$mail->Subject = 'Verification Link';
			$mail->Body    = 'Here is the verification link: <b><a href="http://localhost:8000/auth/login/?verification='.$code.'"><button>Click</button></a></b>';
		
			$mail->send();
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}

		$col=[];
		$val=[];
		$dbData=[];
		$allowedField=[
			'name',
			'email',
			'password',
			'city',
			'state'
		];
		foreach($userData as $key=>$value) {
			if(in_array($key, $allowedField)) {
				$col[]=$key;
				$val[]=":".$key;
				if($key==='password'){
					$value = password_hash($value, PASSWORD_DEFAULT);
				}
				$dbData[$key]=$value;
			}
		}
		$val[]=":code";
		$col[]="code";
		$dbData['code']=$code;
		$column = implode(',',$col);
		$value = implode(',', $val);
		$query = "INSERT INTO users ({$column}) VALUES ({$value})";
		
		$this->db->query($query, $dbData);
		// $userId = $this->db->conn->lastInsertId();
		// Session::start();
		// Session::set('user',[
		// 	'id'=>$userId,
		// 	'name'=>$name,
		// 	'email'=>$email,
		// 	'city'=>$userData['city'],
		// 	'state'=>$userData['state']
		// ]);
		loadView('user/login',[
			'msg'=>"Verification code sent to your mail. Please verify your mail to log in"
		]);
	}

	public function logout() {
		Session::clearAll();
		$params = session_get_cookie_params();
		setcookie('PHPSESSID','',time()-86400,$params['path'], $params['domain']);
		redirect('\auth\login');
	}
	
}
?>