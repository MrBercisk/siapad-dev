<?php defined('BASEPATH') or exit('No direct script access allowed');
class Datatables extends CI_Model
{
    private $table = "";
    private $select_column = array();
    private $order_column = array();
    private $search_columns = array();
    private $join = array();
    private $where = array();
    public function setTable($table)
    {
        $this->table = $table;
    }
    public function addWhere($column, $value = null)
    {
        if ($value === null) {
            $this->where[$column] = null;
        } else {
            $this->where[$column] = $value;
        }
    }
    public function addLike($column, $value, $after = NULL)
    {
        // $this->db->where($column, $value);
        $this->db->like($column, $value, $after);
    }

    public function setSelectColumn($select_column)
    {
        $this->select_column = $select_column;
    }
    public function setOrderColumn($order_column)
    {
        $this->order_column = $order_column;
    }
    public function setSearchColumns($search_columns)
    {
        $this->search_columns = $search_columns;
    }
    public function addJoin($table, $condition, $type = 'left')
    {
        $this->join[] = array('table' => $table, 'condition' => $condition, 'type' => $type);
    }
    private function _make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        foreach ($this->join as $join) {
            $this->db->join($join['table'], $join['condition'], $join['type']);
        }
        foreach ($this->where as $column => $value) {
            if ($value === null) {
                $this->db->where($column);
            } else {
                $this->db->where($column, $value);
            }
        }
        if (!empty($this->input->post("search")["value"])) {
            $this->db->group_start();
            foreach ($this->search_columns as $column) {
                $this->db->or_like($column, $this->input->post("search")["value"]);
            }
            $this->db->group_end();
        }
        if ($this->input->post("order")) {
            $order = $this->input->post("order")[0];
            $this->db->order_by($this->order_column[$order['column']], $order['dir']);
        } else {
            $this->db->order_by('id', 'DESC');
        }
    }
    public function make_datatables()
    {
        $this->_make_query();
        // var_dump($this->db->last_query());
        // die;
        if ($this->input->post("length") != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
        return $this->db->get()->result();
    }
    public function get_filtered_data()
    {
        $this->_make_query();
        foreach ($this->where as $column => $value) {
            if ($value === null) {
                $this->db->where($column);
            } else {
                $this->db->where($column, $value);
            }
        }
        return $this->db->get()->num_rows();
    }
    public function get_all_data()
    {
        $this->db->from($this->table);
        foreach ($this->where as $column => $value) {
            if ($value === null) {
                $this->db->where($column);
            } else {
                $this->db->where($column, $value);
            }
        }
        return $this->db->count_all_results();
    }
    public function tombol($id, $actions = ['edit', 'delete'])
    {
        $tombol[] = '<div class="btn-group pull-right">';
        if (in_array('edit', $actions)) {
            $tombol[] = '
                <a class="btn btn-xs btn-outline-primary modin fa fa-edit" id="edit" href="#" data-id="' . $id . '" data-toggle="modal" data-target="#myModalE" data-placement="bottom" title="Perbarui data">
                </a>';
        }
        if (in_array('delete', $actions)) {
            $tombol[] = '
                <a class="btn btn-xs btn-outline-danger modin fa fa-trash" id="delete" href="#" data-id="' . $id . '" data-toggle="modal" data-target="#myModalD" data-placement="bottom" title="Hapus">
                </a>';
        }
        $tombol[] = '</div>';
        return $tombol;
    }
    public function tombolPend($idstsmaster, $nourut, $actions = ['edit', 'delete'])
    {
        $tombol[] = '<div class="btn-group pull-right">';

        if (in_array('edit', $actions)) {
            $tombol[] = '<a class="btn btn-xs btn-outline-primary modin fa fa-edit" id="edit" href="' . site_url('transaksi/PendDaerah/myModal/' . $idstsmaster . '/' . $nourut) . '" data-placement="bottom" title="Edit data">Edit</a>';
        }

        if (in_array('delete', $actions)) {
            // Tombol untuk hapus data bisa Anda tambahkan sesuai kebutuhan
            $tombol[] = '<a class="btn btn-xs btn-outline-danger modin fa fa-trash" id="delete" href="#" data-id="' . $idstsmaster . '" data-toggle="modal" data-target="#myModalD" data-placement="bottom" title="Hapus data">Hapus</a>';
        }

        $tombol[] = '</div>';

        return implode(' ', $tombol);
    }

    public function get_filtered_datas($nomor = NULL, $npwpd = NULL, $rekening = NULL, $wajibpajak = NULL, $tahun = NULL)
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        foreach ($this->join as $join) {
            $this->db->join($join['table'], $join['condition'], $join['type']);
        }
        if (!empty($nomor)) {
            $this->addLike("a.nomor", $nomor, "after");
        }
        if (!empty($rekening)) {
            $this->addLike("c.nmrekening", $rekening);
        }
        if (!empty($npwpd)) {
            $this->addWhere("b.nomor", $npwpd);
        }

        if (!empty($wajibpajak)) {
            $this->addLike("b.id", $wajibpajak);
        }
        if (!empty($tahun)) {
            $this->addWhere("a.thnpajak", $tahun);
        }
        // $query = $this->db->get();
        // $num_rows = $query->num_rows();

        // Menampilkan query terakhir
        // var_dump($this->db->last_query());
        // die;
        return $this->db->get()->num_rows();
    }
    public function get_all_datas($nomor, $npwpd, $rekening, $wajibpajak, $tahun)
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        foreach ($this->join as $join) {
            $this->db->join($join['table'], $join['condition'], $join['type']);
        }
        if (!empty($nomor)) {
            $this->addLike("a.nomor", $nomor, "after");
        }
        if (!empty($rekening)) {
            $this->addLike("c.nmrekening", $rekening);
        }
        if (!empty($npwpd)) {
            $this->addWhere("b.nomor", $npwpd);
        }

        if (!empty($wajibpajak)) {
            $this->addLike("b.id", $wajibpajak);
        }
        if (!empty($tahun)) {
            $this->addWhere("a.thnpajak", $tahun);
        }
        return $this->db->count_all_results();
    }
    public function get_filtered_dataku($where = array())
    {
        $this->_make_query();
        foreach ($where as $column => $value) {
            if ($value === null) {
                $this->addWhere($column);
            } else {
                $this->addWhere($column, $value);
            }
        }
        return $this->db->get()->num_rows();
    }
    public function get_all_dataku($where = array())
    {
        $this->db->from($this->table);
        foreach ($where as $column => $value) {
            if ($value === null) {
                $this->addWhere($column);
            } else {
                $this->addWhere($column, $value);
            }
            return $this->db->count_all_results();
        }
    }
}
