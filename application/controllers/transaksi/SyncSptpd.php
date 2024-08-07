<?php defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;

setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

class SyncSptpd extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('transaksi/Msyncsptpd');
		$this->load->model('transaksi/MformSptpd');
	}
	public function index()
	{

		$data = [];
		$Jssetup	= $this->Jssetup;
		$base 		= $this->Msetup->setup();
		$setpage	= $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
		// var_dump($base['halaman'] . '/' . $base['fungsi']);
		// die;
		$template 	= $this->Msetup->loadTemplate($setpage->title);
		$data =
			[
				'footer'      => $template['footer'],
				'title'       => $setpage->title,
				'link'        => $setpage->link,
				'topbar'      => $template['topbar'],
				'modalEdit'   => $this->Form->modalKu('E', 'Edit', 'transaksi/syncsptpd/aksi', ['edit']),
				'modalDelete' => $this->Form->modalKu('D', 'Delete', 'transaksi/syncsptpd/aksi', ['delete']),
				'sidebar'     => $template['sidebar'],
				'jstable'     => $Jssetup->jsDatatable3('#ftf', 'transaksi/syncsptpd/getDinas'),
				'jsedit'      => $Jssetup->jsModal('#edit', 'Edit', 'transaksi/syncsptpd/myModal', '#modalkuE'),
				'jsdelete'    => $Jssetup->jsModal('#delete', 'Delete', 'transaksi/syncsptpd/myModal', '#modalkuD'),
				// 'forminsert'  => implode('', $this->Msyncsptpd->formInsert()),
				'datepick'  => $this->Msyncsptpd->datepicker(),
				'filters'  => implode('', $this->Msyncsptpd->filters())
			];
		$this->load->view('transaksi/syncsptpd', $data);
	}


	public function aksi()
	{
		// var_dump($_POST);
		// die;
		$aksi = isset($_POST['AKSI']) ? $_POST['AKSI'] : header('location:' . site_url('404'));
		// lakukan pencarian id wp di wp berdasarkan nop dan npwpd jika kosong maka insert baru di mst_wp
		$postData = $_POST['data'];
		// var_dump($_POST);
		// die;
		$data = [];

		foreach ($postData as $item) {
			$data[] = [
				'tgl_input' => $item['tgl_input'],
				'nomor' => $item['nomor'],
				'tanggal' => $item['tanggal'],
				'wajibPajak' => $item['wajibPajak'],
				'npwpd' => $item['npwpd'],
				'nop' => $item['nop'],
				'namaop' => $item['namausaha'],
				'rekening' => $item['rekening'],
				'bulan' => $item['bulan'],
				'tahun' => $item['tahun'],
				'pokok' => $item['pokok'],
				'denda' => $item['denda'],
				'noPelaporan' => $item['noPelaporan'],
				'jumlah' => $item['jumlah'],
				'keterangan' => $item['keterangan'],
				'jenispajak' => $item['jenispajak']
			];
		}

		$this->load->model('backend/Crud');
		switch ($aksi) {
			case 'Save':

				// lakukan pencarian WP di API
				foreach ($data as $item) {
					$wps =	$this->getAPiWajibPajak($item['nop'], $item['npwpd'], $item['jenispajak']);

					switch (TRUE) {
						case ($item['jenispajak'] == 'Hiburan'):
							$item['idrekening'] = $this->Msetup->get_rekening('', '25');
							break;
						case ($item['jenispajak'] == 'Restoran'):
							$item['idrekening'] = $this->Msetup->get_rekening('', '20');
							break;
						case ($item['jenispajak'] == 'Air Bawah Tanah'):
							$item['idrekening'] = $this->Msetup->get_rekening('', '67');
							break;
						case ($item['jenispajak'] == 'Mineral Non Logam dan Batuan'): //2
							$item['idrekening'] = $this->Msetup->get_rekening('', '72');
							break;
						case ($item['jenispajak'] == 'Parkir'): //72
							$item['idrekening'] = $this->Msetup->get_rekening('', '64');
							break;
						case ($item['jenispajak'] == 'Penerangan Jalan'): //61
							$item['idrekening'] = $this->Msetup->get_rekening('', '61');
							break;
						case ($item['jenispajak'] == 'Hotel'): //5
							$item['idrekening'] = $this->Msetup->get_rekening('', '5');
							break;
						default: //walet
							$item['idrekening'] = $this->Msetup->get_rekening('', '68');
							break;
					}

					// $rek = $this->Msetup->get_rekening('', $wps->kdrekening);
					// var_dump($item['idrekening']['id']);
					// die;
					$wp = [
						'nama' => $wps->namaop,
						'pemilik' => $wps->namawp,
						'alamat' => $wps->alamat,
						'nop' => $wps->nop,
						'npwpd' => $wps->npwpd,
						'kecamatan' => $wps->kecamatan,
						'kelurahan' => $wps->kelurahan,
						'status' => $wps->status,
						'idrekening' => $item['idrekening']["id"],
					];
					$datawp = $this->Msetup->mstWajibPajak('', $item['npwpd'], $item['nop'], $item['idrekening']["id"]);

					if ($datawp == '' || $datawp == null) {
						// jika kosong maka ambil ke API data wp();Kemudian masukan
						$insert = $this->Crud->insert_data('mst_wajibpajak', $wp);
						$idwp = $this->db->insert_id();
					} else {
						$idwp  = $datawp[0]->id;
					}
					$idrek = $item['idrekening']["id"];
					$nomor =  $item['nomor'];
					$bulan =  $item['bulan'];
					$tahun =  $item['tahun'];

					if ($this->MformSptpd->isExists($nomor, $idwp, $bulan, $tahun)) {
						$response = array('success' => false, 'message' => 'Sudah pernah di masukan!');
						header('Content-Type: application/json');
						echo json_encode($response);
						return;
					}
					$data = [
						'nomor' => $nomor,
						// 'tanggal' => $this->input->post('tanggals'),
						'idwp' => $idwp,
						'idrekening' => $idrek,
						'blnpajak' => $bulan,
						'thnpajak' => $tahun,
						'jumlah' =>  $item['jumlah'],
						'pokok' =>  $item['pokok'],
						'denda' =>  $item['denda'],
						'tgl_input' =>  $item['tgl_input'],
						'keterangan' => $item['keterangan'],
					];
					$insert = $this->Crud->insert_data('trx_sptpd', $data);
					if ($insert) {
						$response = array('success' => true, 'message' => 'Data berhasil disimpan');
					} else {
						$response = array('success' => false, 'message' => 'Gagal menyimpan data');
					}
					header('Content-Type: application/json');
					echo json_encode($response);
					return;
				}
				break;
			case 'Edit':
				$kode = $this->input->post('kode');
				$data = [
					'nomor' => $this->input->post('nomors'),
					// 'tanggal' => $this->input->post('tanggals'),
					'idwp' => $this->input->post('idwps'),
					'idrekening' => $this->input->post('idreks'),
					'blnpajak' => $this->input->post('bulans'),
					'thnpajak' => $this->input->post('tahuns'),
					'jumlah' => $this->input->post('jumlahs'),
					'keterangan' => $this->input->post('keterangans'),
					'pokok' => $this->input->post('pokoks'),
					'denda' => $this->input->post('dendas'),
					'tgl_input' => $this->input->post('tglterbits'),
					'keterangan' => $this->input->post('keterangans'),
				];
				$update = $this->Crud->update_data('trx_sptpd', $data, ['id' => $kode]);
				if ($update) {
					$this->session->set_flashdata('message', 'Data has been updated successfully');
					redirect('transaksi/FormSptpd');
				} else {
					$this->session->set_flashdata('message', 'Failed to update data');
					redirect('transaksi/FormSptpd');
				}
				break;
			case 'Delete':
				$kode = $this->input->post('kode');
				$delete = $this->Crud->delete_data('trx_sptpd', ['id' => $kode]);
				if ($delete) {
					$this->session->set_flashdata('message', 'Data has been deleted successfully');
					redirect('transaksi/FormSptpd');
				} else {
					$this->session->set_flashdata('message', 'Failed to delete data');
					redirect('transaksi/FormSptpd');
				}
				break;
			default:
				header('location:' . site_url('404'));
				break;
		}
	}



	public function getAPisptpd()
	{
		$tanggal = $this->input->post("tanggals");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$ch = curl_init('http://103.140.188.162:7073/GetSPTPD?tanggal=' . $tanggal);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_HTTPGET, true);

		$result = curl_exec($ch);
		curl_close($ch);
		$response_object = json_decode($result);
		if (json_last_error() === JSON_ERROR_NONE) {
			// Loop through the data
			$data = array();
			$no   = 1;
			foreach ($response_object->data  as $row) {
				$sub_array = array();
				$sub_array[] = '<input type="checkbox" name="check[]" value="' . $row->npwpd . '|' . $row->namaop . '">';
				$sub_array[] = $no++;
				// $sub_array[] = '<input type="checkbox" name="check[]" value="' . $row->NoSSPD . '">';
				$sub_array[] = $row->NoSSPD;
				$sub_array[] = date('Y-m-d', strtotime($row->TglBayarSSPD));
				$sub_array[] = $row->namawp;
				$sub_array[] = $row->npwpd;
				$sub_array[] = $row->nop;
				$sub_array[] = $row->namaop;
				$sub_array[] = $row->namarek;
				$sub_array[] = $row->masapajak;
				$sub_array[] = $row->tahunpajak;
				$sub_array[] = $row->jumlahbayar;
				$sub_array[] = $row->denda;
				$sub_array[] = $row->nosptpd;
				$sub_array[] = $row->totalbayar;
				$sub_array[] = ($row->statusbayar == 1 ? "Lunas" : "Belum Lunas");
				$sub_array[] = $row->jenispajak;

				$data[] = $sub_array;
			}
			// Total records
			$recordsTotal = count($data);

			// Apply pagination
			$data = array_slice($data, $start, $length);

			$output = array(
				"draw" => intval($_POST["draw"]),
				"recordsTotal" => $recordsTotal,
				"recordsFiltered" => $recordsTotal,
				"data" => $data
			);
			echo json_encode($output);
		}
		// var_dump($result);
		// die;
	}

	public function getAPiWajibPajak($nop, $npwpd, $jnspajak)
	{
		$nop = urlencode($nop);
		$npwpd = urlencode($npwpd);
		$jnspajak = urlencode($jnspajak);

		$url = 'http://103.140.188.162:7073/GetDataWp?nop=' . $nop . '&npwpd=' . $npwpd . '&type=' . $jnspajak;

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_HTTPGET, true);
		$result = curl_exec($ch);
		curl_close($ch);
		$response_object = json_decode($result);
		if (json_last_error() !== JSON_ERROR_NONE) {
			// Log JSON decoding error
			log_message('error', 'JSON Decode Error: ' . json_last_error_msg());
			return array();
		}
		$datas = array();

		if (isset($response_object->data) && !empty($response_object->data)) {
			foreach ($response_object->data as $row) {
				$data = array();
				$data[] = $row->namawp;
				$datas[] = $data;
			}
		} else {
			log_message('error', 'No data found in API response');
		}

		return $response_object->data[0];
	}
}
