<?php defined('BASEPATH') or exit('No direct script access allowed');
class Getdata extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transaksi/MformSptpd');
    }

    public function getTtd()
    {
        $search = $this->input->post('search');
        $page = ($this->input->post('page') == 0) ? 1 : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Query to get the results
        $this->db->select('mst_tandatangan.id, mst_tandatangan.nip, mst_tandatangan.nama, mst_tandatangan.jabatan1, mst_tandatangan.jabatan2')
            ->from('mst_tandatangan');
        $this->db->like('mst_tandatangan.nama', $search);
        $this->db->limit($limit, $offset);
        $query = $this->db->get();

        $results = $query->result_array();

        // Query to get the total count
        $this->db->select('mst_tandatangan.id, mst_tandatangan.nip, mst_tandatangan.nama, mst_tandatangan.jabatan1, mst_tandatangan.jabatan2')
            ->from('mst_tandatangan');
        $this->db->like('nama', $search);
        $total_count = $this->db->count_all_results();

        $more = ($offset + $limit) < $total_count;
        $data = [
            'items' => array_map(function ($row) {
                return ['id' => $row['id'], 'text' => $row['nama'], 'nip' => $row['nip'], 'jabatan1' => $row['jabatan1'], 'jabatan2' => $row['jabatan2']];
            }, $results),
            'pagination' => ['more' => $more]
        ];

        if (empty($data['items'])) {
            $data = [
                'items' => [],
                'pagination' => ['more' => false]
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
