<?php
class ModelModuleOffices extends Model {
    public function getOffices() {
        $sql = "
            SELECT c.*, cd.*
            FROM " . DB_PREFIX . "offices c
            LEFT JOIN " . DB_PREFIX . "offices_descriptions cd ON (c.office_id = cd.office_id AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "')
            WHERE c.status = '1'
            ORDER BY c.sort_order, cd.name
        ";
        $query = $this->db->query($sql);

        return $query->rows;
    }
}
?>