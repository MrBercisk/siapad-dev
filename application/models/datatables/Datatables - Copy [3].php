<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Datatables extends CI_Model {
    private $table = "mst_wajibpajak";
    private $select_column = array("id", "nama", "alamat", "nomor", "npwpd", "idkelurahan", "notype");
    private $order_column = array(null, "nama", "nomor");
    private $search_columns = array("nama", "nomor");

    // Set the table name
    public function setTable($table) {
        $this->table = $table;
    }

    // Set the select columns
    public function setSelectColumn($select_column) {
        $this->select_column = $select_column;
    }

    // Set the order columns
    public function setOrderColumn($order_column) {
        $this->order_column = $order_column;
    }

    // Set the search columns
    public function setSearchColumns($search_columns) {
        $this->search_columns = $search_columns;
    }

    // Build the query
    private function _make_query() {
        $this->db->select($this->select_column);
        $this->db->from($this->table);

        if (!empty($_POST["search"]["value"])) {
            $this->db->group_start();
            foreach ($this->search_columns as $column) {
                $this->db->or_like($column, $_POST["search"]["value"]);
            }
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id', 'DESC');
        }
    }

    // Fetch the datatables results
    public function make_datatables() {
        $this->_make_query();
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    // Get the filtered data count
    public function get_filtered_data() {
        $this->_make_query();
        return $this->db->count_all_results();
    }

    // Get the total data count
    public function get_all_data() {
        return $this->db->count_all($this->table);
    }

    // Generate action buttons
    public function tombol($id, $actions = ['edit', 'delete']) {
        $tombol = '<div class="btn-group pull-right">';
        if (in_array('edit', $actions)) {
            $tombol .= '
                <a class="btn btn-xs btn-outline-primary modin fa fa-edit" id="edit" href="#" data-id="' . $id . '" data-toggle="modal" data-target="#myModalE" data-placement="bottom" title="Perbarui data">
                </a>';
        }
        if (in_array('delete', $actions)) {
            $tombol .= '
                <a class="btn btn-xs btn-outline-danger modin fa fa-trash" id="delete" href="#" data-id="' . $id . '" data-toggle="modal" data-target="#myModalD" data-placement="bottom" title="Hapus">
                </a>';
        }
        $tombol .= '</div>';
        return $tombol;
    }
}

?>
