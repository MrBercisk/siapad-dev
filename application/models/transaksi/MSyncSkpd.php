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
                                <button type="button" class="btn btn-sm btn-success fa fa-save add-data" id="submit"> Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
        ';
    return $formCari;
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
}
