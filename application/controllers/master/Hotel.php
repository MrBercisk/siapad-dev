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
	  
	public function myModal() {
        $wadi = isset($_POST['WADI']) ? $_POST['WADI'] : header('location:'.site_url('404'));
        $form = array(); 
        switch ($wadi) {
            case 'Edit':
                $idnya = $this->input->post('idnya');
                $idhotel = $this->Crud->ambilSatu('mst_wphotel', ['id' => $idnya]);
                $wpdata = $this->db
                    ->select('mst_wajibpajak.id, mst_wajibpajak.nama')
                    ->from('mst_wajibpajak')
                    ->join('mst_wphotel', 'mst_wphotel.idwp = mst_wajibpajak.id')
                    ->get()
                    ->result();
        
                $opsiwp = '<option></option>'; 
                foreach ($wpdata as $wp) {
                    $selected = ($wp->id == $idhotel->idwp) ? 'selected' : '';
                    $opsiwp .= '<option value="'.$wp->id.'" '.$selected.'>'.$wp->nama.'</option>';
                }

                $form[] = '
                    <div class="row">
                        <div class="col-md-12">'
                            .$this->Form->inputText('golkamar', 'Gol Kamar', $idhotel->golkamar)[0].
                        '</div>
                        <div class="col-md-12">'
                            .$this->Form->inputText('tarif', 'Tarif', $idhotel->tarif)[0].
                        '</div>
                        <div class="col-md-12">'
                            .$this->Form->inputText('jmlkamar', 'Jml Kamar', $idhotel->jmlkamar)[0].
                        '</div>
                        <div class="col-md-12">
                            <label for="idwp">Nama Hotel</label>
                            <select id="idwp" name="idwp" class="form-control select2" data-placeholder="Pilih Nama Hotelk" style="width: 100%;">
                                '.$opsiwp.'
                            </select>
                        </div>
                    </div>'
                    .$this->Form->hiddenText('kode', $idhotel->id)[0];
                
                break;

            case 'Delete':
                $form[] = '
                <div class="row">
                    <div class="col-md-12">
                    '.$this->Form->hiddenText('kode', $this->input->post('idnya'))[0].'
                    Apakah kamu yakin ingin menghapus data ini?
                    </div>
                </div>';
                break;

            default:
                $form[] = 'NOTHING !!!';
                break;
        }
        echo implode('', $form);
    }
	public function aksi(){
		$aksi = isset($_POST['AKSI']) ? $_POST['AKSI'] : header('location:'.site_url('404'));
		$this->load->model('backend/Crud'); 
		switch($aksi){
			case 'Save':
				$data = [	'golkamar' 		=> $this->input->post('golkamar'),
							'tarif' 	=> $this->input->post('tarif'),
							'jmlkamar'		=> $this->input->post('jmlkamar'),
							'idwp'=> $this->input->post('idwp')];
				$insert = $this->Crud->insert_data('mst_wphotel', $data);
				if ($insert) {
					$this->session->set_flashdata('message', 'Data has been saved successfully');
					redirect('master/Hotel');
				} else {
					$this->session->set_flashdata('message', 'Failed to save data');
					redirect('master/Hotel');
				}
			break;
			case 'Edit':
				$kode = $this->input->post('kode');
				$data = [	'golkamar' 		=> $this->input->post('golkamar'),
							'tarif' 	=> $this->input->post('tarif'),
							'jmlkamar'		=> $this->input->post('jmlkamar'),
							'idwp'=> $this->input->post('idwp')];
				$update = $this->Crud->update_data('mst_wphotel', $data, ['id' => $kode]);
				if ($update) {
					$this->session->set_flashdata('message', 'Data has been updated successfully');
					redirect('master/Hotel');
				} else {
					$this->session->set_flashdata('message', 'Failed to update data');
					redirect('master/Hotel');
				}
			break;
			case 'Delete':
				$kode = $this->input->post('kode');
				$delete = $this->Crud->delete_data('mst_wphotel', ['id' => $kode]);
				if ($delete) {
					$this->session->set_flashdata('message', 'Data has been deleted successfully');
					redirect('master/Hotel');
				} else {
					$this->session->set_flashdata('message', 'Failed to delete data');
					redirect('master/Hotel');
				}
			break;
			default:
				header('location:'.site_url('404'));
			break;
		}
	}   
	
    
}
?>
		
