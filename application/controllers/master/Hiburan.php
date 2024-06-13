<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Hiburan extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('master/MHiburan');
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
		'modalEdit'   => $this->Form->modalKu('E', 'Edit', 'master/Hiburan/aksi', ['edit']),
		'modalDelete' => $this->Form->modalKu('D', 'Delete', 'master/Hiburan/aksi', ['delete']),
		'sidebar'     => $template['sidebar'],
		'jstable'     => $Jssetup->jsDatatable('#ftf', 'master/Hiburan/getHiburan'),
		'jsedit'      => $Jssetup->jsModal('#edit', 'Edit', 'master/Hiburan/myModal', '#modalkuE'),
		'jsdelete'    => $Jssetup->jsModal('#delete', 'Delete', 'master/Hiburan/myModal', '#modalkuD'),
		'forminsert'  => implode('', $this->MHiburan->formInsert()) ];
		$this->load->view('master/hiburan',$data);
	}
	public function getHiburan() {
		$datatables = $this->Datatables;
		$datatables->setTable("mst_wphiburan");
        $datatables->setSelectColumn([
            "mst_wphiburan.id",
            "mst_wphiburan.kelas",
            "mst_wphiburan.jmlruang",
            "mst_wphiburan.jmlmeja",
            "mst_wphiburan.harga",
            "mst_wajibpajak.nama as nama_hiburan",
        ]);
        $datatables->setOrderColumn([null,"nama", "kelas", "jmlruang", "jmlmeja", "harga"]);
		$datatables->setSearchColumns(["nama", "kelas","jmlruang", "jmlmeja", "harga"]);
        $datatables->addJoin("mst_wajibpajak", "mst_wajibpajak.id = mst_wphiburan.idwp", "left");
        $fetch_data = $this->Datatables->make_datatables();
        $data = array();
		$no   = 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
			$sub_array[] = $row->nama_hiburan;
			$sub_array[] = $row->kelas;
            $sub_array[] = $row->jmlruang;
            $sub_array[] = $row->jmlmeja;
            $sub_array[] = $row->harga;
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
		$form = array(); 
		switch($wadi){
			case 'Edit':
				$idnya = $this->input->post('idnya');
				$idhiburan = $this->Crud->ambilSatu('mst_wphiburan', ['id' => $idnya]);
				$wpdata = $this->db
				->select('mst_wajibpajak.id, mst_wajibpajak.nama')
				->from('mst_wajibpajak')
				->join('mst_wphiburan', 'mst_wphiburan.idwp = mst_wajibpajak.id')
				->get()
				->result();

				$opsiwp = '<option></option>';
				foreach ($wpdata as $wp) {
                    $selected = ($wp->id == $idhiburan->idwp) ? 'selected' : '';
                    $opsiwp .= '<option value="'.$wp->id.'" '.$selected.'>'.$wp->nama.'</option>';
                }
				$form [] 	= '
				<div class="row">
					<div class="col-md-12">'
					.implode($this->Form->inputText('kelas','Kelas',$idhiburan->kelas)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('jmlmeja','JML Meja',$idhiburan->jmlmeja)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('jmlruang','JML Ruang',$idhiburan->jmlruang)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('harga','Harga',$idhiburan->harga)).
				   '</div>
				    <div class="col-md-12">
                            <label for="idwp">Nama Hiburan</label>
                            <select id="idwp" name="idwp" class="form-control select2" data-placeholder="Pilih Nama Hiburan" style="width: 100%;">
                                '.$opsiwp.'
                            </select>
                    </div>
					'.implode($this->Form->hiddenText('kode',$idhiburan->id)).'
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
				$data = [	'kelas' 		=> $this->input->post('kelas'),
							'jmlmeja' 	=> $this->input->post('jmlmeja'),
							'jmlruang'		=> $this->input->post('jmlruang'),
							'harga'		=> $this->input->post('harga'),
							'idwp'=> $this->input->post('idwp')];
				$insert = $this->Crud->insert_data('mst_wphiburan', $data);
				if ($insert) {
					$this->session->set_flashdata('message', 'Data has been saved successfully');
					redirect('master/hiburan');
				} else {
					$this->session->set_flashdata('message', 'Failed to save data');
					redirect('master/hiburan');
				}
			break;
			case 'Edit':
				$kode = $this->input->post('kode');
				$data = [	'kelas' 		=> $this->input->post('kelas'),
							'jmlmeja' 	=> $this->input->post('jmlmeja'),
							'jmlruang'		=> $this->input->post('jmlruang'),
							'harga'		=> $this->input->post('harga'),
							'idwp'=> $this->input->post('idwp')];
				$update = $this->Crud->update_data('mst_wphiburan', $data, ['id' => $kode]);
				if ($update) {
					$this->session->set_flashdata('message', 'Data has been updated successfully');
					redirect('master/hiburan');
				} else {
					$this->session->set_flashdata('message', 'Failed to update data');
					redirect('master/hiburan');
				}
			break;
			case 'Delete':
				$kode = $this->input->post('kode');
				$delete = $this->Crud->delete_data('mst_wphiburan', ['id' => $kode]);
				if ($delete) {
					$this->session->set_flashdata('message', 'Data has been deleted successfully');
					redirect('master/hiburan');
				} else {
					$this->session->set_flashdata('message', 'Failed to delete data');
					redirect('master/hiburan');
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
		
