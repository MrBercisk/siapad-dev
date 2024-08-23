<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MLraperuptdbap extends CI_Model {

    public function ambildata($iduptd,$bulan,$tahun) {
        $mysqli = $this->db->conn_id; 
 
        $statment = $mysqli->prepare("CALL spRptLRAPerUPTDPerBulanan(?, ?, ?)");
        $statment->bind_param('sss',$iduptd,$bulan,$tahun);  
    
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
        $uptddata = $this->db
        ->select('mst_uptd.id, mst_uptd.nama')
        ->from('mst_uptd')
        ->get()
        ->result();
        $opsiuptd = '<option disabled selected>Pilih UPTD</option>';
        foreach ($uptddata as $uptd) {
            $opsiuptd .= '<option value="'.$uptd->id.'">'.$uptd->nama.'</option>';
        }
        $opsiRek = $this->iniopsirekening();
        $form[] = '
        <div class="card">
            <div class="card-body">
                <form action="' . site_url('pembukuan/lraperuptdbap/cetak') . '" class="form-row" method="post" target="printFrame">
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
    
                       
                            <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="uptd">UPTD:</label>
                                        <select id="iduptd" name="iduptd" class="form-control uptd" data-placeholder="Pilih UPTD" style="width: 100%;" required>
                                                '.$opsiuptd.'
                                        </select>
                                    </div>
                                </div>
                              
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tgl_cetak">Tgl. Cetak:</label>
                                    <input type="date" class="form-control" id="tglcetak" name="tglcetak" required>
                                </div>
                            </div>
                           
                            <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="ttd">Tanda Tangan:</label>
                                    <select id="tanda_tangan" name="tanda_tangan" class="form-control tanda_tangan " data-placeholder="Pilih Tanda Tangan" style="width: 100%;" required>
                                            '.$opsittd.'
                                    </select>
                                </div>
                            </div>
                         
                            
                        </div>
                    </div>
                    <div class="col-md-1 mt-3">
                       <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="apbdp_checkbox" name="apbdp_checkbox" >
                            <label class="form-check-label" for="apbdp">APBDP</label>
                        </div>
                       <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="wpajak_checkbox" name="wp" >
                            <label class="form-check-label" for="wpajak">W.Pajak</label>
                        </div>
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
    public function iniopsirekening($key = '') {
        $like = "(b.kdrekening LIKE '%$key%' OR b.kdrekview LIKE '%$key%' OR b.nmrekening LIKE '%$key%')";
        $result = $this->db
            ->select('b.kdrekening, b.nmrekening, b.kdrekview, a.idheader')
            ->join('mst_rekening b', 'b.id=a.idheader')
            ->where($like)
            ->where('a.tipe', 'D')
            ->group_by('b.id')
            ->order_by('b.kdrekening','ASC')
            ->get('mst_rekening a');
    
        $rekData = $result->result();
  
        $opsiRek = '<option></option>';
        foreach ($rekData as $rek) {
            $opsiRek .= '<option value="'.$rek->idheader.'">'.$rek->kdrekview.' - '.$rek->nmrekening.'</option>';
        }
    
        return $opsiRek;
    }
}
