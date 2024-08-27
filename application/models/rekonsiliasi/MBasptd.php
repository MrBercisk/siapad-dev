<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MBasptd extends CI_Model {
   
    public function cetaktotal($tahun, $bulan, $bulanakhir, $kdrekening)
    {
        $query = $this->db->select("
            a.thnpajak AS thnpajak,
            a.nomor,
            a.tgl_input,
            b.nama,
            b.alamat,
            b.nomor AS npwpd,
            b.pemilik as namawp,
            a.blnpajak AS masabulan,
            a.thnpajak AS thnpajak,
            a.pokok AS pokok,
            a.denda AS denda,
            a.jumlah AS total,
            a.keterangan AS keterangan,
            d.nobukti AS sspd,
            d.nopelaporan,
            d.kodebayar,
            a.tanggal AS tgl_bayar", false)
            ->join('mst_wajibpajak b', 'b.id=a.idwp', 'INNER')
            ->join('mst_rekening c', 'c.id=a.idrekening', 'INNER')
            ->join('trx_stsdetail d', 'd.idwp=a.idwp AND d.blnpajak = a.blnpajak AND d.thnpajak = a.thnpajak', 'left')
            ->where('a.thnpajak', $tahun)
            ->where('a.blnpajak >=', $bulan)
            ->where('a.blnpajak <=', $bulanakhir)
            ->where('MONTH(a.tgl_input) >=', $bulan)
            ->where('MONTH(a.tgl_input) <=', $bulanakhir)
            ->where("c.kdrekening LIKE", "{$kdrekening}%")
            ->order_by("b.nama")
            ->get('trx_sptpd a');
            
        $result = $query->result_array();
        return $result;
    }
    public function ambildata($tahun, $bulan, $bulanakhir, $kdrekening)
    {
        $query = $this->db
            ->select("
                a.thnpajak AS thnpajak,
                a.nomor,
                a.tgl_input as tglsts,
                b.pemilik as nama,
                b.nama AS namawp,
                b.alamat,
                b.nomor AS npwpd,
                a.blnpajak AS masapajak,
                a.pokok AS pokok,
                a.denda AS denda,
                a.jumlah AS total,
                a.keterangan AS keterangan,
                d.nobukti AS sspd,
                d.nopelaporan,
                d.kodebayar,
                d.tgl_input AS tgl_bayar,
                d.jumlah AS pokok_sts,
                d.nil_denda AS denda_sts,
                d.total AS jumlah_sts,
                d.iduptd,
                d.blnpajak,
                d.thnpajak,
                e.singkat as namauptd,
                d.keterangan AS keterangan_sts", false)
            ->join('mst_wajibpajak b', 'b.id = a.idwp', 'INNER')
            ->join('mst_rekening c', 'c.id = a.idrekening', 'INNER')
            ->join('trx_stsdetail d', 'd.idwp = a.idwp AND d.blnpajak = a.blnpajak AND d.thnpajak = a.thnpajak', 'LEFT')
            ->join('mst_uptd e', 'e.id = d.iduptd', 'LEFT')
            ->where('a.thnpajak', $tahun)
            ->where('a.blnpajak >=', $bulan)
            ->where('a.blnpajak <=', $bulanakhir)
            ->where("c.kdrekening LIKE", "{$kdrekening}%")
           /* Hanya data yang ada selisih */
            ->group_start()
                ->where('a.pokok != d.jumlah')
                ->or_where('a.denda != d.nil_denda')
                ->or_where('a.jumlah != d.total')
            ->group_end()
            ->order_by("namawp")
            ->get('trx_sptpd a');
        
        $result = $query->result_array();
        return $result;
    }
    

       public function getdata($tahun, $bulan, $bulanakhir, $kdrekening)
    {
        $query = $this->db->select("
            b.tanggal as tgl_bayar,
            a.nopelaporan,
            c.npwpd,
            c.pemilik as namawp,
            c.alamat,
            a.tgl_input,
            c.nama,
            a.total,
            a.nil_denda AS denda,
            a.jumlah AS pokok,
            a.blnpajak AS masabulan,
            a.thnpajak AS thnpajak,
            a.kodebayar,
            b.keterangan AS keterangan,
   
          ", false)
            ->join('trx_stsmaster b', 'b.id=a.idstsmaster', 'INNER')
            ->join('mst_wajibpajak c', 'c.id=a.idwp', 'INNER')
            ->join('trx_rapbd d', 'd.id=a.idrapbd', 'INNER')
            ->join('mst_rekening e', 'e.id=d.idrekening', 'INNER')
            ->join('trx_sptpd f', 'f.idwp=a.idwp AND f.blnpajak = a.blnpajak AND f.thnpajak = a.thnpajak', 'left')
            ->where('YEAR(a.tgl_input)', $tahun)
            ->where('MONTH(f.tanggal) >=', $bulan)
            ->where('MONTH(f.tanggal) <=', $bulanakhir)
            ->where("e.kdrekening LIKE", "{$kdrekening}%")
            ->order_by('namawp')
            ->get('trx_stsdetail a');
            
        $result = $query->result_array();
        return $result;
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

        $opsiRek = $this->iniopsirekening();
        $form[] = '
        <div class="card">
            <div class="card-body">
                <form action="' . site_url('rekonsiliasi/basptd/cetak') . '" class="form-row" method="post">
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
                            <label for="bulan">Bulan Awal:</label>
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
    
                      <div class="col-md-4">
                        <div class="form-group">
                            <label for="bulan">Bulan Akhir:</label>
                            <select class="form-control select2" id="bulanakhir" name="bulanakhir" required>
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
                      <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dinas">Rekening:</label>
                                    <select id="kdrekening" name="kdrekening" class="form-control select2" data-placeholder="Pilih Jenis Pajak" style="width: 100%;">
                                        '.$opsiRek.'
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
        $hotelRestoranHiburanParkir = array(
            '4.1.1.01' => 'Pajak Hotel',
            '4.1.1.02' => 'Pajak Restoran',
            '4.1.1.03' => 'Pajak Hiburan',
            '4.1.1.07' => 'Pajak Parkir'
        );
    
        $reklameAirTanahMineralBatuan = array(
            '4.1.1.04' => 'Pajak Reklame',
            '4.1.1.08' => 'Pajak Air Tanah',
            '4.1.1.11' => 'Pajak Mineral Batuan Bukan Logam'
        );
    
        $rekeningCumaIni = array_merge($hotelRestoranHiburanParkir, $reklameAirTanahMineralBatuan);
    
        $rekData = $this->db
            ->select('mst_rekening.id, mst_rekening.kdrekening, mst_rekening.nmrekening')
            ->from('mst_rekening')
            ->where_in('kdrekening', array_keys($rekeningCumaIni))
            ->get()
            ->result();

        $opsiRek = '<option></option>';

        $opsiRek .= '<optgroup label="Pajak Hotel, Restoran, Hiburan, Parkir">';
        foreach ($rekData as $rek) {
            if (isset($hotelRestoranHiburanParkir[$rek->kdrekening])) {
                $namaRek = $hotelRestoranHiburanParkir[$rek->kdrekening];
                $opsiRek .= '<option value="'.$rek->kdrekening.'">'.$rek->kdrekening.' - '.$namaRek.'</option>';
            }
        }
        $opsiRek .= '</optgroup>';
        
        $opsiRek .= '<optgroup label="Pajak Reklame, Air Tanah, Mineral Batuan">';
        foreach ($rekData as $rek) {
            if (isset($reklameAirTanahMineralBatuan[$rek->kdrekening])) {
                $namaRek = $reklameAirTanahMineralBatuan[$rek->kdrekening];
                $opsiRek .= '<option value="'.$rek->kdrekening.'">'.$rek->kdrekening.' - '.$namaRek.'</option>';
            }
        }
        $opsiRek .= '</optgroup>';
        
        return $opsiRek;
    }
    
}
