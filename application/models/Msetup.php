<?php defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set("Asia/Jakarta");
class Msetup extends CI_Model
{
	public function loadTemplate($title = NULL, $link = NULL)
	{
		$base 	  			= $this->setup();
		$getpola			= $this->get_menu_tree();
		$side				= $this->menuSide($getpola);
		$theme['topbar'][] 	= '<!DOCTYPE html>
			<html lang="en">
			<head>
			  <meta charset="UTF-8">
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
			  <link rel="stylesheet" href="' . $base['url'] . 'assets/modules/bootstrap/css/bootstrap.min.css">
			  <title>' . $title . '</title>
			  <link rel="icon" href="' . $base['url'] . 'assets/img/default/ico.png" type="image/x-icon">
			  <link rel="stylesheet" href="' . $base['url'] . 'assets/modules/fontawesome/css/all.min.css">
			  <link rel="stylesheet" href="' . $base['url'] . 'assets/css/style.css">
			  <link rel="stylesheet" href="' . $base['url'] . 'assets/css/boxicons.min.css">
			  <link rel="stylesheet" href="' . $base['url'] . 'assets/css/components.css">
  			  <link rel="stylesheet" href="' . $base['url'] . 'assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">
			  <link rel="stylesheet" href="' . $base['url'] . 'assets/modules/select2/dist/css/select2.css">
			  <link rel="stylesheet" href="' . $base['url'] . 'assets/modules/datatables/datatables.min.css">
			  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css"> 
			  <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.1.2/css/rowGroup.dataTables.min.css"> 
			 
			  <script src="' . $base['url'] . 'assets/modules/jquery.min.js"></script>
			 
			  <script src="' . $base['url'] . 'assets/modules/datatables/datatables.min.js"></script>
			  <script src="' . $base['url'] . 'assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
			  <script src="' . $base['url'] . 'assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>	  
			 <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script> 
			  <script src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script>
			  <script src="' . $base['url'] . 'assets/modules/select2/dist/js/select2.full.js"></script>

			 
			  <style>
					/* Custom CSS to ensure menu alignment */
					.sidebar-menu .nav-link {
						display: flex;
						align-items: center;
					}
					.sidebar-menu .dropdown-menu {
						margin-left: 0; /* Remove custom margin if not needed */
					}
					
					.dropdown-menu .dropdown-menu {
						left: 100%; /* Ensure nested dropdowns are positioned correctly */
						top: 0;
					}
					.main-sidebar .sidebar-menu li{
						padding: 5px 5px;
					}
					.main-sidebar .sidebar-menu li a i {
						margin-right: 0px !important;
					}
					.main-sidebar .sidebar-menu li a:hover {
						color:red;
					}
					
					.main-sidebar .sidebar-menu li a.active {
					color: white;
						background: linear-gradient(27deg, 
							rgba(46, 7, 12, 1) 4%, 
							rgb(152, 34, 34) 29%, 
							rgb(200, 50, 50) 50%, 
							rgb(230, 100, 100) 75%, 
							rgb(255, 150, 150) 100%
						);
						
						border-radius: 10px;
						padding: 10px
					}
				
					.navbar-bg {
						background: linear-gradient(27deg, 
							rgba(46, 7, 12, 1) 4%, 
							rgb(152, 34, 34) 29%, 
							rgb(200, 50, 50) 50%, 
							rgb(230, 100, 100) 75%, 
							rgb(255, 150, 150) 100%
						);
					}

				</style>


				<!-- Start GA -->
				<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
				<script>
				  window.dataLayer = window.dataLayer || [];
				  function gtag(){dataLayer.push(arguments);}
				  gtag("js", new Date());
				  gtag("config", "UA-94034622-3");
				</script>
			</head>
			<body>
			

			<div id="app">
				<div class="main-wrapper main-wrapper-1">
				<div class="navbar-bg"></div>
				<nav class="navbar navbar-expand-lg main-navbar">
					<form class="form-inline mr-auto">
					<ul class="navbar-nav mr-3">
					
						<li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
					</ul>
					</form>
					<ul class="navbar-nav navbar-right">
					<li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle beep"><i class="far fa-calendar"></i></a>
						<div class="dropdown-menu dropdown-list dropdown-menu-right">
						<div class="dropdown-header">Messages
							<div class="float-right">
							<a href="#">Mark All As Read</a>
							</div>
						</div>
						<div class="dropdown-list-content dropdown-list-message">
							<a href="#" class="dropdown-item dropdown-item-unread">
							<div class="dropdown-item-avatar">
								<i class="fas fa-user"></i>
								<div class="is-online"></div>
							</div>
							<div class="dropdown-item-desc">
								<b>Kusnaedi</b>
								<p>Hello, Bro!</p>
								<div class="time">10 Hours Ago</div>
							</div>
							</a>
						</div>
						<div class="dropdown-footer text-center">
							<a href="#">View All <i class="fas fa-chevron-right"></i></a>
						</div>
						</div>
					</li>
					<li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg beep"><i class="far fa-bell"></i></a>
						<div class="dropdown-menu dropdown-list dropdown-menu-right">
						<div class="dropdown-header">Notifications
							<div class="float-right">
							<a href="#">Mark All As Read</a>
							</div>
						</div>
						<div class="dropdown-list-content dropdown-list-icons">
							<a href="#" class="dropdown-item">
							<div class="dropdown-item-icon bg-info text-white">
								<i class="fas fa-bell"></i>
							</div>
							<div class="dropdown-item-desc">
								Welcome to Stisla template!
								<div class="time">Yesterday</div>
							</div>
							</a>
						</div>
						<div class="dropdown-footer text-center">
							<a href="#">View All <i class="fas fa-chevron-right"></i></a>
						</div>
						</div>
					</li>
					<li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
						<img alt="image" src="' . $base['url'] . 'assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
						<div class="d-sm-none d-lg-inline-block">Hi, Ujang Maman</div></a>
						<div class="dropdown-menu dropdown-menu-right">
						<div class="dropdown-title">Logged in 5 min ago</div>
						<a href="features-profile.html" class="dropdown-item has-icon">
							<i class="far fa-user"></i> Profile
						</a>
						<a href="features-activities.html" class="dropdown-item has-icon">
							<i class="fas fa-bolt"></i> Activities
						</a>
						<a href="features-settings.html" class="dropdown-item has-icon">
							<i class="fas fa-cog"></i> Settings
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item has-icon text-danger">
							<i class="fas fa-sign-out-alt"></i> Logout
						</a>
						</div>
					</li>
					</ul>
				</nav>';

		$theme['sidebar'][] = '
						<div class="main-sidebar sidebar-style-2" >
							<aside id="sidebar-wrapper">
							<div class="sidebar-brand" style="height: 40px;">
								<a href="' . $base['url'] . '">SIAPAD
								</a>
							</div>
							<marquee behavior="scroll" direction="left">
							<center><label class="hide-sidebar-mini text-danger" for="" >
							Sistem Informasi dan Akuntansi Pendapatan Daerah</label>
							</center>
							</marquee>
							<div class="sidebar-brand sidebar-brand-sm ">
								<a href="' . base_url() . '">SIAPAD</a>
							</div>
							<ul class="sidebar-menu">
							' . $side . '
							</ul>
							</aside>
						</div>';
		$theme['myModalEdit'][] = '
				<div class="modal fade bd-example-modal-lg" id="myModalE" tabindex="-1" role="dialog" aria-labelledby="myModalTitle" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="editModalLabel">Edit Modal</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form action="' . site_url($link) . '" class="row">
							<div class="row">
					<div class="modal-body" id="modalkuE">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" name="AKSI" value="Edit" class="btn btn-primary">Save changes</button>
					</div>
					</form>
					</div>
				</div>
				</div>';
		$theme['myModalDelete'][] = '
				<div class="modal fade bd-example-modal-lg" id="myModalD" tabindex="-1" role="dialog" aria-labelledby="myModalTitle" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="deleteModalLabel">Delete Modal</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
							</div>
							<div class="modal-body" id="modalkuD">
							</div>
							<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
							<button type="button" class="btn btn-danger">Delete</button>
						</div>
						</div>
					</div>
				</div>
				';
		$theme['footer'][] = '
					<footer class="main-footer">
						<div class="footer-right">
							Copyright &copy; 2024 <div class="bullet"></div> Design By <a href="https://ftfservices.biz/">FTF GLOBALINDO</a>
						</div>
					</footer>
					</div>
				</div>
				
				<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
			 
				<script src="' . $base['url'] . 'assets/modules/popper.js"></script>
				<script src="' . $base['url'] . 'assets/modules/tooltip.js"></script>
				<script src="' . $base['url'] . 'assets/modules/bootstrap/js/bootstrap.min.js"></script>
				<script src="' . $base['url'] . 'assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
				<script src="' . $base['url'] . 'assets/modules/moment.min.js"></script>
				<script src="' . $base['url'] . 'assets/js/stisla.js"></script>
				<script src="' . $base['url'] . 'assets/js/scripts.js"></script>
				<script src="' . $base['url'] . 'assets/js/custom.js"></script>
				<script src="' . $base['url'] . 'assets/js/select2.js"></script>
        		<script src="'.$base['url'].'assets/js/skpd.js"></script>
	      		<script src="'.$base['url'].'assets/js/datatableaction.js"></script>

				
				</body>
				</html>';
		return $theme;

	}

	public function setup()
	{
		$base			= [];
		$uri			= explode('/', $_SERVER['REQUEST_URI']);
		$base			= [
			'url' 		=> 'http://' . $_SERVER['HTTP_HOST'] . '/siapad-dev/',
			'halaman'	=> isset($uri[2]) ? $uri[2] : NULL,
			'fungsi'	=> isset($uri[3]) ? $uri[3] : NULL
		];
		return $base;
	}
	public function get_menu()
	{
		$query = $this->db->get('menu');
		return $query->result_array();
	}
	public function get_menu_tree($parent_id = 0)
	{
		$this->db->where('parent_id', $parent_id);
		$this->db->order_by('id', 'ASC');
		$query 	= $this->db->get('menu');
		$menu 	= $query->result_array();
		$tree 	= array();
		foreach ($menu as $menu) {
			$children = $this->get_menu_tree($menu['id']);
			if ($children) {
				$menu['children'] = $children;
			}
			$tree[] = $menu;
		}
		return $tree;
	}
	public function menuSide($menus)
	{
		$base = $this->setup();
		$html = [];
		$menu_segment_1 = $this->uri->segment(1);
		$menu_segment_2 = $this->uri->segment(2);

		foreach ($menus as $menu) {
			$icon = !empty($menu['icon']) ? '<i class="' . $menu['icon'] . '" style="font-size:25px;"></i> ' : '';
			$link = !empty($menu['link']) ? $base['url'] . $menu['link'] : '';
			$isActive = ($menu_segment_1 == $menu['link'] ||  $menu_segment_2 == $menu['link'] || $menu_segment_1 . '/' . $menu_segment_2 == $menu['link']) ? ' active' : '';


			if (isset($menu['children'])) {
				$html[] = '<li class="dropdown  ' . $isActive . ' ">';
				$html[] = '<a href="' . $link . '" class="nav-link has-dropdown">' . $icon . '<span> ' . $menu['name'] . '</span></a>';
				$html[] = '<ul class="dropdown-menu  ' . $isActive . ' ">';
				$html[] = $this->menuSide($menu['children']);
				$html[] = '</ul>';
			} else {
				$html[] = '<li class="dropdown ' . $isActive . '">';
				$html[] = '<a href="' . $link . '" class="nav-link' . $isActive . '">' . $icon . '<span>' . $menu['name'] . '</span></a>';
			}
			$html[] = '</li>';
		}
		return implode('', $html);
	}


	public function get_title($uri_segment = 'dashboard')
	{
		$this->db->select('*');
		$this->db->from('menu');
		$this->db->where('link', $uri_segment);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return "Title Not Found";
		}
	}

	public function get_tanda_tangan($ttd_checkbox, $tanda_tangan) {
        if ($ttd_checkbox && $tanda_tangan) {
            $ttddetail = $this->db
                ->select('id, nama, nip, jabatan1, jabatan2')
                ->from('mst_tandatangan')
                ->where('id', $tanda_tangan)
                ->get()
                ->row_array();
            
            return $ttddetail;
        }
        return null;
    }
	public function get_tanda_tangan_tanpa_checbox($tanda_tangan) {
        if ($tanda_tangan) {
            $ttddetail = $this->db
                ->select('id, nama, nip, jabatan1, jabatan2')
                ->from('mst_tandatangan')
                ->where('id', $tanda_tangan)
                ->get()
                ->row_array();
            
            return $ttddetail;
        }
        return null;
    }

	public function get_tanda_tangan_skpd_1($tanda_tangan_1) {
        if ($tanda_tangan_1) {
            $ttddetail = $this->db
                ->select('id, nama, nip, jabatan1, jabatan2')
                ->from('mst_tandatangan')
                ->where('id', $tanda_tangan_1)
                ->get()
                ->row_array();
            
            return $ttddetail;
        }
        return null;
    }
	public function get_tanda_tangan_skpd_2($tanda_tangan_2) {
        if ($tanda_tangan_2) {
            $ttddetail = $this->db
                ->select('id, nama, nip, jabatan1, jabatan2')
                ->from('mst_tandatangan')
                ->where('id', $tanda_tangan_2)
                ->get()
                ->row_array();
            
            return $ttddetail;
        }
        return null;
    }
	public function get_tanda_tangan_skpd_3($tanda_tangan_3) {
        if ($tanda_tangan_3) {
            $ttddetail = $this->db
                ->select('id, nama, nip, jabatan1, jabatan2')
                ->from('mst_tandatangan')
                ->where('id', $tanda_tangan_3)
                ->get()
                ->row_array();
            
            return $ttddetail;
        }
        return null;
    }
	public function get_tanda_tangan_skpd_4($tanda_tangan_4) {
        if ($tanda_tangan_4) {
            $ttddetail = $this->db
                ->select('id, nama, nip, jabatan1, jabatan2')
                ->from('mst_tandatangan')
                ->where('id', $tanda_tangan_4)
                ->get()
                ->row_array();
            
            return $ttddetail;
        }
        return null;
    }
	public function get_tanda_tangan_skpd_5($tanda_tangan_5) {
        if ($tanda_tangan_5) {
            $ttddetail = $this->db
                ->select('id, nama, nip, jabatan1, jabatan2')
                ->from('mst_tandatangan')
                ->where('id', $tanda_tangan_5)
                ->get()
                ->row_array();
            
            return $ttddetail;
        }
        return null;
    }
	public function get_tanda_tangan_skpd($tanda_tangan_id) {
		if ($tanda_tangan_id) {
			$ttddetail = $this->db
				->select('id, nama, nip, jabatan1, jabatan2')
				->from('mst_tandatangan')
				->where('id', $tanda_tangan_id)
				->get()
				->row_array();
			
			return $ttddetail;
		}
		return null;
	}
	
	public function get_pembuat($pembuat_checkbox, $pembuat) {
        if ($pembuat_checkbox && $pembuat) {
            $pembuatdetail = $this->db
                ->select('id, nama, nip, jabatan1, jabatan2')
                ->from('mst_tandatangan')
                ->where('id', $pembuat)
                ->get()
                ->row_array();
            
            return $pembuatdetail;
        }
        return null;
    }
	
	public function get_rekening($kdrekening) {
		if($kdrekening){

			$rekdetail = $this->db
				->select('id,kdrekening, nmrekening')
				->from('mst_rekening')
				->where('kdrekening', $kdrekening)
				->get()
				->row_array();
			return $rekdetail;
		}
	}
	public function get_wp($idwp) {
		if($idwp){

			$rekdetail = $this->db
				->select('id, nama')
				->from('mst_wajibpajak')
				->where('id', $idwp)
				->get()
				->row_array();
			return $rekdetail;
		}
	}

	public function get_uptd($iduptd) {
		if($iduptd){
			$uptddetail = $this->db
			->select('id,nama,singkat')
			->from('mst_uptd')
			->where('id', $iduptd)
			->get()
			->row_array();
			return $uptddetail;
		}
		return null;
	}
	public function get_dinas($iddinas) {
		if($iddinas){
			$dinDetail = $this->db
			->select('id,nama')
			->from('mst_dinas')
			->where('id', $iddinas)
			->get()
			->row_array();
			return $dinDetail;
		}
		return null;
	}
	public function jupukTahun()
	{
		// Set timezone jika diperlukan
		date_default_timezone_set('Asia/Jakarta');

		// Ambil tahun saat ini
		$currentYear = (int)date('Y');

		// Buat array tahun dari 5 tahun kebelakang sampai 1 tahun ke depan
		$years = [];
		for ($i = $currentYear - 5; $i <= $currentYear + 1; $i++) {
			$years[] = $i;
		}

		// Kirim data tahun ke view
		return  $years;
	}

	public function jupukSasiIndo()
	{
		$bulan = array(
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember'
		);
		return $bulan;
	}

	public function detailTtds($nama = '')
	{
		if ($nama != '') {
			$this->db->where("id", $nama);
		}
		$ttddata = $this->db
			->select('mst_tandatangan.id, mst_tandatangan.nip, mst_tandatangan.nama, mst_tandatangan.jabatan1, mst_tandatangan.jabatan2')
			->from('mst_tandatangan')
			->get()
			->result();

		return $ttddata;
	}
	public function detailTtd($nama = '')
	{
		$ttddata = $this->db
			->select('mst_tandatangan.id, mst_tandatangan.nip, mst_tandatangan.nama, mst_tandatangan.jabatan1, mst_tandatangan.jabatan2')
			->from('mst_tandatangan')
			->get()
			->result();

		return $ttddata;
	}

	public function jupuktriwulan()
	{
		$triwulan = array(
			'01' => 'Triwulan I',
			'02' => 'Triwulan II',
			'03' => 'Triwulan III',
			'04' => 'Triwulan IV',

		);
		return $triwulan;
	}

	public function mstWajibPajak($nama = '')
	{

		$ttddata = $this->db
			->select('id, nama,  npwpd')
			->from('mst_wajibpajak')
			->get()
			->result();

		return $ttddata;
	}

	public function jupukJenisPajak()
	{
		$jnspajak = array(
			'4.1.1.01'    => 'Pajak Hotel',
			'4.1.1.02' => 'Pajak Restoran',
			'4.1.1.03' => 'Pajak Hiburan',
			'4.1.1.07' => 'Pajak Parkir',
			'4.1.1.08' => 'Pajak Air Tanah',
			'4.1.1.11' => 'Pajak Mineral Batuan Bukan Logam',

		);
		return $jnspajak;
	}
}
