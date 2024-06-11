<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Ttd extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('master/MTtd');
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
		'modalEdit'   => $this->Form->modalKu('E', 'Edit', 'master/Ttd/aksi', ['edit']),
		'modalDelete' => $this->Form->modalKu('D', 'Delete', 'master/Ttd/aksi', ['delete']),
		'sidebar'     => $template['sidebar'],
		'jstable'     => $Jssetup->jsDatatable('#ftf', 'master/Ttd/getTtd'),
		'jsedit'      => $Jssetup->jsModal('#edit', 'Edit', 'master/Ttd/myModal', '#modalkuE'),
		'jsdelete'    => $Jssetup->jsModal('#delete', 'Delete', 'master/Ttd/myModal', '#modalkuD'),
		'forminsert'  => implode('', $this->MTtd->formInsert()) ];
		$this->load->view('master/ttd',$data);
	}
	public function getTtd() {
		$this->load->model('instrument/Status');
		$status = $this->Status;
		$datatables = $this->Datatables;
		$datatables->setTable("mst_tandatangan");
        $datatables->setSelectColumn(["id","nip", "nama", "jabatan1", "jabatan2", "tipe"]);
        $datatables->setOrderColumn([null,"nip", "nama",  "jabatan1", "jabatan2","tipe"]);
		$datatables->setSearchColumns(["nip", "nama",  "jabatan1", "jabatan2","tipe"]);
        $fetch_data = $this->Datatables->make_datatables();
        $data = array();
		$no   = 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
			$sub_array[] = $row->nip;
			$sub_array[] = $row->nama;
            $sub_array[] = $row->jabatan1;
            $sub_array[] = $row->jabatan2;
            $sub_array[] = $row->tipe;
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
				$selectFields 		= 'id, nama, nip, jabatan1, jabatan2, tipe';
				$kode 				= $this->Crud->gandengan('mst_tandatangan', $joinTables, $selectFields,'id="'.$this->input->post('idnya').'"')[0];
				$form [] 	= '
				<div class="row">
					<div class="col-md-12">'
					.implode($this->Form->inputText('nip','Nip',$kode->nip)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('nama','Nama',$kode->nama)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('jabatan1','Jabatan 1',$kode->jabatan1)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('jabatan2','Jabatan 2',$kode->jabatan2)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('tipe','Tipe',$kode->tipe)).
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
				$data = [	'nip' 		=> $this->input->post('nip'),
							'nama' 	=> $this->input->post('nama'),
							'jabatan1'		=> $this->input->post('jabatan1'),
							'tipe'		=> $this->input->post('tipe'),
							'jabatan2'=> $this->input->post('jabatan2')];
				$insert = $this->Crud->insert_data('mst_tandatangan', $data);
				if ($insert) {
					$this->session->set_flashdata('message', 'Data has been saved successfully');
					redirect('master/Ttd');
				} else {
					$this->session->set_flashdata('message', 'Failed to save data');
					redirect('master/Ttd');
				}
			break;
			case 'Edit':
				$kode = $this->input->post('kode');
				$data = [	'nip' 		=> $this->input->post('nip'),
							'nama' 	=> $this->input->post('nama'),
							'jabatan1'		=> $this->input->post('jabatan1'),
							'tipe'		=> $this->input->post('tipe'),
							'jabatan2'=> $this->input->post('jabatan2')];
				$update = $this->Crud->update_data('mst_tandatangan', $data, ['id' => $kode]);
				if ($update) {
					$this->session->set_flashdata('message', 'Data has been updated successfully');
					redirect('master/Ttd');
				} else {
					$this->session->set_flashdata('message', 'Failed to update data');
					redirect('master/Ttd');
				}
			break;
			case 'Delete':
				$kode = $this->input->post('kode');
				$delete = $this->Crud->delete_data('mst_tandatangan', ['id' => $kode]);
				if ($delete) {
					$this->session->set_flashdata('message', 'Data has been deleted successfully');
					redirect('master/Ttd');
				} else {
					$this->session->set_flashdata('message', 'Failed to delete data');
					redirect('master/Ttd');
				}
			break;
			default:
				header('location:'.site_url('404'));
			break;
		}
	}
}
?>
		
