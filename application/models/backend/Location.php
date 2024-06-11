<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Location extends CI_Model {
    public function get_kecamatan() {
        $query = $this->db->get('mst_kecamatan');
        return $query->result_array();
    }
    public function get_kelurahan_by_kecamatan($kecamatan_id) {
        $this->db->where('idkecamatan', $kecamatan_id);
        $query = $this->db->get('mst_kelurahan');
        return $query->result_array();
    }

    public function getUser(){
        $query = $this->db->get('sys_user');
        return $query->result_array();
    }
}
