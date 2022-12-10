<?php
namespace Controllers;

use \Core\Controller;
use \Models\Users;
use \Models\Categories;

class CategoriesController extends Controller {

	private $user;
	private $arrayInfo;

	public function __construct() {
		$this->user = new Users();

		if(!$this->user->isLogged()) {
			header("Location: ".BASE_URL."login");
			exit;
		}

		if(!$this->user->hasPermission('categories_view')) {
			header("Location: ".BASE_URL);
			exit;
		}

		$this->arrayInfo = array(
			'user' => $this->user,
			'menuActive' => 'categories'
		);

	}

	public function index() {
		$cat = new Categories();

		$this->arrayInfo['list'] = $cat->getAll();

		$this->loadTemplate('categories', $this->arrayInfo);
	}

	public function add() {
		$cat = new Categories();
		$this->arrayInfo['errorItems'] = array();
		$this->arrayInfo['list'] = $cat->getAll();

		if(isset($_SESSION['formError']) && count($_SESSION['formError']) > 0) {
			$this->arrayInfo['errorItems'] = $_SESSION['formError'];
			unset($_SESSION['formError']);
		}

		$this->loadTemplate('categories_add', $this->arrayInfo);
	}

	public function add_action() {
		if(!empty($_POST['name'])) {

			$name = $_POST['name'];
			$sub = '';

			if(!empty($_POST['sub'])) {
				$sub = $_POST['sub'];
			}

			$cat = new Categories();
			$cat->add($name, $sub);

			header("Location: ".BASE_URL."categories");
			exit;

		} else {
			$_SESSION['formError'] = array('name');

			header("Location: ".BASE_URL."categories/add");
			exit;
		}
	}

	public function edit($id) {

		if(!empty($id)) {

			$cat = new Categories();
			$this->arrayInfo['errorItems'] = array();
			$this->arrayInfo['list'] = $cat->getAll();

			$this->arrayInfo['catInfo'] = $cat->get($id);
			$this->arrayInfo['id'] = $id;

			if(count($this->arrayInfo['catInfo']) > 0) {
				if(isset($_SESSION['formError']) && count($_SESSION['formError']) > 0) {
					$this->arrayInfo['errorItems'] = $_SESSION['formError'];
					unset($_SESSION['formError']);
				}

				$this->loadTemplate('categories_edit', $this->arrayInfo);
			} else {
				header("Location: ".BASE_URL."categories");
				exit;
			}

		} else {
			header("Location: ".BASE_URL."categories");
			exit;
		}

	}

	public function edit_action($id) {

		if(!empty($id)) {

			if(!empty($_POST['name'])) {

				$name = $_POST['name'];
				$sub = '';

				if(!empty($_POST['sub'])) {
					$sub = $_POST['sub'];
				}

				$cat = new Categories();

				$cat->update($name, $sub, $id);

				header("Location: ".BASE_URL."categories");
				exit;

			} else {
				$_SESSION['formError'] = array('name');

				header("Location: ".BASE_URL."categories/edit/".$id);
				exit;
			}

		} else {
			header("Location: ".BASE_URL."categories/edit/".$id);
			exit;
		}

	}

	public function del($id) {

		if(!empty($id)) {

			$cat = new Categories();

			$cats = $cat->scanCategories($id);

			if($cat->hasProducts($cats) == false) {
				$cat->deleteCategories($cats);
			}

		}
		
		header("Location: ".BASE_URL."categories");
		exit;

	}

















}