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
		$this->load->view('transaksi/pnddaerah',$data);
	}
    function selectByIdSKPD($id = 0)
    {
        $result = $this->db
            ->where('id', $id)
            ->get('trx_stsmaster');

        if ($result->num_rows() > 0) {
            $row = $result->row();
            $data['nomor'] = $row->nomor;
            $data['tanggal'] = $row->tanggal;
            $data['iddinas'] = $row->iddinas;
            $data['isdispenda'] = $row->isdispenda;
            $data['tmpbayar'] = $row->tmpbayar;
            $data['keterangan'] = $row->keterangan;

            //get detail yang ada pasangannya 
            $result = $this->db
                ->select("a.nourut, a.nobukti, a.idskpd, e.nomor AS noskpd, a.idwp, CONCAT(e.nama, ' - ', e.nomor) AS nmwp, 
                    c.id AS idrek, c.nmrekening AS nmrek, 
                    a.iduptd, f.singkat AS nmuptd, 
                    a.blnpajak AS bln, a.thnpajak AS thn, 
                    a.jumlah, a.prs_denda AS persen, LOWER(a.nil_denda) AS bunga, a.total, a.keterangan", false)
                ->join('trx_rapbd b', 'b.id=a.idrapbd')
                ->join('mst_rekening c', 'c.id=b.idrekening')
                ->join('trx_skpdreklame d', 'd.id=a.idskpd')
                ->join('mst_wajibpajak e', 'e.id=d.idwp')
                ->join('mst_uptd f', 'f.id=a.iduptd')
                ->where('a.idstsmaster', $id)
                ->where('c.jenis', 'REK')
                ->get('trx_stsdetail a');

            //get total all items
            $urutrs = $this->db
                ->select("max(nourut) AS maxurut", false)
                ->where('idstsmaster', $id)
                ->get('trx_stsdetail');

            if ($urutrs->num_rows() > 0) {
                $maxurut = $urutrs->row()->maxurut;
            } else {
                $maxurut = '0000';
            }

            $data['maxurut'] = $maxurut;
            $data['items'] = $result->result_array();
        } else {
            $data = array();
        }


        return $data;
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
            'trx_stsmaster.iddinas'
        ]);
      
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
            $sub_array = array(
                $row->nourut,
                $row->nobukti,
                $row->wajibpajak,
                $row->namarekening,
                $row->uptd,
                $row->tglpajak,
                $row->blnpajak,
                $row->thnpajak,
                $row->jumlah,
                $row->prs_denda,
                $row->nil_denda,
                $row->total,
                $row->keterangan,
                $row->formulir,
                $row->kodebayar,
                $row->tgl_input,
                $row->nopelaporan,
                '<div class="action-buttons">' . 
                '<button type="button" class="btn btn-sm btn-primary modin fa fa-edit edit-data" id="edit-data" data-toggle="modal" data-target="#editModal" data-idstsmaster="'.$row->idstsmaster.'" data-nourut="'.$row->nourut.'" data-nobukti="'.$row->nobukti.'"> Edit</button>' .
                '<button type="button" class="btn btn-sm btn-danger modin fa fa-times delete-data" data-placement="bottom" title="Hapus data" data-idstsmaster="'.$row->idstsmaster.'" data-nourut="'.$row->nourut.'"> Hapus</button>' .
                '</div>'
            );
            $data[] = $sub_array;
        }
        
        $hidden_inputs = '<input type="hidden" name="idstsmaster" id="idstsmaster" value="'.$idrecord.'">';
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
        
                $insert = $this->Mpend->insertdataRecord($data);
        
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
            $idstsmaster = $this->input->post('idstsmaster');
            $jumlah = $this->input->post('jumlah');
            $prs_denda = $this->input->post('prs_denda');
            
            /* Hitung denda rp */
            $nil_denda = ($jumlah * $prs_denda) / 100;
            
            /* Hitung total */
            $total = $jumlah + $nil_denda;
            
            
           /* Ambil nourut terakhir */
           $last_nourut = $this->Mpend->get_last_nourut($idstsmaster);
           if (!$last_nourut) {
               $last_nourut = '0000';
           }
       
           // Tambahkan 1 ke nourut terakhir
           $next_nourut = str_pad((intval($last_nourut) + 1), 4, '0', STR_PAD_LEFT);
            
            $data = [
                'idstsmaster' => $idstsmaster,
                'idwp' => $this->input->post('idwp'),
                'iduptd' => $this->input->post('iduptd'),
                'idrapbd' => $this->input->post('idrapbd'),
                'tglpajak' => $this->input->post('tglpajak'),
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
            $nobukti = $this->input->post('nobukti');
            $kodebayar = $this->input->post('kodebayar');
            $jumlah = $this->input->post('jumlah');
        
            // Cek apakah data yang diperlukan kosong
            if (empty($idstsmaster) || empty($kodebayar)) {
                $response = ['success' => false, 'message' => 'Isi NoSPTPD terlebih dahulu'];
                echo json_encode($response);
                return;
            }
        
            $last_nourut = $this->Mpend->get_last_nourut($idstsmaster);
            if (!$last_nourut) {
                $last_nourut = '0000';
            }
        
            $next_nourut = str_pad((intval($last_nourut) + 1), 4, '0', STR_PAD_LEFT);
        
            $check_duplicate = $this->Mpend->check_duplicate_data($idstsmaster, $kodebayar);
            if ($check_duplicate) {
                $response = ['success' => false, 'message' => 'Data Sudah Pernah Diinput'];
            } else {
                $data = [
                    'idstsmaster' => $idstsmaster,
                    'nobukti' => $nobukti,
                    'idwp' => $this->input->post('idwp'),
                    'idrapbd' => $this->input->post('idrapbd'),
                    'iduptd' => $this->input->post('iduptd'),
                    'blnpajak' => $this->input->post('blnpajak'),
                    'thnpajak' => $this->input->post('thnpajak'),
                    'kodebayar' => $kodebayar,
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
            $jumlah = $this->input->post('jumlah');
            $prs_denda = $this->input->post('prs_denda');
            
            /* Hitung denda rp */
            $nil_denda = ($jumlah * $prs_denda) / 100;
            
            /* Hitung total */
            $total = $jumlah + $nil_denda;
            
        
    
            $data = [
                'idwp' => $this->input->post('idwp'),
                'iduptd' => $this->input->post('iduptd'),
                'idrapbd' => $this->input->post('idrapbd'),
                'tglpajak' => $this->input->post('tglpajak'),
                'nobukti' => $this->input->post('nobukti'),
                'nourut' => $this->input->post('nourut'),
                'blnpajak' =>  $this->input->post('blnpajak'),
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
         
          public function get_namarekening_by_iddinas() {
            $iddinas = $this->input->post('iddinas');
        
            $apbdData = $this->db
                ->select('trx_rapbd.id, trx_rapbd.idrekening, trx_rapbd.iddinas, mst_rekening.idheader, mst_rekening.kdrekview, mst_rekening.kdrekening, mst_rekening.nmrekening, mst_rekening.islrauptd')
                ->from('trx_rapbd')
                ->join('mst_rekening', 'trx_rapbd.idrekening = mst_rekening.id', 'left')
                ->where('trx_rapbd.iddinas', $iddinas)
                ->get()
                ->result();
            
            $options = '<option disabled selected></option>';
            foreach ($apbdData as $apbd) {
                $options .= '<option value="'.$apbd->id.'">'.$apbd->nmrekening.'('.$apbd->kdrekview.')</option>'; 
            }
            
            echo $options;
        }
        
        public function get_data_from_stsdetail() {
            $nosptpd = $this->input->get('kodebayar') ?: 0;
        
            $this->db->select('a.idstsmaster, a.idwp, a.idrapbd, a.iduptd, a.tglpajak, a.nobukti, a.blnpajak, a.thnpajak, a.jumlah, a.prs_denda, a.nil_denda, a.total, a.keterangan, a.formulir, a.kodebayar, a.tgl_input, a.nopelaporan, b.nama, b.alamat, b.npwpd, d.nmrekening');
            $this->db->from('trx_stsdetail a');
            $this->db->where('a.kodebayar', $nosptpd);
            $this->db->join('mst_wajibpajak b', 'a.idwp = b.id', 'left'); 
            $this->db->join('trx_rapbd c', 'a.idrapbd = c.id', 'left'); 
            $this->db->join('mst_rekening d', 'c.idrekening = d.id', 'left'); 
        
            $query = $this->db->get();
            
            $result = $query->result_array();
            
            if (empty($result)) {
                echo json_encode(['error' => 'No data found for the given nosptpd']);
            } else {
                echo json_encode($result, JSON_PRETTY_PRINT);
            }
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
		
