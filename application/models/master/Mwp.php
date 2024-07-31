<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Mwp extends CI_Model {
	public function getWajibPajakByRekening($idrekening)
    {
        $this->db->select('mst_wajibpajak.id, nama, idrekening, nomor');
        $this->db->from('mst_wajibpajak');
        $this->db->join('mst_rekening','mst_rekening.id = mst_wajibpajak.idrekening');
        $this->db->where('mst_wajibpajak.idrekening', $idrekening);
        $query = $this->db->get();
        
        return $query->result_array();
    }
	
    public function selectBySKPD($key='',$skpd='', $start=0, $limit=0, $sort, $dir='ASC', &$total=0){
       
        /* ->select("a.id, a.nama, CONCAT(a.nama, ' - ', a.nomor) AS nmwp, a.alamat, a.idkelurahan, b.nama AS kelurahan, 
                b.idkecamatan, c.nama AS kecamatan, a.nomor, a.notype, a.tglskp, a.tgljthtmp, 
                a.idrekening, d.nmrekening, d.jenis, a.pemilik,
                c.iduptd, e.nama AS nmuptd, e.singkat AS nmuptdsingkat,
                a.awalpajakbln, a.awalpajakthn, a.akhirpajakbln, a.akhirpajakthn, a.isclosed", false)
            ->join('mst_rekening d', 'd.id=a.idrekening')
            ->join('mst_kelurahan b', 'b.id=a.idkelurahan', 'left')
            ->join('mst_kecamatan c', 'c.id=b.idkecamatan', 'left')
            ->join('mst_uptd e', 'e.id=c.iduptd', 'left')
            ->where($where)
            ->like('d.kdrekening', $kdrekening, 'after')
            ->order_by($sort, $dir)
            ->get('mst_wajibpajak a', $limit, $start); */
        $key = $this->db->escape_like_str($key);
        $where = "(a.nama LIKE '%$key%' OR a.nomor LIKE '%$key%' OR a.pemilik LIKE '%$key%' 
        OR a.nama LIKE '%$skpd%' OR a.nomor LIKE '%$skpd%' OR a.pemilik LIKE '%$skpd%')";
        $total = $this->db
            ->where($where)
            ->where('notype', 'No. SKP')
            ->count_all_results('mst_wajibpajak a');

        $result = $this->db
            ->select("a.id, a.nama, CONCAT(a.nama, ' - ', a.nomor) AS nmwp, a.alamat, a.idkelurahan, b.nama AS kelurahan, 
                b.idkecamatan, c.nama AS kecamatan, a.nomor, a.notype, a.tglskp, a.tgljthtmp, 
                a.idrekening, d.nmrekening, d.jenis,
                c.iduptd, e.nama AS nmuptd, e.singkat AS nmuptdsingkat,
                a.awalpajakbln, a.awalpajakthn, a.akhirpajakbln, a.akhirpajakthn, a.isclosed,f.keterangan", false)
            ->join('mst_rekening d', 'd.id=a.idrekening')
            ->join('mst_kelurahan b', 'b.id=a.idkelurahan', 'left')
            ->join('mst_kecamatan c', 'c.id=b.idkecamatan', 'left')
            ->join('mst_uptd e', 'e.id=c.iduptd', 'left')
            ->join('trx_skpdreklame f', 'f.idwp=a.id', 'left')
            ->where($where)
            ->where('a.notype', 'No. SKP')
            ->order_by($sort, $dir)
            ->get('mst_wajibpajak a', $limit, $start);

        return $result;
    }
    public function formInsert(){
		$form[] = '
			<form id="forminput" method="post" enctype="multipart/form-data" action="'.site_url('master/WP/aksi').'" class="form-row">
				<div class="row">
					<div class="col-md-6">
				   '.implode($this->Form->inputText('npwpd','NPWPD(REKLAME)')).
				   '</div>
					<div class="col-md-6">'
					.implode($this->Form->inputText('nomor','NOP/SKP/NPWPD')).
				   '</div>
					<div class="col-md-6">'
					.implode($this->Form->inputText('nama','NAMA')).
				   '</div>
					<div class="col-md-6">'
					.implode($this->Form->inputText('alamat','ALAMAT')).
				   '</div>
					<div class="col-md-6">'
					.implode($this->Form->inputText('no_type','Type Nomor')).
				   '</div>'
					.$this->Form->selectKec('kecamatan','kelurahan','Kecamatan','Kelurahan' , NULL, NULL, 'col-md-3').
				   '<div class="col-md-12 text-center">
						<div class="btn-group">
							<button class="btn btn-outline-danger mr-1" type="reset">
								<i class="fa fa-undo"></i> Reset
							</button>
							<button class="btn btn-outline-primary" type="submit" name="AKSI" value="Save">
								<i class="fa fa-save"></i> Simpan
							</button>
						</div>
					</div>
					</di>
               </form>
        </div>';
		return implode('',$form);
	}
}
