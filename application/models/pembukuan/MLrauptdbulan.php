<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MLrauptdbulan extends CI_Model {
    public function ambildata($bulan, $tahun) {
        $mysqli = $this->db->conn_id; 
 
        $statment = $mysqli->prepare("CALL spRptLRAUPTDBulanan(?, ?)");
        $statment->bind_param('ss', $bulan, $tahun);  
    
        $statment->execute();
        $result = $statment->get_result();  
    
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        while ($mysqli->more_results()) {
            $mysqli->next_result(); 
        }
    
        return $data;
   
    }


    public function formInsert() {
        $ttddata = $this->db
        ->select('mst_tandatangan.id, mst_tandatangan.nip, mst_tandatangan.nama, mst_tandatangan.jabatan1, mst_tandatangan.jabatan2')
        ->from('mst_tandatangan')
        ->get()
        ->result();
        $opsittd = '<option></option>';
        foreach ($ttddata as $ttd) {
            $opsittd .= '<option value="'.$ttd->id.'">'.$ttd->nama.'</option>';
        }
        $rekdata = $this->db
            ->select('mst_rekening.id, mst_rekening.kdrekening, mst_rekening.nmrekening, mst_rekening.islrauptd')
            ->from('mst_rekening')
            ->where('mst_rekening.tipe', "D")
            ->where("(kdrekening LIKE '4.1.1.01%' 
                    OR kdrekening LIKE '4.1.1.02%' 
                    OR kdrekening LIKE '4.1.1.03%' 
                    OR kdrekening LIKE '4.1.1.07%')")
            ->get()
            ->result();

        $opsirek = '<option></option>';
        foreach ($rekdata as $ttd) {
            $opsirek .= '<option value="'.$ttd->kdrekening.'">'.$ttd->nmrekening.'</option>';
        }

        $form[] = '
        
        <div class="card">
            <div class="card-body">
                <form id="reportForm" action="' . site_url('pembukuan/Lrauptdbulan/cetak') . '" class="form-row" method="post" onsubmit="printForm(); return false;">
                <div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;">
                        <h5>Parameters</h5>
                </div>
                <div class="col-md-12">
                    <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tahun">Tahun:</label>
                            <input type="number" class="form-control" id="tahun" name="tahun" min="1900" max="9999" value="2024" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bulan">Bulan:</label>
                            <select class="form-control select2" id="bulan" name="bulan" required>
                                <option value="" disabled selected>Pilih Bulan</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ttd">Tanda Tangan:</label>
                              <select id="tanda_tangan" name="tanda_tangan" class="form-control select2" data-placeholder="Pilih Tanda Tangan" style="width: 100%;">
                                      '.$opsittd.'
                              </select>
                        </div>
                    </div>
                
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tgl_cetak">Tgl. Cetak:</label>
                            <input type="date" class="form-control" id="tglcetak" name="tglcetak" required>
                        </div>
                    </div>

               
    
                    </div>
                </div>
                
                    <div class="col-md-1">
                            <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="apbdp_checkbox" name="apbdp_checkbox" >
                            <label class="form-check-label" for="apbdp">APBDP</label>
                        </div>
                       <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="wpajak_checkbox" name="wp" >
                            <label class="form-check-label" for="wpajak">W.Pajak</label>
                        </div>
                        <div class="button-group">
                            <button type="submit" class="btn btn-primary">Cetak Laporan</button>
                        </div>
                    </div>
                </form>
                    
                <div id="loadingSpinner" style="display:none; text-align:center; margin-top: 20px;">
                <img src="' . base_url('/assets/img/load2.gif') . '" alt="Loading..." />
                 <p>Silahkan tunggu...</p>
            </div>
            </div>
        </div>
        <style>
        #loadingSpinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            text-align: center;
            width: 200px;
        }
        #loadingSpinner img {
            width: 120px; 
            height: 120px; 
        }
        #loadingSpinner p {
            margin-top: 20px;
            font-size: 16px;
            color: black; 
        }
    </style>

    <script>
       function printForm() {
        var form = document.getElementById("reportForm");
        var formData = new FormData(form);
        var loadingSpinner = document.getElementById("loadingSpinner");
        var printWindow;

        loadingSpinner.style.display = "block";

        var xhr = new XMLHttpRequest();
        xhr.open("POST", form.action, true);
        xhr.onload = function () {
            loadingSpinner.style.display = "none";

            if (xhr.status === 200) {
                if (printWindow && !printWindow.closed) {
                    printWindow.focus();
                    printWindow.document.open();
                    printWindow.document.write(xhr.responseText);
                    printWindow.document.close();
                } else {
                    printWindow = window.open("", "", "width=800,height=600");
                    printWindow.document.open();
                    printWindow.document.write("<html><head><title>SIAPAD - Lra Bulanan UPTD Bapenda</title>");
                    printWindow.document.write("<style>body{font-family:Arial,sans-serif; padding: 20px;} table{width: 100%; border-collapse: collapse;} th, td{border: 1px solid black; padding: 8px; text-align: left;}</style>");
                    printWindow.document.write("</head><body>");
                    printWindow.document.write(xhr.responseText);
                    printWindow.document.close();
                    printWindow.focus();
                    printWindow.print();
                }
            } else {
                alert("An error occurred during the request.");
            }
        };
        xhr.send(formData);
    }
    </script>
        ';
        return $form;
    }

    public function iniopsirekening() {
        $rekeningCumaIni = array(
            '4.1.1.01' => 'Pajak Hotel',
            '4.1.1.02' => 'Pajak Restoran',
            '4.1.1.03' => 'Pajak Hiburan',
            '4.1.1.04' => 'Pajak Reklame',
            '4.1.1.05' => 'Pajak Penerangan Jalan',
            '4.1.1.07' => 'Pajak Parkir',
            '4.1.1.08' => 'Pajak Air Tanah',
            '4.1.1.11' => 'Pajak Mineral Batuan Bukan Logam',
            '4.1.1.12' => 'Pajak Bumi dan Bangunan Pedesaan dan Perkotaan',
            '4.1.1.13' => 'Bea Perolehan Hak Atas Tanah dan Bangunan',
        );

        $rekData = $this->db
            ->select('mst_rekening.id, mst_rekening.kdrekening')
            ->from('mst_rekening')
            ->where_in('kdrekening', array_keys($rekeningCumaIni))
            ->get()
            ->result();
    
        $opsiRek = '<option></option>';
        foreach ($rekData as $rek) {
            $namaRek = isset($rekeningCumaIni[$rek->kdrekening]) ? $rekeningCumaIni[$rek->kdrekening] : $rek->kdrekening;
            $opsiRek .= '<option value="'.$rek->kdrekening.'">'.$namaRek.'</option>';
        }
    
        return $opsiRek;
    }
    
}
