<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kecamatan extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('master/Mkecamatan');
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
		$data['modalEdit'] 	= $this->Form->modalKu('E','Edit','master/Kecamatan/aksi',$actions = ['edit']);;
		$data['modalDelete']= $this->Form->modalKu('D','Delete','master/Kecamatan/aksi',$actions = ['delete']);
		$data['sidebar'] 	= $template['sidebar'];
		$data['jstable']	= $Jssetup->jsDatatable('#ftf','master/Kecamatan/getKec');
	 	$data['jsedit']		= $Jssetup->jsModal('#edit','Edit','master/Kecamatan/myModal','#modalkuE');
	 	$data['jsdelete']	= $Jssetup->jsModal('#delete','Delete','master/Kecamatan/myModal','#modalkuD');
		$data['forminsert'] = implode($this->Mkecamatan->formInsert());
		$this->load->view('master/kecamatan',$data);
	}
	public function json_Kecamatan(){
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $kecamatan_data = $this->Crud->ambilsemua('mst_kecamatan');
            echo json_encode($kecamatan_data);
        } else {
            show_error('Method Not Allowed', 405);
        }
	}
	public function getKec(){
        $datatables = new Datatables();
		$datatables->setTable("mst_kecamatan");
        $datatables->setSelectColumn(["kode", "nama","id"]);
        $datatables->setOrderColumn(["kode", "nama"]);
		$fetch_data = $datatables->make_datatables();
        $data 		= array();
		$no   		= 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
			$sub_array[] = $row->kode;
            $sub_array[] = $row->nama;
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
		$kode = $this->Crud->ambilSatu('mst_kecamatan', ['id' => $this->input->post('idnya')]);
		switch($wadi){
			case 'Edit':
				$form [] = '
				<div class="row">
					<div class="col-md-12">
					'.implode($this->Form->inputText('kode','Kode',$kode->kode)).'
					</div>
					<div class="col-md-12">
					'.implode($this->Form->inputText('nama','Kecamatan',$kode->nama)).'
					</div>'.implode($this->Form->hiddenText('kode',$kode->kode)).'
				</div>';
				echo implode($form);
			break;
			case 'Delete':
				$form [] = '
				<div class="row">
					<div class="col-md-12">
					'.implode($this->Form->hiddenText('kode',$this->input->post('idnya'))).'
					Apakah kamu yakin ingin menghapus data ini ?
					</div>
				</div>';
				echo implode($form);
			break;
			default:
				echo 'NOTHING !!!';
			break;
		}
	}
	public function aksi(){
    $aksi = isset($_POST['AKSI']) ? $_POST['AKSI'] : header('location:'.site_url('404'));
    $this->load->model('backend/Crud'); 
    switch($aksi){
        case 'Save':
            $data = [
                'kode' 	=> $this->input->post('kode'),
                'nama' 	=> $this->input->post('nama'),
				'iduptd'=> 13
            ];
            $insert = $this->Crud->insert_data('mst_kecamatan', $data);
            if ($insert) {
                $this->session->set_flashdata('message', 'Data has been saved successfully');
				redirect('master/Kecamatan');
            } else {
                $this->session->set_flashdata('message', 'Failed to save data');
				redirect('master/Kecamatan');
            }
        break;
        case 'Edit':
            $kode = $this->input->post('kode');
            $data = [
                'nama' => $this->input->post('nama')
            ];
            $update = $this->Crud->update_data('mst_kecamatan', $data, ['kode' => $kode]);
            if ($update) {
                $this->session->set_flashdata('message', 'Data has been updated successfully');
				redirect('master/Kecamatan');
            } else {
                $this->session->set_flashdata('message', 'Failed to update data');
				redirect('master/Kecamatan');
            }
        break;
        case 'Delete':
            $kode = $this->input->post('kode');
            $delete = $this->Crud->delete_data('mst_kecamatan', ['id' => $kode]);
            if ($delete) {
                $this->session->set_flashdata('message', 'Data has been deleted successfully');
				redirect('master/Kecamatan');
            } else {
                $this->session->set_flashdata('message', 'Failed to delete data');
				redirect('master/Kecamatan');
            }
        break;
        default:
            header('location:'.site_url('404'));
        break;
    }
}

}

?>
		
