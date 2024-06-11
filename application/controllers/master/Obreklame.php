<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Obreklame extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('master/MReklame');
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
		'modalEdit'   => $this->Form->modalKu('E', 'Edit', 'master/Obreklame/aksi', ['edit']),
		'modalDelete' => $this->Form->modalKu('D', 'Delete', 'master/Obreklame/aksi', ['delete']),
		'sidebar'     => $template['sidebar'],
		'jstable'     => $Jssetup->jsDatatable('#ftf', 'master/Obreklame/getReklame'),
		'jsedit'      => $Jssetup->jsModal('#edit', 'Edit', 'master/Obreklame/myModal', '#modalkuE'),
		'jsdelete'    => $Jssetup->jsModal('#delete', 'Delete', 'master/Obreklame/myModal', '#modalkuD'),
		'forminsert'  => implode('', $this->MReklame->formInsert()) ];
		$this->load->view('master/reklame',$data);
	}
	public function getReklame() {
		$datatables = $this->Datatables;
		$datatables->setTable("mst_wpreklame");
        $datatables->setSelectColumn([
            "mst_wpreklame.id",
            "mst_wpreklame.jnsukuran as jenis_ukuran",
            "mst_wajibpajak.nama as nama_reklame",
            "mst_wajibpajak.nomor as nomor_reklame"
           
        ]);
        $datatables->setOrderColumn([null,"nama_reklame","nomor_reklame","jenis_ukuran"]);
		$datatables->setSearchColumns(["nama_reklame","nomor_reklame","jenis_ukuran"]);
        $datatables->addJoin("mst_wajibpajak", "mst_wajibpajak.id = mst_wpreklame.idwp", "left");
        $fetch_data = $this->Datatables->make_datatables();
        $data = array();
		$no   = 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
            $sub_array[] = $row->nama_reklame;
            $sub_array[] = $row->nomor_reklame;
            $sub_array[] = $row->jenis_ukuran;
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
				$idreklame = $this->Crud->ambilSatu('mst_wpreklame', ['id' => $idnya]);
				$form [] 	= '
				<div class="row">
					<div class="col-md-12">'
					.implode($this->Form->inputText('jnsukuran','Jenis Ukuran',$idreklame->jnsukuran)).
				   '</div>
					 <div class="col-md-12">
                           <div class="form-group">
                    <label for="wp">Reklame</label>
                    <select class="form-control select2" id="wp" name="wp" style="width: 100%;">
                        <option value="">Pilih Nama Hotel</option>
                    </select>
                </div>
					'.implode($this->Form->hiddenText('kode',$idreklame->id)).'
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
		
