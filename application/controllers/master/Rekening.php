<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rekening extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('master/Mrekening');
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
		'modalEdit'   => $this->Form->modalKu('E', 'Edit', 'master/Rekening/aksi', ['edit']),
		'modalDelete' => $this->Form->modalKu('D', 'Delete', 'master/Rekening/aksi', ['delete']),
		'sidebar'     => $template['sidebar'],
		'jstable'     => $Jssetup->jsDatatable('#ftf', 'master/Rekening/getRekening'),
		'jsedit'      => $Jssetup->jsModal('#edit', 'Edit', 'master/Rekening/myModal', '#modalkuE'),
		'jsdelete'    => $Jssetup->jsModal('#delete', 'Delete', 'master/Rekening/myModal', '#modalkuD'),
		'forminsert'  => implode('', $this->Mrekening->formInsert()) ];
		$this->load->view('master/rekening',$data);
	}
	public function getRekening() {
		$this->load->model('instrument/Status');
		$status = $this->Status;
		$datatables = $this->Datatables;
		$datatables->setTable("mst_rekening");
        $datatables->setSelectColumn(["id","idheader", "kdrekening", "kdrekview", "nmrekening", "level", "tipe", "jenis", "islrauptd", "isinsidentil"]);
        $datatables->setOrderColumn([null,"idheader", "kdrekening", "kdrekview", "nmrekening", "level", "tipe", "jenis", "islrauptd", "isinsidentil", null]);
		$datatables->setSearchColumns(["kdrekening", "kdrekview",  "nmrekening", "level","tipe" ,"jenis"]);
        $fetch_data = $this->Datatables->make_datatables();
        $data = array();
		$no   = 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
			$sub_array[] = $row->idheader;
			$sub_array[] = $row->kdrekening;
            $sub_array[] = $row->kdrekview;
            $sub_array[] = $row->nmrekening;
            $sub_array[] = $row->level;
            $sub_array[] = $row->tipe;
            $sub_array[] = $row->jenis;
            $sub_array[] = $row->islrauptd;
            $sub_array[] = $row->isinsidentil;
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
				$idrek = $this->Crud->ambilSatu('mst_rekening', ['id' => $idnya]);
				$enumtipe = ['H', 'D'];
				$enumjenis = ['HRH','REK','BPHTB','PPJ','AKUN','LAIN'];
				$headerData = $this->db->get('mst_rekening')->result();
				$opsiheader = '';
				foreach ($headerData as $header) {
					$opsiheader .= '<option value="'.$header->idheader.'">'.$header->nmrekening.' ('.$header->idheader.')</option>';
				}

				$form [] 	= '
				<div class="row">
				 <div class="col-md-12 ">
                    <div class="form-group">
                        <label for="idheader">Id Header</label>
                        <select name="idheader" id="idheader" class="form-control select2" data-placeholder="Pilih Nama Rekening" style="width: 100%;">
                            '.$opsiheader.'
                        </select>
                    </div>
                </div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('kdrekening','Kode Rekening', $idrek->kdrekening)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('nmrekening','Nama Rekening',$idrek->nmrekening)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('kdrekview','Kode Rekview',$idrek->kdrekview)).
				   '</div>
					<div class="col-md-12">'
					.implode($this->Form->inputText('level','Level',$idrek->level)).
				   '</div>
					<div class="col-md-6">'
					.$this->Form->inputEnumOptions('tipe', 'Tipe', $enumtipe).
				   '</div>
					<div class="col-md-6">'
					.$this->Form->inputEnumOptions('jenis', 'Jenis', $enumjenis).
				   '</div>
					<div class="col-md-6">'
					.implode($this->Form->inputCheckbox('islrauptd', 'islrauptd')).
				   '</div>
					<div class="col-md-6">'
					.implode($this->Form->inputCheckbox('isinsidentil', 'isinsidentil')).
				   '</div>
					'.implode($this->Form->hiddenText('kode',$idrek->id)).'
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
				$data = [	'idheader' 		=> $this->input->post('idheader'),
							'kdrekening' 		=> $this->input->post('kdrekening'),
							'nmrekening' 		=> $this->input->post('nmrekening'),
							'kdrekview' 		=> $this->input->post('kdrekview'),
							'level' 	=> $this->input->post('level'),
							'tipe'		=> $this->input->post('tipe'),
							'jenis'=> $this->input->post('jenis'),
							'islrauptd'=> $this->input->post('islrauptd'),
							'isinsidentil'=> $this->input->post('isinsidentil'),
						];
				$insert = $this->Crud->insert_data('mst_rekening', $data);
				if ($insert) {
					$this->session->set_flashdata('message', 'Data has been saved successfully');
					redirect('master/Rekening');
				} else {
					$this->session->set_flashdata('message', 'Failed to save data');
					redirect('master/Rekening ');
				}
			break;
			case 'Edit':
				$kode = $this->input->post('kode');
				$data = [	'idheader' 		=> $this->input->post('idheader'),
							'kdrekening' 		=> $this->input->post('kdrekening'),
							'nmrekening' 		=> $this->input->post('nmrekening'),
							'kdrekview' 		=> $this->input->post('kdrekview'),
							'level' 	=> $this->input->post('level'),
							'tipe'		=> $this->input->post('tipe'),
							'jenis'=> $this->input->post('jenis'),
							'islrauptd'=> $this->input->post('islrauptd'),
							'isinsidentil'=> $this->input->post('isinsidentil'),
						];
				$update = $this->Crud->update_data('mst_rekening', $data, ['id' => $kode]);
				if ($update) {
					$this->session->set_flashdata('message', 'Data has been updated successfully');
					redirect('master/Rekening');
				} else {
					$this->session->set_flashdata('message', 'Failed to update data');
					redirect('master/Rekening');
				}
			break;
			case 'Delete':
				$kode = $this->input->post('kode');
				$delete = $this->Crud->delete_data('mst_rekening', ['id' => $kode]);
				if ($delete) {
					$this->session->set_flashdata('message', 'Data has been deleted successfully');
					redirect('master/Rekening');
				} else {
					$this->session->set_flashdata('message', 'Failed to delete data');
					redirect('master/Rekening');
				}
			break;
			default:
				header('location:'.site_url('404'));
			break;
		}
	}
}
?>
		
