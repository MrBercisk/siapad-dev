<?php defined('BASEPATH') or exit('No direct script access allowed');
class WP extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('backend/Location');
		$this->load->model('master/Mwp');
	}
	public function index()
	{
		$Jssetup	= $this->Jssetup;
		$base 		= $this->Msetup->setup();
		$setpage	= $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
		$template 	= $this->Msetup->loadTemplate($setpage->title);
		$data = [];
		$data['footer']		= $template['footer'];
		$data['title'] 		= $setpage->title;
		$data['link'] 		= $setpage->link;
		$data['topbar'] 	= $template['topbar'];
		$data['modalEdit'] 	= $this->Form->modalKu('E', 'Edit', 'master/WP/aksi', $actions = ['edit']);;
		$data['modalDelete'] = $this->Form->modalKu('D', 'Delete', 'master/WP/aksi', $actions = ['delete']);
		$data['sidebar'] 	= $template['sidebar'];
		$data['jstable']	= $Jssetup->jsDatatable('#ftf', 'master/WP/getDataWp');
		$data['jsedit']		= $Jssetup->jsModal('#edit', 'Edit', 'master/WP/myModal', '#modalkuE');
		$data['jsdelete']	= $Jssetup->jsModal('#delete', 'Delete', 'master/WP/myModal', '#modalkuD');
		$data['jslurah']	= $Jssetup->jsKelurahan('master/WP/get_kelurahan', 'kecamatan', 'kelurahan');
		$data['forminsert'] = $this->Mwp->formInsert();

		// $data['input_data'] = $this->session->flashdata('input_data');
		$this->load->view('master/WP', $data);
	}
	public function get_kelurahan()
	{
		$kecamatan_id = $this->input->post('idnya');
		$kelurahan = $this->Location->get_kelurahan_by_kecamatan($kecamatan_id);
		echo json_encode($kelurahan);
	}
	public function getDataWp()
	{
		$datatables = $this->Datatables;
		$datatables->setTable("mst_wajibpajak a");
		$datatables->setSelectColumn([
			"a.id as id_wp", "a.nama as nama_wp",
			"alamat", "nomor", "npwpd", "nop",
			"b.nama as nama_kelurahan", "c.nama as nama_kecamatan", "d.nmrekening as namarekening"
		]);
		$datatables->setOrderColumn([null, "a.nama", "nomor"]);
		$datatables->setSearchColumns(["a.nama", "nomor"]);
		$datatables->addJoin('mst_kelurahan b', 'b.id=a.kelurahan', 'left');
		$datatables->addJoin('mst_kecamatan c', 'c.id=a.kecamatan', 'left');
		$datatables->addJoin('mst_rekening d', 'd.id=a.idrekening', 'left');
		$datatables->addWhere('npwpd IS NOT NULL');
		$datatables->addWhere('nop IS NOT NULL');
		$fetch_data = $this->Datatables->make_datatables();
		$data = array();
		$no   = 1;
		foreach ($fetch_data as $row) {
			$sub_array = array();
			$sub_array[] = $no++;
			$sub_array[] = $row->npwpd;
			$sub_array[] = $row->nop;
			$sub_array[] = $row->namarekening;
			$sub_array[] = $row->nama_wp;
			$sub_array[] = $row->alamat;
			$sub_array[] = $row->nama_kecamatan;
			$sub_array[] = $row->nama_kelurahan;
			$sub_array[] = implode('', $this->Datatables->tombol($row->id_wp));
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
	public function myModal()
	{
		$wadi = isset($_POST['WADI']) ? $_POST['WADI'] : header('location:' . site_url('404'));
		switch ($wadi) {
			case 'Edit':
				$joinTables  	= [
					'mst_kelurahan b' => [
						'condition' => 'a.kelurahan = b.id',
						'type' => 'LEFT'
					],
					'mst_kecamatan c' => [
						'condition' => 'a.kecamatan = c.id',
						'type' => 'LEFT'
					],
					'mst_rekening d' => [
						'condition' => 'a.idrekening = d.id',
						'type' => 'LEFT'
					]
				];
				$selectFields 	= 	'a.id as id_wp,nomor,a.nama as nama_wp,alamat,notype,kecamatan,kelurahan,nop,npwpd,status,idrekening,d.nmrekening,d.kdrekening';
				$kode 			= 	$this->Crud->gandengan('mst_wajibpajak a', $joinTables, $selectFields, 'a.id="' . $this->input->post('idnya') . '"')[0];
				$Jssetup	= $this->Jssetup;
				$status = '<option>-- Status --</option>
							<option value="1" ' . ($kode->status == 1 ? 'selected' : '') . ' >Aktif</option>
							<option value=""2 ' . ($kode->status == 2 ? 'selected' : '') . '>Tidak Aktif</option>
		';
				$form[] = '
				<div class="row">
					<div class="col-md-12">'
					. implode($this->Form->inputText('nop', 'NOP', $kode->nop)) .
					'</div>
					<div class="col-md-12">'
					. implode($this->Form->inputText('npwpd', 'NPWPD', $kode->npwpd)) .
					'</div>
					<div class="col-md-12">'
					. implode($this->Form->inputText('nama', 'NAMA', $kode->nama_wp)) .
					'</div>
					<div class="col-md-12">'
					. implode($this->Form->inputSelectText('rekeninge', 'Rekening', '',  $kode->idrekening)) .
					'</div>
					<div class="col-md-12">'
					. implode($this->Form->inputText('alamat', 'ALAMAT', $kode->alamat)) .
					'</div>
					<div class="col-md-12">'
					. implode($this->Form->inputSelectText('status', 'Status', '', $status)) .
					'</div>
				    <div class="col-md-12">'
					.
					'</div>'
					. $this->Form->selectKec('kecamatanx', 'kelurahanx', 'Kecamatan', 'Kelurahan', $kode->kecamatan, $kode->kelurahan, 'col-md-12') . implode($this->Form->hiddenText('kode', $kode->id_wp)) . '
				</div>
				<script type="text/javascript">
		' . $Jssetup->jsKelurahan('master/WP/get_kelurahan', 'kecamatanx', 'kelurahanx') . '

					
				
						var selectedWp = ' . json_encode($kode) . ';
    
						if (selectedWp) {
							
							var option = new Option(selectedWp.nmrekening + " (" + selectedWp.kdrekening + ")", selectedWp.idrekening, true, true);
							$("#rekeninge").append(option).trigger("change");
						}
						$("#rekeninge").select2({
							ajax: {
								url: "' . site_url('master/wp/getRek') . '",
								type: "POST",
								dataType: "json",
								delay: 250,
								data: function(params) {
									return {
										search: params.term,
										page: params.page || 1
									};
								},
								processResults: function(data) {
									return {
										results: data.items,
										pagination: {
											more: data.pagination.more
										}
									};
								},
								cache: true
							},
							minimumInputLength: 5,
							width: "resolve",
							placeholder: "Masukan Nama Rekening Pajak",
							templateResult: function(item) {
								if (item.loading) {
									return item.text;
								}
								var kode = item.kode || "-";
								return $("<span>" + kode + " - " + item.text + " </span>");
							},
							templateSelection: function(item) {
								if (!item.id) {
									return item.text;
								}
								var kode = item.kode || "-";
								return $("<span>" + kode + " - " + item.text + " </span>");
							}
						});

				</script>
				';
				echo implode($form);
				break;
			case 'Delete':
				$form[] = '
				<div class="row">
					<div class="col-md-12">
					' . implode($this->Form->hiddenText('kode', $this->input->post('idnya'))) . '
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

	public function aksi()
	{
		$aksi = isset($_POST['AKSI']) ? $_POST['AKSI'] : header('location:' . site_url('404'));
		$this->load->model('backend/Crud');
		switch ($aksi) {
			case 'Save':
				$data = [
					'nop' 		=> $this->input->post('nop'),
					'npwpd' 		=> $this->input->post('npwpd'),
					'nama' 			=> $this->input->post('nama'),
					'alamat' 		=> $this->input->post('alamat'),
					'kecamatan' 	=> $this->input->post('kecamatan'),
					'kelurahan' 	=> $this->input->post('kelurahan'),
					'status' 		=> $this->input->post('status'),
				];
				// $this->validate_input($data); // Validasi input
				// var_dump($this->input->post());
				// die;
				$insert = $this->Crud->insert_data('mst_wajibpajak', $data);
				if ($insert) {
					$this->session->set_flashdata('message', 'Data has been saved successfully');
					redirect('master/WP');
				} else {
					$this->session->set_flashdata('message', 'Failed to save data');
					redirect('master/WP');
				}
				break;
			case 'Edit':
				$kode = $this->input->post('kode');
				// var_dump($this->input->post());
				// die;
				$data = [
					'nop' 		=> $this->input->post('nop'),
					'npwpd' 		=> $this->input->post('npwpd'),
					'nama' 			=> $this->input->post('nama'),
					'alamat' 		=> $this->input->post('alamat'),
					'kecamatan' 	=> $this->input->post('kecamatanx'),
					'kelurahan' 	=> $this->input->post('kelurahanx'),
					'status' 		=> $this->input->post('status'),
				];
				$update = $this->Crud->update_data('mst_wajibpajak', $data, ['id' => $kode]);
				if ($update) {
					$this->session->set_flashdata('message', 'Data has been updated successfully');
					redirect('master/WP');
				} else {
					$this->session->set_flashdata('message', 'Failed to update data');
					redirect('master/WP');
				}
				break;
			case 'Delete':
				$kode = $this->input->post('kode');
				$delete = $this->Crud->delete_data('mst_wajibpajak', ['id' => $kode]);
				if ($delete) {
					$this->session->set_flashdata('message', 'Data has been deleted successfully');
					redirect('master/WP');
				} else {
					$this->session->set_flashdata('message', 'Failed to delete data');
					redirect('master/WP');
				}
				break;
			default:
				header('location:' . site_url('404'));
				break;
		}
	}
	private function validate_input($data)
	{
		foreach ($data as $key => $value) {
			if (empty($value)) {
				$this->session->set_flashdata('message', ucfirst($key) . ' harus diisi');
				$this->session->set_flashdata('input_data', $data);
				redirect('master/WP');
			}
		}
	}

	public function getRek()
	{
		$search = $this->input->post('search');
		$data = $this->input->post('data');
		$page = ($this->input->post('page') == 0) ? 1 : 1;
		$limit = 10;
		$offset = ($page - 1) * $limit;

		$this->db->select('w.*');
		$this->db->from('mst_rekening w');
		$this->db->like('w.nmrekening', $search);
		if ($data != '' || $data != null) {
			$this->db->where('w.id', $data);
		}
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		$results = $query->result_array();


		// Query to get the total count
		$this->db->select('w.*');
		$this->db->from('mst_rekening w');
		$this->db->like('w.nmrekening', $search);
		if ($data != '' || $data != null) {
			$this->db->where('w.id', $data);
		}
		$total_count = $this->db->count_all_results();

		$more = ($offset + $limit) < $total_count;
		$data = [
			'items' => array_map(function ($row) {
				return ['id' => $row['id'], 'text' => $row['nmrekening'], 'kode' => $row['kdrekening']];
			}, $results),
			'pagination' => ['more' => $more]
		];

		if (empty($data['items'])) {
			$data = [
				'items' => [],
				'pagination' => ['more' => false]
			];
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	public function get_wp_data()
	{
		$limit = $this->input->get('limit') ?: 5;
		$offset = $this->input->get('offset') ?: 0;
		$search = $this->input->get('search') ?: '';

		$this->db->select('id, nama, nomor, tgljthtmp');
		$this->db->from('mst_wajibpajak');
		if (!empty($search)) {
			$this->db->like('nama', $search);
		}
		$this->db->limit($limit, $offset);
		$wpdata = $this->db->get()->result();

		echo json_encode($wpdata);
	}
}
