<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mpiutangrek extends CI_Model {
    /* public function getRptBBPPKDinas($iddinas, $tahun, $bulan) {
        $query = $this->db->query("CALL spRptBBPPKDinas(?, ?, ?)", array($iddinas, $tahun, $bulan));
        return $query->result_array();
    } */


    public function ambilpiutangrek($tahun)
    {
        $this->db->select("
            $tahun AS tahun,
            b.tglskp AS tanggal,
            b.nomor AS noskpd,
            b.nama AS nmwp,
            a.teks,
            b.tgljthtmp,
            a.jumlah,
            a.bunga,
            a.total,
            a.keterangan
        ");
        
        $this->db->from('trx_skpdreklame a');
        $this->db->join('mst_wajibpajak b', 'b.id = a.idwp', 'inner');
        $this->db->where('YEAR(b.tglskp) <=', $tahun);
        $this->db->group_start(); 
        $this->db->where('YEAR(a.tglbayar) >', $tahun);
        $this->db->or_where('a.tglbayar IS NULL');
        $this->db->group_end();
        $this->db->where('a.isdispen', 0);
        $this->db->order_by('b.tglskp', 'asc');

        $query = $this->db->get();
        return $query->result_array();
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
                <form action="' . site_url('pembukuanskpd/PiutangReklame/cetak') . '" class="form-row" method="post">
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

               
                    <div class="col-md-1 mt-3">
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
