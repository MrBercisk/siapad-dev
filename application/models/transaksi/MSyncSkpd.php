<?php defined('BASEPATH') or exit('No direct script access allowed');
class MSyncSkpd extends CI_Model
{
 
  public function formCari()
  {
   
    $formCari[] = ' 
                 <div class="row mt-3">
                    <form  class="form-row" method="post">
                    <div class="col-md-2">
                        <label for="tanggal">Cari:</label> 
                    </div>                         
                      <div class="col-md-6">
                                <div class="form-group">
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                </div>
                            </div>
                
                
                        <div class="col-md-2">
                            <div class="button-group">
                                <button type="button" class="btn btn-sm btn-secondary fa fa-binoculars cari-data" id="cari"> Cari</button>
                            </div>
                        </div>
                      
                        <div class="col-md-2">
                            <div class="button-group">
                                <button type="button" class="btn btn-sm btn-success fa fa-save add-data" id="btnCheckData"> Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
        ';
    return $formCari;
  }
  public function getWajibPajakIdByName($nama) {

    $this->db->select('id, nama, alamat');
    $this->db->from('mst_wajibpajak');
    $this->db->where('LOWER(nama)', strtolower($nama)); 
    $query = $this->db->get();


    if ($query->num_rows() > 0) {
        return $query->row();
    } else {
        return null;
    }
}
public function insertWajibPajak($nama) {
    $data = array(
        'nama' => $nama
    );

    $this->db->insert('mst_wajibpajak', $data);


    return $this->db->insert_id();
}

public function updateTrxSkpdReklame($idwp, $data) {
    $this->db->where('idwp', $idwp);
    return $this->db->update('trx_skpdreklame_temp', $data);
}

public function insertTrxSkpdReklame($idwp, $data) {
    $data['idwp'] = $idwp;
    
    return $this->db->insert('trx_skpdreklame_temp', $data);
}



  public function insertOrUpdate($data)
  {
      $this->db->where('id', $data['id']);
      $query = $this->db->get('trx_skpdreklame_temp');

      if ($query->num_rows() > 0) {
          $this->db->where('id', $data['id']);
          $this->db->update('trx_skpdreklame_temp', $data);
      } else {
          $this->db->insert('trx_skpdreklame_temp', $data);
      }
  }
  public function insertdata($data)
  {
      return $this->db->insert('trx_skpdreklame_temp', $data); 
  }
  public function check_duplicate_data($idwp) {
    $this->db->where('idwp', $idwp);
    $query = $this->db->get('trx_skpdreklame'); 
    
    return $query->num_rows() > 0; 
}
public function submitData($id) {
    $this->db->where_in('id', $id);
    $query = $this->db->get('trx_skpdreklame');

    if ($query->num_rows() > 0) {
        $data = $query->result_array();
        $insert = $this->db->insert_batch('trx_skpdreklame_temp', $data);
        return $insert;
    }
    return false;
}
    public function updateNomor($NPWPD, $nomor) {
    $this->db->set('nomor', $nomor);
    $this->db->where('NPWPD', $NPWPD);
    return $this->db->update('mst_wajibpajak');
}

public function getDatabaseData($tanggal) {

    $this->db->select('mst.nopelaporan, mst_nomor, mst.npwpd, mst.nama, mst.alamat');
    $this->db->from('trx_skpdreklame skpd'); 
    $this->db->join('mst_wajibpajak mst', 'skpd.idwp = mst.id', 'left'); 
    $this->db->where('skpd.tanggal', $tanggal);

    $query = $this->db->get();
    return $query->result_array();
}
}
