<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Mrlapdtriwulan extends CI_Model
{
    public function get_data_triwulan($tahun, $bulan)
    {

        $query = $this->db->query("CALL spRptLRATriwulan(?,?)", array($tahun, $bulan));

        return $query->result_array();
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
        $milehTriwulan = $this->Jstambah->milehtriwulan((int)date("m"));
        $opsittd = $this->Jstambah->milehTtd();
        $escaped_link = htmlspecialchars("pendapatandaerah/lrlapdtriwulan/detailTtd", ENT_QUOTES, 'UTF-8');
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
                                        ' . $milehTriwulan . '
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
