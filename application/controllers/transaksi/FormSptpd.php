<?php defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;

setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

class FormSptpd extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
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
				'modalEdit'   => $this->Form->modalKu('E', 'Edit', 'transaksi/formsptpd/aksi', ['edit']),
				'modalDelete' => $this->Form->modalKu('D', 'Delete', 'transaksi/formsptpd/aksi', ['delete']),
				'sidebar'     => $template['sidebar'],
				'jstable'     => $Jssetup->jsDatatable3('#ftf', 'transaksi/formsptpd/getDinas'),
				'jsedit'      => $Jssetup->jsModal('#edit', 'Edit', 'transaksi/formsptpd/myModal', '#modalkuE'),
				'jsdelete'    => $Jssetup->jsModal('#delete', 'Delete', 'transaksi/formsptpd/myModal', '#modalkuD'),
				'forminsert'  => implode('', $this->MformSptpd->formInsert()),
				'datepick'  => $this->MformSptpd->datepicker(),
				'filters'  => implode('', $this->MformSptpd->filters())
			];
		$this->load->view('transaksi/formsptpd', $data);
	}
	public function getDinas()
	{
		$this->load->model('instrument/Status');
		$status = $this->Status;
		// die;
		$nomor = $this->input->post('nomor');
		$rekening = $this->input->post('rekening');
		$npwpd = $this->input->post('npwpd');
		$nop = $this->input->post('nop');
		$wajibpajak = $this->input->post('wajibpajak');
		$tahun = $this->input->post('tahun');



		$datatables = $this->Datatables;
		$datatables->setTable("trx_sptpd a");
		$datatables->setSelectColumn([
			"a.id",
			"a.nomor",
			"a.tanggal",
			"a.tgl_input AS tanggal_terbit",
			"b.nomor AS npwpd",
			"a.idwp",
			"b.nama AS nmwp",
			"a.blnpajak",
			"a.thnpajak",
			"a.jumlah",
			"a.keterangan",
			"a.idrekening",
			"c.nmrekening",
			"a.isskpdn",
			"a.noskpdn",
			"a.tglskpdn",
			"a.jmlskpdn",
			"a.denda",
			"a.pokok"
		]);
		$datatables->addJoin('mst_wajibpajak b', 'b.id = a.idwp',  'inner');
		$datatables->addJoin('mst_rekening c', '`c`.`id` = `a`.`idrekening` ',  'inner');
		$datatables->setOrderColumn([null, "`a`.`isskpdn`", "`a`.`id`", "a.tgl_input", "ASC"]);
		// $datatables->setSearchColumns(["a.nomor", "b.nama", "noskpdn"]);
		if (!empty($nomor)) {
			$datatables->addLike("a.nomor", $nomor, "after");
		}
		if (!empty($rekening)) {
			$datatables->addLike("c.nmrekening", $rekening);
		}
		if (!empty($npwpd)) {
			$datatables->addWhere("b.nomor", $npwpd);
		}

		if (!empty($wajibpajak)) {
			$datatables->addLike("b.id", $wajibpajak);
		}
		if (!empty($tahun)) {
			$datatables->addWhere("a.thnpajak", $tahun);
		}

		if (empty($wajibpajak) && empty($npwpd) && empty($rekening) && empty($nomor) && empty($tahun)) {

			$data = [];
			$output = array(
				"draw" 				=> intval($_POST["draw"]),
				"recordsTotal" 		=> 0,
				"recordsFiltered" 	=> 0,
				"data" 				=> $data
			);
			echo json_encode($output);
			die;
		}


		$fetch_data = $this->Datatables->make_datatables();
		// $this->Datatables->get_all_datas($nomor, $npwpd, $rekening, $wajibpajak, $tahun);
		$data = array();
		$no   = 1;
		foreach ($fetch_data as $row) {
			$sub_array = array();
			$sub_array[] = $no++;
			$sub_array[] = $row->nomor;
			$sub_array[] = $row->tanggal;
			$sub_array[] = $row->tanggal_terbit;
			$sub_array[] = $row->nmwp;
			$sub_array[] = $row->npwpd;
			$sub_array[] = $row->nmrekening;
			$sub_array[] = $row->blnpajak;
			$sub_array[] = $row->thnpajak;
			$sub_array[] = $row->pokok;
			$sub_array[] = $row->denda;
			$sub_array[] = $row->jumlah;
			$sub_array[] = $row->keterangan;

			$sub_array[] = implode('', $this->Datatables->tombol($row->id));
			$data[] = $sub_array;
		}
		$output = array(
			"draw" 				=> intval($_POST["draw"]),
			"recordsTotal" 		=> $this->Datatables->get_all_datas($nomor, $npwpd, $rekening, $wajibpajak, $tahun),
			"recordsFiltered" 	=> $this->Datatables->get_filtered_datas($nomor, $npwpd, $rekening, $wajibpajak, $tahun),
			"data" 				=> $data
		);
		echo json_encode($output);
	}
	public function myModal()
	{
		$wadi = isset($_POST['WADI']) ? $_POST['WADI'] : header('location:' . site_url('404'));
		switch ($wadi) {
			case 'Edit':

				$joinTables  		= [
					'mst_wajibpajak b' => ['condition' => 'b.id = a.idwp', 'type' => 'inner'],
					'mst_rekening c' => ['condition' => 'c.id = a.idrekening', 'type' => 'inner']
				];
				// $selectFields 		= 'id, nama, singkat, urut, isdispenda';
				$selectFields 		= 'a.id,a.nomor,a.tanggal,a.tgl_input AS tanggal_terbit,b.npwpd AS npwpd,a.idwp,b.nama AS nmwp,b.nop,a.blnpajak,a.thnpajak,a.jumlah,a.keterangan,a.idrekening,c.nmrekening,a.isskpdn,a.noskpdn,a.tglskpdn,a.jmlskpdn,a.denda,a.pokok';
				$kode 				= $this->Crud->gandengan('trx_sptpd a', $joinTables, $selectFields, 'a.id="' . $this->input->post('idnya') . '"')[0];
				if ($kode->idwp == 0 || empty($kode->idwp)) {
					$kode->idwp = 0;
				}
				$milehTahun = $this->Jstambah->milehTahun((int)date($kode->thnpajak));
				$milehSasi = $this->Jstambah->milehBulan((int)date($kode->blnpajak));
				$form[] 	= '
				<script>
				var selectedWp = ' . json_encode($kode) . ';
    
				if (selectedWp) {
					var option = new Option(selectedWp.nmwp + " (" + selectedWp.npwpd + ")", selectedWp.idwp, true, true);
					$("#wajibpajake").append(option).trigger("change");
				}
				$("#wajibpajake").select2({
					ajax: {
						url: "http://localhost/siapad-dev/transaksi/formsptpd/get_data",
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
					placeholder:"Masukan Nama Wajib Pajak"
				})
				</script>

				<div class="row">
				<div class="col-12">'
					. implode($this->Form->inputRowsText('nomore', 'Nomor Biling', 'col-sm-3', 'form-control-sm', $kode->nomor)) .
					'</div>
				<div class="col-12">'
					. implode($this->Form->inputRowsText('rekeninge', 'Rekening', 'col-sm-3', 'form-control-sm', $kode->nmrekening, 'readonly')) .
					'</div>
				<div class="col-12">'
					. implode($this->Form->inputRowsText('npwpde', 'NPWPD', 'col-sm-3', 'form-control-sm', $kode->npwpd, 'readonly')) .
					'</div>
				<div class="col-12">'
					. implode($this->Form->inputRowsText('nope', 'NOP', 'col-sm-3', 'form-control-sm', $kode->nop, 'readonly')) .
					'</div>
				<div class="col-12">'
					. implode($this->Form->inputRowsSelect('wajibpajake', 'Wajib Pajak', 'col-sm-3', 'form-control-sm select2', $kode->idwp)) .
					'</div>
				<div class="col-12">'
					. implode($this->Form->inputRowsText('tglterbite', 'Tgl. Terbit', 'col-sm-3', 'form-control-sm datepicker', $kode->tanggal_terbit, 'readonly')) .
					'</div>
			<div class="col-12">'
					. implode($this->Form->inputRowsSelect('bulane', 'Bulan', 'col-sm-3', 'form-control-sm', $milehSasi)) .
					'</div>
			<div class="col-12">'
					. implode($this->Form->inputRowsSelect('tahune', 'Tahun', 'col-sm-3', 'form-control-sm', $milehTahun)) .
					'</div>
			<div class="col-12">'
					. implode($this->Form->inputRowsText('pokoke', 'Pokok', 'col-sm-3', 'form-control-sm', $kode->pokok)) .
					'</div>
			<div class="col-12">'
					. implode($this->Form->inputRowsText('dendae', 'Denda', 'col-sm-3', 'form-control-sm', $kode->denda)) .
					'</div>
			<div class="col-12">'
					. implode($this->Form->inputRowsText('jumlahe', 'Jumlah', 'col-sm-3', 'form-control-sm', $kode->jumlah)) .
					'</div>
			<div class="col-12">'
					. implode($this->Form->inputRowsTextArea('keterangane', 'Keterangan', 'col-sm-3', 'form-control-sm', $kode->keterangan)) .
					'</div>
				</div>
				';

				break;
			case 'Delete':
				$form[] = '
				<div class="row">
					<div class="col-md-12">
					' . implode($this->Form->hiddenText('kode', $this->input->post('idnya'))) . '
					Apakah kamu yakin ingin menghapus data ini ?
					</div>
				</div>';
				break;
			default:
				$form[] = 'NOTHING !!!';
				break;
		}
		echo implode('', $form);
	}
	public function aksi()
	{
		$aksi = isset($_POST['AKSI']) ? $_POST['AKSI'] : header('location:' . site_url('404'));
		$this->load->model('backend/Crud');
		switch ($aksi) {
			case 'Save':

				$nomor = $this->input->post('nomors');
				$idwp = $this->input->post('idwps');
				$bulan = $this->input->post('bulans');
				$tahun = $this->input->post('tahuns');

				if ($this->MformSptpd->isExists($nomor, $idwp, $bulan, $tahun)) {
					$this->session->set_flashdata('message', 'Failed to save data');
					redirect('transaksi/FormSptpd');
				}
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
				$insert = $this->Crud->insert_data('trx_sptpd', $data);
				if ($insert) {
					$this->session->set_flashdata('message', 'Data has been saved successfully');
					redirect('transaksi/FormSptpd');
				} else {
					$this->session->set_flashdata('message', 'Failed to save data');
					redirect('transaksi/FormSptpd');
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

	public function get_data()
	{
		$search = $this->input->post('search');
		$page = ($this->input->post('page') == 0) ? 1 : 1;
		$limit = 10;
		$offset = ($page - 1) * $limit;

		// Query to get the results
		$this->db->select('w.*, a.nmrekening,a.id as idrek');
		$this->db->from('mst_wajibpajak w');
		$this->db->join('mst_rekening a', 'a.id = w.idrekening', 'left');
		$this->db->like('w.nama', $search);
		$this->db->or_like('w.npwpd', $search);
		$this->db->limit($limit, $offset);
		$query = $this->db->get();

		$results = $query->result_array();


		// Query to get the total count
		$this->db->select('w.*, a.nmrekening');
		$this->db->from('mst_wajibpajak w');
		$this->db->join('mst_rekening a', 'a.id = w.idrekening', 'left');
		$this->db->like('w.nama', $search);
		$this->db->or_like('w.npwpd', $search);
		$total_count = $this->db->count_all_results();

		$more = ($offset + $limit) < $total_count;
		$data = [
			'items' => array_map(function ($row) {
				return ['id' => $row['id'], 'text' => $row['nama'], 'npwpd' => $row['npwpd'], 'rekening' => $row['nmrekening'], 'nop' => $row['nop'], 'idrek' => $row['idrek']];
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

	public function Cetak()
	{

		if ($this->input->server('REQUEST_METHOD') !== 'POST') {
			redirect('404');
		}
		$nomor = $this->input->post('nomor');
		$rekening = $this->input->post('rekening');
		$npwpd = $this->input->post('npwpd');
		$nop = $this->input->post('nop');
		$wajibpajak = $this->input->post('wajibpajak');
		$tahun = $this->input->post('tahun');
		/**
		 * cetak 
		 * penandatanggan n tanggal cetak
		 */
		$idttd = $this->input->post('ttd');
		$bulan = $this->input->post('bulan');
		$pajak = $this->input->post('jnspajak');
		$pembuat = $this->input->post('pembuat');
		$nip = $this->input->post('nip');
		$tglcetak = $this->input->post('tglcetak');



		$Jssetup 		  = $this->Jssetup;
		$base 			  = $this->Msetup->setup();
		$setpage 		  = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
		$template 		  = $this->Msetup->loadTemplate($setpage->title);
		$data['footer']   = $template['footer'];
		$data['title'] 	  = $setpage->title;
		$data['link'] 	  = $setpage->link;
		$data['topbar']   = $template['topbar'];
		$data['sidebar']  = $template['sidebar'];
		$data['jstable']  = '';
		$data['ttd']	  = $this->Msetup->detailTtds($idttd);
		$data['pembuat']	  = $pembuat;
		$data['nip']	  = $nip;
		$data['tglcetak']	  = $tglcetak;
		$data['tahun']	  = $tahun;
		$data['bulan']	  = $this->MformSptpd->getPajakBulanIndo($bulan);
		$data['pajak']	  = $this->MformSptpd->getPajakByKdRekening($pajak);
		$data['tablenya'] = $this->MformSptpd->getDataForm($nomor, $npwpd, $rekening, $wajibpajak, $tahun, $bulan, $pajak);

		ob_start();
		$html = $this->load->view('cetakan/cetakformsptpd', $data, true);
		echo ob_get_clean();

		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper("A4", 'landscape');
		$dompdf->render();
		$dompdf->stream("formsptpd.pdf", array("Attachment" => 0));
	}

	public function test()
	{
		// var_dump("sjdai");
		$this->load->view('test');
	}
}
