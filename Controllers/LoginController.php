<?php
namespace Controllers;

use \Core\Controller;
use \Models\Users;

class LoginController extends Controller {

	public function index() {
		$array = array(
			'error' => ''
		);

		if(!empty($_SESSION['errorMsg'])) {
			$array['error'] = $_SESSION['errorMsg'];
			$_SESSION['errorMsg'] = '';
		}

		$this->loadView('login', $array);
	}

	public function index_action() {
		if(!empty($_POST['email']) && !empty($_POST['password'])) {
			$email = $_POST['email'];
			$password = $_POST['password'];

			$u = new Users();
			if($u->validateLogin($email, $password)) {
				header("Location: ".BASE_URL);
				exit;
			} else {
				$_SESSION['errorMsg'] = 'Usuário e/ou senha errados!';
			}
		} else {
			$_SESSION['errorMsg'] = 'Preencha os campos abaixo.';
		}
		
		header("Location: ".BASE_URL."login");
		exit;
	}

	public function logout() {

		unset($_SESSION['token']);
		header("Location: ".BASE_URL);
		exit;

	}



}















