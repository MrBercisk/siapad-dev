<?php defined('BASEPATH') or exit('No direct script access allowed');
class Apbd extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transaksi/MApbd');
        $this->load->model('master/MDinas');
    }
    public function index()
    {
        $data = [];
        $Jssetup    = $this->Jssetup;
        $base         = $this->Msetup->setup();
        $setpage    = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
        $template     = $this->Msetup->loadTemplate($setpage->title);

        $data =
            [
                'footer'      => $template['footer'],
                'title'       => $setpage->title,
                'link'        => $setpage->link,
                'topbar'      => $template['topbar'],
                'modalEdit'   => $this->Form->modalKu('E', 'Edit', 'master/Dinas/aksi', ['edit']),
                'modalDelete' => $this->Form->modalKu('D', 'Delete', 'master/Dinas/aksi', ['delete']),
                'sidebar'     => $template['sidebar'],
                'jstable'     => $Jssetup->jsDatatable('#ftf', 'master/Dinas/getDinas'),
                'jsedit'      => $Jssetup->jsModal('#edit', 'Edit', 'master/Dinas/myModal', '#modalkuE'),
                'jsdelete'    => $Jssetup->jsModal('#delete', 'Delete', 'master/Dinas/myModal', '#modalkuD'),
                'forminsert'  => implode('', $this->MApbd->formInsert()),
                'formCari'  => implode('', $this->MApbd->formCari())
            ];

        $this->load->view('transaksi/Apbd', $data);
    }

    public function getDinas()
    {
        $dinas = $this->input->post('dinas'); // Mengambil nilai dinas dari POST
        $tahun = $this->input->post('tahun'); // Mengambil nilai tahun dari POST

        $this->load->model('instrument/Status');
        $status = $this->Status;
        $datatables = $this->Datatables;
        $datatables->setTable("v_rapbd_basic");
        $datatables->setSelectColumn(["iddinas", "nmrekening", "apbd", "apbdp", "level"]);
        $datatables->setOrderColumn([null, "nmrekening", "iddinas"]);
        $datatables->setSearchColumns(["nmrekening", "iddinas"]);

        // Menambahkan WHERE clause berdasarkan dinas dan tahun (jika ada)
        if (!empty($dinas)) {
            $this->db->where('iddinas', $dinas);
        }
        if (!empty($tahun)) {
            $this->db->where('tahun', $tahun);
        }
        // $query = $this->db->query("CALL spSQLGetRAPBD($dinas, $tahun)");
        // $fetch_data = $query;
        $fetch_data = $this->Datatables->make_datatables();

        $data = array();
        $no = 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
            $sub_array[] = $row->nmrekening;
            $sub_array[] = $row->apbd;
            $sub_array[] = $row->apbdp;
            $sub_array[] = $status->yOrNo($row->level);
            $sub_array[] = implode('', $this->Datatables->tombol($row->iddinas));
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => $this->Datatables->get_all_data(),
            "recordsFiltered" => $this->Datatables->get_filtered_data(),
            "data" => $data
        );

        echo json_encode($output);
    }
}
