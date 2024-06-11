<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Hotel extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('master/Mhotel');
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
		'modalEdit'   => $this->Form->modalKu('E', 'Edit', 'master/Hotel/aksi', ['edit']),
		'modalDelete' => $this->Form->modalKu('D', 'Delete', 'master/Hotel/aksi', ['delete']),
		'sidebar'     => $template['sidebar'],
		'jstable'     => $Jssetup->jsDatatable('#ftf', 'master/Hotel/getHotel'),
		'jsedit'      => $Jssetup->jsModal('#edit', 'Edit', 'master/Hotel/myModal', '#modalkuE'),
		'jsdelete'    => $Jssetup->jsModal('#delete', 'Delete', 'master/Hotel/myModal', '#modalkuD'),
		'forminsert'  => implode('', $this->Mhotel->formInsert()) ];
		$this->load->view('master/Hotel',$data);
	}
	public function getHotel() {
		$datatables = $this->Datatables;
		$datatables->setTable("mst_wphotel");
        $datatables->setSelectColumn([
            "mst_wphotel.id",
            "mst_wphotel.golkamar",
            "mst_wphotel.tarif",
            "mst_wphotel.jmlkamar",
            "mst_wajibpajak.nama"
        ]);
        $datatables->setOrderColumn([null,"nama", "golkamar", "tarif", "jmlkamar"]);
		$datatables->setSearchColumns(["nama","golkamar", "tarif", "jmlkamar"]);
        $datatables->addJoin("mst_wajibpajak", "mst_wajibpajak.id = mst_wphotel.idwp", "left");
        $fetch_data = $this->Datatables->make_datatables();
        $data = array();
		$no   = 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
			$sub_array[] = $row->nama;
			$sub_array[] = $row->golkamar;
            $sub_array[] = $row->tarif;
            $sub_array[] = $row->jmlkamar;
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
				$idhotel = $this->Crud->ambilSatu('mst_wphotel', ['id' => $idnya]);
				$form [] 	= '
				<div class="row">
					<div class="col-md-12">'
					.implode($this->Form->inputText('golkamar','Gol Kamar',$idhotel->golkamar)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('tarif','Tarif',$idhotel->tarif)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('jmlkamar','Jml Kamar',$idhotel->jmlkamar)).
				   '</div>
				    <div class="col-md-12">
                           <div class="form-group">
                    <label for="wp">Hotel</label>
                    <select class="form-control select2" id="wp" name="wp" style="width: 100%;">
                        <option value="">Pilih Nama Hotel</option>
                    </select>
                </div>
					'
					.implode($this->Form->hiddenText('kode',$idhotel->id)).'
				</div>';
				
			break;
			case 'Delete':
				$form [] = '
				<div class="row">
					<div class="col-md-12">
					'.implode($this->Form->hiddenText('kode',$this->input->post('idnya'))).'
					Apakah kamu yakin ingin menghapus data ini ?
					</div>
				</div>'
				;
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
		
