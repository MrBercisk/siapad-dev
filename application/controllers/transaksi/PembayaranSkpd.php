<?php defined('BASEPATH') or exit('No direct script access allowed');
class PembayaranSkpd extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transaksi/Mbyrskpd');
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
                'modalEdit'   => $this->Form->modalKu('E', 'Edit', 'transaksi/PembayaranSkpd/aksi', ['edit']),
                'modalDelete' => $this->Form->modalKu('D', 'Delete', 'transaksi/PembayaranSkpd/aksi', ['delete']),
                'sidebar'     => $template['sidebar'],
                'jstable'     => $Jssetup->jsDatatable('#SKPD', 'transaksi/PembayaranSkpd/get_datatable_data'),
                'jsedit'      => $Jssetup->jsModal('#edit', 'Edit', 'transaksi/PembayaranSkpd/myModal', '#modalkuE'),
                'jsdelete'    => $Jssetup->jsModal('#delete', 'Delete', 'transaksi/PembayaranSkpd/myModal', '#modalkuD'),
                // 'formTambah'  => implode('', $this->MApbd->formModal())
            ];

        $this->load->view('transaksi/pmbyrnskpd', $data);
    }
   /*  public function get_record_option()
    {
        $limit = $this->input->get('limit') ?: 10;
        $offset = $this->input->get('offset') ?: 0;
        $search = $this->input->get('search') ?: '';
        $this->db->select('a.id, b.nomor AS noskpd, b.tglskp AS tglskpd, b.tgljthtmp, a.idwp, b.nama AS nmwp, a.teks, a.blnpajak, a.thnpajak, 
                a.jumlah, a.bunga, a.total, a.tglbayar, a.isbayar, a.isdispen, b.idrekening, c.nmrekening, 
                e.iduptd, f.nama AS nmuptd, f.singkat AS nmuptdsingkat, a.keterangan');
        $this->db->from('trx_skpdreklame a');
        $this->db->join('mst_wajibpajak b', 'b.id=a.idwp');
        $this->db->join('mst_rekening c', 'c.id=b.idrekening');
        $this->db->join('mst_kelurahan d', 'd.id=b.idkelurahan', 'left');
        $this->db->join('mst_kecamatan e', 'e.id=d.idkecamatan', 'left');
        $this->db->join('mst_uptd f', 'f.id=e.iduptd', 'left');
        $this->db->order_by('a.isbayar', 'ASC');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('b.nama', $search);
            $this->db->or_like('b.nomor', $search);
            $this->db->group_end();
        }
         $this->db->limit($limit, $offset);
        $wpdata = $this->db->get()->result();

        echo json_encode($wpdata);
    } */
    public function get_record_option()
    {
        $limit = $this->input->get('limit') ?: 10;
        $offset = $this->input->get('offset') ?: 0;
        $search = $this->input->get('search') ?: '';
        
        $this->db->select('trx_stsmaster.id, trx_stsmaster.iddinas, trx_stsmaster.nomor, trx_stsmaster.tanggal, trx_stsmaster.keterangan, trx_stsmaster.isnonkas, trx_stsmaster.tmpbayar, mst_dinas.isdispenda, mst_dinas.nama');
        $this->db->from('trx_stsmaster');
        $this->db->join('mst_dinas', 'trx_stsmaster.iddinas = mst_dinas.id', 'inner');
        $this->db->where('mst_dinas.isdispenda', 1);
        $this->db->order_by('trx_stsmaster.nomor', 'ASC');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('mst_dinas.nama', $search);
            $this->db->or_like('trx_stsmaster.nomor', $search);
            $this->db->group_end();
        }
        
        $this->db->limit($limit, $offset);
        $wpdata = $this->db->get()->result();
        
        echo json_encode($wpdata);
    }
    
   
    
    public function get_edit_data() {
        $this->load->model('Mbyrskpd'); 

        $idstsmaster = $this->input->post('idstsmaster');
        $nourut = $this->input->post('nourut');

        $data = $this->Mbyrskpd->get_data_by_idsts_nourut($idstsmaster, $nourut);
        if ($data) {
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Data not found']);
        }
    }
    public function get_all_skpd() {
        $data = $this->Mbyrskpd->get_all_skpd();
        echo json_encode(array('data' => $data));
    }
    public function getSkpd() {
        $datatables = $this->Datatables;
        $datatables->setTable("trx_skpdreklame");
        $datatables->setSelectColumn([
            "trx_skpdreklame.id as id_skpd", 
            "trx_skpdreklame.idwp",
            "trx_skpdreklame.tanggal",
            "trx_skpdreklame.teks",
            "trx_skpdreklame.blnpajak",
            "trx_skpdreklame.thnpajak",
            "trx_skpdreklame.jumlah",
            "trx_skpdreklame.bunga",
            "trx_skpdreklame.total",
            "trx_skpdreklame.isbayar",
            "trx_skpdreklame.tglbayar",
            "trx_skpdreklame.keterangan",
            "mst_wajibpajak.id",
            "mst_wajibpajak.nama as wajibpajak",
            "mst_wajibpajak.nomor as noskpd",
            "mst_wajibpajak.tgljthtmp",
            "mst_wajibpajak.tglskp",
        ]);
        $datatables->setOrderColumn([null, "wajibpajak", "noskpd", "tgljthtmp", "teks", "blnpajak", "thnpajak", "jumlah", "bunga", "total", "tglbayar", "keterangan"]);
        $datatables->setSearchColumns(["nama", "nomor", "tanggal", "teks", "thnpajak"]);
        $datatables->addJoin('mst_wajibpajak', 'mst_wajibpajak.id=trx_skpdreklame.idwp', 'left');
        
        $fetch_data = $this->Datatables->make_datatables();
        $data = array();
        $no   = 1;
        
        foreach ($fetch_data as $row) {
            $sub_array = array();
            if ($row->isbayar != 0 || $row->tglbayar !== null && $row->tglbayar !== '0000-00-00') {
                foreach ($row as $key => $value) {
                    if ($key == 'isbayar') continue; 
                    $sub_array[$key] = $value;
                }
            } else {
                $sub_array = (array) $row;
                $sub_array['DT_RowClass'] = 'bg-danger text-white';
            }
            
            $check_delete = '<input type="checkbox" id="delete_check" class="delete-checkbox" data-id="' . $row->id_skpd . '">';
            
            $sub_array[] = $no++;
            /* $sub_array[] = $check_delete; */
            $sub_array[] = $row->wajibpajak;
            $sub_array[] = $row->noskpd;
            $sub_array[] = $row->tgljthtmp;
            $sub_array[] = $row->teks;
            $sub_array[] = $row->blnpajak;
            $sub_array[] = $row->thnpajak;
            $sub_array[] = $row->jumlah;
            $sub_array[] = $row->bunga;
            $sub_array[] = $row->total;
            $sub_array[] = $row->tglbayar;
            $sub_array[] = $row->keterangan;
            $sub_array[] = implode('', $this->Datatables->tombol($row->id_skpd));
            
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

        $this->db->select('id, nama, nomor, tgljthtmp, tglskp');
        $this->db->from('mst_wajibpajak');
        if (!empty($search)) {
            $this->db->like('nama', $search);
            $this->db->or_like('nomor', $search);
        }
        $this->db->limit($limit, $offset);
        $wpdata = $this->db->get()->result();

        echo json_encode($wpdata);
    }

    public function get_skpd_data(){
        $limit = $this->input->get('limit') ?: 10;
        $offset = $this->input->get('offset') ?: 0;
        $search = $this->input->get('search') ?: '';
    
        $this->db->select('a.id, a.idwp, b.idrekening, c.iduptd, c.idrapbd, d.nmrekening, b.nomor AS nomor, b.nama AS nama, a.teks, a.blnpajak, a.thnpajak, a.jumlah, c.nil_denda as bunga, a.total, c.prs_denda as persen');
        $this->db->join('mst_wajibpajak b', 'b.id = a.idwp');
        $this->db->join('trx_stsdetail c', 'c.idskpd = a.id');
        $this->db->join('mst_rekening d', 'd.id = b.idrekening');
        $this->db->from('trx_skpdreklame a');
        
        if (!empty($search)) {
            $this->db->like('b.nomor', $search);
            $this->db->or_like('b.nama', $search);
        }
        
        $this->db->limit($limit, $offset);
        $wpdata = $this->db->get()->result_array(); 
    
        echo json_encode($wpdata);
    }
    
    
    public function myModal()
    {
        $wadi = isset($_POST['WADI']) ? $_POST['WADI'] : header('location:' . site_url('404'));
        switch ($wadi) {
            case 'Edit':
                $idnya = $this->input->post('idnya');
                $idreklame = $this->Crud->ambilSatu('trx_skpdreklame', ['id' => $idnya]);
    
                $wpdata = $this->db
                    ->select('mst_wajibpajak.id, mst_wajibpajak.nama, mst_wajibpajak.nomor, mst_wajibpajak.tgljthtmp, mst_wajibpajak.tglskp')
                    ->from('mst_wajibpajak')
                    ->get()
                    ->result();
    
                $selectedWp = null;
                foreach ($wpdata as $wp) {
                    if ($wp->id == $idreklame->idwp) {
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
                        $("#idwp2").append(option).trigger("change");
                        $(".nomor").val(selectedWp.nomor);
                        $(".tglskp").val(selectedWp.tglskp);
                    }
                    $("#idwp2").select2({
                        ajax: {
                            url: \'SkpdReklame/get_wp_data\',
                            dataType: "json",
                            delay: 250,
                            data: function (params) {
                                return {
                                    search: params.term,
                                    limit: 10,
                                    offset: params.page ? (params.page - 1) * 10 : 0
                                };
                            },
                            processResults: function (data, params) {
                                params.page = params.page || 1;
                                return {
                                    results: $.map(data, function (item) {
                                        return {
                                            id: item.id,
                                            text: item.nama + " (" + item.nomor + ")",
                                            nomor: item.nomor,
                                            tglskp: item.tglskp,
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
                        return wp.text;
                    }
    
                    function formatWpSelection(wp) {
                        return wp.text || wp.id;
                    }
    
                    $("#idwp2").on("select2:select", function (e) {
                        var data = e.params.data;
                        $(".nomor").val(data.nomor);
                        $(".tglskp").val(data.tglskp);
                    });
    
                    function calculateTotal() {
                        var jumlah = parseFloat($(".jumlah").val()) || 0;
                        var bunga = parseFloat($(".bunga").val()) || 0;
                        var total = jumlah + bunga;
                        $(".total").val(total);
                    }
    
                    $(".jumlah, .bunga").on("input", calculateTotal);
                });
                </script>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="wp">Wajib Pajak:</label>
                            <select id="idwp2" name="idwp" class="form-control select2" data-placeholder="Pilih WP" style="width: 100%;"></select>
                        </div>
                    </div>
                    <div class="col-md-12">' . implode($this->Form->inputText('teks', 'Teks', $idreklame->teks)) . '</div>
                    <div class="col-md-4">' . implode($this->Form->inputNumber('blnpajak', 'Bulan Pajak', $idreklame->blnpajak)) . '</div>
                    <div class="col-md-4">' . implode($this->Form->inputNumber('thnpajak', 'Tahun Pajak', $idreklame->thnpajak)) . '</div>
                    <div class="col-md-4">' . implode($this->Form->inputNumber('jumlah', 'Jumlah', $idreklame->jumlah)) . '</div>
                    <div class="col-md-4">' . implode($this->Form->inputNumber('bunga', 'Bunga', $idreklame->bunga)) . '</div>
                    <div class="col-md-4">' . implode($this->Form->inputNumber('total', 'Total', $idreklame->total, 'readonly')) . '</div>
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
                $tglbayar = $this->input->post('tglbayar');
                $isbayar = ($tglbayar && $tglbayar !== '0000-00-00') ? 1 : 0;
                $data = [
                    'idwp'        => $this->input->post('idwp'),
                    'teks' => $this->input->post('teks'),
                    'tglbayar' => $tglbayar,
                    'blnpajak' => $this->input->post('blnpajak'),
                    'thnpajak' => $this->input->post('thnpajak'),
                    'jumlah' => $this->input->post('jumlah'),
                    'bunga' => $this->input->post('bunga'),
                    'total' => $this->input->post('total'),
                    'isbayar' => $isbayar,
                    'keterangan' => $this->input->post('keterangan'),
                ];

                $insert = $this->Crud->insert_data('trx_skpdreklame', $data);
                if ($insert) {
                    $this->session->set_flashdata('message', 'Data has been saved successfully');
                    redirect('transaksi/SkpdReklame');
                } else {
                    $this->session->set_flashdata('message', 'Failed to save data');
                    redirect('transaksi/SkpdReklame');
                }
                break;
            case 'Edit':
                $kode = $this->input->post('kode');
                $data = [
                    'idwp'        => $this->input->post('idwp'),
                    'teks' => $this->input->post('teks'),
                    'blnpajak' => $this->input->post('blnpajak'),
                    'thnpajak' => $this->input->post('thnpajak'),
                    'jumlah' => $this->input->post('jumlah'),
                    'bunga' => $this->input->post('bunga'),
                    'total' => $this->input->post('total'),
                    'keterangan' => $this->input->post('keterangan'),
                ];
                $update = $this->Crud->update_data('trx_skpdreklame', $data, ['id' => $kode]);
                if ($update) {
                    $this->session->set_flashdata('message', 'Data has been updated successfully');
                    redirect('transaksi/SkpdReklame');
                } else {
                    $this->session->set_flashdata('message', 'Failed to update data');
                    redirect('transaksi/SkpdReklame');
                }
                break;
            case 'Delete':
                $kode = $this->input->post('kode');
                $delete = $this->Crud->delete_data('trx_skpdreklame', ['id' => $kode]);
                if ($delete) {
                    $this->session->set_flashdata('message', 'Data has been deleted successfully');
                    redirect('transaksi/SkpdReklame');
                } else {
                    $this->session->set_flashdata('message', 'Failed to delete data');
                    redirect('transaksi/SkpdReklame');
                }
                break;
            default:
                header('location:' . site_url('404'));
                break;
        }
    }
    public function add_record_data() {
        $isnonkas = $this->input->post('isnonkas') ? $this->input->post('isnonkas') : 0;
        $iddinas = $this->input->post('iddinas');
        $tanggal = $this->input->post('tanggal');
        $nomor = $this->input->post('nomor');
        $tahun = date('Y', strtotime($tanggal));
    
        $this->db->select('nomor');
        $this->db->from('trx_stsmaster');
        $this->db->where('nomor', $nomor);
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            $response = ['success' => false, 'message' => 'Nomor sudah ada. Tidak dapat menambahkan record baru.'];
        } else {
        
            $this->db->select('isdispenda');
            $this->db->from('mst_dinas');
            $this->db->where('id', $iddinas);
            $isdispenda_result = $this->db->get()->row();
    
            $isdispenda = $isdispenda_result ? $isdispenda_result->isdispenda : 0;
    
            $data = [
                'iddinas' => $iddinas,
                'nomor' => $nomor,
                'tanggal' => $tanggal,
                'keterangan' => $this->input->post('keterangan'),
                'tahun' => $tahun,
                'isnonkas' => $isnonkas,
                'isdispenda' => $isdispenda,
                'tmpbayar' => $this->input->post('tmpbayar'),
            ];
    
            $insert = $this->Mbyrskpd->insertdataRecord($data);
    
            if ($insert) {
                $response = ['success' => true, 'message' => 'Berhasil Tambah Record.'];
            } else {
                $response = ['success' => false, 'message' => 'Gagal Tambah Record'];
            }
        }
    
        echo json_encode($response);
    }
    public function update_record_data() {
            
        $idrecord = $this->input->post('id');
        $isnonkas = $this->input->post('isnonkas') ? 1 : 0;
        $iddinas = $this->input->post('iddinas');
        $tanggal = $this->input->post('tanggal');
        $tahun = date('Y', strtotime($tanggal));
        
        $this->db->select('isdispenda');
        $this->db->from('mst_dinas');
        $this->db->where('id', $iddinas);
        $isdispenda_result = $this->db->get()->row();
    
        if ($isdispenda_result) {
            $isdispenda = $isdispenda_result->isdispenda;
        } else {
            $isdispenda = 0; 
        }
    
        $data = [
            'iddinas' => $iddinas,
            'nomor' => $this->input->post('nomor'),
            'tanggal' => $tanggal,
            'keterangan' => $this->input->post('keterangan'),
            'tahun' => $tahun,
            'isnonkas' => $isnonkas,
            'isdispenda' => $isdispenda,
            'tmpbayar' => $this->input->post('tmpbayar'),
        ];


        $update = $this->Mbyrskpd->update_record_data($idrecord, $data);
        if ($update) {
            $response = ['success' => true, 'message' => 'Berhasil update Data.'];
          } else {
            $response = ['success' => false, 'message' => 'Gagal update Data'];
          }
        
          echo json_encode($response);
    }

    	
    public function get_record_data() {
        $opsireklame = $this->input->post('id');
        if ($opsireklame) {
            $this->db->select('trx_stsmaster.id, trx_stsmaster.iddinas,trx_stsmaster.nomor, trx_stsmaster.tanggal, trx_stsmaster.keterangan,trx_stsmaster.isnonkas,trx_stsmaster.tmpbayar, mst_dinas.isdispenda');
            $this->db->from('trx_stsmaster');
            $this->db->join('mst_dinas', 'trx_stsmaster.iddinas = mst_dinas.id', 'left');
            $this->db->where('trx_stsmaster.id', $opsireklame);
            $record = $this->db->get()->row();

            if ($record) {
                $data = [
                    'id' => $record->id,
                    'iddinas' => $record->iddinas,
                    'nomor' => $record->nomor,
                    'tanggal' => $record->tanggal,
                    'keterangan' => $record->keterangan,
                    'isnonkas' => $record->isnonkas,
                    'isdispenda' => $record->isdispenda, 
                    'tmpbayar' => $record->tmpbayar,
                ];
                echo json_encode(['success' => true, 'data' => $data]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
    }   
    public function get_datatable_data() {
        $opsireklame = $this->input->post('id'); 
    
        $datatables = $this->Datatables;
        $datatables->setTable("trx_stsdetail");
        $datatables->setSelectColumn([
            'trx_stsdetail.idstsmaster',
            'trx_stsdetail.idwp',
            'trx_stsdetail.nourut',
            'trx_stsdetail.nobukti',
            'trx_stsdetail.tglpajak',
            'trx_stsdetail.idskpd',
            'trx_stsdetail.iduptd',
            'trx_stsdetail.blnpajak AS bln',
            'trx_stsdetail.thnpajak AS thn',
            'trx_stsdetail.prs_denda AS persen',
            'trx_stsdetail.nil_denda AS bunga',
            'trx_stsdetail.jumlah',
            'trx_stsdetail.total',
            'trx_stsdetail.keterangan',
            'trx_rapbd.id as rapbdid',
            'trx_rapbd.idrekening',
            'mst_wajibpajak.id',
            'mst_wajibpajak.nomor as noskpd',
            "CONCAT(mst_wajibpajak.nama, ' - ', mst_wajibpajak.nomor) AS nmwp",
            'mst_uptd.id as uptdid',
            'mst_uptd.singkat as nmuptd',
            'mst_rekening.id as idrek',
            'mst_rekening.nmrekening as nmrek',
            'trx_stsmaster.id as idmaster',
            'trx_stsmaster.iddinas',
            'trx_stsmaster.nomor'
        ]);
        $datatables->addJoin('trx_stsmaster', 'trx_stsmaster.id = trx_stsdetail.idstsmaster', 'left');
        $datatables->addJoin('mst_wajibpajak', 'mst_wajibpajak.id = trx_stsdetail.idwp', 'left');
        $datatables->addJoin('trx_skpdreklame', 'trx_skpdreklame.id = trx_stsdetail.idskpd', 'left');
        $datatables->addJoin('mst_uptd', 'mst_uptd.id = trx_stsdetail.iduptd', 'left');
        $datatables->addJoin('trx_rapbd', 'trx_rapbd.id = trx_stsdetail.idrapbd', 'left');
        $datatables->addJoin('mst_rekening', 'mst_rekening.id = trx_rapbd.idrekening', 'left');
        $datatables->addWhere('trx_stsdetail.idstsmaster', $opsireklame);
       /*  $datatables->addWhere('trx_stsmaster.nomor', '20240226/rek.trf'); */
    
        $datatables->setOrder('trx_stsdetail.nourut', 'asc');
        $fetch_data = $datatables->make_datatables();
    
        $data = array();
        foreach ($fetch_data as $row) {
            $sub_array = array(
                $row->nourut,
                $row->nobukti,
                $row->noskpd,
                $row->nmwp,
                $row->nmrek,
                $row->nmuptd,
                $row->bln,
                $row->thn,
                $row->jumlah,
                $row->persen,
                $row->bunga,
                $row->total,
                $row->keterangan,
                '<div class="action-buttons">' . 
                '<button type="button" class="btn btn-sm btn-primary modin fa fa-edit edit-data-skpd" id="edit-data-skpd" data-toggle="modal" data-target="#editModalSkpd" data-idstsmaster="'.$row->idstsmaster.'" data-nourut="'.$row->nourut.'" data-nobukti="'.$row->nobukti.'"> Edit</button>' .
                '<button type="button" class="btn btn-sm btn-danger modin fa fa-times delete-data-skpd" data-placement="bottom" title="Hapus data" data-idstsmaster="'.$row->idstsmaster.'" data-nourut="'.$row->nourut.'"> Hapus</button>' .
                '</div>'
            );
            $data[] = $sub_array;
        }
    
        $hidden_inputs = '<input type="hidden" name="idstsmaster" id="idstsmaster" value="'.$opsireklame.'">';
        if (!empty($fetch_data)) {
            $hidden_inputs .= '<input type="hidden" name="iddinas" id="iddinas" value="'.$fetch_data[0]->iddinas.'">';
        }
    
        $output = array(
            "draw" => intval($this->input->post("draw")),
            "data" => $data,
            "extra_data" => $hidden_inputs
        );
    
        echo json_encode($output);
    }
    public function get_nmwp_by_idwp() {
        $idwp = $this->input->post('idwp');
        $this->load->model('Mbyrskpd');
        $nmwp = $this->Mbyrskpd->get_namapajak_by_id($idwp);
        
        if ($nmwp) {
            echo json_encode(['success' => true, 'data' => ['nmwp' => $nmwp]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nama WP tidak ditemukan']);
        }
    }
    
    public function get_namarekening_skpd() {
        $apbdData = $this->db
            ->select('trx_rapbd.id, trx_rapbd.idrekening, trx_rapbd.iddinas, mst_rekening.idheader, mst_rekening.kdrekview, mst_rekening.kdrekening, mst_rekening.nmrekening, mst_rekening.islrauptd')
            ->from('trx_rapbd')
            ->join('mst_rekening', 'trx_rapbd.idrekening = mst_rekening.id', 'left')
            ->where('mst_rekening.kdrekview', '4.1.01.09.01.01')
            ->get()
            ->result();
    
        $dataunik = [];
        foreach ($apbdData as $item) {
            if (!isset($dataunik[$item->kdrekview])) {
                $dataunik[$item->kdrekview] = $item;
            }
        }
    
        $options = '<option disabled selected></option>';
        foreach ($dataunik as $item) {
            $options .= '<option value="'.$item->id.'">'.$item->nmrekening.' ('.$item->kdrekview.')</option>'; 
        }
    
        echo $options;
    }
    public function get_nomor_data() {
        $idstsmaster = $this->input->post('idstsmaster');
    
        if (!$idstsmaster) {
            echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan.']);
            return;
        }
    
        $this->load->model('Mbyrskpd');
        $nomor_data = $this->Mbyrskpd->ambilnomornyaMaster($idstsmaster);
    
        if ($nomor_data) {
            $last_nourut = $this->Mbyrskpd->ambilnourut($idstsmaster);
            if (!$last_nourut) {
                $last_nourut = '0000';
            }
    
            $response = [
                'success' => true,
                'data' => [
                    'nomor' => $nomor_data->nomor,
                    'last_nourut' => $last_nourut
                ]
            ];
        } else {
            $response = ['success' => false, 'message' => 'Nomor tidak ditemukan.'];
        }
    
        echo json_encode($response);
    }
    
    public function add_data() {
        $idstsmaster = $this->input->post('idstsmaster');
        $jumlah = (float) $this->input->post('jumlah');
        $prs_denda = (float) $this->input->post('prs_denda');

         
        if (!is_numeric($jumlah) || !is_numeric($prs_denda)) {
            $respon = ['success' => false, 'message' => 'harus angka.'];
            echo json_encode($respon);
            return;
        }
        
        /* Hitung denda rp */
        $nil_denda = ($jumlah * $prs_denda) / 100;
        
        /* Hitung total */
        $total = $jumlah + $nil_denda;
        
        $nomor_data = $this->Mbyrskpd->ambilnomornyaMaster($idstsmaster);
        if ($nomor_data) {
            $nomor = $nomor_data->nomor;
        } else {
            $nomor = NULL; 
        }
       /* Ambil nourut terakhir */
       $last_nourut = $this->Mbyrskpd->ambilnourut($idstsmaster);
       if (!$last_nourut) {
           $last_nourut = '0000';
       }
   
       $next_nourut = str_pad((intval($last_nourut) + 1), 4, '0', STR_PAD_LEFT);

       $nobukti = $next_nourut . '/' . $nomor;
        $data = [
            'idstsmaster' => $idstsmaster,
            'idwp' => $this->input->post('idwp'),
            'iduptd' => $this->input->post('iduptd'),
            'idrapbd' => $this->input->post('idrapbd'),
            'nobukti' => $nobukti,
            'nourut' => $next_nourut,
            'blnpajak' => $this->input->post('blnpajak'),
            'thnpajak' => $this->input->post('thnpajak'),
            'jumlah' => $jumlah,
            'prs_denda' => $prs_denda,
            'nil_denda' => $nil_denda,
            'total' => $total,
            'keterangan' => $this->input->post('keterangan'),
        ];
    
        $insert = $this->Mbyrskpd->insertdata($data);
    
        if ($insert) {
            $response = ['success' => true, 'message' => 'Berhasil Tambah Data.'];
        } else {
            $response = ['success' => false, 'message' => 'Gagal Tambah Data'];
        }
    
        echo json_encode($response);
    }
    public function update_data() 
    {
        $this->load->model('Mbyrskpd'); 
        $idstsmaster = $this->input->post('idstsmaster');
        $nourut = $this->input->post('nourut');
        
        $jumlah = (float) $this->input->post('jumlah');
        $prs_denda = (float) $this->input->post('prs_denda');
        
        if (!is_numeric($jumlah) || !is_numeric($prs_denda)) {
            $respon = ['success' => false, 'message' => 'harus angka.'];
            echo json_encode($respon);
            return;
        }

        $nil_denda = ($jumlah * $prs_denda) / 100;
        $total = $jumlah + $nil_denda;
        
        $data = [
            'idwp' => $this->input->post('idwp'),
            'iduptd' => $this->input->post('iduptd'),
            'idrapbd' => $this->input->post('idrapbd'),
            'idskpd' => $this->input->post('idskpd'),
            'nobukti' => $this->input->post('nobukti'),
            'nourut' => $nourut,
            'blnpajak' =>  $this->input->post('blnpajak'),
            'thnpajak' => $this->input->post('thnpajak'),
            'jumlah' => $jumlah,
            'prs_denda' => $prs_denda,
            'nil_denda' => $nil_denda,
            'total' => $total,
            'keterangan' => $this->input->post('keterangan'),
        ];
        
        $update = $this->Mbyrskpd->updatedata($idstsmaster, $nourut, $data);
        
        if ($update) {
            $response = ['success' => true, 'message' => 'Berhasil update Data.'];
        } else {
            $response = ['success' => false, 'message' => 'Gagal update Data'];
        }
        
        echo json_encode($response);
    }
    
     public function delete() {
            $this->load->model('Mbyrskpd'); 
     
            $idstsmaster = $this->input->post('idstsmaster');
            $nourut = $this->input->post('nourut');

            header('Content-Type: application/json'); 
            if (empty($idstsmaster) || empty($nourut)) {
              $response = ['success' => false, 'message' => 'id dan nourut salah.'];
              echo json_encode($response);
              return;
            }
          
            $delete_result = $this->Mbyrskpd->delete_record($idstsmaster, $nourut);

            if ($delete_result) {
              $response = ['success' => true, 'message' => 'Data Berhasil Dihapus.'];
            } else {
              $response = ['success' => false, 'message' => 'Gagal Delete Record'];
            }
          
            echo json_encode($response);
          }
          public function delete_all_data() {
            $this->load->model('Mbyrskpd'); 
     
            $idstsmaster = $this->input->post('idstsmaster');

            header('Content-Type: application/json'); 
          
            $delete_all_results = $this->Mbyrskpd->deleteAll($idstsmaster);

            if ($delete_all_results) {
              $response = ['success' => true, 'message' => 'Berhasil Hapus Semua Data.'];
            } else {
              $response = ['success' => false, 'message' => 'Gagal Delete Record'];
            }
          
            echo json_encode($response);
          }
          
          public function getapisimpada()
          {
              $nosptpd = empty($this->input->get('nosptpd')) ? 0 : $this->input->get('nosptpd');
              
              $urlApi = ENDPOINT_API_SIMPATDA_SKPD_REKLAME . "?noskpd=$nosptpd";
              $data = [
                  'nosptpd' => $nosptpd
              ];
              $payload = json_encode($data);
             
              $curl = curl_init($urlApi);
          
              curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
              curl_setopt($curl, CURLINFO_HEADER_OUT, true);
              curl_setopt($curl, CURLOPT_HTTPGET, true);
          
              $response = curl_exec($curl);
          
              if (curl_errno($curl)) {
                  echo "Terjadi Kesalahan pada Curl: " . curl_error($curl);
              } else {
                  $responseData = json_decode($response, true);
                  $prettyResponse = json_encode($responseData, JSON_PRETTY_PRINT); 
                  echo $prettyResponse;
              }
          
              curl_close($curl);
          }
          
          
}
