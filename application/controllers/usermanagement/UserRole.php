<?php defined('BASEPATH') or exit('No direct script access allowed');
class UserRole extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('master/Muser');
	}
	public function index()
	{
		$Jssetup	= $this->Jssetup;
		$base 		= $this->Msetup->setup();
		$setpage	= $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
		$template 	= $this->Msetup->loadTemplate($setpage->title);
		$data = [];
		$data['footer']		= $template['footer'];
		$data['title'] 		= $setpage->title;
		$data['link'] 		= $setpage->link;
		$data['topbar'] 	= $template['topbar'];
		$data['modalEdit'] 	= $this->Form->modalKu('E', 'Edit', 'master/UserManagement/aksi', $actions = ['edit']);;
		$data['modalDelete'] = $this->Form->modalKu('D', 'Delete', 'master/UserManagement/aksi', $actions = ['delete']);
		$data['sidebar'] 	= $template['sidebar'];
		$data['jstable']	= $Jssetup->jsDatatable('#ftf', 'usermanagement/UserRole/getuser');
		$data['jsedit']		= $Jssetup->jsModal('#edit', 'Edit', 'master/UserManagement/myModal', '#modalkuE');
		$data['jsdelete']	= $Jssetup->jsModal('#delete', 'Delete', 'master/UserManagement/myModal', '#modalkuD');
		$data['forminsert'] = implode($this->Muser->fromRole());
		$this->load->view('users/role', $data);
	}

	public function getuser()
	{
		$datatables = new Datatables();
		$datatables->setTable("mst_role a");
		$datatables->setSelectColumn([
			"a.idrole as id",
			"name",
			"b.singkat"
		]);
		$datatables->setOrderColumn([null, "login", "username", "role", "nama"]);
		$datatables->setSearchColumns(['login', 'username', 'role', 'nama']);
		$datatables->addJoin("mst_level b", "a.idlevel=b.idlevel", "INNER");
		$datatables->addJoin("menu c", "a.idmenu= c.id", "INNER");
		$fetch_data = $datatables->make_datatables();
		$data 		= array();
		$no   		= 1;
		foreach ($fetch_data as $row) {
			$sub_array = array();
			$sub_array[] = $no++;
			$sub_array[] = $row->name;
			$sub_array[] = $row->singkat;
			$sub_array[] = implode('', $datatables->tombol($row->id));
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
	public function myModal()
	{
		$wadi = isset($_POST['WADI']) ? $_POST['WADI'] : header('location:' . site_url('404'));
		$idnya = $this->input->post('idnya');
		$iduser = $this->Crud->ambilSatu('sys_user', ['id' => $idnya]);
		switch ($wadi) {
			case 'Edit':
				$uptdData = $this->db->get('mst_uptd')->result();
				$opsiuptd = '';
				foreach ($uptdData as $uptd) {
					$opsiuptd .= '<option value="' . $uptd->id . '">' . $uptd->nama . '</option>';
				}
				$enum = ['adm', 'man', 'opr', 'pjb', 'kadis', 'uptd', 'mhs', 'pjk', 'bpk'];
				$form[] 	= '
				<div class="row">
					<div class="col-md-12">'
					. implode($this->Form->inputText('login', 'Login', $iduser->login)) .
					'</div>
					<div class="col-md-12">'
					. implode($this->Form->inputText('username', 'Username', $iduser->username)) .
					'</div>
					<div class="col-md-12">'
					. implode($this->Form->inputPassword('passwd', 'Password', $iduser->passwd)) .
					'</div>
					<div class="col-md-12">'
					. $this->Form->inputEnumOptions('role', 'Role', $enum) .
					'</div>
				   <div class="col-md-6 offset-3">
                    <div class="form-group">
                        <label for="iduptd">Nama Kecamatan/UPTD</label>
                        <select name="iduptd" id="iduptd" class="form-control">
                            ' . $opsiuptd . '
                        </select>
                    </div>
                </div>
				   '
					. implode($this->Form->hiddenText('kode', $iduser->id)) . '
				</div>';

				break;
			case 'Delete':
				$form[] = '
				<div class="row">
					<div class="col-md-12">
					' . implode($this->Form->hiddenText('kode', $this->input->post('idnya'))) . '
					Apakah kamu yakin ingin menghapus data ini ?
					</div>
				</div>';
				break;
			default:
				$form[] = 'NOTHING !!!';
				break;
		}
		echo implode('', $form);
	}
	public function aksi()
	{
		$aksi = isset($_POST['AKSI']) ? $_POST['AKSI'] : header('location:' . site_url('404'));
		$this->load->model('backend/Crud');
		switch ($aksi) {
			case 'Save':
				$hash = md5($this->input->post('passwd'));
				$data = [
					'login' 	=> $this->input->post('login'),
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
				$data = [
					'login' 	=> $this->input->post('login'),
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
				header('location:' . site_url('404'));
				break;
		}
	}
	public function get_datatable_data()
	{
		$idrecord = $this->input->get('id');

		$datatables = $this->Datatables;
		$datatables->setTable("trx_stsdetail");
		$datatables->setSelectColumn('trx_stsdetail.idstsmaster,trx_stsdetail.nourut, trx_stsdetail.tglpajak, trx_stsdetail.idskpd, trx_stsdetail.nobukti, trx_stsdetail.blnpajak, trx_stsdetail.thnpajak, trx_stsdetail.jumlah, trx_stsdetail.prs_denda, trx_stsdetail.nil_denda, trx_stsdetail.total, trx_stsdetail.keterangan');
		$datatables->setOrderColumn(["trx_stsdetail.nourut", "trx_stsdetail.nobukti", "trx_stsdetail.blnpajak", "trx_stsdetail.thnpajak"]);
		$datatables->addJoin('trx_stsmaster', 'trx_stsmaster.id = trx_stsdetail.idstsmaster', 'left');
		$datatables->addWhere('trx_stsdetail.idstsmaster', $idrecord);
		$fetch_data = $this->Datatables->make_datatables();
		$data = array();
		foreach ($fetch_data as $row) {
			$sub_array = array();
			$sub_array[] = $row->nourut;
			$sub_array[] = $row->nobukti;
			$sub_array[] = $row->tglpajak;
			$sub_array[] = $row->blnpajak;
			$sub_array[] = $row->thnpajak;
			$sub_array[] = $row->jumlah;
			$sub_array[] = $row->prs_denda;
			$sub_array[] = $row->nil_denda;
			$sub_array[] = $row->total;
			$sub_array[] = $row->keterangan;
			$sub_array[] = implode('', $datatables->tombol($row->idstsmaster));
			$data[] = $sub_array;
		}
		$output = array(
			"draw" => intval($_POST["draw"]),
			"data" => $data
		);

		echo json_encode($output);
	}
	public function getMenu($role = null)
	{
		$role = $_POST['role'];
		$this->db->select('a.idrole,c.id as idmenu, name, b.singkat');
		$this->db->from('mst_role a');
		$this->db->join('mst_level b', 'a.idlevel = b.idlevel', 'inner');
		$this->db->join('menu c', 'a.idmenu = c.id', 'inner');
		if ($role !== null || $role !== '') {
			$this->db->where("b.singkat", $role);
		}
		$query = $this->db->get();
		// var_dump($this->db->last_query());
		header('Content-Type: application/json');
		echo json_encode($query->result_array());
	}

	public function saveRoleMenu()
	{
		$idlevel = $_POST['role'];

		// ambil level Role
		$role = $this->db->from('mst_level')->where('singkat', $idlevel)->get()->row();
		$idrole = $role->idlevel;
		// ambil id terbesar
		$query = $this->db->select_max('idrole')->get('mst_role')->row();
		$idbig = $query->idrole + 1;
		// foreach ($_POST["id"] as $menu) {
		// 	$data[] = [
		// 		"idrole" => $idbig++,
		// 		"idmenu" => $menu,
		// 		"status" => 1,
		// 		"idlevel" => $idrole,
		// 	];
		// }
		// $insert = $this->db->insert_batch("mst_role", $data);

		// var_dump($data);
		// die;
		$idmenu = $_POST['id'];

		// ambil level Role
		$role = $this->db->from('mst_level')->where('singkat', $idlevel)->get()->row();
		$idrole = $role->idlevel;
		// simpan data
		$data = [
			"idrole" => $idbig,
			"idmenu" => $idmenu,
			"status" => 1,
			"idlevel" => $idrole,
		];
		$insert = $this->db->insert("mst_role", $data);
		// Menyiapkan respons JSON
		if ($insert) {
			// Jika insert berhasil
			$response = array('success' => true);
		} else {
			// Jika insert gagal
			$response = array('success' => false);
		}

		// Mengatur header konten ke JSON dan mengirimkan respons
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}
	public function deleteRoleMenu()
	{
		$idmenu = $_POST['id'];
		$idlevel = $_POST['role'];
		var_dump($_POST);
		die;
		// ambil level Role
		$role = $this->db->from('mst_level')->where('singkat', $idlevel)->get()->row();
		$idrole = $role->idlevel;

		$this->db->where('idlevel', $idrole);
		$this->db->where('idmenu', $idmenu);
		$delete = $this->db->delete('mst_role');

		// Menyiapkan respons JSON
		if ($delete) {
			// Jika delete berhasil
			$response = array('success' => true);
		} else {
			// Jika delete gagal
			$response = array('success' => false);
		}

		// Mengatur header konten ke JSON dan mengirimkan respons
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}

	public function simpanMenu()
	{
		$idlevel = $_POST['role'];

		// ambil level Role
		$role = $this->db->from('mst_level')->where('singkat', $idlevel)->get()->row();
		$idrole = $role->idlevel;

		// lakukan pengecekan ke dalam database menggunkan wherein anatra id menu dan idlevel
		$menus = $_POST["menus"];
		// $this->db->select('idmenu, idlevel');
		$this->db->from('mst_role'); // Ganti dengan nama tabel yang sesuai
		// $this->db->where_in('idmenu', $menus);
		$this->db->where('idlevel', $idrole);
		$query = $this->db->get();
		// Mengambil hasil query
		$result = $query->result_array();

		// Jika data ada, hapus semua entri
		if (!empty($result)) {
			// $this->db->where_in('idmenu', $menus);
			$this->db->where('idlevel', $idrole);
			$this->db->delete('mst_role'); // Hapus entri dari tabel
		}
		// ambil id terbesar
		$query = $this->db->select_max('idrole')->get('mst_role')->row();
		$idbig = $query->idrole + 1;
		foreach ($_POST["menus"] as $menu) {
			$data[] = [
				"idrole" => $idbig++,
				"idmenu" => $menu,
				"status" => 1,
				"idlevel" => $idrole,
			];
		}

		$insert = $this->db->insert_batch("mst_role", $data);

		if ($insert) {
			$this->session->set_flashdata('message', 'Data has been saved successfully');
			redirect('usermanagement/userrole');
		} else {
			$this->session->set_flashdata('message', 'Failed to save data');
			redirect('usermanagement/userrole');
		}
	}
}
