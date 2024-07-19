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
            return $query->row(); // Return a single row object
        } else {
            return null; // No data found
        }
    }
    public function delete($idstsmaster, $nourut)
    {
        $idstsmaster = $this->input->post('idstsmaster');
        $nourut = $this->input->post('nourut');

        $delete = $this->Crud->delete_data('trx_stsdetail', ['idstsmaster' => $idstsmaster, 'nourut' => $nourut]);

        if ($delete) {
            echo json_encode(['success' => true, 'message' => 'All Data has been deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete data']);
        }
    }
    public function deleteAll()
    {
        $idstsmaster = $this->input->post('idstsmaster');

        $delete = $this->Crud->delete_data('trx_stsdetail', ['idstsmaster' => $idstsmaster]);

        if ($delete) {
            echo json_encode(['success' => true, 'message' => 'All Data has been deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete data']);
        }
    }
    public function formInsert()
    {
        $dinasData = $this->db->get('mst_dinas')->result();
        $opsidin = '';
        foreach ($dinasData as $din) {
            $opsidin .= '<option value="' . $din->id . '">' . $din->nama . '</option>';
        }
        $rekData = $this->db->get('mst_rekening')->result();
        $opsirek = '';
        foreach ($rekData as $rek) {
            $opsirek .= '<option value="' . $rek->id . '">' . $rek->nmrekening . '</option>';
        }
        $form[] = '
			<form id="forminput" method="post" enctype="multipart/form-data" action="' . site_url('transaksi/PendDaerah/aksi') . '" class="form-row">
				<div class="row">
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="iddinas">Nama Dinas</label>
                            <select name="iddinas" id="iddinas" class="form-control" placeholder="Pilih Nama Dinas" style="width: 100%;">
                                ' . $opsidin . '
                            </select>
                        </div>
                    </div>
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="tahun">Tahun:</label>
                            <input type="number" class="form-control" id="tahun" name="tahun" min="1900" max="9999" value="2024" required>
                        </div>
                    </div>
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="idrekening">Nama Rekening</label>
                            <select name="idrekening" id="idrekening" class="form-control select2" data-placeholder="Pilih Nama Rekening" style="width: 100%;">
                                ' . $opsirek . '
                            </select>
                        </div>
                    </div>
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="apbd">APBD</label>
                            <input type="number" class="form-control" id="apbd" name="apbd" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="apbdp">APBDP</label>
                            <input type="number" class="form-control" id="apbdp" name="apbdp" step="0.01" required>
                        </div>
                    </div>
				   <div class="col-md-12 text-center">
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
        return implode('', $form);
    }
}
