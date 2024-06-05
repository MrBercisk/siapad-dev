<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Datatables extends CI_Model {
    private $table = "mst_wajibpajak";
    private $select_column = array("id", "nama", "alamat", "nomor","npwpd","idkelurahan","notype");
    private $order_column = array(null, "nama", "nomor");
    public function setTable($table) {
        $this->table = $table;
    }
    public function setSelectColumn($select_column) {
        $this->select_column = $select_column;
    }
    public function setOrderColumn($order_column) {
        $this->order_column = $order_column;
    }
	private function _make_query() {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        if (!empty($_POST["search"]["value"])) {
            $this->db->group_start()
                     ->like("nama", $_POST["search"]["value"])
                     ->or_like("nomor", $_POST["search"]["value"])
                     ->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id', 'DESC');
        }
    }
    public function make_datatables() {
        $this->_make_query();
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }
    public function get_filtered_data() {
        $this->_make_query();
        return $this->db->count_all_results();
    }
    public function get_all_data() {
        return $this->db->count_all($this->table);
    }
	public function tombol($id){
		$tombol = '
		<div class="btn-group pull-right">
				<a class="btn btn-xs btn-outline-primary modin fa fa-edit" id="edit" href="#" data-id="'.$id.'" data-toggle="modal" data-target="#myModalE" data-placement="bottom" title="Perbarui data">
				</a>
				<a class="btn btn-xs btn-outline-danger modin fa fa-trash" id="delete" href="#" data-id="'.$id.'" data-toggle="modal" data-target="#myModalD" data-placement="bottom" title="Hapus">
				</a>
		</div>';
		return $tombol;
	}
}
?>
