<?php   
require('template/setup.php');
$theme['main'][] = '
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
          <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle beep"><i class="far fa-envelope"></i></a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
              <div class="dropdown-header">Messages
                <div class="float-right">
                  <a href="#">Mark All As Read</a>
                </div>
              </div>
              <div class="dropdown-list-content dropdown-list-message">
                <a href="#" class="dropdown-item dropdown-item-unread">
                  <div class="dropdown-item-avatar">
                    <img alt="image" src="assets/img/avatar/avatar-1.png" class="rounded-circle">
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
            <img alt="image" src="assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
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
      </nav>
      <div class="main-sidebar sidebar-style-2" >
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand" style="height: 40px;">
            <a href="index.html">SIAPAD
			</a>
          </div>
		  <center><label class="hide-sidebar-mini" for="">Sistem Informasi dan Akuntansi Pendapatan Daerah</label>
          </center>
		  <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">SIAPAD</a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="dropdown">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="index-0.html">General Dashboard</a></li>
                <li><a class="nav-link" href="index.html">Ecommerce Dashboard</a></li>
              </ul>
            </li>          
          </ul>    
		 </aside>
 </div>';
$theme['main'][] = 
	'<div class="main-content">
        <section class="section">
          <div class="section-header">
				<h1>SIAPAD <br><span class="h2">Sistem Informasi dan Akuntansi Pendapatan Daerah</span></h1>
		  </div>
          <div class="section-body">
			 <div class="row">
				<div class="col-lg-3 col-md-6 col-sm-6 col-12">
				  <div class="card card-statistic-1">
					<div class="card-icon bg-primary">
					  <i class="far fa-user"></i>
					</div>
					<div class="card-wrap">
					  <div class="card-header">
						<h4>TEST</h4>
					  </div>
					  <div class="card-body">
						789
					  </div>
					</div>
				  </div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-12">
				  <div class="card card-statistic-1">
					<div class="card-icon bg-danger">
					  <i class="far fa-newspaper"></i>
					</div>
					<div class="card-wrap">
					  <div class="card-header">
						<h4>Pendapatan Tahun Ini</h4>
					  </div>
					  <div class="card-body">
						Rp. 0,-
					  </div>
					</div>
				  </div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-12">
				  <div class="card card-statistic-1">
					<div class="card-icon bg-warning">
					  <i class="far fa-file"></i>
					</div>
					<div class="card-wrap">
					  <div class="card-header">
						<h4>WP Belum Bayar</h4>
					  </div>
					  <div class="card-body">
						211
					  </div>
					</div>
				  </div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-12">
					  <div class="card card-statistic-1">
						<div class="card-icon bg-success">
						  <i class="fas fa-circle"></i>
						</div>
						<div class="card-wrap">
						  <div class="card-header">
							<h4>WP Sudah Bayar</h4>
						  </div>
						  <div class="card-body">
							432
						  </div>
						</div>
					  </div>
				</div>                  
			  </div>
		  </div>
        </section>
      </div>';
echo preg_replace('/\r|\n|\t/','',implode('',$theme['topbar']).
				  				  implode('',$theme['main']).
				  				  implode('',$theme['footer']));
?>