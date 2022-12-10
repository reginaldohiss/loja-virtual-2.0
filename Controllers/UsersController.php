<?php
namespace Controllers;

use \Core\Controller;
use \Models\Users;
use \Models\Brands;
use \Models\Permissions;

class UsersController extends Controller {

	private $user;
	private $arrayInfo;

	public function __construct() {
		$this->user = new Users();

		if(!$this->user->isLogged()) {
			header("Location: ".BASE_URL."login");
			exit;
		}

		if(!$this->user->hasPermission('users_view')) {
			header("Location: ".BASE_URL);
			exit;
		}

		$this->arrayInfo = array(
			'user' => $this->user,
			'menuActive' => 'users'
		);

	}

	public function index() {
		$users = new Users();
		$permissions = new Permissions();

		// FILTRO
		$this->arrayInfo['filter'] = array('name'=>'', 'permission'=>'');

		if(!empty($_GET['name'])) {
			$this->arrayInfo['filter']['name'] = $_GET['name'];
		}
		if(!empty($_GET['permission'])) {
			$this->arrayInfo['filter']['permission'] = $_GET['permission'];
		}

		// PAGINAÇÃO
		$this->arrayInfo['pag'] = array('currentpage'=>0, 'total'=>0, 'per_page'=>2);
		if(!empty($_GET['p'])) {
			$this->arrayInfo['pag']['currentpage'] = intval($_GET['p']) - 1;
		}

		$this->arrayInfo['permission_list'] = $permissions->getAllGroups();
		$this->arrayInfo['list'] = $users->getAll($this->arrayInfo['filter'], $this->arrayInfo['pag']);

		$this->arrayInfo['pag']['total'] = $users->getTotal($this->arrayInfo['filter']);

		$this->loadTemplate('users', $this->arrayInfo);
	}

	/*
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

	*/













}