<?php
namespace Models;

use \Core\Model;

class Pages extends Model {

	public function getAll() {
		$array = array();

		$sql = "SELECT id, title FROM pages";
		$sql = $this->db->query($sql);

		if($sql->rowCount() > 0) {
			$array = $sql->fetchAll(\PDO::FETCH_ASSOC);
		}

		return $array;
	}

	public function get($id) {
		$array = array();

		$sql = "SELECT * FROM pages WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$array = $sql->fetch(\PDO::FETCH_ASSOC);
		}

		return $array;
	}

	public function add($title, $body) {

		$sql = "INSERT INTO pages (title, body) VALUES (:title, :body)";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':title', $title);
		$sql->bindValue(':body', $body);
		$sql->execute();

	}

	public function del($id) {

		$sql = "DELETE FROM pages WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

	}

	public function update($title, $body, $id) {

		$sql = "UPDATE pages SET title = :title, body = :body WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':title', $title);
		$sql->bindValue(':body', $body);
		$sql->bindValue(':id', $id);
		$sql->execute();

	}

}













