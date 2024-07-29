<?php defined('BASEPATH') or exit('No direct script access allowed');
class Mpend extends CI_Model
{

    public function get_data_by_idsts_nourut($idstsmaster, $nourut)
    {
        $this->db->select([
            'trx_stsdetail.idstsmaster',
            'trx_stsdetail.idwp',
            'trx_rapbd.id as rapbdid',
            'trx_rapbd.idrekening',
            'mst_wajibpajak.id',
            'mst_wajibpajak.nama as wajibpajak',
            'mst_uptd.id as uptdid',
            'mst_uptd.singkat as uptd',
            'mst_rekening.id as idrek',
            'mst_rekening.nmrekening as namarekening',
            'trx_stsdetail.nourut',
            'trx_stsdetail.tglpajak',
            'trx_stsdetail.idskpd',
            'trx_stsdetail.nobukti',
            'trx_stsdetail.blnpajak',
            'trx_stsdetail.thnpajak',
            'trx_stsdetail.jumlah',
            'trx_stsdetail.prs_denda',
            'trx_stsdetail.nil_denda',
            'trx_stsdetail.total',
            'trx_stsdetail.keterangan',
            'trx_stsdetail.formulir',
            'trx_stsdetail.kodebayar',
            'trx_stsdetail.tgl_input',
            'trx_stsdetail.nopelaporan',
        ]);

        $this->db->from('trx_stsdetail');

        $this->db->join('trx_stsmaster', 'trx_stsmaster.id = trx_stsdetail.idstsmaster', 'left');
        $this->db->join('mst_wajibpajak', 'mst_wajibpajak.id = trx_stsdetail.idwp', 'left');
        $this->db->join('mst_uptd', 'mst_uptd.id = trx_stsdetail.iduptd', 'left');
        $this->db->join('trx_rapbd', 'trx_rapbd.id = trx_stsdetail.idrapbd', 'left');
        $this->db->join('mst_rekening', 'mst_rekening.id = trx_rapbd.idrekening', 'left');

        $this->db->where('trx_stsdetail.idstsmaster', $idstsmaster);
        $this->db->where('trx_stsdetail.nourut', $nourut);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

          return $query->row(); 
        } else {
          return null;
        }
      }
      public function ambilnourut($idstsmaster) {

        $this->db->select('nourut');
        $this->db->from('trx_stsdetail');
        $this->db->where('idstsmaster', $idstsmaster);
        $this->db->order_by('nourut', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            return $query->row()->nourut;
        } else {
            return false;
        }

    }
    public function delete($idstsmaster, $nourut)
    {
        $idstsmaster = $this->input->post('idstsmaster');
        $nourut = $this->input->post('nourut');

        $delete = $this->Crud->delete_data('trx_stsdetail', ['idstsmaster' => $idstsmaster, 'nourut' => $nourut]);
        echo json_encode(['success' => true, 'message' => 'All Data has been deleted successfully']);
       
    }
    public function ambilnomornyaMaster($idstsmaster) {
        $this->db->select('nomor');
        $this->db->from('trx_stsmaster');
        $this->db->where('id', $idstsmaster);
        $query = $this->db->get();
    
        return $query->row();
    }

    public function delete_record($idstsmaster, $nourut) {
        $this->db->where('idstsmaster', $idstsmaster);
        $this->db->where('nourut', $nourut);
        return $this->db->delete('trx_stsdetail'); 
      }
      
    public function deleteAll($idstsmaster)
    {
        $this->db->where('idstsmaster', $idstsmaster);
        return $this->db->delete('trx_stsdetail'); 
    }
    public function insertdataRecord($data)
    {

        return $this->db->insert('trx_stsmaster', $data); 
    }
    public function insertdata($data)
    {
        return $this->db->insert('trx_stsdetail', $data); 
    }
    
    public function insertdatatemp($data)
    {
        return $this->db->insert('trx_stsdetail_temp', $data); 
    }
    
   /*  public function selectRekRAPBD($iddinas = 0, $tahun, $key = '', $start = 0, $limit = 0, $sort, $dir = 'ASC', &$total = 0) {
        $key = $this->db->escape_like_str($key);
        $like = "(b.kdrekening LIKE '%$key%' OR b.kdrekview LIKE '%$key%' OR b.nmrekening LIKE '%$key%')";

        $this->db->select("a.id AS idrapbd, b.*", false)
                 ->from('trx_rapbd a')
                 ->join('mst_rekening b', 'b.id = a.idrekening', 'left')
                 ->where('a.iddinas', $iddinas)
                 ->where('a.tahun', $tahun)
                 ->like($like);

        $total = $this->db->count_all_results();

        $this->db->order_by($sort, $dir);
        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        return $this->db->get()->result_array();
    } */
    
  /*   public function getDataByIdNourut($idstsmaster, $nourut) {
        return $this->db->get_where('trx_stsdetail', ['idstsmaster' => $idstsmaster, 'nourut' => $nourut])->row_array();
    } */
    public function getDataById($idstsmaster, $nourut) {
        $this->db->select('trx_stsdetail.*'); 
        $this->db->from('trx_stsdetail');
        $this->db->where('idstsmaster', $idstsmaster);
        $this->db->where('nourut', $nourut);
        $query = $this->db->get();


        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function check_duplicate_data($idstsmaster, $kodebayar) {
        $this->db->where('idstsmaster', $idstsmaster);
        $this->db->where('kodebayar', $kodebayar);
        $query = $this->db->get('trx_stsdetail'); 
        
        return $query->num_rows() > 0; 
    }
    public function check_duplicate_record($nomor) {
        $this->db->from('trx_stsmaster');
        $this->db->where('nomor', $nomor);
        $query = $this->db->get('trx_stsmaster'); 
        
        return $query->num_rows() > 0; 
    }

    public function get_idwp_by_namaop($namaop) {
        // Query untuk mencari idwp berdasarkan namaop
        $this->db->select('id'); // Ganti 'id' sesuai dengan kolom yang menyimpan idwp
        $this->db->from('mst_wajibpajak'); // Ganti 'mst_wajibpajak' sesuai dengan nama tabel yang benar
        $this->db->where('nama', $namaop); // Ganti 'namawp' sesuai dengan kolom yang menyimpan nama WP

        // Lakukan query dan ambil hasilnya
        $query = $this->db->get();
        
        // Periksa apakah hasil query mengembalikan baris data
        if ($query->num_rows() > 0) {
            // Ambil idwp dari hasil query
            $row = $query->row();
            return $row->id; // Mengembalikan nilai idwp
        } else {
            return NULL; // Mengembalikan NULL jika tidak ditemukan idwp
        }
    }
    public function databyid($id)
    {
        $this->db->select('*');
        $this->db->from('trx_stsdetail');
        $this->db->where('idstsmaster',$id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function updatedata($idstsmaster, $nourut, $data) {
        return $this->db->update('trx_stsdetail', $data, ['idstsmaster' => $idstsmaster, 'nourut' => $nourut]);
    }

    public function update_record_data($idrecord, $data) {
        return $this->db->update('trx_stsmaster', $data, ['id' => $idrecord]);
    }

  
}
