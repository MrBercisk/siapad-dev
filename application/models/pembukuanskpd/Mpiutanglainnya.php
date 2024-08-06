<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mpiutanglainnya extends CI_Model {
    public function ambildata($kdrekening, $tahun) {
        $mysqli = $this->db->conn_id; 
 
        $statment = $mysqli->prepare("CALL spRptHRHPiutang(?, ?)");
        $statment->bind_param('ss', $kdrekening, $tahun);  
    
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
    
    public function get_apbd_apbdp_total($tahun, $kdrekening) {
        $this->db->select('SUM(a.apbd) as total_apbd, SUM(a.apbdp) as total_apbdp');
        $this->db->from('trx_rapbd a');
        $this->db->join('mst_rekening b', 'b.id = a.idrekening', 'inner');
        $this->db->where('a.tahun', $tahun);
        $this->db->where('LEFT(b.kdrekening, LENGTH(' . $this->db->escape($kdrekening) . ')) =', $kdrekening);
        $query = $this->db->get();
        return $query->row();
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

        $opsiRek = $this->iniopsirekening();
        $form[] = '
        <div class="card">
            <div class="card-body">
                <form action="' . site_url('pembukuanskpd/piutanglainnya/cetak') . '" class="form-row" method="post" target="printFrame">
                    <div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;">
                        <h5>Parameters</h5>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tahun">Tahun:</label>
                                    <input type="number" class="form-control" id="tahun" name="tahun" min="1900" max="9999" value="2024" required>
                                </div>
                            </div>
    
                            <script>
                                document.getElementById("tahun").value = new Date().getFullYear();
                            </script>
    
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tgl_cetak">Tgl. Cetak:</label>
                                    <input type="date" class="form-control" id="tgl_cetak" name="tgl_cetak" required>
                                </div>
                            </div>
    
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ttd">Tanda Tangan:</label>
                                    <select id="tanda_tangan" name="tanda_tangan" class="form-control select2" data-placeholder="Pilih Tanda Tangan" style="width: 100%;">
                                        '.$opsittd.'
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dinas">Rekening:</label>
                                    <select id="kdrekening" name="kdrekening" class="form-control select2" data-placeholder="Pilih Rekening" style="width: 100%;">
                                        '.$opsiRek.'
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="col-md-2">
                        <label class="form-check-label" for="ttd">Penandatangan</label>
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="ttd_checkbox" name="ttd_checkbox">
                                <label class="form-check-label" for="ttd">Ttd</label>
                            </div>
                        
                        </div>
                    </div>
    
                    <div class="col-md-1">
                        <div class="button-group">
                          <button type="submit" class="btn btn-primary">Cetak Laporan</button>
                           
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script>
            function printForm() {
                var form = document.getElementById("reportForm");
                var formData = new FormData(form);
                
                var xhr = new XMLHttpRequest();
                xhr.open("POST", form.action, true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var printWindow = window.open("", "", "width=800,height=600");
                        printWindow.document.open();
                        printWindow.document.write("<html><head><title>Print Preview</title>");
                        printWindow.document.write("<style>body{font-family:Arial,sans-serif; padding: 20px;} table{width: 100%; border-collapse: collapse;} th, td{border: 1px solid black; padding: 8px; text-align: left;}</style>");
                        printWindow.document.write("</head><body>");
                        printWindow.document.write(xhr.responseText);
                        printWindow.document.write("</body></html>");
                        printWindow.document.close();
                        printWindow.focus();
                        printWindow.print();
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
            '4.1.1.07' => 'Pajak Parkir',
            '4.1.1.08' => 'Pajak Air Tanah',
            '4.1.1.11' => 'Pajak Mineral Batuan Bukan Logam'
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
            $opsiRek .= '<option value="'.$rek->kdrekening.'">'.$rek->kdrekening.' - '.$namaRek.'</option>';
        }
    
        return $opsiRek;
    }
    
}
