<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Restoran extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('master/MRestoran');
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
		'modalEdit'   => $this->Form->modalKu('E', 'Edit', 'master/Restoran/aksi', ['edit']),
		'modalDelete' => $this->Form->modalKu('D', 'Delete', 'master/Restoran/aksi', ['delete']),
		'sidebar'     => $template['sidebar'],
		'jstable'     => $Jssetup->jsDatatable('#ftf', 'master/Restoran/getRestoran'),
		'jsedit'      => $Jssetup->jsModal('#edit', 'Edit', 'master/Restoran/myModal', '#modalkuE'),
		'jsdelete'    => $Jssetup->jsModal('#delete', 'Delete', 'master/Restoran/myModal', '#modalkuD'),
		'forminsert'  => implode('', $this->MRestoran->formInsert()) ];
		$this->load->view('master/Restoran',$data);
	}
	public function getRestoran() {
		$datatables = $this->Datatables;
		$datatables->setTable("mst_wprestoran");
        $datatables->setSelectColumn([
            "mst_wprestoran.id",
            "mst_wprestoran.menu",
            "mst_wprestoran.jmlmeja",
            "mst_wprestoran.jmlkursi",
            "mst_wajibpajak.nama as nama_restoran",
            "mst_wajibpajak.nomor as nomor_restoran"
        ]);
        $datatables->setOrderColumn([null,"nama","nomor" ,"menu", "jmlmeja", "jmlkursi"]);
		$datatables->setSearchColumns(["menu", "jmlmeja", "jmlkursi"]);
        $datatables->addJoin("mst_wajibpajak", "mst_wajibpajak.id = mst_wprestoran.idwp", "left");
        $fetch_data = $this->Datatables->make_datatables();
        $data = array();
		$no   = 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
			$sub_array[] = $row->nama_restoran;
			$sub_array[] = $row->nomor_restoran;
			$sub_array[] = $row->menu;
            $sub_array[] = $row->jmlmeja;
            $sub_array[] = $row->jmlkursi;
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
				$idnya = $this->input->post('idnya');
				$idresto = $this->Crud->ambilSatu('mst_wprestoran', ['id' => $idnya]);
				$wpdata = $this->db
				->select('mst_wajibpajak.id, mst_wajibpajak.nama')
				->from('mst_wajibpajak')
				->join('mst_wprestoran', 'mst_wprestoran.idwp = mst_wajibpajak.id')
				->get()
				->result();
				$opsiwp = '<option></option>';
				foreach ($wpdata as $wp) {
                    $selected = ($wp->id == $idresto->idwp) ? 'selected' : '';
                    $opsiwp .= '<option value="'.$wp->id.'" '.$selected.'>'.$wp->nama.'</option>';
                }
				$form [] 	= '
				<div class="row">
					<div class="col-md-12">'
					.implode($this->Form->inputText('menu','Menu',$idresto->menu)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('jmlmeja','JML Meja',$idresto->jmlmeja)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('jmlkursi','JML Kursi',$idresto->jmlkursi)).
				   '</div>
					 <div class="col-md-12">
                            <label for="idwp">Nama Restoran</label>
                            <select id="idwp" name="idwp" class="form-control select2" data-placeholder="Pilih Nama Restoran" style="width: 100%;">
                                '.$opsiwp.'
                            </select>
                    </div>
				   '
				   .implode($this->Form->hiddenText('kode',$idresto->id)).'
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
				$data = [	'menu' 		=> $this->input->post('menu'),
							'jmlmeja' 	=> $this->input->post('jmlmeja'),
							'jmlkursi'		=> $this->input->post('jmlkursi'),
							'idwp'=> $this->input->post('idwp')];
				$insert = $this->Crud->insert_data('mst_wprestoran', $data);
				if ($insert) {
					$this->session->set_flashdata('message', 'Data has been saved successfully');
					redirect('master/Restoran');
				} else {
					$this->session->set_flashdata('message', 'Failed to save data');
					redirect('master/Restoran');
				}
			break;
			case 'Edit':
				$kode = $this->input->post('kode');
				$data = [	'menu' 		=> $this->input->post('menu'),
							'jmlmeja' 	=> $this->input->post('jmlmeja'),
							'jmlkursi'		=> $this->input->post('jmlkursi'),
							'idwp'=> $this->input->post('idwp')];
				$update = $this->Crud->update_data('mst_wprestoran', $data, ['id' => $kode]);
				if ($update) {
					$this->session->set_flashdata('message', 'Data has been updated successfully');
					redirect('master/Restoran');
				} else {
					$this->session->set_flashdata('message', 'Failed to update data');
					redirect('master/Restoran');
				}
			break;
			case 'Delete':
				$kode = $this->input->post('kode');
				$delete = $this->Crud->delete_data('mst_wprestoran', ['id' => $kode]);
				if ($delete) {
					$this->session->set_flashdata('message', 'Data has been deleted successfully');
					redirect('master/Restoran');
				} else {
					$this->session->set_flashdata('message', 'Failed to delete data');
					redirect('master/Restoran');
				}
			break;
			default:
				header('location:'.site_url('404'));
			break;
		}
	}
     
    public function getWpData() {
        $search = $this->input->get('term');
        $this->db->like('nama', $search);
        $query = $this->db->get('mst_wajibpajak');
        $data = $query->result();
        
        $results = [];
        foreach ($data as $row) {
            $results[] = [
                'id' => $row->id,
                'text' => $row->nama
            ];
        }
        
        echo json_encode(['results' => $results]);
    }
    
    
}
?>
		
