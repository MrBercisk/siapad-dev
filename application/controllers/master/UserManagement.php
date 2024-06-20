<?php defined('BASEPATH') OR exit('No direct script access allowed');
class UserManagement extends CI_Controller {
	public function __construct() {
        parent::__construct();
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
		$data['forminsert'] = implode($this->Muser->formInsert());
		$this->load->view('master/user',$data);
	}

    public function getuser()
    {
        $datatables = new Datatables();
		$datatables->setTable("sys_user");
        $datatables->setSelectColumn([
            "sys_user.id",
            "sys_user.login",
            "sys_user.username",
            "sys_user.role",
            "mst_uptd.nama as nama_uptd"
        ]);
        $datatables->setOrderColumn([null,"login", "username", "role", "nama"]);
		$datatables->setSearchColumns(['login', 'username','role','nama']); 
		$datatables->addJoin("mst_uptd", "sys_user.iduptd = mst_uptd.id", "left");
		$fetch_data = $datatables->make_datatables();
        $data 		= array();
		$no   		= 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
			$sub_array[] = $row->login;
            $sub_array[] = $row->username;
            $sub_array[] = $row->role;
            $sub_array[] = $row->nama_uptd;
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
		$idnya = $this->input->post('idnya');
		$iduser = $this->Crud->ambilSatu('sys_user', ['id' => $idnya]);
		switch($wadi){
			case 'Edit':
				$uptdData = $this->db->get('mst_uptd')->result();
				$opsiuptd = '';
				foreach ($uptdData as $uptd) {
					$opsiuptd .= '<option value="'.$uptd->id.'">'.$uptd->nama.'</option>';
				}
				$enum = ['adm', 'man', 'opr','pjb','kadis','uptd','mhs','pjk','bpk'];
				$form [] 	= '
				<div class="row">
					<div class="col-md-12">'
					.implode($this->Form->inputText('login','Login', $iduser->login)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('username','Username',$iduser->username)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputPassword('passwd','Password',$iduser->passwd)).
				   '</div>
					<div class="col-md-12">'
					.$this->Form->inputEnumOptions('role', 'Role', $enum).
				   '</div>
				   <div class="col-md-6 offset-3">
                    <div class="form-group">
                        <label for="iduptd">Nama Kecamatan/UPTD</label>
                        <select name="iduptd" id="iduptd" class="form-control">
                            '.$opsiuptd.'
                        </select>
                    </div>
                </div>
				   '
				   .implode($this->Form->hiddenText('kode',$iduser->id)).'
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
				$hash = md5($this->input->post('passwd'));
				$data = [	'login' 	=> $this->input->post('login'),
							'username' 	=> $this->input->post('username'),
							'passwd' 	=> $hash,
							'role ' 	=> $this->input->post('role'),
							'iduptd' 	=> $this->input->post('iduptd'),
						];
				$insert = $this->Crud->insert_data('sys_user', $data);
				if ($insert) {
					$this->session->set_flashdata('message', 'Data has been saved successfully');
					redirect('master/usermanagement');
				} else {
					$this->session->set_flashdata('message', 'Failed to save data');
					redirect('master/usermanagement');
				}
			break;
			case 'Edit':
				$hash = md5($this->input->post('passwd'));
				$kode = $this->input->post('kode');
				$data = [	'login' 	=> $this->input->post('login'),
							'username' 	=> $this->input->post('username'),
							'passwd' 	=> $hash,
							'role ' 	=> $this->input->post('role'),
							'iduptd' 	=> $this->input->post('iduptd'),
						];
				$update = $this->Crud->update_data('sys_user', $data, ['id' => $kode]);
				if ($update) {
					$this->session->set_flashdata('message', 'Data has been updated successfully');
					redirect('master/usermanagement');
				} else {
					$this->session->set_flashdata('message', 'Failed to update data');
					redirect('master/usermanagement');
				}
			break;
			case 'Delete':
				$kode = $this->input->post('kode');
				$delete = $this->Crud->delete_data('sys_user', ['id' => $kode]);
				if ($delete) {
					$this->session->set_flashdata('message', 'Data has been deleted successfully');
					redirect('master/usermanagement');
				} else {
					$this->session->set_flashdata('message', 'Failed to delete data');
					redirect('master/usermanagement');
				}
			break;
			default:
				header('location:'.site_url('404'));
			break;
		}
	}
	}
?>
		
