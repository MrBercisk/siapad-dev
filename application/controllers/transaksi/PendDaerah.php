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
		$data['sidebar'] 	= $template['sidebar'];
		$data['jstable']	= $Jssetup->jsDatatable('#pendapatan','transaksi/PendDaerah/get_datatable_data');
	 	$data['jsedit']		= $Jssetup->jsModal('#edit','Edit','transaksi/PendDaerah/myModal','#modalkuE');
	 	$data['jsdelete']	= $Jssetup->jsModal('#delete','Delete','transaksi/PendDaerah/myModal','#modalkuD');
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
            $edit_button = '<button type="button" class="btn btn-xs btn-outline-primary modin fa fa-edit edit-data"  data-toggle="modal" data-target="#editModal" data-idstsmaster="'.$row->idstsmaster.'" data-nourut="'.$row->nourut.'">Edit</button>';
            $sub_array[] = $edit_button;
            
            $data[] = $sub_array;
        }
        
        $output = array(
            "draw" => intval($_POST["draw"]),
            "data" => $data
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
            $idPend = $this->Crud->ambilSatu('trx_stsdetail', ['idstsmaster' => $idnya]);
            switch($wadi){
                case 'Edit':
                    $form [] = '
                    <div class="row">
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
    
        public function aksi(){
            $aksi = isset($_POST['AKSI']) ? $_POST['AKSI'] : header('location:'.site_url('404'));
            $this->load->model('backend/Crud'); 
            switch($aksi){
                case 'Save':
                    $data = [	'iddinas' 		=> $this->input->post('iddinas'),
                                 'tahun' 			=> $this->input->post('tahun'),
                                'idrekening' 		=> $this->input->post('idrekening'),
                                'apbd' 		=> $this->input->post('apbd'),
                                'apbdp' 		=> $this->input->post('apbdp'),
                            ];
                    $insert = $this->Crud->insert_data('trx_rapbd', $data);
                    if ($insert) {
                        $this->session->set_flashdata('message', 'Data has been saved successfully');
                        redirect('transaksi/apbd');
                    } else {
                        $this->session->set_flashdata('message', 'Failed to save data');
                        redirect('transaksi/apbd');
                    }
                break;
                case 'Edit':
                    $kode = $this->input->post('kode');
                    $data = [	'nobukti' 		=> $this->input->post('nobukti'),
                            ];
                    $update = $this->Crud->update_data('trx_stsdetail', $data, ['idstsmaster' => $kode]);
                    if ($update) {
                        $this->session->set_flashdata('message', 'Data has been updated successfully');
                        redirect('transaksi/PendDaerah');
                    } else {
                        $this->session->set_flashdata('message', 'Failed to update data');
                        redirect('transaksi/PendDaerah');
                    }
                break;
                case 'Delete':
                    $kode = $this->input->post('kode');
                    $delete = $this->Mpend->delete('trx_stsdetail', ['idstsmaster' => $kode]);
                    if ($delete) {
                        $this->session->set_flashdata('message', 'Data has been deleted successfully');
                        redirect('transaksi/penddaerah');
                    } else {
                        $this->session->set_flashdata('message', 'Failed to delete data');
                        redirect('transaksi/penddaerah');
                    }
                break;
                default:
                    header('location:'.site_url('404'));
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
        
        public function add_data() {
            $idrecord = $this->input->post('id');
    
            $data = [
                'idstsmaster' => $idrecord,
                'tglpajak' => $this->input->post('tglpajak'),
                'idskpd' => $this->input->post('idskpd'),
                'nobukti' => $this->input->post('nobukti'),
                'blnpajak' =>  $this->input->post('blnpajak'),
                'thnpajak' => $this->input->post('thnpajak'),
                'jumlah' => $this->input->post('jumlah'),
                'prs_denda' =>  $this->input->post('prs_denda'),
                'nil_denda' => $this->input->post('nil_denda'),
                'total' => $this->input->post('total'),
                'keterangan' => $this->input->post('keterangan'),
            ];
   
            $insert = $this->db->insert('trx_stsdetail', $data);
        
            if ($insert) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Data berhasil ditambahkan'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Gagal menambahkan data'
                );
            }
            echo json_encode($response);
        }
        
        public function updateData() {
            
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
    

            $update = $this->Crud->update_data('trx_stsmaster', $data, ['id' => $idrecord]);
    
            if ($update) {
                $this->session->set_flashdata('message', 'Data has been updated successfully');
                redirect('transaksi/PendDaerah');
            } else {
                $this->session->set_flashdata('message', 'Failed to update data');
                redirect('transaksi/PendDaerah');
            }
        }
      
        public function edit_datatable_row() {
            $idstsmaster = $this->input->post('idstsmaster');
            $nourut = $this->input->post('nourut');
          
            $this->Mpend->get_data_by_idsts_nourut($idstsmaster, $nourut);
          
          }
}
?>
		
