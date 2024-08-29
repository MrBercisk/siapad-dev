<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

date_default_timezone_set("Asia/Jakarta");

class Rekonsptd extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('rekonsiliasi/MRekonsptd');
    }
	public function index()
	{	
		$Jssetup	= $this->Jssetup;
		$base 		= $this->Msetup->setup();
		$setpage	= $this->Msetup->get_title($base['halaman'].'/'.$base['fungsi']);
		$template 	= $this->Msetup->loadTemplate($setpage->title);
		$data['footer']		= $template['footer'];
		$data['title'] 		= $setpage->title;
		$data['link'] 		= $setpage->link;
		$data['topbar'] 	= $template['topbar'];
		$data['modalEdit'] 	= [];
		$data['modalDelete']= [];
		$data['sidebar'] 	= $template['sidebar'];
		$data['forminsert'] = implode($this->MRekonsptd->formInsert());
		$this->load->view('rekonsiliasi/rekonsptd',$data);
	}
	public function cetak() {
		if ($this->input->server('REQUEST_METHOD') !== 'POST') {
			redirect('404');
		}
	
		$base = $this->Msetup->setup();
		$setpage = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
		$template = $this->Msetup->loadTemplate($setpage->title);
	
		$tglcetak = $this->input->post('tglcetak');
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
		$kdrekening = $this->input->post('kdrekening');

		$tanda_tangan = $this->input->post('tanda_tangan');
		$ttd_checkbox = $this->input->post('ttd_checkbox') ? true : false;
	
		$tablenya = $this->MRekonsptd->ambildatasptd($tahun,$bulan,$kdrekening);
		/* echo '<pre>';
		var_dump($tablenya);
		die();
		echo '</pre>'; */
		
		$data = [
			'footer' => $template['footer'],
			'title' => $setpage->title,
			'link' => $setpage->link,
			'topbar' => $template['topbar'],
			'sidebar' => $template['sidebar'],
			'ttd_checkbox' => $ttd_checkbox,
			'format_tahun' => $tahun,
			'format_bulan' => strftime('%B', strtotime("$tahun-$bulan")),
			'tglcetak' => $tglcetak,
			'tablenya' => $tablenya,
			'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tglcetak)),
		];
		$tanda_tangan_data = $this->Msetup->get_tanda_tangan($ttd_checkbox, $tanda_tangan);

		if ($tanda_tangan_data) {
			$data['tanda_tangan'] = $tanda_tangan_data;
		}
		$rek_data = $this->Msetup->get_rekening($kdrekening);
	if ($rek_data) {
		$data['kdrekening'] = $rek_data;
	}
 
		/* $this->load->view('rekonsiliasi/printrekonsptpd', $data); */

		ob_start();
		$html = $this->load->view('rekonsiliasi/printrekonsptpd', $data, true);
		ob_get_clean();
		
	
		$dompdf = new Dompdf();
		$dompdf->set_option('isRemoteEnabled', true);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('legal', 'landscape');
		$dompdf->render();
		$dompdf->stream("rekonsptpd.pdf", array("Attachment" => 0));
	}
/* 	
	public function exportToExcel() {
		if ($this->input->server('REQUEST_METHOD') !== 'POST') {
			redirect('404');
		}
	
		$base = $this->Msetup->setup();
		$setpage = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
		$template = $this->Msetup->loadTemplate($setpage->title);
	
		$tglcetak = $this->input->post('tglcetak');
		$tahun = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
		$kdrekening = $this->input->post('kdrekening');
	
		$tablenya = $this->MRekonsptd->ambildatasptd($tahun, $bulan, $kdrekening);
	
		// Load PHPExcel library from thirdparty
		require_once APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
	
		// Create a new PHPExcel object
		$excel = new PHPExcel();
		$excel->getProperties()->setCreator("YourApp")
								->setLastModifiedBy("YourApp")
								->setTitle("Rekonsiliasi Report")
								->setSubject("Rekonsiliasi Report")
								->setDescription("Rekonsiliasi Report for the month.")
								->setKeywords("rekonsiliasi")
								->setCategory("Report");
	
		// Set header
		$sheet = $excel->setActiveSheetIndex(0);
		$sheet->setCellValue('A1', 'No');
		$sheet->setCellValue('B1', 'No Pelaporan');
		$sheet->setCellValue('C1', 'NPWPD');
		$sheet->setCellValue('D1', 'Nama WP');
		$sheet->setCellValue('E1', 'Thn Pajak');
		$sheet->setCellValue('F1', 'Bulan Pajak');
		$sheet->setCellValue('G1', 'Pokok');
		$sheet->setCellValue('H1', 'Denda');
		$sheet->setCellValue('I1', 'Total');
		$sheet->setCellValue('J1', 'Kode Bayar');
		$sheet->setCellValue('K1', 'Tgl Input');
		$sheet->setCellValue('L1', 'Status');
		$sheet->setCellValue('M1', 'Selisih Pokok');
		$sheet->setCellValue('N1', 'Selisih Denda');
		$sheet->setCellValue('O1', 'Selisih Total');
		$sheet->setCellValue('P1', 'Tgl Bayar');
		$sheet->setCellValue('Q1', 'Nama WP (2)');
		$sheet->setCellValue('R1', 'Namauptd');
		$sheet->setCellValue('S1', 'Blnpajak');
		$sheet->setCellValue('T1', 'SSPD');
		$sheet->setCellValue('U1', 'Pokok STS');
		$sheet->setCellValue('V1', 'Denda STS');
		$sheet->setCellValue('W1', 'Jumlah STS');
		$sheet->setCellValue('X1', 'Keterangan');
	
		// Fill data
		$rowNumber = 2; // Start in the second row
		$totalpokok = $totaldenda = $totalseluruh = 0;
		$totalpokoksts = $totaldendasts = $totalseluruhsts = 0;
		$totalselisihpokok = $totalselisihdenda = $totalselisihseluruh = 0;
		
		$baristanpdabeda = [];
		$v = [];
	
		foreach ($tablenya as $index => $row) {
			$selisihpokok = $row['pokok'] - $row['pokok_sts'];
			$selisihdenda = $row['denda'] - $row['denda_sts'];
			$selisihtotal = $row['total'] - $row['jumlah_sts'];
			
			$totalpokok += $row['pokok'];
			$totaldenda += $row['denda'];
			$totalseluruh += $row['total'];
	
			$totalpokoksts += $row['pokok_sts'];
			$totaldendasts += $row['denda_sts'];
			$totalseluruhsts += $row['jumlah_sts'];
	
			$totalselisihpokok = $totalpokok - $totalpokoksts;
			$totalselisihdenda = $totaldenda - $totaldendasts;
			$totalselisihseluruh = $totalseluruh - $totalseluruhsts;
	
			if ($selisihpokok != 0 || $selisihdenda != 0 || $selisihtotal != 0) {
				$v[] = $row;
			} else {
				$baristanpdabeda[] = $row;
			}
		}
	
		foreach ($baristanpdabeda as $row) {
			$sheet->setCellValue('A' . $rowNumber, $rowNumber - 1);
			$sheet->setCellValue('B' . $rowNumber, $row['nopelaporan']);
			$sheet->setCellValue('C' . $rowNumber, $row['npwpd']);
			$sheet->setCellValue('D' . $rowNumber, $row['namawp']);
			$sheet->setCellValue('E' . $rowNumber, $row['thnpajak']);
			$sheet->setCellValue('F' . $rowNumber, bulan_indonesia($row['masapajak']));
			$sheet->setCellValue('G' . $rowNumber, $row['pokok']);
			$sheet->setCellValue('H' . $rowNumber, $row['denda']);
			$sheet->setCellValue('I' . $rowNumber, $row['total']);
			$sheet->setCellValue('J' . $rowNumber, $row['kodebayar']);
			$sheet->setCellValue('K' . $rowNumber, $row['tgl_input']);
			$sheet->setCellValue('L' . $rowNumber, ($row['tgl_bayar'] == '0000-00-00' ? 'Belum Lunas' : 'Lunas'));
			$sheet->setCellValue('M' . $rowNumber, '-');
			$sheet->setCellValue('N' . $rowNumber, '-');
			$sheet->setCellValue('O' . $rowNumber, '-');
			$sheet->setCellValue('P' . $rowNumber, $row['tgl_bayar']);
			$sheet->setCellValue('Q' . $rowNumber, $row['namawp']);
			$sheet->setCellValue('R' . $rowNumber, $row['namauptd']);
			$sheet->setCellValue('S' . $rowNumber, $row['blnpajak'] . '-' . $row['thnpajak']);
			$sheet->setCellValue('T' . $rowNumber, $row['sspd']);
			$sheet->setCellValue('U' . $rowNumber, $row['pokok_sts']);
			$sheet->setCellValue('V' . $rowNumber, $row['denda_sts']);
			$sheet->setCellValue('W' . $rowNumber, $row['jumlah_sts']);
			$sheet->setCellValue('X' . $rowNumber, $row['keterangan']);
			$rowNumber++;
		}
	
		foreach ($v as $row) {
			$selisihpokok = $row['pokok'] - $row['pokok_sts'];
			$selisihdenda = $row['denda'] - $row['denda_sts'];
			$selisihtotal = $row['total'] - $row['jumlah_sts'];
			
			$sheet->getStyle('A' . $rowNumber . ':X' . $rowNumber)->applyFromArray([
				'fill' => [
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => ['rgb' => 'FFFF00']
				]
			]);
	
			$sheet->setCellValue('A' . $rowNumber, $rowNumber - 1);
			$sheet->setCellValue('B' . $rowNumber, $row['nopelaporan']);
			$sheet->setCellValue('C' . $rowNumber, $row['npwpd']);
			$sheet->setCellValue('D' . $rowNumber, $row['namawp']);
			$sheet->setCellValue('E' . $rowNumber, $row['thnpajak']);
			$sheet->setCellValue('F' . $rowNumber, bulan_indonesia($row['masapajak']));
			$sheet->setCellValue('G' . $rowNumber, $row['pokok']);
			$sheet->setCellValue('H' . $rowNumber, $row['denda']);
			$sheet->setCellValue('I' . $rowNumber, $row['total']);
			$sheet->setCellValue('J' . $rowNumber, $row['kodebayar']);
			$sheet->setCellValue('K' . $rowNumber, $row['tgl_input']);
			$sheet->setCellValue('L' . $rowNumber, ($row['tgl_bayar'] == '0000-00-00' ? 'Belum Lunas' : 'Lunas'));
			$sheet->setCellValue('M' . $rowNumber, $selisihpokok);
			$sheet->setCellValue('N' . $rowNumber, $selisihdenda);
			$sheet->setCellValue('O' . $rowNumber, $selisihtotal);
			$sheet->setCellValue('P' . $rowNumber, $row['tgl_bayar']);
			$sheet->setCellValue('Q' . $rowNumber, $row['namawp']);
			$sheet->setCellValue('R' . $rowNumber, $row['namauptd']);
			$sheet->setCellValue('S' . $rowNumber, $row['blnpajak'] . '-' . $row['thnpajak']);
			$sheet->setCellValue('T' . $rowNumber, $row['sspd']);
			$sheet->setCellValue('U' . $rowNumber, $row['pokok_sts']);
			$sheet->setCellValue('V' . $rowNumber, $row['denda_sts']);
			$sheet->setCellValue('W' . $rowNumber, $row['jumlah_sts']);
			$sheet->setCellValue('X' . $rowNumber, $row['keterangan']);
			$rowNumber++;
		}
	
		// Total row
		$sheet->setCellValue('A' . $rowNumber, 'TOTAL');
		$sheet->setCellValue('G' . $rowNumber, $totalpokok);
		$sheet->setCellValue('H' . $rowNumber, $totaldenda);
		$sheet->setCellValue('I' . $rowNumber, $totalseluruh);
		$sheet->setCellValue('U' . $rowNumber, $totalpokoksts);
		$sheet->setCellValue('V' . $rowNumber, $totaldendasts);
		$sheet->setCellValue('W' . $rowNumber, $totalseluruhsts);
		$sheet->setCellValue('M' . $rowNumber, $totalselisihpokok);
		$sheet->setCellValue('N' . $rowNumber, $totalselisihdenda);
		$sheet->setCellValue('O' . $rowNumber, $totalselisihseluruh);
	
		// Save the file
		$filename = 'rekonsiliasi_report_' . date('YmdHis') . '.xlsx';
		$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$writer->save(FCPATH . 'assets/excel/' . $filename);
	
		$this->output
			->set_content_type('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
			->set_header('Content-Disposition: attachment; filename="' . $filename . '"')
			->set_output(file_get_contents(FCPATH . 'assets/excel/' . $filename));
	
		// Delete the file after download
		unlink(FCPATH . 'assets/excel/' . $filename);
	}
	 */
	public function cetakexcel() {
		// Hanya menerima permintaan POST
		if ($this->input->server('REQUEST_METHOD') !== 'POST') {
			redirect('404');
		}
	
		// Mengambil pengaturan dan template
		$base = $this->Msetup->setup();
		$setpage = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
		$template = $this->Msetup->loadTemplate($setpage->title);
	
		// Mengambil data dari POST
		$tglcetak = $this->input->post('tglcetak');
		$tahun = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
		$kdrekening = $this->input->post('kdrekening');
		$tanda_tangan = $this->input->post('tanda_tangan');
		$ttd_checkbox = $this->input->post('ttd_checkbox') ? true : false;
	
		$tablenya = $this->MRekonsptd->ambildatasptd($tahun, $bulan, $kdrekening);
	
		$filename = "rekonsiliasi_" . date('Ymd') . ".xls";
	
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Cache-Control: max-age=0");

		$output = fopen("php://output", "w");

		fputcsv($output, ['Pemerintah Kota Bandar Lampung'], "\t");
		fputcsv($output, ['Badan Pendapatan Daerah'], "\t");
		fputcsv($output, ['Rekonsiliasi SSPD/STS dan SPTPD/STPD'], "\t");
		if (!empty($kdrekening)) {
			fputcsv($output, ['Kode Rekening: ' . $kdrekening['nmrekening']], "\t");
		}
	
		fputcsv($output, ['', ''], "\t");
	
		fputcsv($output, [
			'No', 
			'Tgl Transaksi', 
			'No SPTPD/STPD', 
			'Tgl SPTPD/STPD', 
			'Nama Wajib Pajak', 
			'Masa Pajak', 
			'SSPD/STS (RP)', 
			'SPTPD/STPD (RP)', 
			'Selisih', 
			'Keterangan'
		], "\t");
	
		if (!empty($tablenya)) {
			$no = 1;
	
			foreach ($tablenya as $row) {
				fputcsv($output, [
					$no++,
					date('d-m-Y', strtotime($row['tanggal'])),
					htmlspecialchars($row['nosptpd']),
					date('d-m-Y', strtotime($row['tglsptpd'])),
					htmlspecialchars($row['nmwp']),
					htmlspecialchars($row['masapajak']),
					number_format($row['jmlsts'], 2),
					number_format($row['jmlsptpd'], 2),
					number_format($row['selisih'], 2),
					htmlspecialchars($row['keterangan'])
				], "\t");
			}
		} else {
			fputcsv($output, ['Tidak Ada Data'], "\t");
		}
		if ($tanda_tangan && $ttd_checkbox) {
			fputcsv($output, ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''], "\t");
			fputcsv($output, ['Bandar Lampung, ' . strftime('%d %B %Y', strtotime($tglcetak))], "\t");
			fputcsv($output, ['Mengetahui,'], "\t");
			fputcsv($output, ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''], "\t");
			fputcsv($output, [$tanda_tangan], "\t");
		}
	
		fclose($output);
	
		exit();
	}
	
	
	
}

?>
		
