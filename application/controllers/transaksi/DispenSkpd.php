<?php defined('BASEPATH') or exit('No direct script access allowed');
class DispenSkpd extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transaksi/MDisSkpd');
    }
    public function index()
    {
        $data = [];
        $Jssetup    = $this->Jssetup;
        $base         = $this->Msetup->setup();
        $setpage    = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
        $template     = $this->Msetup->loadTemplate($setpage->title);

/* 	$tablenya = $this->MDisSkpd->get_wp_data();
	echo '<pre>';
	var_dump($tablenya);
	echo '</pre>';
	die(); */
        $data =
            [
                'footer'      => $template['footer'],
                'title'       => $setpage->title,
                'link'        => $setpage->link,
                'topbar'      => $template['topbar'],
                'modalEdit'   => $this->Form->modalKu('E', 'Edit', 'transaksi/DispenSkpd/aksi', ['edit']),
                'modalDelete' => $this->Form->modalKu('D', 'Delete', 'transaksi/DispenSkpd/aksi', ['delete']),
                'sidebar'     => $template['sidebar'],
                'jstable'     => $Jssetup->jsDatatable('#disskpdTable', 'transaksi/DispenSkpd/getDisSkpd'),
                'jsedit'      => $Jssetup->jsModal('#edit', 'Edit', 'transaksi/DispenSkpd/myModal', '#modalkuE'),
                'jsdelete'    => $Jssetup->jsModal('#delete', 'Delete', 'transaksi/DispenSkpd/myModal', '#modalkuD'),
                'forminsert'  => implode('', $this->MDisSkpd->formInsert()),
                // 'formTambah'  => implode('', $this->MApbd->formModal())
            ];

        $this->load->view('transaksi/disskpd', $data);
    }

        public function getDisSkpd() {
        $datatables = $this->Datatables;
        $datatables->setTable("trx_dispensasi_skpd");
        $datatables->setSelectColumn([
            "trx_dispensasi_skpd.id as id_dispen", 
            "trx_dispensasi_skpd.idskpdrek",
            "trx_dispensasi_skpd.jumlah",
            "trx_dispensasi_skpd.keterangan",
            "trx_skpdreklame.id as id_skpd",
            "trx_skpdreklame.tanggal",
            "trx_skpdreklame.teks",
            "CONCAT(trx_skpdreklame.blnpajak, '-', trx_skpdreklame.thnpajak) as masapajak",
            "mst_wajibpajak.nama as wajibpajak",
            "mst_wajibpajak.nomor as noskpd",
            "mst_wajibpajak.tglskp",
        ]);
        
        $datatables->setOrderColumn([null, "wajibpajak", "noskpd", "tglskp", "teks", "masapajak", "jumlah", "keterangan"]);
        $datatables->setSearchColumns(["nama", "nomor","teks"]);    
        $datatables->addJoin('trx_skpdreklame', 'trx_skpdreklame.id=trx_dispensasi_skpd.idskpdrek', 'left');
        $datatables->addJoin('mst_wajibpajak', 'mst_wajibpajak.id=trx_skpdreklame.idwp', 'left');
        
        $fetch_data = $this->Datatables->make_datatables();
        $data = array();
        $no   = 1;
        
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $check_delete = '<input type="checkbox" id="delete_check"  class="delete-checkbox" data-id="' . $row->id_dispen . '">';
            $sub_array[] = $no++;
          /*   $sub_array[] = $check_delete; */
            $sub_array[] = $row->wajibpajak;
            $sub_array[] = $row->noskpd;
            $sub_array[] = $row->tglskp;
            $sub_array[] = $row->teks;
            $sub_array[] = $row->masapajak; 
            $sub_array[] = $row->jumlah;
            $sub_array[] = $row->keterangan;
            $sub_array[] = implode('', $this->Datatables->tombol($row->id_dispen));
            $data[] = $sub_array;
        }
        
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->Datatables->get_all_data(),
            "recordsFiltered" => $this->Datatables->get_filtered_data(),
            "data" => $data
        );
        
        echo json_encode($output);
    }

        
        public function get_wp_data() {
            $limit = $this->input->get('limit') ?: 10;
            $offset = $this->input->get('offset') ?: 0;
            $search = $this->input->get('search') ?: '';
            
            $this->db->select('mst_wajibpajak.id, mst_wajibpajak.nama, mst_wajibpajak.nomor, mst_wajibpajak.tgljthtmp, mst_wajibpajak.tglskp,trx_skpdreklame.id as idskpdrek, trx_skpdreklame.teks, trx_skpdreklame.blnpajak, trx_skpdreklame.thnpajak, trx_skpdreklame.jumlah, CONCAT(trx_skpdreklame.blnpajak, "-", trx_skpdreklame.thnpajak) as masapajak');
            $this->db->from('mst_wajibpajak');
            $this->db->join('trx_skpdreklame', 'trx_skpdreklame.idwp = mst_wajibpajak.id', 'inner'); 
            $this->db->join('trx_dispensasi_skpd', 'trx_dispensasi_skpd.idskpdrek = trx_skpdreklame.id', 'left'); 
            if (!empty($search)) {
                $this->db->like('mst_wajibpajak.nama', $search);
            }
            $this->db->limit($limit, $offset);
            $wpdata = $this->db->get()->result();
            
            echo json_encode($wpdata);
        }
 public function myModal()
{
    $wadi = isset($_POST['WADI']) ? $_POST['WADI'] : header('location:' . site_url('404'));
    switch ($wadi) {
        case 'Edit':
            $idnya = $this->input->post('idnya');
            $idreklame = $this->Crud->ambilSatu('trx_dispensasi_skpd', ['id' => $idnya]);

            $wpdata = $this->db
            ->select('mst_wajibpajak.id, mst_wajibpajak.nama, mst_wajibpajak.nomor, mst_wajibpajak.tgljthtmp, mst_wajibpajak.tglskp,trx_skpdreklame.id as idskpdrek, trx_skpdreklame.teks, trx_skpdreklame.blnpajak, trx_skpdreklame.thnpajak, trx_skpdreklame.jumlah, CONCAT(trx_skpdreklame.blnpajak, "-", trx_skpdreklame.thnpajak) as masapajak')
            ->from('mst_wajibpajak')
            ->join('trx_skpdreklame', 'trx_skpdreklame.idwp = mst_wajibpajak.id', 'inner')
            ->join('trx_dispensasi_skpd', 'trx_dispensasi_skpd.idskpdrek = trx_skpdreklame.id', 'left') 
            ->get()
            ->result();
            $selectedWp = null;
            foreach ($wpdata as $wp) {
                if ($wp->idskpdrek == $idreklame->idskpdrek) {
                    $selectedWp = $wp;
                    break;
                }
            }
            $form[] = '
            <script>
            $(document).ready(function() {
                    var selectedWp = ' . json_encode($selectedWp) . ';

                      if (selectedWp) {
                        var option = new Option(selectedWp.nama + " (" + selectedWp.nomor + ")", selectedWp.id, true, true);
                        $("#editwp2").append(option).trigger("change");
                        $(".nomor2").val(selectedWp.nomor);
                        $(".tglskp2").val(selectedWp.tglskp);
                        $(".teks2").val(selectedWp.teks);
                        $(".masapajak2").val(selectedWp.masapajak);
                        $(".jumlah").val(selectedWp.jumlah);
                        $(".idskpdrek").val(selectedWp.idskpdrek);
                    }
                $(\'.opsiwp2\').select2({
                    
                    ajax: {
                        url: \'DispenSkpd/get_wp_data\',
                        dataType: \'json\',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term, 
                                limit: 10,
                                offset: params.page ? (params.page - 1) * 5 : 0 
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        id: item.id,
                                        text: item.nama,
                                        nomor: item.nomor, 
                                        tgljthtmp: item.tgljthtmp, 
                                        tglskp: item.tglskp, 
                                        teks: item.teks, 
                                        masapajak: item.masapajak, 
                                        jumlah: item.jumlah, 
                                        idskpdrek: item.idskpdrek, 
                                    };
                                }),
                                pagination: {
                                    more: data.length === 10
                                }
                            };
                        },
                        cache: true
                    },
                    templateResult: formatWp,
                    templateSelection: formatWpSelection
                });

                function formatWp(wp) {
                    if (wp.loading) {
                        return wp.text;
                    }
                    var $container = $(\'<div>\' + wp.text + \'</div>\');
                    return $container;
                }

                function formatWpSelection(wp) {
                    return wp.text || wp.id;
                }

                $(\'.opsiwp2\').on(\'select2:select\', function (e) {
                    var data = e.params.data;
                    console.log("Selected data:", data);
                    $(\'#nomor2\').val(data.nomor); 
                    $(\'#tgljthtmp2\').val(data.tgljthtmp); 
                    $(\'#teks2\').val(data.teks); 
                    $(\'#tglskp2\').val(data.tglskp); 
                    $(\'#masapajak2\').val(data.masapajak); 
                    $(\'.jumlah\').val(data.jumlah); 
                    $(\'.idskpdrek\').val(data.idskpdrek); 
                });
            });
            </script>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="wp">Wajib Pajak:</label>
                        <select id="editwp2" class="form-control opsiwp2 select2" name="idwp" data-placeholder="Pilih WP" style="width: 100%;"></select>
                    </div>
                </div>
                <div class="col-md-12">' . implode($this->Form->hiddenText('idskpdrek', 'skpdrek')) . '</div>
                <div class="col-md-12">' . implode($this->Form->inputReadonly('nomor2', 'No. SKPD')) . '</div>
                <div class="col-md-12">' . implode($this->Form->inputReadonly('teks2', 'Teks')) . '</div>
                <div class="col-md-12">' . implode($this->Form->inputReadonly('tglskp2', 'Tgl.SKPD')) . '</div>
                <div class="col-md-12">' . implode($this->Form->inputReadonly('masapajak2', 'Masa Pajak')) . '</div>
                <div class="col-md-12">' . implode($this->Form->inputReadonly('jumlah', 'Jumlah', $idreklame->jumlah)) . '</div>
                <div class="col-md-12">' . implode($this->Form->inputText('keterangan', 'Keterangan', $idreklame->keterangan)) . '</div>
            </div>' . implode($this->Form->hiddenText('kode', $idreklame->id)) . '
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
                /* inputan data ke dispen skpd reklame */
                $data = [
                    'idskpdrek' => $this->input->post('idskpdrek'),
                    'jumlah' => $this->input->post('jumlah'),
                    'keterangan' => $this->input->post('keterangan'),
                ];
            
                $insert = $this->Crud->insert_data('trx_dispensasi_skpd', $data);
            
                if ($insert) {
                    /* jikd data skpd reklame berhasil di input ke dispen maka ubah isdipen nya jadi true  */
                    $idskpdrek = $this->input->post('idskpdrek');
                    $updateData = ['isdispen' => 1];
                    $this->Crud->update_data('trx_skpdreklame', $updateData, ['id' => $idskpdrek]);
            
                    $this->session->set_flashdata('message', 'Data has been saved successfully');
                    redirect('transaksi/DispenSkpd');
                } else {
                    $this->session->set_flashdata('message', 'Failed to save data');
                    redirect('transaksi/DispenSkpd');
                }
                break;
            
            case 'Edit':
                $kode = $this->input->post('kode');
                $data = [
                    'idskpdrek'        => $this->input->post('idskpdrek'),
                    'jumlah' => $this->input->post('jumlah'),
                    'keterangan' => $this->input->post('keterangan'),
                ];
                $update = $this->Crud->update_data('trx_dispensasi_skpd', $data, ['id' => $kode]);
                if ($update) {
                    $idskpdrek = $this->input->post('idskpdrek');
                    $updateData = ['isdispen' => 1];
                    $this->Crud->update_data('trx_skpdreklame', $updateData, ['id' => $idskpdrek]);
                    
                    $this->session->set_flashdata('message', 'Data has been updated successfully');
                    redirect('transaksi/DispenSkpd');
                } else {
                    $this->session->set_flashdata('message', 'Failed to update data');
                    redirect('transaksi/DispenSkpd');
                }
                break;
            case 'Delete':
                $kode = $this->input->post('kode');
                $dispensasiData = $this->Crud->ambilSatu('trx_dispensasi_skpd', ['id' => $kode]);
                $idskpdrek = $dispensasiData->idskpdrek;

                /* Hapus data dispen */
                $delete = $this->Crud->delete_data('trx_dispensasi_skpd', ['id' => $kode]);

                /* Jika data dispen skpd berhasil dihapus maka buat isdispen nya pad skpd reklame jadi false */
                if ($delete) {
                    $updateData = ['isdispen' => 0];
                    $this->Crud->update_data('trx_skpdreklame', $updateData, ['id' => $idskpdrek]);

                    $this->session->set_flashdata('message', 'Data has been deleted successfully');
                    redirect('transaksi/DispenSkpd');
                } else {
                    $this->session->set_flashdata('message', 'Failed to delete data');
                    redirect('transaksi/DispenSkpd');
                }
                break;
            default:
                header('location:' . site_url('404'));
                break;
        }
    }
}
