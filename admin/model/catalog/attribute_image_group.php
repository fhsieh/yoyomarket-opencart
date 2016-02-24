<?php 
class ModelCatalogAttributeImageGroup extends Model {
	public function addAttributeImageGroup($data) {
		$this->event->trigger('pre.admin.add.attribute.image.group', $data);
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_image_group SET sort_order = '" . (int)$data['sort_order'] . "'");

		$attribute_image_group_id = $this->db->getLastId();

		foreach ($data['attribute_image_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_image_group_description SET attribute_image_group_id = '" . (int)$attribute_image_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->event->trigger('post.admin.add.attribute.image.group', $attribute_image_group_id);
		
		return $attribute_image_group_id;
		
	}

	public function editAttributeImageGroup($attribute_image_group_id, $data) {
		$this->event->trigger('pre.admin.edit.attribute.image.group', $data);
		
		$this->db->query("UPDATE " . DB_PREFIX . "attribute_image_group SET sort_order = '" . (int)$data['sort_order'] . "' WHERE attribute_image_group_id = '" . (int)$attribute_image_group_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_image_group_description WHERE attribute_image_group_id = '" . (int)$attribute_image_group_id . "'");

		foreach ($data['attribute_image_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_image_group_description SET attribute_image_group_id = '" . (int)$attribute_image_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->event->trigger('post.admin.edit.attribute.image.group', $attribute_image_group_id);
	}

	public function deleteAttributeImageGroup($attribute_image_group_id) {
		$this->event->trigger('pre.admin.delete.attribute.image.group', $attribute_image_group_id);
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_image_group WHERE attribute_image_group_id = '" . (int)$attribute_image_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_image_group_description WHERE attribute_image_group_id = '" . (int)$attribute_image_group_id . "'");
		
		$this->event->trigger('post.admin.delete.attribute.image.group', $attribute_image_group_id);
	}

	public function getAttributeImageGroup($attribute_image_group_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_image_group WHERE attribute_image_group_id = '" . (int)$attribute_image_group_id . "'");

		return $query->row;
	}

	public function getAttributeImageGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "attribute_image_group aig LEFT JOIN " . DB_PREFIX . "attribute_image_group_description aigd ON (aig.attribute_image_group_id = aigd.attribute_image_group_id) WHERE aigd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'aigd.name',
			'aig.sort_order'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY aigd.name";	
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

	public function getAttributeImageGroupDescriptions($attribute_image_group_id) {
		$attribute_image_group_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_image_group_description WHERE attribute_image_group_id = '" . (int)$attribute_image_group_id . "'");

		foreach ($query->rows as $result) {
			$attribute_image_group_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $attribute_image_group_data;
	}

	public function getTotalAttributeImageGroups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute_image_group");

		return $query->row['total'];
	}	
}
?>