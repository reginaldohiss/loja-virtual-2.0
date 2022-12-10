<?php
namespace Controllers;

use \Core\Controller;
use \Models\Users;
use \Models\Brands;

class BrandsController extends Controller {

	private $user;
	private $arrayInfo;

	public function __construct() {
		$this->user = new Users();

		if(!$this->user->isLogged()) {
			header("Location: ".BASE_URL."login");
			exit;
		}

		if(!$this->user->hasPermission('brands_view')) {
			header("Location: ".BASE_URL);
			exit;
		}

		$this->arrayInfo = array(
			'user' => $this->user,
			'menuActive' => 'brands'
		);

	}

	public function index() {
		$brands = new Brands();

		$this->arrayInfo['list'] = $brands->getAll(true);

		$this->loadTemplate('brands', $this->arrayInfo);
	}

	public function add() {
		$this->arrayInfo['errorItems'] = array();

		if(isset($_SESSION['formError']) && count($_SESSION['formError']) > 0) {
			$this->arrayInfo['errorItems'] = $_SESSION['formError'];
			unset($_SESSION['formError']);
		}

		$this->loadTemplate('brands_add', $this->arrayInfo);
	}

	public function add_action() {

		if(!empty($_POST['name'])) {
			$brands = new Brands();

			$name = $_POST['name'];

			$brands->add($name);

			header("Location: ".BASE_URL."brands");
			exit;

		} else {
			$_SESSION['formError'] = array('name');

			header("Location: ".BASE_URL."brands/add");
			exit;
		}

	}

	public function edit($id) {
		if(!empty($id)) {

			$brands = new Brands();
			$this->arrayInfo['info'] = $brands->get($id);

			$this->arrayInfo['errorItems'] = array();

			if(isset($_SESSION['formError']) && count($_SESSION['formError']) > 0) {
				$this->arrayInfo['errorItems'] = $_SESSION['formError'];
				unset($_SESSION['formError']);
			}

			if(count($this->arrayInfo['info']) > 0) {
				$this->loadTemplate('brands_edit', $this->arrayInfo);
			} else {
				header("Location: ".BASE_URL."brands");
				exit;
			}

		} else {
			header("Location: ".BASE_URL."brands");
			exit;
		}
	}

	public function edit_action($id) {
		if(!empty($id)) {

			if(!empty($_POST['name'])) {
				$brands = new Brands();

				$name = $_POST['name'];

				$brands->update($name, $id);

				header("Location: ".BASE_URL."brands");
				exit;

			} else {
				$_SESSION['formError'] = array('name');

				header("Location: ".BASE_URL."brands/edit/".$id);
				exit;
			}

		} else {
			header("Location: ".BASE_URL."brands");
			exit;
		}
	}

	public function del($id) {
		if(!empty($id)) {

			$brands = new Brands();
			$brands->del($id);

		}

		header("Location: ".BASE_URL."brands");
		exit;
	}















}