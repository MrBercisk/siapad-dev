<?php defined('BASEPATH') OR exit('No direct script access allowed');
class WP extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('backend/Location');
		$this->load->model('master/Mwp');
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
		$data['modalEdit'] 	= $this->Form->modalKu('E','Edit','master/WP/aksi',$actions = ['edit']);;
		$data['modalDelete']= $this->Form->modalKu('D','Delete','master/WP/aksi',$actions = ['delete']);
		$data['sidebar'] 	= $template['sidebar'];
		$data['jstable']	= $Jssetup->jsDatatable('#ftf','master/WP/getDataWp');
	 	$data['jsedit']		= $Jssetup->jsModal('#edit','Edit','master/WP/myModal','#modalkuE');
	 	$data['jsdelete']	= $Jssetup->jsModal('#delete','Delete','master/WP/myModal','#modalkuD');
	 	$data['jslurah']	= $Jssetup->jsKelurahan('master/WP/get_kelurahan','kecamatan','kelurahan');
		$data['forminsert'] = $this->Mwp->formInsert();
		$this->load->view('master/WP',$data);
	}
	public function get_kelurahan() {
        $kecamatan_id = $this->input->post('idnya');
        $kelurahan = $this->Location->get_kelurahan_by_kecamatan($kecamatan_id);
        echo json_encode($kelurahan);
    }
	public function getDataWp() {
		$datatables = $this->Datatables;
		$datatables->setTable("mst_wajibpajak a");
        $datatables->setSelectColumn(["a.id as id_wp", "a.nama as nama_wp", 
									  "alamat", "nomor", "npwpd", 
									  "b.nama as nama_kelurahan", "notype"]);
        $datatables->setOrderColumn([null, "a.nama", "nomor"]);
		$datatables->setSearchColumns(["a.nama", "nomor"]);
		$datatables->addJoin('mst_kelurahan b', 'b.id=a.idkelurahan','left');
        $fetch_data = $this->Datatables->make_datatables();
        $data = array();
		$no   = 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
			$sub_array[] = $row->nomor;
            $sub_array[] = $row->nama_wp;
            $sub_array[] = $row->alamat;
            $sub_array[] = $row->notype;
			$sub_array[] = $row->nama_kelurahan;
			$sub_array[] = implode('',$this->Datatables->tombol($row->id_wp));
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
            $data = [	'npwpd' 		=> $this->input->post('npwpd'),
					 	'nama' 			=> $this->input->post('nama'),
						'nomor' 		=> $this->input->post('nomor'),
						'alamat' 		=> $this->input->post('alamat'),
						'notype' 		=> $this->input->post('no_type'),
						'idkelurahan' 	=> $this->input->post('kelurahan')];
            $insert = $this->Crud->insert_data('mst_wajibpajak', $data);
            if ($insert) {
                $this->session->set_flashdata('message', 'Data has been saved successfully');
				redirect('master/WP');
            } else {
                $this->session->set_flashdata('message', 'Failed to save data');
				redirect('master/WP');
            }
        break;
        case 'Edit':
            $kode = $this->input->post('kode');
            $data = [	'npwpd' 		=> $this->input->post('npwpd'),
					 	'nama' 			=> $this->input->post('nama'),
						'nomor' 		=> $this->input->post('nomor'),
						'alamat' 		=> $this->input->post('alamat'),
						'notype' 		=> $this->input->post('no_type'),
						'idkelurahan' 	=> $this->input->post('idkelurahan')];
            $update = $this->Crud->update_data('mst_wajibpajak', $data, ['id' => $kode]);
            if ($update) {
                $this->session->set_flashdata('message', 'Data has been updated successfully');
				redirect('master/WP');
            } else {
                $this->session->set_flashdata('message', 'Failed to update data');
				redirect('master/WP');
            }
        break;
        case 'Delete':
            $kode = $this->input->post('kode');
            $delete = $this->Crud->delete_data('mst_wajibpajak', ['id' => $kode]);
            if ($delete) {
                $this->session->set_flashdata('message', 'Data has been deleted successfully');
				redirect('master/WP');
            } else {
                $this->session->set_flashdata('message', 'Failed to delete data');
				redirect('master/WP');
            }
        break;
        default:
            header('location:'.site_url('404'));
        break;
    }
}
}
?>
		
