<?php defined('BASEPATH') OR exit('No direct script access allowed');
class UserManagement extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('backend/Location');
		$this->load->model('master/Muser');
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
		$data['modalEdit'] 	= $this->Form->modalKu('E','Edit','master/UserManagement/aksi',$actions = ['edit']);;
		$data['modalDelete']= $this->Form->modalKu('D','Delete','master/UserManagement/aksi',$actions = ['delete']);
		$data['sidebar'] 	= $template['sidebar'];
		$data['jstable']	= $Jssetup->jsDatatable('#ftf','master/UserManagement/getuser');
		$data['jsedit']		= $Jssetup->jsModal('#edit','Edit','master/UserManagement/myModal','#modalkuE');
	 	$data['jsdelete']	= $Jssetup->jsModal('#delete','Delete','master/UserManagement/myModal','#modalkuD');
		$data['forminsert'] = $this->Muser->formInsert();
		$this->load->view('master/user',$data);
	}

    public function getuser()
    {
        $datatables = new Datatables();
		$datatables->setTable("sys_user");
        $datatables->setSelectColumn(["id","login","username", "role", "iduptd"]);
        $datatables->setOrderColumn(["login", "username", "role"]);
		$fetch_data = $datatables->make_datatables();
        $data 		= array();
		$no   		= 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
			$sub_array[] = $row->login;
            $sub_array[] = $row->username;
            $sub_array[] = $row->role;
            $sub_array[] = $row->iduptd;
			$sub_array[] = implode('',$datatables->tombol($row->id));
            $data[] = $sub_array;
        }
        $output = array(
            "draw" 			  => intval($_POST["draw"]),
            "recordsTotal" 	  => $datatables->get_all_data(),
            "recordsFiltered" => $datatables->get_filtered_data(),
            "data" 			  => $data
        );
        echo json_encode($output);
    }
	public function myModal(){
		$wadi = isset($_POST['WADI']) ? $_POST['WADI'] : header('location:'.site_url('404'));
		switch($wadi){
			case 'Edit':
				$form [] 	= '
				<div class="row">
					<div class="col-md-12">'
					.implode($this->Form->inputText('login','Login', $kode->login)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('username','Username',$kode->username)).
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
							'singkat' 	=> $this->input->post('singkat')];
				$insert = $this->Crud->insert_data('mst_uptd', $data);
				if ($insert) {
					$this->session->set_flashdata('message', 'Data has been saved successfully');
					redirect('master/Uptd');
				} else {
					$this->session->set_flashdata('message', 'Failed to save data');
					redirect('master/Uptd');
				}
			break;
			case 'Edit':
				$kode = $this->input->post('kode');
				$data = [	'nama' 		=> $this->input->post('nama'),
							'singkat' 	=> $this->input->post('singkat')];
				$update = $this->Crud->update_data('mst_uptd', $data, ['id' => $kode]);
				if ($update) {
					$this->session->set_flashdata('message', 'Data has been updated successfully');
					redirect('master/Uptd');
				} else {
					$this->session->set_flashdata('message', 'Failed to update data');
					redirect('master/Uptd');
				}
			break;
			case 'Delete':
				$kode = $this->input->post('kode');
				$delete = $this->Crud->delete_data('mst_uptd', ['id' => $kode]);
				if ($delete) {
					$this->session->set_flashdata('message', 'Data has been deleted successfully');
					redirect('master/Uptd');
				} else {
					$this->session->set_flashdata('message', 'Failed to delete data');
					redirect('master/Uptd');
				}
			break;
			default:
				header('location:'.site_url('404'));
			break;
		}
	}
	}
?>
		
