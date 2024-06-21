<?php defined('BASEPATH') OR exit('No direct script access allowed');
class PendDaerah extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('backend/Location');
		$this->load->model('transaksi/Mapbd');
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
		$data['jstable']	= $Jssetup->jsDatatable('#ftf','transaksi/PendDaerah/getDataPendDaerah');
	 	$data['jsedit']		= $Jssetup->jsModal('#edit','Edit','transaksi/PendDaerah/myModal','#modalkuE');
	 	$data['jsdelete']	= $Jssetup->jsModal('#delete','Delete','transaksi/PendDaerah/myModal','#modalkuD');
		$data['forminsert'] = $this->Mapbd->formInsert();
		
		$this->load->view('transaksi/pnddaerah',$data);
	}
	
	public function getDataPendDaerah() {
		$datatables = $this->Datatables;
		$datatables->setTable("trx_stsdetail");

        $datatables->setSelectColumn([
            "trx_stsdetail.idstsmaster", 
            "trx_stsdetail.iddinas", 
            "mst_dinas.nama as namadinas", 
            "trx_stsdetail.tahun", 
            "trx_stsdetail.idrekening", 
            "mst_rekening.nmrekening as namarekening", 
            "mst_rekening.kdrekening as koderekening", 
            "trx_stsdetail.apbd",
            "trx_stsdetail.apbdp"]);
        $datatables->setOrderColumn([null, "namadinas", "tahun","namarekening","apbd","apbdp"]);
        $datatables->setSearchColumns(['mst_dinas.nama', 'mst_rekening.nmrekening', 'trx_rapbd.tahun','trx_rapbd.apbd']); 
        $datatables->addJoin("mst_dinas", "trx_rapbd.iddinas = mst_dinas.id", "left");
        $datatables->addJoin("mst_rekening", "trx_rapbd.idrekening = mst_rekening.id", "left");
        
        if (!empty($dinas)) {
            $datatables->addWhere("trx_rapbd.iddinas", $dinas);
        }
        if (!empty($tahun)) {
            $datatables->addWhere("trx_rapbd.tahun", $tahun);
        }

        $fetch_data = $this->Datatables->make_datatables();
        $data = array();
		$no   = 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
            $sub_array[] = $row->namadinas;
			$sub_array[] = $row->tahun;
			$sub_array[] = $row->namarekening;
			$sub_array[] = number_format($row->apbd, 2, ',', '.');
            $sub_array[] = number_format($row->apbdp, 2, ',', '.');
			$sub_array[] = implode('',$this->Datatables->tombol($row->id));
            $data[] = $sub_array;
        }
        $output = array(
            "draw" 				=> intval($_POST["draw"]),
            "recordsTotal" 		=> $this->Datatables->get_all_data(),
            "recordsFiltered" 	=> $this->Datatables->get_filtered_data(),
            "data" 				=> $data
        );
        echo json_encode($output);
    }
	public function myModal(){
		$wadi = isset($_POST['WADI']) ? $_POST['WADI'] : header('location:'.site_url('404'));
		switch($wadi){
			case 'Edit':
		$joinTables  	= [	'mst_kelurahan b' => [
							'condition' => 'a.idkelurahan = b.id',
							'type' => 'LEFT'
							],
							'mst_kecamatan c' => [
							'condition' => 'b.idkecamatan = c.id',
							'type' => 'LEFT'
							]];
		$selectFields 	= 	'a.id as id_wp,nomor,a.nama as nama_wp,alamat,notype,idkecamatan,idkelurahan';
		$kode 			= 	$this->Crud->gandengan('mst_wajibpajak a', $joinTables, $selectFields,'a.id="'.$this->input->post('idnya').'"')[0];
				$Jssetup	= $this->Jssetup;
				$form [] = '
				<div class="row">
					<div class="col-md-12">'
					.implode($this->Form->inputText('nomor','NOP/SKP/NPWPD',$kode->nomor)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('nama','NAMA',$kode->nama_wp)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('alamat','ALAMAT',$kode->alamat)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('no_type','Type Nomor',$kode->notype)).
				   '</div>
				    <div class="col-md-12">'
					.
				   '</div>'
					.$this->Form->selectKec('kecamatanx','kelurahanx','Kecamatan','Kelurahan' ,$kode->idkecamatan, $kode->idkelurahan, 'col-md-12').implode($this->Form->hiddenText('kode',$kode->id_wp)).'
				</div>
				<script type="text/javascript">
		'.$Jssetup->jsKelurahan('master/WP/get_kelurahan','kecamatanx','kelurahanx').'
				</script>
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
				echo implode($form);
			break;
			default:
				echo 'NOTHING !!!';
			break;
		}
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
            $data = [	'iddinas' 		=> $this->input->post('iddinas'),
					 	'tahun' 			=> $this->input->post('tahun'),
						'idrekening' 		=> $this->input->post('idrekening'),
						'apbd' 		=> $this->input->post('apbd'),
						'apbdp' 		=> $this->input->post('apbdp'),
                    ];
            $update = $this->Crud->update_data('trx_rapbd', $data, ['id' => $kode]);
            if ($update) {
                $this->session->set_flashdata('message', 'Data has been updated successfully');
				redirect('transaksi/apbd');
            } else {
                $this->session->set_flashdata('message', 'Failed to update data');
				redirect('transaksi/apbd');
            }
        break;
        case 'Delete':
            $kode = $this->input->post('kode');
            $delete = $this->Crud->delete_data('trx_rapbd', ['id' => $kode]);
            if ($delete) {
                $this->session->set_flashdata('message', 'Data has been deleted successfully');
				redirect('transaksi/apbd');
            } else {
                $this->session->set_flashdata('message', 'Failed to delete data');
				redirect('transaksi/apbd');
            }
        break;
        default:
            header('location:'.site_url('404'));
        break;
    }
}
}
?>
		
