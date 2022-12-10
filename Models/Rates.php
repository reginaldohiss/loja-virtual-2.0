<?php
namespace Models;

use \Core\Model;

class Rates extends Model {

	public function getRatesFromProduct($id_product) {
		$array = array();

		if(!empty($id_product)) {

			$sql = "SELECT 
				rates.id,
				rates.date_rated,
				rates.points,
				rates.comment,
				users.name
			FROM rates
			LEFT JOIN users ON users.id = rates.id_user
			WHERE rates.id_product = :id";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id_product);
			$sql->execute();

			if($sql->rowCount() > 0) {
				$array = $sql->fetchAll(\PDO::FETCH_ASSOC);
			}

		}

		return $array;
	}

	public function del($id_rate) {
		$sql = "SELECT id_product FROM rates WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id_rate);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$data = $sql->fetch();

			$sql = "DELETE FROM rates WHERE id = :id";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id_rate);
			$sql->execute();

			$rating = 0;

			$sql = "SELECT * FROM rates WHERE id_product = :id_product";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id_product', $data['id_product']);
			$sql->execute();

			if($sql->rowCount() > 0) {
				$total = $sql->rowCount();
				$soma = 0;

				$rdata = $sql->fetchAll();
				foreach($rdata as $item) {
					$soma += intval($item['points']);
				}

				$rating = $soma / $total;
			}

			$sql = "UPDATE products SET rating = :rating WHERE id = :id";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':rating', $rating);
			$sql->bindValue(':id', $data['id_product']);
			$sql->execute();

			return $data['id_product'];

		}

		return 0;
		
	}

}













