<?php defined('BASEPATH') OR exit('No direct script access allowed');
class PendDaerah extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('backend/Location');
		$this->load->model('transaksi/Mpend');
    }
    
	public function index()
	{	$Jssetup	= $this->Jssetup;
		$base 		= $this->Msetup->setup();
		$setpage	= $this->Msetup->get_title($base['halaman'].'/'.$base['fungsi']);
		$template 	= $this->Msetup->loadTemplate($setpage->title);
		$data = [];
		$data['footer']		= $template['footer'];
		$data['title'] 		= $setpage->title;
		$data['link'] 		= $setpage->link;
		$data['topbar'] 	= $template['topbar'];
		$data['modalEdit'] 	= $this->Form->modalKu('E','Edit','transaksi/PendDaerah/aksi',$actions = ['edit']);;
		$data['modalDelete']= $this->Form->modalKu('D','Delete','transaksi/PendDaerah/aksi',$actions = ['delete']);
		$data['modalAdd']   = $this->Form->modalKu('A','Add','transaksi/PendDaerah/aksi',$actions = ['add']);
		$data['sidebar'] 	= $template['sidebar'];
		$data['jstable']	= $Jssetup->jsDatatable('#pendapatan','transaksi/PendDaerah/get_datatable_data');
	 	$data['jsedit']		= $Jssetup->jsModal('#edit','Edit','transaksi/PendDaerah/myModal','#modalkuE');
	 	$data['jsdelete']	= $Jssetup->jsModal('#delete','Delete','transaksi/PendDaerah/myModal','#modalkuD');
	 	$data['jsadd']	    = $Jssetup->jsModal('#add','Add','transaksi/PendDaerah/myModal','#modalkuA');
		$data['forminsert'] = $this->Mpend->formInsert();
		$this->load->view('transaksi/pnddaerah',$data);
	}
	
    public function get_datatable_data() {
        $idrecord = $this->input->get('id');  
    
        $datatables = $this->Datatables;
        $datatables->setTable("trx_stsdetail");
        $datatables->setSelectColumn([
            'trx_stsdetail.idstsmaster', 
            'trx_stsdetail.idwp', 
            'trx_rapbd.id as rapbdid', 
            'trx_rapbd.idrekening', 
            'mst_wajibpajak.id', 
            'mst_wajibpajak.nama as wajibpajak', 
            'mst_uptd.id as uptdid', 
            'mst_uptd.singkat as uptd', 
            'mst_rekening.id as idrek', 
            'mst_rekening.nmrekening as namarekening', 
            'trx_stsdetail.nourut', 
            'trx_stsdetail.tglpajak', 
            'trx_stsdetail.idskpd', 
            'trx_stsdetail.nobukti', 
            'trx_stsdetail.blnpajak', 
            'trx_stsdetail.thnpajak', 
            'trx_stsdetail.jumlah', 
            'trx_stsdetail.prs_denda', 
            'trx_stsdetail.nil_denda', 
            'trx_stsdetail.total', 
            'trx_stsdetail.keterangan',
            'trx_stsdetail.formulir',
            'trx_stsdetail.kodebayar',
            'trx_stsdetail.tgl_input',
            'trx_stsdetail.nopelaporan',
        ]);
        $datatables->setOrderColumn(["trx_stsdetail.nourut", "trx_stsdetail.nobukti", "trx_stsdetail.blnpajak", "trx_stsdetail.thnpajak"]);
        $datatables->addJoin('trx_stsmaster', 'trx_stsmaster.id = trx_stsdetail.idstsmaster', 'left');
        $datatables->addJoin('mst_wajibpajak', 'mst_wajibpajak.id = trx_stsdetail.idwp', 'left');
        $datatables->addJoin('mst_uptd', 'mst_uptd.id = trx_stsdetail.iduptd', 'left');
        $datatables->addJoin('trx_rapbd', 'trx_rapbd.id = trx_stsdetail.idrapbd', 'left');
        $datatables->addJoin('mst_rekening', 'mst_rekening.id = trx_rapbd.idrekening', 'left');
        $datatables->addWhere('trx_stsdetail.idstsmaster', $idrecord); 
        $datatables->setOrder('trx_stsdetail.nourut', 'asc');
        $fetch_data = $datatables->make_datatables();
        $data = array();
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $row->nourut;
            $sub_array[] = $row->nobukti;
            $sub_array[] = $row->wajibpajak;
            $sub_array[] = $row->namarekening;
            $sub_array[] = $row->uptd;
            $sub_array[] = $row->tglpajak;
            $sub_array[] = $row->blnpajak;
            $sub_array[] = $row->thnpajak;
            $sub_array[] = $row->jumlah;
            $sub_array[] = $row->prs_denda;
            $sub_array[] = $row->nil_denda;
            $sub_array[] = $row->total;
            $sub_array[] = $row->keterangan;
            $sub_array[] = $row->formulir;
            $sub_array[] = $row->kodebayar;
            $sub_array[] = $row->tgl_input;
            $sub_array[] = $row->nopelaporan;
           /*  $sub_array[] = implode('',$this->Datatables->tombol($row->idstsmaster)); */
              
           $edit_button = '<button type="button" class="btn btn-sm btn-primary modin fa fa-edit edit-data" id="edit-data"  data-toggle="modal" data-target="#editModal" data-idstsmaster="'.$row->idstsmaster.'" data-nourut="'.$row->nourut.'" data-nobukti="'.$row->nobukti.'"> Edit</button>';
           $delete_button = '<button type="button" class="btn btn-sm btn-danger modin fa fa-times delete-data"  data-placement="bottom" title="Hapus data" data-idstsmaster="'.$row->idstsmaster.'" data-nourut="'.$row->nourut.'"> Hapus</button>';
   
           $buttons = '<div class="action-buttons">' . $edit_button . $delete_button . '</div>';
           $sub_array[] = $buttons;
        
           $data[] = $sub_array;
        }
        
        $hidden_input = '<input type="hidden" name="idstsmaster" id="idstsmaster" value="'.$idrecord.'">';

        $output = array(
            "draw" => intval($this->input->post("draw")),
            "data" => $data,
            "extra_data" => $hidden_input
        );
    
        echo json_encode($output);
    }
	
       public function get_record_data() {
            $idrecord = $this->input->post('id');
            if ($idrecord) {
                $this->db->select('trx_stsmaster.iddinas,trx_stsmaster.nomor, trx_stsmaster.tanggal, trx_stsmaster.keterangan,trx_stsmaster.isnonkas,trx_stsmaster.tmpbayar, mst_dinas.isdispenda');
                $this->db->from('trx_stsmaster');
                $this->db->join('mst_dinas', 'trx_stsmaster.iddinas = mst_dinas.id', 'left');
                $this->db->where('trx_stsmaster.id', $idrecord);
                $record = $this->db->get()->row();

                if ($record) {
                    $data = [
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
       
        public function myModal(){
            $wadi = isset($_POST['WADI']) ? $_POST['WADI'] : header('location:'.site_url('404'));
            $idnya = $this->input->post('idnya');
            $nourut = $this->input->post('nourut');
            $idPend = $this->Mpend->get_data_by_id($idnya, $nourut);

            switch($wadi){
                case 'Add':
                    $form [] = '
                    <div class="row">
                    <div class="col-md-6">
                          '.implode($this->Form->inputText('kode',$this->input->post('idnya'))).'
                    </div>
                             
                   <div class="col-md-6">'
                        .implode($this->Form->inputText('nobukti','NOP/SKP/NPWPD',$idPend->nobukti)).
                       '</div>
                    </div>
                  ';
                    echo implode($form);
                break;
                case 'Edit':
                    $form [] = '
                    <div class="row">
                    <div class="col-md-6">
                          '.implode($this->Form->inputText('kode',$this->input->post('idnya'))).'
                    </div>
                             
                   <div class="col-md-6">'
                        .implode($this->Form->inputText('nobukti','NOP/SKP/NPWPD',$idPend->nobukti)).
                       '</div>
                    </div>
                  ';
                    echo implode($form);
                break;
                case 'Delete':
                    $form [] = '
                    <div class="row">
                        <div class="col-md-12">
                        '.implode($this->Form->hiddenText('kode',$this->input->post('idnya'))).'
                        '.implode($this->Form->hiddenText('nourut',$this->input->post('nourut'))).'
                        Apakah kamu yakin ingin menghapus data ini ?
                        </div>
                    </div>';
                    
                break;
                case 'DeleteAll':
                    $form [] = '
                    <div class="row">
                        <div class="col-md-12">
                        '.implode($this->Form->hiddenText('kode',$this->input->post('idnya'))).'
                        Apakah kamu yakin ingin menghapus data ini ?
                        </div>
                    </div>';
                    
                break;
                default:
                    echo 'NOTHING !!!';
                break;
            }
            echo implode($form);
        }
    
        public function getRecordData()
        {
            $this->load->database();
            
            $this->db->select('id, nomor');
            $recData = $this->db->get('trx_stsmaster')->result();
        
            $opsiRec = array();
            foreach ($recData as $record) {
                $opsiRec[] = array(
                    'id' => $record->id,
                    'nomor' => $record->nomor
                );
            }
        
            header('Content-Type: application/json');
            echo json_encode($opsiRec);
        }
        /* Record fynction */
        public function add_record_data() {
            $isnonkas = $this->input->post('isnonkas') ? $this->input->post('isnonkas') : 0;
            $iddinas = $this->input->post('iddinas');
            $tanggal = $this->input->post('tanggal');
            $nomor = $this->input->post('nomor');
            $tahun = date('Y', strtotime($tanggal));
        
            $this->db->select('nomor');
            $this->db->from('trx_stsmaster');
            $this->db->where('nomor', $nomor);
            $existing_nomor = $this->db->get()->row();
        
            if ($existing_nomor) {
                $this->session->set_flashdata('massage', 'Nomor record sudah ada, gagal tambah data');
                redirect('transaksi/PendDaerah');
                return;
            }
        
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
        
            $this->db->insert('trx_stsmaster', $data);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('message', 'Berhasil Tambah Record Baru');
                redirect('transaksi/PendDaerah');
            } else {
                $this->session->set_flashdata('error', 'Gagal Tambah Record Baru');
                redirect('transaksi/PendDaerah');
            }
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
    

            $update = $this->Mpend->update_record_data($idrecord, $data);
            if ($update) {
                $response = ['success' => true, 'message' => 'Berhasil update Data.'];
              } else {
                $response = ['success' => false, 'message' => 'Gagal update Data'];
              }
            
              echo json_encode($response);
        }

        /* End record function */

        
        /* Action datatable Record fynction */
        public function add_data() {
            $jumlah = $this->input->post('jumlah');
            $prs_denda = $this->input->post('prs_denda');
            
            /* Hitung denda rp */
            $nil_denda = ($jumlah * $prs_denda) / 100;
            
            /* Hitung total */
            $total = $jumlah + $nil_denda;
            
            /* Ambil nourut terakhir */
            $last_nourut = $this->Mpend->get_last_nourut(); 

            if (!$last_nourut) {
                $last_nourut = '0000';
            }
            
            
           /*  Tambahkan 1 ke nourut terakhir */
            $next_nourut = str_pad((intval($last_nourut) + 1), strlen($last_nourut), '0', STR_PAD_LEFT);
            
            $data = [
                'idstsmaster' => $this->input->post('idstsmaster'),
                'idwp' => $this->input->post('idwp'),
                'iduptd' => $this->input->post('iduptd'),
                'tglpajak' => $this->input->post('tglpajak'),
                /* 'idskpd' => $this->input->post('idskpd'), */
                'nobukti' => $this->input->post('nobukti'),
                'nourut' => $next_nourut,
                'blnpajak' => $this->input->post('blnpajak'),
                'thnpajak' => $this->input->post('thnpajak'),
                'jumlah' => $jumlah,
                'prs_denda' => $prs_denda,
                'nil_denda' => $nil_denda,
                'total' => $total,
                'keterangan' => $this->input->post('keterangan'),
                'formulir' => $this->input->post('formulir'),
                'kodebayar' => $this->input->post('kodebayar'),
                'tgl_input' => $this->input->post('tgl_input'),
                'nopelaporan' => $this->input->post('nopelaporan'),
            ];
        
            $insert = $this->Mpend->insertdata($data);
        
            if ($insert) {
                $response = ['success' => true, 'message' => 'Berhasil Tambah Data.'];
            } else {
                $response = ['success' => false, 'message' => 'Gagal Tambah Data'];
            }
        
            echo json_encode($response);
        }
        public function add_data_temp() {
            $idstsmaster = $this->input->post('idstsmaster');
            $jumlah = $this->input->post('jumlah');     
           /*  $nosptpd = $this->input->post('nosptpd');   */

            /* Ambil nourut terakhir */
            $last_nourut = $this->Mpend->get_last_nourut($idstsmaster);
            if (!$last_nourut) {
                $last_nourut = '0000';
            }
        
            // Tambahkan 1 ke nourut terakhir
            $next_nourut = str_pad((intval($last_nourut) + 1), 4, '0', STR_PAD_LEFT);

            $data = [
                'idstsmaster' => $idstsmaster,          
                'nobukti' => $this->input->post('nobukti'),
                'idwp' => $this->input->post('idwp'),
                'blnpajak' => $this->input->post('blnpajak'),
                'thnpajak' => $this->input->post('thnpajak'),
                'kodebayar' => $this->input->post('kodebayar'),
                'jumlah' => $jumlah,
                'nil_denda' => $this->input->post('nil_denda'),
                'total' => $this->input->post('total'),
                'tgl_input' => $this->input->post('tgl_input'),
                'nopelaporan' => $this->input->post('nopelaporan'),
                'formulir' => $this->input->post('formulir'),
                'nourut' => $next_nourut,
                
            ];
        
            $insert = $this->Mpend->insertdata($data);
        
            if ($insert) {
                $response = ['success' => true, 'message' => 'Berhasil Tambah Data.'];
            } else {
                $response = ['success' => false, 'message' => 'Gagal Tambah Data'];
            }
        
            echo json_encode($response);
        }
        
        public function get_edit_data() {
            $this->load->model('Mpend'); 

            $idstsmaster = $this->input->post('idstsmaster');
            $nourut = $this->input->post('nourut');
    
            $data = $this->Mpend->getDataById($idstsmaster, $nourut);
            if ($data) {
                echo json_encode(['success' => true, 'data' => $data]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Data not found']);
            }
        }
        
   
        public function update_data() 
        {
            $this->load->model('Mpend'); 
            $idstsmaster = $this->input->post('idstsmaster');
            $nourut = $this->input->post('nourut');
        
    
            $data = [
                'idwp' => $this->input->post('idwp'),
                'iduptd' => $this->input->post('iduptd'),
                'idrapbd' => $this->input->post('idrapbd'),
                'tglpajak' => $this->input->post('tglpajak'),
                'nobukti' => $this->input->post('nobukti'),
                'nourut' => $this->input->post('nourut'),
                'blnpajak' =>  $this->input->post('blnpajak'),
                'thnpajak' => $this->input->post('thnpajak'),
                'jumlah' => $this->input->post('jumlah'),
                'prs_denda' =>  $this->input->post('prs_denda'),
                'nil_denda' => $this->input->post('nil_denda'),
                'total' => $this->input->post('total'),
                'keterangan' => $this->input->post('keterangan'),
                'formulir' => $this->input->post('formulir'),
                'kodebayar' => $this->input->post('kodebayar'),
                'tgl_input' => $this->input->post('tgl_input'),
                'nopelaporan' => $this->input->post('nopelaporan'),
            ];
            $update = $this->Mpend->updatedata($idstsmaster, $nourut, $data);
           
            if ($update) {
                $response = ['success' => true, 'message' => 'Berhasil update Data.'];
              } else {
                $response = ['success' => false, 'message' => 'Gagal update Data'];
              }
            
              echo json_encode($response);
        }
        public function delete() {
            $this->load->model('Mpend'); 
     
            $idstsmaster = $this->input->post('idstsmaster');
            $nourut = $this->input->post('nourut');

            header('Content-Type: application/json'); 
            if (empty($idstsmaster) || empty($nourut)) {
              $response = ['success' => false, 'message' => 'Invalid ID or nourut provided.'];
              echo json_encode($response);
              return;
            }
          
            $delete_result = $this->Mpend->delete_record($idstsmaster, $nourut);

            if ($delete_result) {
              $response = ['success' => true, 'message' => 'Data deleted successfully.'];
            } else {
              $response = ['success' => false, 'message' => 'Error deleting record'];
            }
          
            echo json_encode($response);
          }
        public function delete_all_data() {
            $this->load->model('Mpend'); 
     
            $idstsmaster = $this->input->post('idstsmaster');

            header('Content-Type: application/json'); 
          
            $delete_all_results = $this->Mpend->deleteAll($idstsmaster);

            if ($delete_all_results) {
              $response = ['success' => true, 'message' => 'All Data deleted successfully.'];
            } else {
              $response = ['success' => false, 'message' => 'Error deleting record'];
            }
          
            echo json_encode($response);
          }

          /* End Action datatable Record fynction */


         /*  public function get_wajibpajak()
          {
              $page = $_GET['page'] ?: 1; 
              $limit = 10; 
              $offset = ($page - 1) * $limit;
          
              $this->db->limit($limit, $offset);
              $wpData = $this->db->get('mst_wajibpajak')->result();
          
              $results = array();
              foreach ($wpData as $wp) {
                  $results[] = array(
                      'id' => $wp->id,
                      'text' => $wp->nama
                  );
              }
          
              echo json_encode($results);
          } */
          public function get_rekening_by_idrapbd()
          {
              $idstsmaster = $this->input->post('idstsmaster');
          
              $rekdata = $this->db
                  ->select('mst_rekening.id, mst_rekening.kdrekening, mst_rekening.nmrekening, mst_rekening.islrauptd')
                  ->from('mst_rekening')
                  ->join('trx_rapbd', 'trx_rapbd.idrekening = mst_rekening.id')
                  ->join('trx_stsdetail', 'trx_stsdetail.idrapbd = trx_rapbd.id')
                  ->join('trx_stsmaster', 'trx_stsmaster.id = trx_stsdetail.idstsmaster')
                  ->where('trx_stsmaster.id', $idstsmaster)
                  ->get()
                  ->result();
          
              $opsirek = '<option></option>';
              foreach ($rekdata as $ttd) {
                  $opsirek .= '<option value="'.$ttd->kdrekening.'">'.$ttd->nmrekening.'</option>';
              }
          
              echo $opsirek;
          }
          public function getapisimpada()
          {
              $nosptpd = empty($this->input->get('nosptpd')) ? 0 : $this->input->get('nosptpd');
              
              $urlApi = ENDPOINT_API_SIMPATDA . "?kodeBayar=$nosptpd";
          
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
          
              // Menutup koneksi Curl
              curl_close($curl);
          }
          
          public function getapisimpadabphtb()
          {
               $noformulir = empty($this->input->get('noformulir')) ? 0 : $this->input->get('noformulir');
               
               $urlApi = ENDPOINT_API_SIMPATDA_BPHTB . "?noformulir=$noformulir";

               $data = [
                'kodebayar' => $noformulir
                ];
                $payload = json_encode($data);
                $curl = curl_init($urlApi);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);

                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    echo "Terjadi Kesalahan pada Curl: " . curl_error($curl);
                } else {
                    $responseData = json_decode($response, true);
                    $prettyResponse = json_encode($responseData, JSON_PRETTY_PRINT); 
                    echo $prettyResponse;
                }

                // Menutup koneksi Curl
                curl_close($curl);     
                
            }
            

}
?>
		
