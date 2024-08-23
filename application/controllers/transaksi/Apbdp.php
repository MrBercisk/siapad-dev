<?php defined('BASEPATH') or exit('No direct script access allowed');
class Apbdp extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transaksi/MApbdp');
        // $this->load->model('master/MDinas');
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
                'modalEdit'   => $this->Form->modalKu('E', 'Edit', 'transaksi/Apbdp/aksi', ['edit']),
                'modalDelete' => $this->Form->modalKu('D', 'Delete', 'transaksi/Apbdp/aksi', ['delete']),
                'sidebar'     => $template['sidebar'],
                'jstable'     => $Jssetup->jsDatatable('#ftf', 'transaksi/Apbdp/getDinas'),
                'jsedit'      => $Jssetup->jsModal('#edit', 'Edit', 'transaksi/Apbdp/myModal', '#modalkuE'),
                'jsdelete'    => $Jssetup->jsModal('#delete', 'Delete', 'transaksi/Apbdp/myModal', '#modalkuD'),
                'forminsert'  => implode('', $this->MApbdp->formInsert()),
                'formCari'  => implode('', $this->MApbdp->formCari()),
                // 'formTambah'  => implode('', $this->MApbdp->formModal())
            ];

        $this->load->view('transaksi/Apbdp', $data);
    }

    public function getDinas()
    {
        $dinas = $this->input->post('dinas'); // Mengambil nilai dinas dari POST
        $tahun = $this->input->post('tahun'); // Mengambil nilai tahun dari POST

        // perulangan insert ke temporary
        // $this->MApbd->temporaryTable($dinas, $tahun);
        $sqlDropView = "DROP VIEW IF EXISTS v_rapbd_basic";

        $sqlCreateView = "
            CREATE VIEW v_rapbd_basic AS
            SELECT 
                a.id AS idrapbd,
                a.idrekening,
                b.kdrekening,
                b.kdrekview,
                b.nmrekening,
                a.apbd,
                a.apbdp,
                b.tipe,
                b.level
            FROM trx_rapbd a
            INNER JOIN mst_rekening b ON b.id = a.idrekening
            WHERE a.iddinas = ? AND a.tahun = ?
        ";
        // $this->db->query($viewsql);
        // Execute the SQL queries
        $this->db->query($sqlDropView);
        $this->db->query($sqlCreateView, array($dinas, $tahun));

        $this->load->model('instrument/Status');
        $status = $this->Status;
        $datatables = $this->Datatables;
        $datatables->setTable("v_rapbd_basic");
        $datatables->setSelectColumn(['idrekening', 'kdrekening', 'kdrekview', 'nmrekening', 'idrapbd', 'apbd', 'apbdp', 'tipe', 'level']);
        $datatables->setOrderColumn([null, "nmrekening", "idrekening"]);
        $datatables->setSearchColumns(["nmrekening", "idrekening"]);

        // Menambahkan WHERE clause berdasarkan dinas dan tahun (jika ada)
        // if (!empty($dinas)) {
        //     $this->db->where('iddinas', $dinas);
        // }
        // if (!empty($tahun)) {
        //     $this->db->where('tahun', $tahun);
        // }

        $fetch_data = $this->Datatables->make_datatables();

        $data = array();
        $no = 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
            $sub_array[] = $row->nmrekening;
            $sub_array[] = $row->kdrekening;
            $sub_array[] = $row->apbd;
            $sub_array[] = $row->apbdp;
            $sub_array[] = implode('', $this->Datatables->tombol($row->idrapbd));
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

    public function myModal()
    {
        $wadi = isset($_POST['WADI']) ? $_POST['WADI'] : header('location:' . site_url('404'));
        switch ($wadi) {
            case 'Edit':
                $joinTables          = [];
                $selectFields         = 'id, iddinas, tahun, idrekening, apbd,apbdp';
                $kode                 = $this->Crud->gandengan('trx_rapbd', $joinTables, $selectFields, 'id="' . $this->input->post('idnya') . '"')[0];
                $namarekening = $this->MApbdp->dinasByName('id', $kode->idrekening, 'mst_rekening', 'nmrekening');
                $namadinas = $this->MApbdp->dinasByName('id', $kode->iddinas, 'mst_dinas', 'nama');
                $form[]     = '
				<div class="row">
                    <div class="col-md-12">'
                    . implode($this->Form->inputText('tahun', 'Tahun', $kode->tahun, 'readonly')) .
                    '</div>
					<div class="col-md-12">'
                    . implode($this->Form->inputText('dinas', 'Nama Dinas', $namadinas[0]->nama, 'readonly')) .
                    '</div>
					<div class="col-md-12">'
                    . implode($this->Form->inputText('rekening', 'Rekening', $namarekening[0]->nmrekening, 'readonly')) .
                    '</div>
					<div class="col-md-12">'
                    . implode($this->Form->inputText('apbd', 'APBD', $kode->apbd)) .
                    '</div>
					<div class="col-md-12">'
                    . implode($this->Form->inputText('apbdp', 'APBDP', $kode->apbdp)) .
                    '</div>' . implode($this->Form->hiddenText('kode', $kode->id)) . '
				</div>';

                break;
            case 'Delete':
                $form[] = '
				<div class="row">
					<div class="col-md-12">
					' . implode($this->Form->hiddenText('kode', $this->input->post('idnya'))) . '
					Apakah kamu yakin ingin menghapus data ini ?
					</div>
				</div>';
                break;
            default:
                $form[] = 'NOTHING !!!';
                break;
        }
        echo implode('', $form);
    }
    public function aksi()
    {
        $aksi = isset($_POST['AKSI']) ? $_POST['AKSI'] : header('location:' . site_url('404'));
        $this->load->model('backend/Crud');
        switch ($aksi) {
            case 'Save':
                $nama = $this->input->post('dinas');
                // dinasByName($kunci,$where, $table, $selected)
                $iddinas = $this->MApbdp->dinasByName('nama', $nama, 'mst_dinas', 'id');
                // var_dump($iddinas[0]->id);
                // die;
                $data = [
                    'iddinas'         => $iddinas[0]->id,
                    'tahun'     => $this->input->post('tahun'),
                    'idrekening'        => $this->input->post('rekening'),
                    'apbd' => $this->input->post('apbd'),
                    'apbdp' => $this->input->post('apbdp'),
                ];

                $insert = $this->Crud->insert_data('trx_rapbd', $data);
                if ($insert) {
                    $this->session->set_flashdata('message', 'Data has been saved successfully');
                    redirect('transaksi/Apbd');
                } else {
                    $this->session->set_flashdata('message', 'Failed to save data');
                    redirect('transaksi/Apbd');
                }
                break;
            case 'Edit':
                $kode = $this->input->post('kode');
                // var_dump($this->input->post());
                // die;
                $data = [
                    // 'id'         => $iddinas,
                    // 'tahun'     => $this->input->post('tahun'),
                    // 'idrekening'        => $this->input->post('rekening'),
                    'apbd' => $this->input->post('apbd'),
                    'apbdp' => $this->input->post('apbdp'),
                ];
                $update = $this->Crud->update_data('trx_rapbd', $data, ['id' => $kode]);
                if ($update) {
                    $this->session->set_flashdata('message', 'Data has been updated successfully');
                    redirect('transaksi/Apbd');
                } else {
                    $this->session->set_flashdata('message', 'Failed to update data');
                    redirect('transaksi/Apbd');
                }
                break;
            case 'Delete':
                $kode = $this->input->post('kode');
                $delete = $this->Crud->delete_data('trx_rapbd', ['id' => $kode]);
                if ($delete) {
                    $this->session->set_flashdata('message', 'Data has been deleted successfully');
                    redirect('transaksi/Apbd');
                } else {
                    $this->session->set_flashdata('message', 'Failed to delete data');
                    redirect('transaksi/Apbd');
                }
                break;
            default:
                header('location:' . site_url('404'));
                break;
        }
    }
}
