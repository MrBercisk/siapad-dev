<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mlapbulanan extends CI_Model {
    /* public function getRptBBPPKDinas($iddinas, $tahun, $bulan) {
        $query = $this->db->query("CALL spRptBBPPKDinas(?, ?, ?)", array($iddinas, $tahun, $bulan));
        return $query->result_array();
    } */

    public function get_saldo_sudahbayar($tahun, $bulan)
	{
        if ($bulan == 1) {
            return 0;
        }
		$this->db->select('SUM(a.total) as saldo');
		$this->db->from('trx_skpdreklame a');
		$this->db->join('mst_wajibpajak b', 'b.id = a.idwp', 'inner');
		$this->db->where('MONTH(b.tglskp) <', $bulan);
        $this->db->where('YEAR(b.tglskp)', $tahun);
		$this->db->where('a.isbayar', 1);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->row()->saldo;
		} else {
			return 0;
		}
	}
    public function get_saldo_semua($tahun, $bulan)
	{
        if ($bulan == 1) {
            return 0;
        }
		$this->db->select('SUM(a.total) as saldo');
		$this->db->from('trx_skpdreklame a');
		$this->db->join('mst_wajibpajak b', 'b.id = a.idwp', 'inner');
		$this->db->where('MONTH(b.tglskp) <', $bulan);
        $this->db->where('YEAR(b.tglskp)', $tahun);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->row()->saldo;
		} else {
			return 0;
		}
	}

    public function ambilskpdbulanan($bulan, $tahun, $tipe)
    {
        if ($tipe == 'S') {
            $this->db->select("
               $tahun AS tahun,
                b.nomor AS noskpd,
                CASE 
                    WHEN LENGTH(LEFT(b.nomor, INSTR(b.nomor, '/')-1)) = 2 
                    THEN CONCAT('0', b.nomor) 
                    ELSE b.nomor 
                END AS nourut,
                b.nama AS nmwp,
                a.teks,
                CONCAT(a.blnpajak, '-', a.thnpajak) AS masapajak,
                b.tgljthtmp,
                a.jumlah,
                a.bunga,
                a.total,
                a.tglbayar,
                a.blnpajak,
                a.thnpajak,
                b.tglskp AS tglskpd
            ");
            $this->db->from('trx_skpdreklame a');
            $this->db->join('mst_wajibpajak b', 'b.id = a.idwp', 'inner');
            $this->db->where('MONTH(b.tglskp)', $bulan);
            $this->db->where('YEAR(b.tglskp)', $tahun);
            $this->db->where('a.isbayar',1 );
            $this->db->order_by('b.tglskp', 'asc');
    
        } elseif ($tipe == 'A') {
            $this->db->select("
                b.nomor AS noskpd,
                CASE 
                    WHEN LENGTH(LEFT(b.nomor, INSTR(b.nomor, '/')-1)) = 2 
                    THEN CONCAT('0', b.nomor) 
                    ELSE b.nomor 
                END AS nourut,
                b.tglskp AS tglskpd,
                b.nama AS nmwp,
                a.teks,
                CONCAT(LPAD(MONTH(b.tglskp), 2, '0'), '-', YEAR(b.tglskp)) AS masapajak,
                b.tgljthtmp,
                a.jumlah,
                a.bunga,
                a.total,
                a.tglbayar,
                a.blnpajak,
                a.thnpajak
            ");
            $this->db->from('trx_skpdreklame a');
            $this->db->join('mst_wajibpajak b', 'b.id = a.idwp');
            $this->db->where('MONTH(b.tglskp)', $bulan);
            $this->db->where('YEAR(b.tglskp)', $tahun);
            $this->db->group_by('b.nomor');
            $this->db->order_by('b.tglskp');
    
        } elseif ($tipe == 'B') {
            $this->db->select("
                0 AS saldo,
                b.nomor AS noskpd,
                CASE 
                    WHEN LENGTH(LEFT(b.nomor, INSTR(b.nomor, '/')-1)) = 2 
                    THEN CONCAT('0', b.nomor) 
                    ELSE b.nomor 
                END AS nourut,
                b.tglskp AS tglskpd,
                b.nama AS nmwp,
                a.teks,
                CONCAT(a.blnpajak, '-', a.thnpajak) AS masapajak,
                b.tgljthtmp,
                a.jumlah,
                a.bunga,
                a.total,
                a.tglbayar,
                a.blnpajak,
                a.thnpajak
            ");
            $this->db->from('trx_skpdreklame a');
            $this->db->join('mst_wajibpajak b', 'b.id = a.idwp');
            $this->db->where('a.isbayar', 0);
            $this->db->order_by('b.tglskp');
    
        } else {
            return [];
        }
    
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
                <form action="' . site_url('pembukuanskpd/LapBulananSkpd/cetak') . '" class="form-row" method="post">
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
                    <div class="col-md-4">
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

                        <script>
                            document.getElementById("tahun").value = new Date().getFullYear();
                        </script>
        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tgl_cetak">Tgl. Cetak:</label>
                                <input type="date" class="form-control" id="tgl_cetak" name="tgl_cetak" required>
                            </div>
                        </div>

                       	 <div class="col-md-6">
                            <div class="form-group">
                                <label for="ttd">Tanda Tangan:</label>
                                <select id="tanda_tangan" name="tanda_tangan" class="form-control select2" data-placeholder="Pilih Tanda Tangan" style="width: 100%;" required>
                                        '.$opsittd.'
                                </select>
                            </div>
                        </div>
                   
                    </div>
                </div>

                    <div class="col-md-2">
                        <label class="label mt-2" for="label"><b>Bayar</b></label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="sudah" name="tipe" value="S" checked>
                                <label class="form-check-label" for="sudah">Sudah Bayar</label>
                            </div>       
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="belum" name="tipe" value="B">
                                <label class="form-check-label" for="belum">Belum Bayar</label>
                            </div>     
                             <div class="form-check">
                                <input type="radio" class="form-check-input" id="semua" name="tipe" value="A">
                                <label class="form-check-label" for="semua">Semua Data</label>
                            </div>   
                      
                    </div>
               
                    <div class="col-md-1">
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
