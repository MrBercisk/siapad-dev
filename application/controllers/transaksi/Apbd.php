
<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Apbd extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('backend/Location');
		$this->load->model('transaksi/Mapbd');
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
		$data['modalEdit'] 	= $this->Form->modalKu('E','Edit','transaksi/apbd/aksi',$actions = ['edit']);;
		$data['modalDelete']= $this->Form->modalKu('D','Delete','transaksi/apbd/aksi',$actions = ['delete']);
		$data['sidebar'] 	= $template['sidebar'];
		$data['jstable']	= $Jssetup->jsDatatable('#ftf','transaksi/apbd/getDataApbd');
	 	$data['jsedit']		= $Jssetup->jsModal('#edit','Edit','transaksi/apbd/myModal','#modalkuE');
	 	$data['jsdelete']	= $Jssetup->jsModal('#delete','Delete','transaksi/apbd/myModal','#modalkuD');
		$data['forminsert'] = $this->Mapbd->formInsert();
		
		$this->load->view('transaksi/apbd',$data);
	}
	
	public function getDataApbd() {
		$datatables = $this->Datatables;
		$datatables->setTable("trx_rapbd");

        $dinas = $this->input->post('dinas');
        $tahun = $this->input->post('tahun');

        $datatables->setSelectColumn([
            "trx_rapbd.id", 
            "trx_rapbd.iddinas", 
            "mst_dinas.nama as namadinas", 
            "trx_rapbd.tahun", 
            "trx_rapbd.idrekening", 
            "mst_rekening.nmrekening as namarekening", 
            "mst_rekening.kdrekening as koderekening", 
            "trx_rapbd.apbd",
            "trx_rapbd.apbdp"]);
        $datatables->setOrderColumn([null, "namadinas", "tahun","namarekening","apbd","apbdp"]);
        $datatables->setSearchColumns(['mst_dinas.nama', 'mst_rekening.nmrekening', 'trx_rapbd.tahun','trx_rapbd.apbd']); 
        $datatables->addJoin("mst_dinas", "trx_rapbd.iddinas = mst_dinas.id", "left");
        $datatables->addJoin("mst_rekening", "trx_rapbd.idrekening = mst_rekening.id", "left");
        
        if (!empty($dinas)) {
            $datatables->addWhere("trx_rapbd.iddinas", $dinas);
        }
        if (!empty($tahun)) {
            $datatables->addWhere("trx_rapbd.tahun", $tahun);
        }

        $fetch_data = $this->Datatables->make_datatables();
        $data = array();
		$no   = 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $no++;
            $sub_array[] = $row->namadinas;
			$sub_array[] = $row->tahun;
			$sub_array[] = $row->namarekening;
			$sub_array[] = number_format($row->apbd, 2, ',', '.');
            $sub_array[] = number_format($row->apbdp, 2, ',', '.');
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
		$idnya = $this->input->post('idnya');
		$idapbd = $this->Crud->ambilSatu('trx_rapbd', ['id' => $idnya]);
		switch($wadi){
			case 'Edit':
                $dinasData = $this->db->get('mst_dinas')->result();
                $opsidin = '';
                foreach ($dinasData as $din) {
                    $opsidin .= '<option value="'.$din->id.'">'.$din->nama.'</option>';
                }
                $rekData = $this->db->get('mst_rekening')->result();
                $opsirek = '';
                foreach ($rekData as $rek) {
                    $opsirek .= '<option value="'.$rek->id.'">'.$rek->nmrekening.'</option>';
                }
				$form [] 	= '
				<div class="row">
					<div class="col-md-12">
					<div class="form-group">
                            <label for="iddinas">Nama Dinas</label>
                            <select name="iddinas" id="iddinas" class="form-control select2" data-placeholder="Pilih Nama Dinas" style="width: 100%;">
                                '.$opsidin.'
                            </select>
                        </div>
				    </div>
					<div class="col-md-12">
                        <div class="form-group">
                            <label for="tahun">Tahun:</label>
                            <input type="number" class="form-control" id="tahun" name="tahun" min="1900" max="9999" value="2024" required>
                        </div></div>
					<div class="col-md-12">
					    <div class="form-group">
                            <label for="idrekening">Nama Rekening</label>
                            <select name="idrekening" id="idrekening" class="form-control select2" data-placeholder="Pilih Nama Rekening" style="width: 100%;">
                                '.$opsirek.'
                            </select>
                        </div>
				    </div>
					<div class="col-md-12"> 
                        <div class="form-group">
                            <label for="apbd">APBD</label>
                            <input type="number" class="form-control" id="apbd" name="apbd" step="0.01" required>
                        </div>	
                    </div>
					<div class="col-md-12"> 
                        <div class="form-group">
                            <label for="apbdp">APBDP</label>
                            <input type="number" class="form-control" id="apbdp" name="apbdp" step="0.01" required>
                        </div>
                    </div>
				   '
				   .implode($this->Form->hiddenText('kode',$idapbd->id)).'
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
            $data = [	'iddinas' 		=> $this->input->post('iddinas'),
					 	'tahun' 			=> $this->input->post('tahun'),
						'idrekening' 		=> $this->input->post('idrekening'),
						'apbd' 		=> $this->input->post('apbd'),
						'apbdp' 		=> $this->input->post('apbdp'),
                    ];
            $insert = $this->Crud->insert_data('trx_rapbd', $data);
            if ($insert) {
                $this->session->set_flashdata('message', 'Data has been saved successfully');
				redirect('transaksi/apbd');
            } else {
                $this->session->set_flashdata('message', 'Failed to save data');
				redirect('transaksi/apbd');
            }
        break;
        case 'Edit':
            $kode = $this->input->post('kode');
            $data = [	'iddinas' 		=> $this->input->post('iddinas'),
					 	'tahun' 			=> $this->input->post('tahun'),
						'idrekening' 		=> $this->input->post('idrekening'),
						'apbd' 		=> $this->input->post('apbd'),
						'apbdp' 		=> $this->input->post('apbdp'),
                    ];
            $update = $this->Crud->update_data('trx_rapbd', $data, ['id' => $kode]);
            if ($update) {
                $this->session->set_flashdata('message', 'Data has been updated successfully');
				redirect('transaksi/apbd');
            } else {
                $this->session->set_flashdata('message', 'Failed to update data');
				redirect('transaksi/apbd');
            }
        break;
        case 'Delete':
            $kode = $this->input->post('kode');
            $delete = $this->Crud->delete_data('trx_rapbd', ['id' => $kode]);
            if ($delete) {
                $this->session->set_flashdata('message', 'Data has been deleted successfully');
				redirect('transaksi/apbd');
            } else {
                $this->session->set_flashdata('message', 'Failed to delete data');
				redirect('transaksi/apbd');
            }
        break;
        default:
            header('location:'.site_url('404'));
        break;
    }
}
}
?>

