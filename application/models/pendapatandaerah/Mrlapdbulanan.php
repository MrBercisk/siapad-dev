<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Mrlapdbulanan extends CI_Model
{
    public function get_data_bulanan($tahun, $bulan)
    {

        $querys = $this->db->query('
                    SELECT 
                                    c.kdrekening, 
                                    CASE WHEN c.level=5 THEN 0 ELSE 1 END AS isheader,
                                    CASE WHEN LEFT(c.kdrekening, 3)="4.1" THEN b.id ELSE NULL END AS iddinas, 
                                    CASE WHEN LEFT(c.kdrekening, 3)="4.1" THEN b.nama ELSE NULL END AS nmdinas, 
                                    CASE WHEN c.kdrekening LIKE "4.1.1.%" OR c.kdrekening LIKE "4.1.2.%" OR c.kdrekening LIKE "4.1.3.%" THEN CONCAT("4.1.0.00.00.", b.urut)
                                    WHEN c.jenis<>"LAIN" AND c.kdrekening LIKE "4.1.4.17.%" THEN CONCAT("4.1.4.17.00.", b.urut)
                                    WHEN c.jenis="LAIN" AND c.kdrekening LIKE "4.1.4.17.%" THEN CONCAT("4.1.4.17.01.", b.urut)
                                    WHEN c.kdrekening LIKE "4.1.4.18.%" THEN CONCAT("4.1.4.18.00.", b.urut)
                                    WHEN c.kdrekening LIKE "4.1.4.%" THEN CONCAT(LEFT(c.kdrekening, 11), ".", b.urut)
                                    ELSE "X.X.X.XX.XX.XX" END AS urutdinas,
                                    IF(c.jenis="LAIN", 1, 0) AS islain,
                                    CASE WHEN c.level=5 THEN g.id ELSE h.id END AS idrek1,
                                    CASE WHEN c.level=5 THEN g.kdrekening ELSE h.kdrekening END AS kdurut1,
                                    CASE WHEN c.level=5 THEN g.kdrekview ELSE h.kdrekview END AS kdrek1,
                                    CASE WHEN c.level=5 THEN g.nmrekening ELSE h.nmrekening END AS nmrek1,
                                    
                                    CASE WHEN c.level=5 THEN f.id ELSE g.id END AS idrek2,
                                    CASE WHEN c.level=5 THEN f.kdrekening ELSE g.kdrekening END AS kdurut2,
                                    CASE WHEN c.level=5 THEN f.kdrekview ELSE g.kdrekview END AS kdrek2,
                                    CASE WHEN c.level=5 THEN f.nmrekening ELSE g.nmrekening END AS nmrek2,
                                    
                                    CASE WHEN c.level=5 THEN e.id ELSE f.id END AS idrek3,
                                    CASE WHEN c.level=5 THEN e.kdrekening ELSE f.kdrekening END AS kdurut3,
                                    CASE WHEN c.level=5 THEN e.kdrekview ELSE f.kdrekview END AS kdrek3,
                                    CASE WHEN c.level=5 THEN e.nmrekening ELSE f.nmrekening END AS nmrek3,
                                    
                                    CASE WHEN c.level=5 THEN d.id ELSE e.id END AS idrek4,
                                    CASE WHEN c.level=5 THEN d.kdrekening ELSE e.kdrekening END AS kdurut4,
                                    CASE WHEN c.level=5 THEN d.kdrekview ELSE e.kdrekview END AS kdrek4,
                                    CASE WHEN c.level=5 THEN d.nmrekening ELSE e.nmrekening END AS nmrek4,
                                    
                                    CASE WHEN c.level=5 THEN c.id ELSE d.id END AS idrek5,
                                    CASE WHEN c.level=5 THEN c.kdrekening ELSE d.kdrekening END AS kdurut5,
                                    CASE WHEN c.level=5 THEN c.kdrekview ELSE d.kdrekview END AS kdrek5,
                                    CASE WHEN c.level=5 THEN c.nmrekening ELSE d.nmrekening END AS nmrek5,
                                    
                                    CASE WHEN c.level=5 THEN NULL ELSE c.id END AS idrek6,
                                    CASE WHEN c.level=5 THEN NULL ELSE c.kdrekening END AS kdurut6,
                                    CASE WHEN c.level=5 THEN NULL ELSE c.kdrekview END AS kdrek6,
                                    CASE WHEN c.level=5 THEN NULL ELSE c.nmrekening END AS nmrek6,
                                    
                                    a.apbd, a.apbdp,
                                    (
                                        SELECT SUM(total) AS total
                                        FROM trx_stsdetail x 
                                        INNER JOIN trx_stsmaster y ON y.id=x.idstsmaster
                                        INNER JOIN trx_rapbd z ON z.id=x.idrapbd
                                        WHERE z.idrekening = c.id
                                            AND y.iddinas = b.id
                                            AND y.tahun = a.tahun
                                            AND MONTH(y.tanggal) = ' . $bulan . '
                                    ) AS totini,
                                    (
                                        SELECT SUM(total) AS total
                                        FROM trx_stsdetail x 
                                        INNER JOIN trx_stsmaster y ON y.id=x.idstsmaster
                                        INNER JOIN trx_rapbd z ON z.id=x.idrapbd
                                        WHERE z.idrekening = c.id
                                            AND y.iddinas = b.id
                                            AND y.tahun = a.tahun
                                            AND MONTH(y.tanggal) < ' . $bulan . '
                                    ) AS totlalu,
                                         c.id, b.id
                                                        
                                FROM trx_rapbd a
                                INNER JOIN mst_dinas b ON b.id= a.iddinas
                                INNER JOIN mst_rekening c ON c.id = a.idrekening
                                LEFT JOIN mst_rekening d ON d.id = c.idheader
                                LEFT JOIN mst_rekening e ON e.id = d.idheader
                                LEFT JOIN mst_rekening f ON f.id = e.idheader
                                LEFT JOIN mst_rekening g ON g.id = f.idheader
                                LEFT JOIN mst_rekening h ON h.id = g.idheader
                                WHERE a.tahun = ' . $tahun . '
                                ORDER BY kdurut1, kdurut2, kdurut3, urutdinas, islain, kdurut4, kdurut5, kdurut6
        ');
        return $querys->result_array();
    }

    public function formInsert1()
    {
        $ttddata = $this->db
            ->select('mst_tandatangan.id, mst_tandatangan.nip, mst_tandatangan.nama, mst_tandatangan.jabatan1, mst_tandatangan.jabatan2')
            ->from('mst_tandatangan')
            ->get()
            ->result();
        $opsittd = '<option></option>';
        foreach ($ttddata as $ttd) {
            $opsittd .= '<option value="' . $ttd->id . '">' . $ttd->nama . '</option>';
        }
        $form[] = '
        <div class="card">
            <div class="card-body">
                <form action="' . site_url('laporan/Lradaerah/cetak') . '" class="form-row" method="post">
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
                            <label for="tgl_cetak">Tgl. Cetak:</label>
                            <input type="date" class="form-control" id="tgl_cetak" name="tgl_cetak" required>
                        </div>
                    </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ttd">Tanda Tangan:</label>
                                <select id="tanda_tangan" name="tanda_tangan" class="form-control select2" data-placeholder="Pilih Tanda Tangan" style="width: 100%;">
                                        ' . $opsittd . '
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                        <div class="form-group">
                            <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="ttd_checkbox" name="ttd_checkbox" >
                            <label class="form-check-label" for="ttd">Ttd</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="apbdp_checkbox" name="apbdp_checkbox" >
                                <label class="form-check-label" for="apbdp">APBDP</label>
                            </div>
                            <label class="label mt-2" for="label"><b>BPK</b></label>
                        <div class="form-check">
                                <input type="radio" class="form-check-input" id="no_choice" name="audit_status" value="" checked>
                                <label class="form-check-label" for="no_choice">Tidak keduanya</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="un_audited" name="audit_status" value="un_audited">
                                <label class="form-check-label" for="un_audited">Un-Audited BPK</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="audited" name="audit_status" value="audited">
                                <label class="form-check-label" for="audited">Audited BPK</label>
                            </div>
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

    public function formInsert()
    {
        $milehTahun = $this->Jstambah->milehTahun((int)date("Y"));
        $milehSasi = $this->Jstambah->milehBulan((int)date("m"));
        $opsittd = $this->Jstambah->milehTtd();
        $escaped_link = htmlspecialchars("pendapatandaerah/lrlapdbulanan/detailTtd", ENT_QUOTES, 'UTF-8');
        // var_dump($detail);
        // die;
        $form[] = '
              <div class="card">
            <div class="card-body">
                <form action="' . site_url('pendapatandaerah/Lrlapdbulanan/cetak') . '" class="form-row" method="post"  target="_blank">
              
              
                <div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;">
                        <h5>Parameters</h5>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="row">
                        <div class="form-group row col-6">
                        <label for="tahun" class="col-sm-3 col-form-label">Tahun</label>
                        <div class="col-sm-9">
                             <select id="tahun" name="tahun" class="form-control" placeholder="Pilih Tahun" style="width: 100%;">
                                        ' . $milehTahun . '
                                </select>
                        </div>
                        </div>
                        <div class="form-group row col-6">
                        <label for="bulan" class="col-sm-3 col-form-label">Bulan</label>
                        <div class="col-sm-9">
                              <select id="bulan" name="bulan" class="form-control" placeholder="Pilih Bulan" style="width: 100%;">
                                        ' . $milehSasi . '
                                </select>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                     <div class="row">
                        <div class="form-group row col-6">
                        <label for="break" class="col-sm-3 col-form-label">Pg. Break</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="break" name="pgbreak" placeholder="Page Break">
                        </div>
                        </div>
                        <div class="form-group row col-6">
                            <label for="tglcetak" class="col-sm-3 col-form-label">Tgl. Cetak</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="tglcetak" name="tglcetak" placeholder="Tanggal Cetak" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                     <div class="row">
                        <div class="form-group row col-6">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9 form-check">
                           <input class="form-check-input" type="checkbox" value="nobaris" id="nobaris" name="nobaris" >
                            <label class="form-check-label" for="nobaris">
                            No. Baris
                            </label>
                        </div>
                        </div>
                        <div class="form-group row col-6">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9 form-check">
                            <input class="form-check-input" type="checkbox" value="unaudited" id="unaudited" name="unaudited" >
                                <label class="form-check-label" for="unaudited">
                                Un-Audited BPK
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                     <div class="row">
                        <div class="form-group row col-6">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9 form-check">
                           <input class="form-check-input" type="checkbox" value="apbdp" id="apbdp" name="apbdp" >
                            <label class="form-check-label" for="apbdp">
                           APBDP
                            </label>
                        </div>
                        </div>
                        <div class="form-group row col-6">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9 form-check">
                            <input class="form-check-input" type="checkbox" value="audited" id="audited" name="audited" >
                                <label class="form-check-label" for="audited">
                                Audited BPK
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="col-md-12 border-bottom border-secondary mt-4 mb-4" style="border-bottom: 2px solid #dee2e6 !important;">
                        <h5>Penandatangan</h5>
                </div>

                <div class="col-md-12">
                    <div class="form-group row">
                      <label for="namattd" class="col-sm-3 col-form-label">Nama</label>
                      <div class="col-sm-9">
                        <select id="namattd" name="namattd" class="form-control select2" data-placeholder="Pilih Tanda Tangan" style="width: 100%;">
                                        ' . $opsittd . '
                                </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="nipttd" class="col-sm-3 col-form-label">NIP</label>
                      <div class="col-sm-9">
                            <input type="text" class="form-control" id="nipttd" placeholder="NIP" readonly>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="jabatan" class="col-sm-3 col-form-label">Jabatan</label>
                      <div class="col-sm-9">
                            <input type="text" class="form-control" id="jabatan" placeholder="Jabatan" readonly>
                      </div>
                    </div>
                     <div class="form-group row">
                      <label  class="col-sm-3 col-form-label"></label>
                      <div class="col-sm-9">
                            <input type="text" class="form-control" id="jabatan2" placeholder="Jabatan" readonly>
                      </div>
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
        </div>

        <script>
            $(document).ready(function(){
                $("#namattd").change(function(){
                    var nama =$(this).val();
                    
                    $.ajax({
                        url:"' . site_url($escaped_link) . '",
                        type:"POST",
                        data:{nama:nama},
                        datatype:"json",
                        success: function(response){
                            var hasil = JSON.parse(response);
                            $("#nipttd").val(hasil.data[0].nip);
                            $("#jabatan").val(hasil.data[0].jabatan1);
                            $("#jabatan2").val(hasil.data[0].jabatan2);
                          
                        },
                        error:function(xhr,status,error){
                            console.error("Ajax error :"+status+ " - "+ error)
                        }   
                    })
                })
            });
        </script>
        ';
        return $form;
    }
    // $detail = $this->Msetup->detailTtd();

    public function datepicker()
    {
        $datepicker = $this->Jssetup->datePicker('', 'tglcetak');

        return $datepicker;
    }
}
