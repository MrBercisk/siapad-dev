<?php defined('BASEPATH') OR exit('No direct script access allowed');
class RelBphtb extends CI_Controller {
	public function __construct() {
        parent::__construct();	
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
		$data['modalEdit'] 	= $this->Form->modalKu('E','Edit','laporan/RelBphtb/aksi',$actions = ['edit']);;
		$data['modalDelete']= $this->Form->modalKu('D','Delete','laporan/RelBphtb/aksi',$actions = ['delete']);
		$data['sidebar'] 	= $template['sidebar'];
		$data['jstable']	= $Jssetup->jsDatatable('#ftf','laporan/RelBphtb/getBphtb');
	 	$data['jsedit']		= $Jssetup->jsModal('#edit','Edit','laporan/RelBphtb/myModal','#modalkuE');
	 	$data['jsdelete']	= $Jssetup->jsModal('#delete','Delete','laporan/RelBphtb/myModal','#modalkuD');
		$data['forminsert'] = implode($this->formInsert());
		$this->load->view('laporan/relbphtb',$data);
	}
	public function getBphtb(){
        $datatables = new Datatables();
		$datatables->setTable("mst_kecamatan");
        $datatables->setSelectColumn(["kode", "nama","id"]);
        $datatables->setOrderColumn(["kode", "nama"]);
		$fetch_data = $datatables->make_datatables();
        $data = array();
		$no   = 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
			$sub_array[] = $row->kode;
            $sub_array[] = $row->nama;
			$sub_array[] = $datatables->tombol($row->id);
            $data[] = $sub_array;
        }
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $datatables->get_all_data(),
            "recordsFiltered" => $datatables->get_filtered_data(),
            "data" => $data
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
	public function formInsert(){
		$form[] = '
		<form action="'.site_url('laporan/RelBphtb/aksi').'" method="post" enctype="multipart/form-data" class="form-row">
				<div class="row">
					<div class="col-md-6 offset-7">
				   '.implode($this->Form->inputText('tanggal','Tanggal')).
				   '</div>
					<div class="col-md-6 offset-7">'
					.implode($this->Form->inputText('pbreak','Page Break')).
				   '</div>
				    <div class="col-md-6 offset-7">'
					.implode($this->Form->inputText('nobaris','No. Baris')).
				   '</div>
				   	<div class="col-md-6 offset-7">'
					.implode($this->Form->inputText('apbdp','APBDP')).
				   '</div>
				   	<div class="col-md-6 offset-7">'
					.implode($this->Form->inputText('boautfh','APBDP')).
				   '</div>
				   	<div class="col-md-6 offset-7">'
					.implode($this->Form->inputText('apbdp','APBDP')).
				   '</div>
					<div class="col-md-12 text-center">
						<div class="btn-group">
							<button class="btn btn-outline-danger mr-1" type="reset">
								<i class="fa fa-undo"></i> Reset
							</button>
							<button class="btn btn-outline-primary" type="submit" name="AKSI" value="Save">
								<i class="fa fa-save"></i> Simpan
							</button>
						</div>
					</div>
				</div>
               </form>
        </div>';
		return $form;
	}
	public function aksi(){
    $aksi = isset($_POST['AKSI']) ? $_POST['AKSI'] : header('location:'.site_url('404'));
    $this->load->model('backend/Crud'); 
    switch($aksi){
        case 'Save':
            $data = [
                'kode' 	=> $this->input->post('kode'),
                'nama' 	=> $this->input->post('nama')
            ];
            $insert = $this->Crud->insert_data('mst_kecamatan', $data);
            if ($insert) {
                $this->session->set_flashdata('message', 'Data has been saved successfully');
				redirect('laporan/RelBphtb');
            } else {
                $this->session->set_flashdata('message', 'Failed to save data');
				redirect('laporan/RelBphtb');
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
				redirect('laporan/RelBphtb');
            } else {
                $this->session->set_flashdata('message', 'Failed to update data');
				redirect('laporan/RelBphtb');
            }
        break;
        case 'Delete':
            $kode = $this->input->post('kode');
            $delete = $this->Crud->delete_data('mst_kecamatan', ['id' => $kode]);
            if ($delete) {
                $this->session->set_flashdata('message', 'Data has been deleted successfully');
				redirect('laporan/RelBphtb');
            } else {
                $this->session->set_flashdata('message', 'Failed to delete data');
				redirect('laporan/RelBphtb');
            }
        break;
        default:
            header('location:'.site_url('404'));
        break;
    }
}

}

?>
		
