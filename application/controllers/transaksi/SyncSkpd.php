<?php defined('BASEPATH') or exit('No direct script access allowed');
class SyncSkpd extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transaksi/MSyncSkpd');
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
                'modalEdit'   => $this->Form->modalKu('E', 'Edit', 'transaksi/SyncSkpd/aksi', ['edit']),
                'modalDelete' => $this->Form->modalKu('D', 'Delete', 'transaksi/SyncSkpd/aksi', ['delete']),
                'sidebar'     => $template['sidebar'],
                'jstable'     => $Jssetup->jsDatatable('#ftf', 'transaksi/SyncSkpd/getSkpd'),
                'jsedit'      => $Jssetup->jsModal('#edit', 'Edit', 'transaksi/SyncSkpd/myModal', '#modalkuE'),
                'jsdelete'    => $Jssetup->jsModal('#delete', 'Delete', 'transaksi/SyncSkpd/myModal', '#modalkuD'),
                'formCari'  => implode('', $this->MSyncSkpd->formCari()),
            ];

        $this->load->view('transaksi/syncskpd', $data);
    }
    function tes(){
        if ($this->userinfo!='' && ($this->userinfo['role']=='adm' || $this->userinfo['role']=='man' || $this->userinfo['role']=='opr' || $this->userinfo['role']=='mhs' || $this->userinfo['role']=='pjk')) {
            $postdata = file_get_contents("php://input");
            $param = get_object_vars(json_decode($postdata));

          
                $dataArray = array(
            
                    'items'=>$param['items']
                );
                $data = $dataArray['items'];

            for($x=0;$x<count($data);$x++){
                $cek=$this->skpdrek->cekData($data[$x]->nopelaporan);
                $success = $this->skpdrek->insert($data[$x]);
                
           
            $json['id'] = $success;
            $json['success'] = true;
            if($cek=== true){
                $json['message'] = 'Data sudah ada! dan di perbaarui';
            }else{
                $json['message'] = 'Data telah disimpan!';
            }
            header('Content-type: application/json');
            header("HTTP/1.0 200 OK");
            }
        }else{
            $json['success'] = false;
            $json['message'] = 'Session anda telah expire, silahkan login kembali';
            header('Content-type: application/json');
            header("HTTP/1.0 500 Internal Server Error");
        }
        echo json_encode($json);
    }

 
    public function selectBySKPD()
    {
        // Get parameters from input
        $query = $this->input->get('query'); // Assuming 'query' is the parameter to search with
        $npwpd = $this->input->get('npwpd');
        $nmwp = $this->input->get('nmwp');
        $alamat = $this->input->get('alamat');
        $noskpd = $this->input->get('noskpd');
        $kodebayar = $this->input->get('kodebayar');
        $nosptpd = $this->input->get('nosptpd');
        $kecamatan = $this->input->get('kecamatan');
        $kelurahan = $this->input->get('kelurahan');
        $masapajak = $this->input->get('masapajak');
        $tgljthtmp = $this->input->get('tgljthtmp');
        $teks = $this->input->get('teks');
        $blnpajak = $this->input->get('blnpajak');
        $thnpajak = $this->input->get('thnpajak');
        $jumlah = $this->input->get('jumlah');
        $bunga = $this->input->get('bunga');
        $total = $this->input->get('total');
        $tglbayar = $this->input->get('tglbayar');
        $keterangan = $this->input->get('keterangan');
    
        // Perform the query to get records based on the search criteria
        $this->db
            ->select("a.id, a.nama, CONCAT(a.nama, ' - ', a.nomor) AS nmwp, a.alamat, a.idkelurahan, b.nama AS kelurahan, 
                b.idkecamatan, c.nama AS kecamatan, a.nomor, a.notype, a.tglskp, a.tgljthtmp, 
                a.idrekening, d.nmrekening, d.jenis,
                c.iduptd, e.nama AS nmuptd, e.singkat AS nmuptdsingkat,
                a.awalpajakbln, a.awalpajakthn, a.akhirpajakbln, a.akhirpajakthn, a.isclosed, f.keterangan", false)
            ->join('mst_rekening d', 'd.id=a.idrekening')
            ->join('mst_kelurahan b', 'b.id=a.idkelurahan', 'left')
            ->join('mst_kecamatan c', 'c.id=b.idkecamatan', 'left')
            ->join('mst_uptd e', 'e.id=c.iduptd', 'left')
            ->join('trx_skpdreklame f', 'f.idwp=a.id', 'left')
            ->where('a.notype', 'No. SKP');
    
        // Apply filter based on 'NamaObjekPajak'
        if (!empty($nmwp)) {
            $this->db->where('a.nama', $nmwp);
        }
    
        $query_result = $this->db->get('mst_wajibpajak a');
    
        $result = [];
        if ($query_result->num_rows() > 0) {
            $result = $query_result->result_array();
            
            // Add idwp to each record
            foreach ($result as &$record) {
                $record['idwp'] = $record['id'];
            }
        }
    
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'total' => count($result),
                'data' => $result
            ]));
    }
    
    public function getSkpd()
    {
        $tanggal = $this->input->post('tanggal');


        $datatables = $this->Datatables;
        $datatables->setTable("trx_skpdreklame");
        $datatables->setSelectColumn([
            "trx_skpdreklame.id",
            "trx_skpdreklame.idwp",
            "CONCAT(trx_skpdreklame.blnpajak, '-', trx_skpdreklame.thnpajak) as masapajak",
            "trx_skpdreklame.teks",
            "trx_skpdreklame.blnpajak",
            "trx_skpdreklame.thnpajak",
            "trx_skpdreklame.jumlah",
            "trx_skpdreklame.bunga",
            "trx_skpdreklame.total",
            "trx_skpdreklame.tglbayar",
            "trx_skpdreklame.keterangan",
            "trx_skpdreklame.isbayar",
            "trx_skpdreklame.tanggal",
            "mst_wajibpajak.npwpd",
            "mst_wajibpajak.nomor",
            "SUBSTRING(mst_wajibpajak.nomor, 1, 4) as nokohir",
            "mst_wajibpajak.nama as nmwp",
            "mst_wajibpajak.alamat",
            "mst_wajibpajak.idkelurahan",
            "mst_wajibpajak.tglskp",
            "mst_wajibpajak.tgljthtmp",
            "mst_kelurahan.nama as kelurahan",
            "mst_kelurahan.idkecamatan",
            "mst_kecamatan.nama as kecamatan",
            "trx_stsdetail.kodebayar",
            "trx_stsdetail.nopelaporan",
         
        ]);
        $datatables->addJoin('mst_wajibpajak', 'mst_wajibpajak.id=trx_skpdreklame.idwp', 'left');
        $datatables->addJoin('mst_kelurahan', 'mst_kelurahan.id=mst_wajibpajak.idkelurahan', 'left');
        $datatables->addJoin('mst_kecamatan', 'mst_kecamatan.id=mst_kelurahan.idkecamatan', 'left');
        $datatables->addJoin('trx_stsdetail', 'trx_stsdetail.idwp=mst_wajibpajak.id', 'left');
        $datatables->addWhere('mst_wajibpajak.tglskp', $tanggal);
        $fetch_data = $this->Datatables->make_datatables();

        $data = array();
        foreach ($fetch_data as $row) {
            $sub_array = array();
            if ($row->isbayar != 1) {
                foreach ($row as $key => $value) {
                    if ($key == 'isbayar') continue; 
                    $sub_array[$key] = $value;
                }
            } else {
                $sub_array = (array) $row;
                $sub_array['DT_RowClass'] = 'bg-success text-dark';
            }
            $check_delete = '<input type="checkbox" id="submit_check" class="submit-checkbox" data-id="' . $row->id . '">';
            $sub_array[] = $check_delete;
            $sub_array[] = $row->npwpd;
            $sub_array[] = $row->nmwp;
            $sub_array[] = $row->alamat;
            $sub_array[] = $row->nokohir;
            $sub_array[] = $row->kelurahan;
            $sub_array[] = $row->kecamatan;
            $sub_array[] = $row->kodebayar;
            $sub_array[] = $row->nopelaporan;
            $sub_array[] = $row->masapajak;
            $sub_array[] = $row->tgljthtmp;
            $sub_array[] = $row->teks;
            $sub_array[] = $row->blnpajak;
            $sub_array[] = $row->thnpajak;
            $sub_array[] = $row->jumlah;
            $sub_array[] = $row->bunga;
            $sub_array[] = $row->total;
            $sub_array[] = $row->tglbayar;
            $sub_array[] = $row->keterangan;
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

    public function getapisimpadareklame()
    {
            $tanggal = empty($this->input->get('tanggal')) ? 0 : $this->input->get('tanggal');
            $urlApi = ENDPOINT_API_SIMPATDA_REKLAME . "?tanggal=$tanggal";
            $data = [
                'tanggal' => $tanggal
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

    public function cekData($data){
        $cek = $this->db->select('id AS idrwp','nomor')
        ->where('nopelaporan', $data)
        ->get('mst_wajibpajak');
        if($cek->num_rows() > 0){
            return true;
        }else{
            return false;  
        }

    }
    public function check_namaobjekpajak() {
        $namaobjekpajak = $this->input->post('namaobjekpajak');
        $alamatobjek = $this->input->post('alamatobjek');
        $noskpdn = $this->input->post('noskpdn');
        $masapajak = $this->input->post('masapajak');
        $tahun = $this->input->post('tahun');
        $tanggal = $this->input->post('tanggal');
        $tanggalbayar = $this->input->post('tanggalbayar');
        $jumlahbayar = $this->input->post('jumlahbayar');
        $denda = $this->input->post('denda');
        $totalbayar = $this->input->post('totalbayar');
        $jenisreklame = $this->input->post('jenisreklame');
        $nama = $this->input->post('nama');
        $statusbayar = $this->input->post('statusbayar');
        
        $tgljatuhtempo = $this->input->post('tgljatuhtempo');
        $npwpd = $this->input->post('npwpd');

        $isbayar = ($statusbayar === 'LUNAS') ? 1 : 0;
        $tgljatuhtempo = date('Y-m-d', strtotime(str_replace('-', '/', $tgljatuhtempo)));
        $tanggal = date('Y-m-d', strtotime(str_replace('-', '/', $tanggal)));

        $bulan = date('m', strtotime(str_replace('-', '/', $masapajak)));
        $this->load->database();
   
        $this->db->select('
        mst_wajibpajak.id, 
        mst_wajibpajak.nama, 
        mst_wajibpajak.alamat, 
        mst_wajibpajak.tgljthtmp, 
        mst_wajibpajak.npwpd, 
        mst_wajibpajak.nopelaporan, 
        mst_wajibpajak.idkelurahan, 
        trx_skpdreklame.idwp,
        trx_skpdreklame.blnpajak,
        trx_skpdreklame.thnpajak,
        trx_skpdreklame.tanggal,
        trx_skpdreklame.tglbayar,
        trx_skpdreklame.jumlah,
        trx_skpdreklame.bunga,
        trx_skpdreklame.total,
        trx_skpdreklame.keterangan,
        trx_skpdreklame.teks,
        trx_skpdreklame.nopelaporan,
        trx_skpdreklame.isbayar,
        ');
        $this->db->from('mst_wajibpajak');
        $this->db->join('trx_skpdreklame', 'trx_skpdreklame.idwp = mst_wajibpajak.id');
        
        $this->db->like('mst_wajibpajak.nama', $namaobjekpajak);
        $this->db->like('mst_wajibpajak.alamat', $alamatobjek);
        $this->db->where('trx_skpdreklame.nopelaporan', $noskpdn);
        
        $query = $this->db->get();
        $results = $query->result_array();
    
        if (!empty($results)) {
            $existingIdwp = $results[0]['idwp'];
            
            $this->db->trans_start();
            
            $this->db->where('id', $existingIdwp);
            $this->db->update('mst_wajibpajak', [
                'nama' => $namaobjekpajak,
                'alamat' => $alamatobjek,
                'tgljthtmp' => $tgljatuhtempo,
                'npwpd' => $npwpd,
                'nopelaporan' => $noskpdn,
            ]);
    
            $this->db->where('idwp', $existingIdwp);
            $this->db->update('trx_skpdreklame', [
                'nopelaporan' => $noskpdn,
                'blnpajak' => $bulan,
                'thnpajak' => $tahun,
                'tanggal' => $tanggal,
                'tglbayar' => $tanggalbayar,
                'jumlah' => $jumlahbayar,
                'bunga' => $denda,
                'total' => $totalbayar,
                'keterangan' => $jenisreklame,
                'teks' => $nama,
                'isbayar' => $isbayar,
            ]);
            
            $this->db->trans_complete(); 
    
            if ($this->db->trans_status() === FALSE) {
              
                echo json_encode(['exists' => true, 'message' => 'Failed to update data.']);
            } else {
       
                echo json_encode(['exists' => true, 'message' => 'Data updated successfully.']);
            }
        } else {

            $this->db->trans_start();

            $this->db->insert('mst_wajibpajak', [
                'nama' => $namaobjekpajak,
                'alamat' => $alamatobjek,
                'tgljthtmp' => $tgljatuhtempo,
                'npwpd' => $npwpd,
                'nopelaporan' => $noskpdn,
            ]);
    
            $idwp = $this->db->insert_id(); 
    
            $this->db->insert('trx_skpdreklame', [
                'idwp' => $idwp,
                'nopelaporan' => $noskpdn,
                'blnpajak' => $bulan,
                'thnpajak' => $tahun,
                'tanggal' => $tanggal,
                'tglbayar' => $tanggalbayar,
                'jumlah' => $jumlahbayar,
                'bunga' => $denda,
                'total' => $totalbayar,
                'keterangan' => $jenisreklame,
                'teks' => $nama,
                'isbayar' => $isbayar,
             
            ]);
    
            $this->db->trans_complete(); 
    
            if ($this->db->trans_status() === FALSE) {
         
                echo json_encode(['exists' => false, 'message' => 'Failed to insert data.']);
            } else {
              
                echo json_encode(['exists' => false, 'message' => 'Data inserted successfully.', 'idwp' => $idwp]);
            }
        }
    }
    
   
 
    
    public function submit() {
        $data = json_decode($this->input->raw_input_stream, true);

        foreach ($data['data'] as $item) {
            $namaObjekPajak = $item['NamaObjekPajak'];
            $wajibPajak = $this->MSyncSkpd->getWajibPajakIdByName($namaObjekPajak);

            if ($wajibPajak) {
                $insertData = [
                    'idwp' => $wajibPajak->idwp,
                    'nama_objek' => $namaObjekPajak,
                ];

                $this->MSyncSkpd->insertdata($insertData);
            } else {
                log_message('error', 'Nama Objek Pajak tidak ditemukan: ' . $namaObjekPajak);
            }
        }

        echo json_encode(['status' => 'success']);
    }

    public function submit2() {
       
        $idwp = $this->input->post('idwp');
        $check_duplicate = $this->MSyncSkpd->check_duplicate_data($idwp);
        if ($check_duplicate) {
            $response = ['error' => false, 'message' => 'Data Sudah Pernah Diinput'];
        } else {
            $data = [
                /* 'idwp'=>$idwp, */
                'tanggal'=>$this->input->post('tanggal'),
                'blnpajak'=>$this->input->post('blnpajak'),
                'thnpajak'=>$this->input->post('thnpajak'),
                'jumlah'=>$this->input->post('jumlah'),
                'keterangan'=>$this->input->post('keterangan'),
            ];
    
            $insert = $this->MSyncSkpd->insertdata($data);
    
            if ($insert) {
                $response = ['success' => true, 'message' => 'Berhasil Simpan Data.'];
            } else {
                $response = ['success' => false, 'message' => 'Gagal Simpan Data'];
            }
        }
    
        echo json_encode($response);
    }
    
}
