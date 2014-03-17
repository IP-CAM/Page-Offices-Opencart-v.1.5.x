<?php
class ModelCommonOffices extends Model {
    public function addOffice($data) {
        $this->db->query("
			INSERT INTO " . DB_PREFIX . "offices".
            " SET parent_id = '" . (int)$data['parent_id'] . "',".
            " sort_order = '" . (int)$data['sort_order'] . "',".
            " status = '" . (int)$data['status'] . "',".
            " phone = '" . $data['phone'] . "',".
            " fax = '" . $data['fax'] . "',".
            " email = '" . $data['email'] . "',".
            " longitude = '" . $data['longitude'] . "',".
            " latitude = '" . $data['latitude'] . "'
		");

        $office_id = $this->db->getLastId();

        foreach ($data['office_description'] as $language_id => $value) {
            $this->db->query("
				INSERT INTO " . DB_PREFIX . "offices_descriptions".
                " SET office_id = '" . (int)$office_id . "',".
                " language_id = '" . (int)$language_id . "',".
                " name = '" . $this->db->escape($value['name']) . "',".
                " address = '" . $this->db->escape($value['address']) . "',".
                " title = '" . $this->db->escape($value['title']) . "',".
                " description = '" . $this->db->escape($value['description']) . "'
			");
        }

        return $office_id;
    }

    public function editOffice($office_id, $data) {
        $this->db->query("
			UPDATE " . DB_PREFIX . "offices".
            " SET parent_id = '" . (int)$data['parent_id'] . "',".
            " sort_order = '" . (int)$data['sort_order'] . "',".
            " status = '" . (int)$data['status'] . "',".
            " phone = '" . $data['phone'] . "',".
            " fax = '" . $data['fax'] . "',".
            " email = '" . $data['email'] . "',".
            " longitude = '" . $data['longitude'] . "',".
            " latitude = '" . $data['latitude'] . "'".
            " WHERE office_id = '" . (int)$office_id . "'
		");

        $this->db->query("DELETE FROM " . DB_PREFIX . "offices_descriptions WHERE office_id = '" . (int)$office_id . "'");

        foreach ($data['office_description'] as $language_id => $value) {
            $this->db->query("
				INSERT INTO " . DB_PREFIX . "offices_descriptions".
                " SET office_id = '" . (int)$office_id . "',".
                " language_id = '" . (int)$language_id . "',".
                " name = '" . $this->db->escape($value['name']) . "',".
                " address = '" . $this->db->escape($value['address']) . "',".
                " title = '" . $this->db->escape($value['title']) . "',".
                " description = '" . $this->db->escape($value['description']) . "'
			");
        }
    }

    public function deleteOffice($office_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "offices WHERE office_id = '" . (int)$office_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "offices_descriptions WHERE office_id = '" . (int)$office_id . "'");
        $query = $this->db->query("SELECT office_id FROM " . DB_PREFIX . "offices WHERE parent_id = '" . (int)$office_id . "'");

        foreach ($query->rows as $result) {
            $this->deleteOffice($result['office_id']);
        }
    }

    public function getOffice($office_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "offices WHERE office_id = '" . (int)$office_id . "'");

        return $query->row;
    }

    public function getOffices($parent_id = 0) {
        $office_data = array();

        $query = $this->db->query("
			SELECT c.*, cd.*
			FROM " . DB_PREFIX . "offices c
			LEFT JOIN " . DB_PREFIX . "offices_descriptions cd ON (c.office_id = cd.office_id)
			WHERE c.parent_id = '" . (int)$parent_id . "'
			AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			ORDER BY c.sort_order, cd.name ASC
		");

        foreach ($query->rows as $result) {
            $office_data[] = array(
                'office_id'   => $result['office_id'],
                'name'        => $this->getPath($result['office_id'], $this->config->get('config_language_id')),
                'status'  	  => $result['status'],
                'sort_order'  => $result['sort_order']
            );

            $office_data = array_merge($office_data, $this->getOffices($result['office_id']));
        }

        return $office_data;
    }

    public function getPath($office_id, $language) {
        $query = $this->db->query("
			SELECT name, parent_id
			FROM " . DB_PREFIX . "offices c
			LEFT JOIN " . DB_PREFIX . "offices_descriptions cd ON (c.office_id = cd.office_id)
			WHERE c.office_id = '" . (int)$office_id . "'
			AND cd.language_id = '" . (int)$language . "'
			ORDER BY c.sort_order, cd.name ASC
		");

        if ($query->row['parent_id']) {
            return $this->getPath($query->row['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $query->row['name'];
        } else {
            return $query->row['name'];
        }
    }

    public function getOfficeDescriptions($office_id) {
        $office_description_data = array();

        $query = $this->db->query("
			SELECT *
			FROM " . DB_PREFIX . "offices_descriptions
			WHERE office_id = '" . (int)$office_id . "'
		");

        foreach ($query->rows as $result) {
            $office_description_data[$result['language_id']] = array(
                'name'          => $result['name'],
                'address'       => $result['address'],
                'title'         => $result['title'],
                'description'   => $result['description']
            );
        }

        return $office_description_data;
    }

    public function getTotalOffices() {
        $query = $this->db->query("
			SELECT COUNT(*) AS total
			FROM " . DB_PREFIX . "offices
		");

        return $query->row['total'];
    }
}
?>