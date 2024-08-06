<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MBattd extends CI_Model {
    
    public function cetaktotal($tahun, $bulan, $kdrekening)
    {
        $query = $this->db
            ->select("a.thnpajak AS thnpajak, a.nomor, a.tgl_input, b.nama, b.alamat, b.nomor AS npwpd,
                a.blnpajak AS masabulan, a.thnpajak AS thnpajak, a.pokok AS pokok, a.denda AS denda,
                a.jumlah AS total, a.keterangan AS keterangan, d.nobukti AS sspd, a.tanggal AS tgl_bayar, 
                COUNT(a.nomor) as totalnomor,
                COUNT(CASE WHEN a.tanggal <> '0000-00-00' THEN 1 END) AS totalnomorsb,
                COUNT(CASE WHEN a.tanggal = '0000-00-00' THEN 1 END) AS totalnomorbb,
                SUM(CASE WHEN a.tanggal <> '0000-00-00' THEN a.pokok ELSE 0 END) AS totalpokoksb,
                SUM(CASE WHEN a.tanggal = '0000-00-00' THEN a.pokok ELSE 0 END) AS totalpokokbb,
                SUM(a.pokok) AS totalpokok,
                SUM(CASE WHEN a.tanggal <> '0000-00-00' THEN a.denda ELSE 0 END) AS totaldendasb,
                SUM(CASE WHEN a.tanggal = '0000-00-00' THEN a.denda ELSE 0 END) AS totaldendabb,
                SUM(a.denda) AS totaldenda,
                SUM(CASE WHEN a.tanggal <> '0000-00-00' THEN a.jumlah ELSE 0 END) AS totaljumlahsb,
                SUM(CASE WHEN a.tanggal = '0000-00-00' THEN a.jumlah ELSE 0 END) AS totaljumlahbb,
                SUM(a.jumlah) AS totaljumlah", false)
            ->join('mst_wajibpajak b', 'b.id=a.idwp', 'INNER')
            ->join('mst_rekening c', 'c.id=a.idrekening', 'INNER')
            ->join('trx_stsdetail d', 'd.idwp=a.idwp AND d.blnpajak = a.blnpajak AND d.thnpajak = a.thnpajak', 'left')
            ->where('a.thnpajak', $tahun)
            ->where("DATE_FORMAT(a.tgl_input, '%Y-%m') =", "{$tahun}-{$bulan}")
            ->where("c.kdrekening LIKE", "{$kdrekening}%")
            ->get('trx_sptpd a');
        $result = $query->result_array();
        return $result;
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
                <form action="' . site_url('pembukuanskpd/beritaacarattd/cetak') . '" class="form-row" method="post" target="printFrame">
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
                                    <input type="date" class="form-control" id="tglcetak" name="tglcetak" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dinas">Jenis Pajak:</label>
                                    <select id="kdrekening" name="kdrekening" class="form-control select2" data-placeholder="Pilih Jenis Pajak" style="width: 100%;">
                                        '.$opsiRek.'
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="ttd">Penandatangan 1:</label>
                                    <select id="tanda_tangan_1" name="tanda_tangan_1" class="form-control tanda_tangan_1 " data-placeholder="Pilih Tanda Tangan" style="width: 100%;" required>
                                            '.$opsittd.'
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ttd">Penandatangan 2:</label>
                                    <select id="tanda_tangan_2" name="tanda_tangan_2" class="form-control tanda_tangan_2" data-placeholder="Pilih Tanda Tangan" style="width: 100%;" required>
                                            '.$opsittd.'
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ttd">Penandatangan 3:</label>
                                    <select id="tanda_tangan_3" name="tanda_tangan_3" class="form-control tanda_tangan_3" data-placeholder="Pilih Tanda Tangan" style="width: 100%;" required>
                                            '.$opsittd.'
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ttd">Penandatangan 4:</label>
                                    <select id="tanda_tangan_4" name="tanda_tangan_4" class="form-control tanda_tangan_4" data-placeholder="Pilih Tanda Tangan" style="width: 100%;" required>
                                            '.$opsittd.'
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ttd">Penandatangan 5:</label>
                                    <select id="tanda_tangan_5" name="tanda_tangan_5" class="form-control tanda_tangan_5" data-placeholder="Pilih Tanda Tangan" style="width: 100%;" required>
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
            </div>
        </div>


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
            ->select('mst_rekening.id, mst_rekening.kdrekening, mst_rekening.nmrekening')
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
