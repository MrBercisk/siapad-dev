<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mlapdispen extends CI_Model {
    /* public function getRptBBPPKDinas($iddinas, $tahun, $bulan) {
        $query = $this->db->query("CALL spRptBBPPKDinas(?, ?, ?)", array($iddinas, $tahun, $bulan));
        return $query->result_array();
    } */

    public function get_apbd_apbdp_total($tahun, $iddinas) {
        $this->db->select('SUM(apbd) as total_apbd, SUM(apbdp) as total_apbdp');
        $this->db->from('trx_rapbd');
        $this->db->where('tahun', $tahun);
        $this->db->where('iddinas', $iddinas);
        $query = $this->db->get();
        return $query->row();
    }

    public function ambildispenskpd($tahun)
    {
         $this->db->select("
                $tahun AS tahun,
                c.nomor AS noskpd,
                c.tglskp AS tglskpd,
                CONCAT(c.nama, ' - ', b.teks) AS uraian,
                CONCAT(b.blnpajak,'-', b.thnpajak) AS masapajak,
                a.jumlah, 
                a.keterangan
              
            ");
            $this->db->from('trx_dispensasi_skpd a');
            $this->db->join('trx_skpdreklame b', 'b.id = a.idskpdrek','inner');
            $this->db->join('mst_wajibpajak c', 'c.id = b.idwp','inner');
            $this->db->where('YEAR(c.tglskp)', $tahun);
            $this->db->order_by('c.tglskp');
    
        $query = $this->db->get_compiled_select();
        $result = $this->db->query($query);
        return $result->result_array();
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
        $dinData = $this->db
        ->select('mst_dinas.id, mst_dinas.nama')
        ->from('mst_dinas')
        ->get()
        ->result();
        $opsiDinas = '<option></option>';
        foreach ($dinData as $ttd) {
            $opsiDinas .= '<option value="'.$ttd->id.'">'.$ttd->nama.'</option>';
        }
        $form[] = '
        
        <div class="card">
            <div class="card-body">
                <form action="' . site_url('pembukuanskpd/LapDispenSkpd/cetak') . '" class="form-row" method="post">
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
                                <select id="tanda_tangan" name="tanda_tangan" class="form-control select2" data-placeholder="Pilih Tanda Tangan" style="width: 100%;" required>
                                        '.$opsittd.'
                                </select>
                            </div>
                        </div>
                   
                    </div>
                </div>

                   
                    <div class="col-md-2 mt-4">
                        <div class="button-group">
                            <button type="submit" class="btn btn-primary">Cetak Laporan</button>
                        </div>
                    </div>
                    </div>
                </form>
            </div>
        </div>';
        return $form;
    }

}
