<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Status extends CI_Model {
	public function yOrNo($val) {
		switch($val){
			case 0:
				$status = 'Tidak';
			break;
			case 1:
				$status = 'Ya';
			break;
			default:
				$status = 'BELUM DIISI / TIDAK SESUAI';
			break;
		}
		return $status;
	}
}
?>