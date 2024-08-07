<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dinas extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('master/MDinas');
	}
	public function index()
	{	$data = [];
		$Jssetup	= $this->Jssetup;
		$base 		= $this->Msetup->setup();
		$setpage	= $this->Msetup->get_title($base['halaman'].'/'.$base['fungsi']);
		$template 	= $this->Msetup->loadTemplate($setpage->title);
		$data = 
	[	'footer'      => $template['footer'],
		'title'       => $setpage->title,
		'link'        => $setpage->link,
		'topbar'      => $template['topbar'],
		'modalEdit'   => $this->Form->modalKu('E', 'Edit', 'master/Dinas/aksi', ['edit']),
		'modalDelete' => $this->Form->modalKu('D', 'Delete', 'master/Dinas/aksi', ['delete']),
		'sidebar'     => $template['sidebar'],
		'jstable'     => $Jssetup->jsDatatable('#ftf', 'master/Dinas/getDinas'),
		'jsedit'      => $Jssetup->jsModal('#edit', 'Edit', 'master/Dinas/myModal', '#modalkuE'),
		'jsdelete'    => $Jssetup->jsModal('#delete', 'Delete', 'master/Dinas/myModal', '#modalkuD'),
		'forminsert'  => implode('', $this->MDinas->formInsert()) ];
		$this->load->view('master/Dinas',$data);
	}
	public function getDinas() {
		$this->load->model('instrument/Status');
		$status = $this->Status;
		$datatables = $this->Datatables;
		$datatables->setTable("mst_dinas");
        $datatables->setSelectColumn(["id", "nama", "singkat", "urut", "isdispenda"]);
        $datatables->setOrderColumn([null, "nama", "id"]);
		$datatables->setSearchColumns(["nama", "singkat"]);
        $fetch_data = $this->Datatables->make_datatables();
        $data = array();
		$no   = 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
			$sub_array[] = $row->nama;
            $sub_array[] = $row->singkat;
            $sub_array[] = $row->urut;
            $sub_array[] = $status->yOrNo($row->isdispenda);
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
				$joinTables  		= [];
				$selectFields 		= 'id, nama, singkat, urut, isdispenda';
				$kode 				= $this->Crud->gandengan('mst_dinas', $joinTables, $selectFields,'id="'.$this->input->post('idnya').'"')[0];
				if($kode->isdispenda==0 || empty($kode->isdispenda)){
					$kode->isdispenda = 0;
				}
				$form [] 	= '
				<div class="row">
					<div class="col-md-12">'
					.implode($this->Form->inputText('nama','Nama Dinas',$kode->nama)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('singkat','Singkatan',$kode->singkat)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('urut','No. Urut',$kode->urut)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('isdispenda','Instdispenda',intval($kode->isdispenda))).
				   '</div>'.implode($this->Form->hiddenText('kode',$kode->id)).'
				</div>';
				
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
				$form [] = 'NOTHING !!!';
			break;
		}
		echo implode('',$form);
	}
	public function aksi(){
		$aksi = isset($_POST['AKSI']) ? $_POST['AKSI'] : header('location:'.site_url('404'));
		$this->load->model('backend/Crud'); 
		switch($aksi){
			case 'Save':
				$data = [	'nama' 		=> $this->input->post('nama'),
							'singkat' 	=> $this->input->post('singkat'),
							'urut'		=> $this->input->post('urut'),
							'isdispenda'=> $this->input->post('isdispenda')];
				$insert = $this->Crud->insert_data('mst_dinas', $data);
				if ($insert) {
					$this->session->set_flashdata('message', 'Data has been saved successfully');
					redirect('master/Dinas');
				} else {
					$this->session->set_flashdata('message', 'Failed to save data');
					redirect('master/Dinas');
				}
			break;
			case 'Edit':
				$kode = $this->input->post('kode');
				$data = [	'nama' 		=> $this->input->post('nama'),
							'singkat' 	=> $this->input->post('singkat'),
							'urut'		=> $this->input->post('urut'),
							'isdispenda'=> $this->input->post('isdispenda')];
				$update = $this->Crud->update_data('mst_dinas', $data, ['id' => $kode]);
				if ($update) {
					$this->session->set_flashdata('message', 'Data has been updated successfully');
					redirect('master/Dinas');
				} else {
					$this->session->set_flashdata('message', 'Failed to update data');
					redirect('master/Dinas');
				}
			break;
			case 'Delete':
				$kode = $this->input->post('kode');
				$delete = $this->Crud->delete_data('mst_dinas', ['id' => $kode]);
				if ($delete) {
					$this->session->set_flashdata('message', 'Data has been deleted successfully');
					redirect('master/Dinas');
				} else {
					$this->session->set_flashdata('message', 'Failed to delete data');
					redirect('master/Dinas');
				}
			break;
			default:
				header('location:'.site_url('404'));
			break;
		}
	}
}
?>
		
