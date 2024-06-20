<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kelurahan extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('backend/Location');
		$this->load->model('master/Mkelurahan');
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
		$data['modalEdit'] 	= $this->Form->modalKu('E','Edit','master/Kelurahan/aksi',$actions = ['edit']);;
		$data['modalDelete']= $this->Form->modalKu('D','Delete','master/Kelurahan/aksi',$actions = ['delete']);
		$data['sidebar'] 	= $template['sidebar'];
		$data['jstable']	= $Jssetup->jsDatatable('#ftf','master/Kelurahan/getKel');
	 	$data['jsedit']		= $Jssetup->jsModal('#edit','Edit','master/Kelurahan/myModal','#modalkuE');
	 	$data['jsdelete']	= $Jssetup->jsModal('#delete','Delete','master/Kelurahan/myModal','#modalkuD');
		$data['forminsert'] = implode($this->Mkelurahan->formInsert());
		$this->load->view('master/kelurahan',$data);
	}
	public function getKel(){
		$datatables = new Datatables();
		$datatables->setTable("mst_kelurahan");
        $datatables->setSelectColumn([
            "mst_kelurahan.id",
            "mst_kelurahan.kode",
            "mst_kelurahan.nama",
            "mst_kecamatan.nama as nama_kecamatan"
        ]);
        $datatables->setOrderColumn([null, "mst_kelurahan.kode", "mst_kelurahan.nama", "mst_kelurahan.nama_kecamatan"]);
		$datatables->setSearchColumns(['mst_kelurahan.kode', 'mst_kelurahan.nama', 'mst_kecamatan.nama']); 
		$datatables->addJoin("mst_kecamatan", "mst_kelurahan.idkecamatan = mst_kecamatan.id", "left");
		$fetch_data = $datatables->make_datatables();
        $data 		= array();
		$no   		= 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
			$sub_array[] = $row->kode;
            $sub_array[] = $row->nama;
            $sub_array[] = $row->nama_kecamatan;
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
		$idkel = $this->Crud->ambilSatu('mst_kelurahan', ['id' => $idnya]);
		
		switch($wadi){
			case 'Edit':
				$kecData = $this->db->get('mst_kecamatan')->result();
				$opsikec = '';
				foreach ($kecData as $kec) {
					$opsikec .= '<option value="'.$kec->id.'">'.$kec->nama.'</option>';
				}
				$form [] = '
				<div class="row">
					<div class="col-md-12">
					'.implode($this->Form->inputText('kode','Kode',$idkel->kode)).'
					</div>
					<div class="col-md-12">
					'.implode($this->Form->inputText('nama','Kelurahan',$idkel->nama)).'
					</div>
					 <div class="col-md-12">
                    <div class="form-group">
                        <label for="iduptd">Nama Kecamatan</label>
                        <select name="idkecamatan" id="idkecamatan" class="form-control">
                            '.$opsikec.'
                        </select>
                    </div>
                </div>
					'
					.implode($this->Form->hiddenText('kode',$idkel->kode)).'
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
				'idkecamatan'=> $this->input->post('idkecamatan')
            ];
            $insert = $this->Crud->insert_data('mst_kelurahan', $data);
            if ($insert) {
                $this->session->set_flashdata('message', 'Data has been saved successfully');
				redirect('master/Kelurahan');
            } else {
                $this->session->set_flashdata('message', 'Failed to save data');
				redirect('master/Kelurahan');
            }
        break;
        case 'Edit':
            $kode = $this->input->post('kode');
            $data = [
                'nama' => $this->input->post('nama'),
				'nama' 	=> $this->input->post('nama'),
				'idkecamatan'=> $this->input->post('idkecamatan')
            ];
            $update = $this->Crud->update_data('mst_kelurahan', $data, ['kode' => $kode]);
            if ($update) {
                $this->session->set_flashdata('message', 'Data has been updated successfully');
				redirect('master/Kelurahan');
            } else {
                $this->session->set_flashdata('message', 'Failed to update data');
				redirect('master/Kelurahan');
            }
        break;
        case 'Delete':
            $kode = $this->input->post('kode');
            $delete = $this->Crud->delete_data('mst_kelurahan', ['id' => $kode]);
            if ($delete) {
                $this->session->set_flashdata('message', 'Data has been deleted successfully');
				redirect('master/Kelurahan');
            } else {
                $this->session->set_flashdata('message', 'Failed to delete data');
				redirect('master/Kelurahan');
            }
        break;
        default:
            header('location:'.site_url('404'));
        break;
    }
}

}

?>
		
