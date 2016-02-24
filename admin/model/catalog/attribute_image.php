<?php 
class ModelCatalogAttributeImage extends Model {
	public function addAttributeImage($data) {
		$this->event->trigger('pre.admin.add.attribute.image', $data);
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_image SET attribute_image_group_id = '" . (int)$data['attribute_image_group_id'] . "', added_image = '".$data['image']."', sort_order = '" . (int)$data['sort_order'] . "'");

		$attribute_image_id = $this->db->getLastId();

		foreach ($data['attribute_image_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_image_description SET attribute_image_id = '" . (int)$attribute_image_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->event->trigger('post.admin.add.attribute.image', $attribute_image_id);

		return $attribute_image_id;
	}

	public function editAttributeImage($attribute_image_id, $data) {
		$this->event->trigger('pre.admin.edit.attribute.image', $data);
	
		$sql = "UPDATE " . DB_PREFIX . "attribute_image SET attribute_image_group_id = '" . (int)$data['attribute_image_group_id'] . "', sort_order = '" . (int)$data['sort_order']."'" ;
		
		$sql.= " , added_image = '".$data['image']."'";
		
		$sql.= " WHERE attribute_image_id = '" . (int)$attribute_image_id . "'";
		
		$this->db->query($sql);

		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_image_description WHERE attribute_image_id = '" . (int)$attribute_image_id . "'");

		foreach ($data['attribute_image_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_image_description SET attribute_image_id = '" . (int)$attribute_image_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->event->trigger('post.admin.edit.attribute.image', $attribute_image_id);
	}

	public function deleteAttributeImage($attribute_image_id) {
		$this->event->trigger('pre.admin.delete.attribute.image', $attribute_image_id);
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_image WHERE attribute_image_id = '" . (int)$attribute_image_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_image_description WHERE attribute_image_id = '" . (int)$attribute_image_id . "'");
		
		$this->event->trigger('post.admin.delete.attribute.image', $attribute_image_id);
	}

	public function getAttributeImage($attribute_image_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_image ai LEFT JOIN " . DB_PREFIX . "attribute_image_description aid ON (ai.attribute_image_id = aid.attribute_image_id) WHERE ai.attribute_image_id = '" . (int)$attribute_image_id . "' AND aid.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getAttributeImages($data = array()) {
		$sql = "SELECT *, (SELECT aigd.name FROM " . DB_PREFIX . "attribute_image_group_description aigd WHERE aigd.attribute_image_group_id = ai.attribute_image_group_id AND aigd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_image_group FROM " . DB_PREFIX . "attribute_image ai LEFT JOIN " . DB_PREFIX . "attribute_image_description aid ON (ai.attribute_image_id = aid.attribute_image_id) WHERE aid.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND aid.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_attribute_image_group_id'])) {
			$sql .= " AND ai.attribute_image_group_id = '" . $this->db->escape($data['filter_attribute_image_group_id']) . "'";
		}

		$sort_data = array(
			'aid.name',
			'attribute_image_group',
			'ai.sort_order'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY attribute_image_group, aid.name";	
		}	

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getAttributeImageDescriptions($attribute_image_id) {
		$attribute_image_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_image_description WHERE attribute_image_id = '" . (int)$attribute_image_id . "'");

		foreach ($query->rows as $result) {
			$attribute_image_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $attribute_image_data;
	}

	public function getAttributeImagesByAttributeImageGroupId($data = array()) {
		$sql = "SELECT *, (SELECT aigd.name FROM " . DB_PREFIX . "attribute_image_group_description aigd WHERE aigd.attribute_image_group_id = ai.attribute_image_group_id AND aigd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_image_group FROM " . DB_PREFIX . "attribute_image ai LEFT JOIN " . DB_PREFIX . "attribute_image_description aid ON (ai.attribute_image_id = aid.attribute_image_id) WHERE aid.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND aid.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_attribute_image_group_id'])) {
			$sql .= " AND ai.attribute_image_group_id = '" . $this->db->escape($data['filter_attribute_image_group_id']) . "'";
		}

		$sort_data = array(
			'aid.name',
			'attribute_image_group',
			'ai.sort_order'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY aid.name";	
		}	

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalAttributeImages() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute_image");

		return $query->row['total'];
	}	

	public function getTotalAttributeImagesByAttributeImageGroupId($attribute_image_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute_image WHERE attribute_image_group_id = '" . (int)$attribute_image_group_id . "'");

		return $query->row['total'];
	}	
	public function getTotalProductsByAttributeImageId($attribute_image_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute_image WHERE attribute_image_id = '" . (int)$attribute_image_id . "'");

		return $query->row['total'];
	}			
}
?>