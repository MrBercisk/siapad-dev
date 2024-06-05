<?php defined('BASEPATH') OR exit('No direct script access allowed');
class LapBphtb extends CI_Model {
    private function get_saldo_awal($tahun, $bulan, $iduptd = null) {
        $this->db->select('SUM(a.total) as saldoawal')
                 ->from('trx_stsdetail a')
                 ->join('trx_stsmaster b', '
				 b.id = a.idstsmaster')
                 ->join('trx_rapbd c', 'c.id = a.idrapbd')
                 ->join('mst_rekening d', 'd.id = c.idrekening')
                 ->where('d.jenis', 'BPHTB')
                 ->where('MONTH(b.tanggal) <', $bulan)
                 ->where('b.tahun', $tahun);

        if ($iduptd !== null) {
            $this->db->where('a.iduptd', $iduptd);
        }

        $query = $this->db->get();
        $result = $query->row();
        return $result ? (float) $result->saldoawal : 0.00;
    }
    public function get_laporan_uptd($tahun, $bulan, $iduptd) {
        $saldoawal = $this->get_saldo_awal($tahun, $bulan, $iduptd);

        $this->db->select([
            'NULL AS nosspd',
            'NULL AS tanggal',
            'NULL AS nmwp',
            '0 AS total',
            "$saldoawal AS saldoawal"
        ]);
        $query1 = $this->db->get_compiled_select();
        $this->db->select([
            'a.nobukti AS nosspd',
            'b.tanggal',
            'c.nama AS nmwp',
            'a.total',
            "$saldoawal AS saldoawal"
        ])
        ->from('trx_stsdetail a')
        ->join('trx_stsmaster b', 'b.id = a.idstsmaster')
        ->join('mst_wajibpajak c', 'c.id = a.idwp')
        ->join('trx_rapbd d', 'd.id = a.idrapbd')
        ->join('mst_rekening e', 'e.id = d.idrekening')
        ->join('mst_uptd f', 'f.id = a.iduptd')
        ->where('e.jenis', 'BPHTB')
        ->where('a.iduptd', $iduptd)
        ->where('MONTH(b.tanggal)', $bulan)
        ->where('b.tahun', $tahun);
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query("$query1 UNION ALL $query2");
        return $query->result();
    }
    public function get_laporan_bulanan($tahun, $bulan) {
        $saldoawal = $this->get_saldo_awal($tahun, $bulan);

        $this->db->select([
            'NULL AS nmuptd',
            'NULL AS lembar',
            '0 AS total',
            "$saldoawal AS saldoawal"
        ]);
        $query1 = $this->db->get_compiled_select();
        $this->db->select([
            'f.nama AS nmuptd',
            'COUNT(*) AS lembar',
            'SUM(a.total) AS total',
            "$saldoawal AS saldoawal"
        ])
        ->from('trx_stsdetail a')
        ->join('trx_stsmaster b', 'b.id = a.idstsmaster')
        ->join('mst_wajibpajak c', 'c.id = a.idwp')
        ->join('trx_rapbd d', 'd.id = a.idrapbd')
        ->join('mst_rekening e', 'e.id = d.idrekening')
        ->join('mst_uptd f', 'f.id = a.iduptd')
        ->where('e.jenis', 'BPHTB')
        ->where('MONTH(b.tanggal)', $bulan)
        ->where('b.tahun', $tahun)
        ->group_by('a.iduptd');
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query("$query1 UNION ALL $query2");
        return $query->result();
    }
    public function get_laporan_harian($tanggal) {
        $tahun = date('Y', strtotime($tanggal));
        $saldoawal = $this->get_saldo_awal($tahun, date('m', strtotime($tanggal)));

        $this->db->select([
            'NULL AS nosspd',
            'NULL AS skpd',
            'NULL AS nmpejabat',
            'NULL AS nmwp',
            'NULL AS nmuptd',
            '0 AS total',
            "$saldoawal AS saldoawal"
        ]);
        $query1 = $this->db->get_compiled_select();
        $this->db->select([
            'a.nobukti AS nosspd',
            'a.formulir AS skpd',
            'a.nama AS nmpejabat',
            'c.nama AS nmwp',
            'IFNULL(f.nama, \'-\') AS nmuptd',
            'a.total',
            "$saldoawal AS saldoawal"
        ])
        ->from('trx_stsdetail a')
        ->join('trx_stsmaster b', 'b.id = a.idstsmaster')
        ->join('mst_wajibpajak c', 'c.id = a.idwp')
        ->join('trx_rapbd d', 'd.id = a.idrapbd')
        ->join('mst_rekening e', 'e.id = d.idrekening')
        ->left_join('mst_uptd f', 'f.id = a.iduptd')
        ->where('e.jenis', 'BPHTB')
        ->where('b.tanggal', $tanggal)
        ->where('b.tahun', $tahun);
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query("$query1 UNION ALL $query2");
        return $query->result();
    }
    public function get_real_hari_ini($idrekening, $iddinas, $tahun, $tanggal) {
        $this->db->select('SUM(a.total) AS total')
                 ->from('trx_stsdetail a')
                 ->join('trx_stsmaster b', 'b.id = a.idstsmaster')
                 ->join('trx_rapbd c', 'c.id = a.idrapbd')
                 ->where('c.idrekening', $idrekening)
                 ->where('b.iddinas', $iddinas)
                 ->where('b.tahun', $tahun)
                 ->where('b.tanggal', $tanggal);

        $query = $this->db->get();
        $result = $query->row();

        return $result ? (float) $result->total : 0.00;
    }
}
