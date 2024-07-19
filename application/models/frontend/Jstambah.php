<?php defined('BASEPATH') or exit('No direct script access allowed');
class Jstambah extends CI_Model
{
    public function milehTahun($tahunsaiki)
    {
        $tahuns     = $this->Msetup->jupukTahun();
        $milehtahun = '';
        foreach ($tahuns as $tahun) {
            $milehtahun .= '<option value="' . $tahun . '" ' . ($tahun == $tahunsaiki ? "selected" : "") . '>' . $tahun . '</option>';
        }
        return $milehtahun;
    }

    public function milehBulan($bulaniki)
    {
        $bulan_array = $this->Msetup->jupukSasiIndo();
        $options = '';
        foreach ($bulan_array as $key => $value) {
            $selected_attr = ($key == $bulaniki) ? 'selected="selected"' : '';
            $options .= '<option value="' . $key . '" ' . $selected_attr . '>' . $value . '</option>';
        }

        return $options;
    }

    public function milehtriwulan($triwulaniki)
    {
        $bulan_array = $this->Msetup->jupuktriwulan();
        $options = '';
        foreach ($bulan_array as $key => $value) {
            $selected_attr = ($key == $triwulaniki) ? 'selected="selected"' : '';
            $options .= '<option value="' . $key . '" ' . $selected_attr . '>' . $value . '</option>';
        }

        return $options;
    }
    public function milehTtd()
    {
        $ttddata = $this->Msetup->detailTtd();
        $opsittd = '<option></option>';
        foreach ($ttddata as $ttd) {
            $opsittd .= '<option value="' . $ttd->id . '">' . $ttd->nama . '</option>';
        }
        return $opsittd;
    }

    public function milihWajibPajak()
    {
        $ttddata = $this->Msetup->mstWajibPajak();
        $opsittd = '<option></option>';
        foreach ($ttddata as $ttd) {
            $opsittd .= '<option value="' . $ttd->id . '">' . $ttd->nama . '</option>';
        }
        return $opsittd;
    }

    public function milihJenisPajak()
    {
        $bulan_array = $this->Msetup->jupukJenisPajak();
        $options = '<option value="" >-- Pilih Jenis Pajak --</option>';
        foreach ($bulan_array as $key => $value) {

            $options .= '<option value="' . $key . '" >' . $value . '</option>';
        }

        return $options;
    }
}
