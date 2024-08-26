<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Mbukuuptdbap extends CI_Model {
    public function get_sts_data($tanggal, $iduptd)
    {
        $year = date('Y', strtotime($tanggal));
    
        $sql1 = "
            SELECT SUM(a.total) AS total
            FROM trx_stsdetail a
            INNER JOIN trx_stsmaster b ON b.id = a.idstsmaster
            INNER JOIN trx_rapbd c ON c.id = a.idrapbd
            INNER JOIN mst_rekening d ON d.id = c.idrekening
            INNER JOIN mst_wajibpajak e ON e.id = a.idwp
            WHERE b.tanggal < ?
            AND YEAR(b.tanggal) = ?
            AND a.iduptd = ?
            AND d.jenis <> 'BPHTB';
        ";
        $query1 = $this->db->query($sql1, array($tanggal, $year, $iduptd));
        $result1 = $query1->row_array();
        $jmllalu = $result1['total'] ?? 0;
    
        $sql2 = "
            SELECT a.*
            FROM trx_stsdetail a
            INNER JOIN trx_stsmaster b ON b.id = a.idstsmaster
            INNER JOIN trx_rapbd c ON c.id = a.idrapbd
            INNER JOIN mst_rekening d ON d.id = c.idrekening
            INNER JOIN mst_wajibpajak e ON e.id = a.idwp
            WHERE b.tanggal = ?
            AND YEAR(b.tanggal) = ?
            AND a.iduptd = ?
            AND d.jenis <> 'BPHTB';
        ";
        $query2 = $this->db->query($sql2, array($tanggal, $year, $iduptd));
        
        if ($query2->num_rows() > 0) {
            $sql3 = "
                SELECT 
                    ? AS tahun, 
                    b.nomor, 
                    d.kdrekview AS kdrekening, 
                    d.nmrekening, 
                    e.nama AS nmwp,
                    CASE 
                        WHEN (NOT a.tglpajak IS NULL AND a.tglpajak <> '') 
                        THEN CONCAT(a.tglpajak, '-', a.blnpajak, '-', a.thnpajak)
                        WHEN NOT a.blnpajak IS NULL 
                        THEN CONCAT(a.blnpajak, '-', a.thnpajak)
                        ELSE '-' 
                    END AS masapajak, 
                    a.total, 
                    a.keterangan, 
                    ? AS saldoawal, 
                    1 AS isexists
                FROM trx_stsdetail a
                INNER JOIN trx_stsmaster b ON b.id = a.idstsmaster
                INNER JOIN trx_rapbd c ON c.id = a.idrapbd
                INNER JOIN mst_rekening d ON d.id = c.idrekening
                INNER JOIN mst_wajibpajak e ON e.id = a.idwp
                WHERE b.tanggal = ?
                AND YEAR(b.tanggal) = ?
                AND a.iduptd = ?
                AND d.jenis <> 'BPHTB';
            ";
            $query3 = $this->db->query($sql3, array($year, $jmllalu, $tanggal, $year, $iduptd));
        } else {
            $sql3 = "
                SELECT 
                    ? AS tahun, 
                    NULL AS nomor, 
                    NULL AS kdrekening, 
                    NULL AS nmrekening, 
                    NULL AS nmwp,
                    NULL AS masapajak, 
                    0.00 AS total, 
                    NULL AS keterangan, 
                    ? AS saldoawal, 
                    0 AS isexists;
            ";
            $query3 = $this->db->query($sql3, array($year, $jmllalu));
        }
    
        $results = $query3->result_array();
        return $results;
    }
    
    public function ambildata($tanggal,$iduptd) {
        $mysqli = $this->db->conn_id; 
 
        $statment = $mysqli->prepare("CALL spRptBBPKUPTD(?, ?)");
        $statment->bind_param('ss', $tanggal,$iduptd);  
    
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
        $opsittd = '<option disabled selected>Pilih Tanda Tangan</option>';
        foreach ($ttddata as $ttd) {
            $opsittd .= '<option value="'.$ttd->id.'">'.$ttd->nama.'</option>';
        }
        $uptddata = $this->db
        ->select('mst_uptd.id, mst_uptd.nama')
        ->from('mst_uptd')
        ->get()
        ->result();
        $opsiuptd = '<option disabled selected>Pilih UPTD</option>';
        foreach ($uptddata as $uptd) {
            $opsiuptd .= '<option value="'.$uptd->id.'">'.$uptd->nama.'</option>';
        }
        $form[] = '
        <div class="card">
            <div class="card-body">
                <form id="reportForm"  action="' . site_url('pembukuan/bukuuptd/cetak') . '" class="form-row" method="post" onsubmit="printForm(); return false;">
                    <div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;">
                        <h5>Parameters</h5>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                        <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal:</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" required> 
                                </div>
                            </div>
                       
                            <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="uptd">UPTD:</label>
                                        <select id="iduptd" name="iduptd" class="form-control uptd" data-placeholder="Pilih UPTD" style="width: 100%;" required>
                                                '.$opsiuptd.'
                                        </select>
                                    </div>
                                </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tgl_cetak">Tgl. Cetak:</label>
                                    <input type="date" class="form-control" id="tglcetak" name="tglcetak" required>
                                </div>
                            </div>
                           
                            <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="ttd">Tanda Tangan:</label>
                                    <select id="tanda_tangan" name="tanda_tangan" class="form-control tanda_tangan select2" data-placeholder="Pilih Tanda Tangan" style="width: 100%;" required>
                                            '.$opsittd.'
                                    </select>
                                </div>
                            </div>
                         
                            
                        </div>
                    </div>
                    <div class="col-md-1 mt-3">
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
                    printWindow.document.write("<html><head><title>SIAPAD - Buku Kas UPTD Bapenda</title>");
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
    
}
