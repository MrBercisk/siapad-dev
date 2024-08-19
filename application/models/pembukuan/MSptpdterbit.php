<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MSptpdterbit extends CI_Model {
    
    public function cetaktotal($tahun, $bulan, $kdrekening)
    {
        $query = $this->db
            ->select("a.thnpajak AS thnpajak,a.nomor,a.tgl_input,b.nama,b.alamat,b.nomor AS npwpd,
            a.blnpajak AS masabulan,a.thnpajak AS thnpajak,a.pokok AS pokok,a.denda AS denda,
            a.jumlah AS total,a.keterangan AS keterangan,d.nobukti AS sspd,a.tanggal AS tgl_bayar", false)
            ->join('mst_wajibpajak b', 'b.id=a.idwp', 'INNER')
            ->join('mst_rekening c', 'c.id=a.idrekening', 'INNER')
            ->join('trx_stsdetail d', 'd.idwp=a.idwp AND d.blnpajak = a.blnpajak AND d.thnpajak = a.thnpajak ', 'left')
            ->where('a.thnpajak', $tahun)
            ->where('a.blnpajak', $bulan)
            ->where("c.kdrekening LIKE", "{$kdrekening}%")
            ->get('trx_sptpd a');
            
        $result = $query->result_array();
        return $result;
    }
    
    public function formInsert() {
      

        $opsiRek = $this->iniopsirekening();
        $form[] = '
        <div class="card">
            <div class="card-body">
                <form action="' . site_url('pembukuan/sptpdterbit/cetak') . '" class="form-row" method="post" target="printFrame">
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
                                    <label for="dinas">Rekening:</label>
                                    <select id="kdrekening" name="kdrekening" class="form-control select2" data-placeholder="Pilih Jenis Pajak" style="width: 100%;">
                                        '.$opsiRek.'
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tgl_cetak">Tgl. Cetak:</label>
                                    <input type="date" class="form-control" id="tglcetak" name="tglcetak" required>
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
