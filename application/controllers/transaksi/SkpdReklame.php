<?php defined('BASEPATH') or exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

class SkpdReklame extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transaksi/MSkpd');
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
                'modalEdit'   => $this->Form->modalKu('E', 'Edit', 'transaksi/SkpdReklame/aksi', ['edit']),
                'modalDelete' => $this->Form->modalKu('D', 'Delete', 'transaksi/SkpdReklame/aksi', ['delete']),
                'sidebar'     => $template['sidebar'],
                'jstable'     => $Jssetup->jsDatatable('#skpdTable', 'transaksi/SkpdReklame/getSkpd'),
                'jsedit'      => $Jssetup->jsModal('#edit', 'Edit', 'transaksi/SkpdReklame/myModal', '#modalkuE'),
                'jsdelete'    => $Jssetup->jsModal('#delete', 'Delete', 'transaksi/SkpdReklame/myModal', '#modalkuD'),
                'forminsert'  => implode('', $this->MSkpd->formInsert()),
                'formcetak'  => implode('', $this->MSkpd->formcetak()),
                // 'formTambah'  => implode('', $this->MApbd->formModal())
            ];

        $this->load->view('transaksi/skpd', $data);
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
    
    public function cetak() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('404');
        }
        $base 			  = $this->Msetup->setup();
        $setpage 		  = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
        $template 		  = $this->Msetup->loadTemplate($setpage->title);
    
        $tgl_cetak = $this->input->post('tgl_cetak');
    
        $tanda_tangan_1 = $this->input->post('tanda_tangan_1');
        $tanda_tangan_2 = $this->input->post('tanda_tangan_2');
    
        $blnpajak =  $this->input->post('blnpajak');
        $thnpajak = $this->input->post('thnpajak');
    
        $data = [
            'footer' => $template['footer'],
            'title' => $setpage->title,
            'link' => $setpage->link,
            'topbar' => $template['topbar'],
            'sidebar' => $template['sidebar'],
            'format_bulan' => strftime('%B', strtotime("$thnpajak-$blnpajak")),
            'format_tahun' => $thnpajak,
            'tablenya' => $this->MSkpd->getSkpdData($thnpajak, $blnpajak),
            'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tgl_cetak)),
    
        ];
        
        $tanda_tangan_data_1 = $this->Msetup->get_tanda_tangan_skpd_1($tanda_tangan_1);
        $tanda_tangan_data_2 = $this->Msetup->get_tanda_tangan_skpd_2($tanda_tangan_2);
    
        if ($tanda_tangan_data_1) {
            $data['tanda_tangan_1'] = $tanda_tangan_data_1;
        }
        if ($tanda_tangan_data_2) {
            $data['tanda_tangan_2'] = $tanda_tangan_data_2;
        }
 
       /*  $this->load->view('transaksi/printskpd', $data); */
        ob_start();
        $html = $this->load->view('transaksi/printskpd', $data, true);
        ob_get_clean();
        
    
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("skpd_reklame.pdf", array("Attachment" => 0));
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

    function selectBySKPD($key='',$skpd='', $start=0, $limit=0, $sort, $dir='ASC', &$total=0){
        $key = $this->db->escape_like_str($key);
        // $where = "(a.nama LIKE '%$key%' OR a.nomor LIKE '%$key%' OR a.pemilik LIKE '%$key%')";
        $where = "(a.nama LIKE '%$key%' OR a.nomor LIKE '%$key%' OR a.pemilik LIKE '%$key%' 
        OR a.nama LIKE '%$skpd%' OR a.nomor LIKE '%$skpd%' OR a.pemilik LIKE '%$skpd%')";
        $total = $this->db
            ->where($where)
            ->where('notype', 'No. SKP')
            ->count_all_results('mst_wajibpajak a');

        $result = $this->db
            ->select("a.id, a.nama, CONCAT(a.nama, ' - ', a.nomor) AS nmwp, a.alamat, a.idkelurahan, b.nama AS kelurahan, 
                b.idkecamatan, c.nama AS kecamatan, a.nomor, a.notype, a.tglskp, a.tgljthtmp, 
                a.idrekening, d.nmrekening, d.jenis,
                c.iduptd, e.nama AS nmuptd, e.singkat AS nmuptdsingkat,
                a.awalpajakbln, a.awalpajakthn, a.akhirpajakbln, a.akhirpajakthn, a.isclosed,f.keterangan", false)
            ->join('mst_rekening d', 'd.id=a.idrekening')
            ->join('mst_kelurahan b', 'b.id=a.idkelurahan', 'left')
            ->join('mst_kecamatan c', 'c.id=b.idkecamatan', 'left')
            ->join('mst_uptd e', 'e.id=c.iduptd', 'left')
            ->join('trx_skpdreklame f', 'f.idwp=a.id', 'left')
            ->where($where)
            ->where('a.notype', 'No. SKP')
            ->order_by($sort, $dir)
            ->get('mst_wajibpajak a', $limit, $start);

        return $result;
    }

    public function selectBySKPD1($query) {
        $sql = "SELECT * FROM ($query) AS union_result";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
}
