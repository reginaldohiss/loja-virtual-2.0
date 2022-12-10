<?php
namespace Models;

use \Core\Model;

class Products extends Model {

	public function get($id) {
		$array = array();

		$sql = "SELECT * FROM products WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$array = $sql->fetch(\PDO::FETCH_ASSOC);

			// Pegando as OPTIONS do produto
			$sql = "SELECT id_option, p_value FROM products_options WHERE id_product = :id";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->execute();

			if($sql->rowCount() > 0) {
				$ops = $sql->fetchAll(\PDO::FETCH_ASSOC);

				$array['options'] = array();
				foreach($ops as $item) {
					$array['options'][$item['id_option']] = $item['p_value'];
				}
			}

			// Pegando as IMAGENS do produto
			$sql = "SELECT id, url FROM products_images WHERE id_product = :id";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->execute();

			if($sql->rowCount() > 0) {
				$imgs = $sql->fetchAll(\PDO::FETCH_ASSOC);

				$array['images'] = array();
				foreach($imgs as $item) {
					$array['images'][$item['id']] = BASE_URL_SITE.'media/products/'.$item['url'];
				}
			}
		}

		return $array;
	}

	public function getAll() {
		$array = array();

		$cat = new Categories();
		$brands = new Brands();

		$sql = "SELECT id, id_category, id_brand, name, stock, price, price_from FROM products";
		$sql = $this->db->query($sql);

		if($sql->rowCount() > 0) {
			$array = $sql->fetchAll(\PDO::FETCH_ASSOC);

			foreach($array as $key => $item) {
				$catInfo = $cat->get($item['id_category']);
				$brandInfo = $brands->get($item['id_brand']);

				$array[$key]['name_category'] = $catInfo['name'];
				$array[$key]['name_brand'] = $brandInfo['name'];
			}
		}

		return $array;
	}

	public function add($id_category,
					$id_brand,
					$name,
					$description,
					$stock,
					$price_from,
					$price,

					$weight,
					$width,
					$height,
					$length,
					$diameter,

					$featured,
					$sale,
					$bestseller,
					$new_product,
					
					$options,
					$images) {

		if(!empty($id_category) && !empty($id_brand) && !empty($name) && !empty($stock) && !empty($price)) {

			$options_selected = array();
			foreach($options as $optk => $opt) {
				if(!empty($opt)) {
					$options_selected[$optk] = $opt;
				}
			}

			$options_ids = implode(',', array_keys($options_selected));

			$sql = "INSERT INTO products (id_category, id_brand, name, description, stock, price, price_from, featured, sale, bestseller, new_product, options, weight, width, height, length, diameter) VALUES (:id_category, :id_brand, :name, :description, :stock, :price, :price_from, :featured, :sale, :bestseller, :new_product, :options, :weight, :width, :height, :length, :diameter)";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id_category', $id_category);
			$sql->bindValue(':id_brand', $id_brand);
			$sql->bindValue(':name', $name);
			$sql->bindValue(':description', $description);
			$sql->bindValue(':stock', $stock);
			$sql->bindValue(':price', $price);
			$sql->bindValue(':price_from', $price_from);
			$sql->bindValue(':featured', $featured);
			$sql->bindValue(':sale', $sale);
			$sql->bindValue(':bestseller', $bestseller);
			$sql->bindValue(':new_product', $new_product);
			$sql->bindValue(':options', $options_ids);
			$sql->bindValue(':weight', $weight);
			$sql->bindValue(':width', $width);
			$sql->bindValue(':height', $height);
			$sql->bindValue(':length', $length);
			$sql->bindValue(':diameter', $diameter);
			$sql->execute();

			$id = $this->db->lastInsertId();

			foreach($options_selected as $optk => $opt) {

				$sql = "INSERT INTO products_options (id_product, id_option, p_value) VALUES (:id_product, :id_option, :p_value)";
				$sql = $this->db->prepare($sql);
				$sql->bindValue(':id_product', $id);
				$sql->bindValue(':id_option', $optk);
				$sql->bindValue(':p_value', $opt);
				$sql->execute();

			}

			// Adicionar as imagens
			$allowed_images = array(
				'image/jpeg',
				'image/jpg',
				'image/png'
			);
			for($q=0;$q<count($images['name']);$q++) {

				$tmp_name = $images['tmp_name'][$q];
				$type = $images['type'][$q];

				if(in_array($type, $allowed_images)) {

					$this->addProductImage( $id, $tmp_name, $type );

				}

			}

		}

	}

	public function edit($id_category,
					$id_brand,
					$name,
					$description,
					$stock,
					$price_from,
					$price,

					$weight,
					$width,
					$height,
					$length,
					$diameter,

					$featured,
					$sale,
					$bestseller,
					$new_product,
					
					$options,
					$images,
					$c_images,

					$id) {

		if(!empty($id) && !empty($id_category) && !empty($id_brand) && !empty($name) && !empty($stock) && !empty($price)) {

			$options_selected = array();
			foreach($options as $optk => $opt) {
				if(!empty($opt)) {
					$options_selected[$optk] = $opt;
				}
			}

			$options_ids = implode(',', array_keys($options_selected));

			$sql = "UPDATE products SET id_category = :id_category, id_brand = :id_brand, name = :name, description = :description, stock = :stock, price = :price, price_from = :price_from, featured = :featured, sale = :sale, bestseller = :bestseller, new_product = :new_product, options = :options, weight = :weight, width = :width, height = :height, length = :length, diameter = :diameter WHERE id = :id";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id_category', $id_category);
			$sql->bindValue(':id_brand', $id_brand);
			$sql->bindValue(':name', $name);
			$sql->bindValue(':description', $description);
			$sql->bindValue(':stock', $stock);
			$sql->bindValue(':price', $price);
			$sql->bindValue(':price_from', $price_from);
			$sql->bindValue(':featured', $featured);
			$sql->bindValue(':sale', $sale);
			$sql->bindValue(':bestseller', $bestseller);
			$sql->bindValue(':new_product', $new_product);
			$sql->bindValue(':options', $options_ids);
			$sql->bindValue(':weight', $weight);
			$sql->bindValue(':width', $width);
			$sql->bindValue(':height', $height);
			$sql->bindValue(':length', $length);
			$sql->bindValue(':diameter', $diameter);
			$sql->bindValue(':id', $id);
			$sql->execute();

			$sql = "DELETE FROM products_options WHERE id_product = :id";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->execute();

			foreach($options_selected as $optk => $opt) {

				$sql = "INSERT INTO products_options (id_product, id_option, p_value) VALUES (:id_product, :id_option, :p_value)";
				$sql = $this->db->prepare($sql);
				$sql->bindValue(':id_product', $id);
				$sql->bindValue(':id_option', $optk);
				$sql->bindValue(':p_value', $opt);
				$sql->execute();

			}

			// Deletar as imagens não-presentes
			if(is_array($c_images)) {
				foreach($c_images as $ckey => $cimg) {
					$c_images[$ckey] = intval($cimg);
				}

				$sql = "DELETE FROM products_images WHERE id_product = :id AND id NOT IN (".implode(',', $c_images).")";
				$sql = $this->db->prepare($sql);
				$sql->bindValue(':id', $id);
				$sql->execute();
			}			

			// Adicionar as imagens
			$allowed_images = array(
				'image/jpeg',
				'image/jpg',
				'image/png'
			);
			for($q=0;$q<count($images['name']);$q++) {

				$tmp_name = $images['tmp_name'][$q];
				$type = $images['type'][$q];

				if(in_array($type, $allowed_images)) {

					$this->addProductImage( $id, $tmp_name, $type );

				}

			}

		}

	}

	public function addProductImage($id, $tmp_name, $type) {

		switch($type) {
			case 'image/jpg':
			case 'image/jpeg':
				$o_img = imagecreatefromjpeg($tmp_name);
				break;
			case 'image/png':
				$o_img = imagecreatefrompng($tmp_name);
				break;
		}

		if(!empty($o_img)) {

			$width = 460;
			$height = 400;
			$ratio = $width / $height;

			list($o_width, $o_height) = getimagesize($tmp_name);

			$o_ratio = $o_width / $o_height;

			if($ratio > $o_ratio) {
				$img_w = $height * $o_ratio;
				$img_h = $height;
			} else {
				$img_h = $width / $o_ratio;
				$img_w = $width;
			}

			if($img_w < $width) {
				$img_w = $width;
				$img_h = $img_w / $o_ratio;
			}
			if($img_h < $height) {
				$img_h = $height;
				$img_w = $img_h * $o_ratio;
			}

			$px = 0;
			$py = 0;

			if($img_w > $width) {
				$px = ($img_w - $width) / 2;
			}

			if($img_h > $height) {
				$py = ($img_h - $height) / 2;
			}


			$img = imagecreatetruecolor( $width, $height );
			imagecopyresampled($img, $o_img, -$px, -$py, 0, 0, $img_w, $img_h, $o_width, $o_height);

			$filename = md5(time().rand(0,999).rand(0,999)).'.jpg';

			imagejpeg($img, PATH_SITE.'media/products/'.$filename);

			$sql = "INSERT INTO products_images (id_product, url) VALUES (:id_product, :url)";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id_product', $id);
			$sql->bindValue(':url', $filename);
			$sql->execute();

		}

	}

	public function del($id) {

		$sql = "UPDATE products SET stock = 0 WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

	}

	/*
	public function get($id) {
		$array = array();

		$sql = "SELECT * FROM brands WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$array = $sql->fetch(\PDO::FETCH_ASSOC);
		}

		return $array;
	}

	public function update($name, $id) {

		$sql = "UPDATE brands SET name = :name WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':name', $name);
		$sql->bindValue(':id', $id);
		$sql->execute();

	}

	public function del($id) {

		$sql = "SELECT count(*) as c FROM products WHERE id_brand = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();
		$data = $sql->fetch();

		if($data['c'] == '0') {
			$sql = "DELETE FROM brands WHERE id = :id";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->execute();
		}

	}
	*/

}













