<?php
namespace Models;

use \Core\Model;

class Categories extends Model {

	public function getAll() {
		$array = array();

		$sql = "SELECT * FROM categories ORDER BY sub DESC";
		$sql = $this->db->query($sql);

		if($sql->rowCount() > 0) {
			$data = $sql->fetchAll(\PDO::FETCH_ASSOC);

			foreach($data as $item) {
				$item['subs'] = array();
				$array[$item['id']] = $item;
			}

			while( $this->stillNeed($array) ) {
				$this->organizeCategory($array);
			}

		}

		return $array;
	}

	private function organizeCategory(&$array) {

		foreach($array as $id => $item) {
			if(!empty($item['sub'])) {
				$array[$item['sub']]['subs'][$item['id']] = $item;
				unset($array[$id]);
				break;
			}
		}

	}

	private function stillNeed($array) {
		foreach($array as $item) {
			if(!empty($item['sub'])) {
				return true;
			}
		}

		return false;
	}

	public function add($name, $sub) {

		$sql = "INSERT INTO categories (name, sub) VALUES (:name, :sub)";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':name', $name);
		$sql->bindValue(':sub', $sub);
		$sql->execute();

	}

	public function update($name, $sub, $id) {
		$sql = "UPDATE categories SET name = :name, sub = :sub WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':name', $name);
		$sql->bindValue(':sub', $sub);
		$sql->bindValue(':id', $id);
		$sql->execute();
	}

	public function get($id) {
		$array = array();

		$sql = "SELECT name, sub FROM categories WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$array = $sql->fetch(\PDO::FETCH_ASSOC);
		}

		return $array;
	}

	public function scanCategories($id, $cats = array()) {
		if(!in_array($id, $cats)) {
			$cats[] = $id;
		}

		$sql = "SELECT id FROM categories WHERE sub = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$data = $sql->fetchAll();

			foreach($data as $item) {
				if(!in_array($item['id'], $cats)) {
					$cats[] = $item['id'];
				}

				$cats = $this->scanCategories($item['id'], $cats);
			}
		}

		return $cats;
	}

	public function hasProducts($array) {
		$sql = "SELECT COUNT(*) as c FROM products WHERE 
		id_category IN (".implode(',', $array).")";
		$sql = $this->db->query($sql);

		$data = $sql->fetch();

		if(intval($data['c']) > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function deleteCategories($array) {
		$sql = "DELETE FROM categories WHERE id IN (".implode(',', $array).")";
		$sql = $this->db->query($sql);
	}

}













