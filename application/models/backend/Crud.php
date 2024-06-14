<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Crud extends CI_Model {
    public function insert_data($table, $data) {
        return $this->db->insert($table, $data);
    }
    public function update_data($table, $data, $where) {
        return $this->db->update($table, $data, $where);
    }
    public function delete_data($table, $where) {
        return $this->db->delete($table, $where);
    }
    public function ambilsemua($table) {
        $query = $this->db->get($table);
        return $query->result();
    }
    public function ambilSatu($table, $where) {
        $query = $this->db->get_where($table, $where);
        return $query->row();
    }
    public function ambilBanyak($table, $where) {
        $query = $this->db->get_where($table, $where);
        return $query->result();
    }
	public function gandengan($baseTable, $joinTables, $selectFields = '*', $where = NULL) {
    $this->db->select($selectFields);
    $this->db->from($baseTable);
    foreach ($joinTables as $table => $details) {
        $this->db->join($table, $details['condition'], $details['type']);
    }
    if ($where != NULL) {
        $this->db->where($where);
    }
    $query = $this->db->get();
    return $query->result();
	}
    public function getUptds() {
        return $this->db->get('mst_updt')->result();
    }

}

?>