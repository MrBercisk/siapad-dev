<?php   
$theme['main'][] = implode($sidebar);
$theme['main'][] = 
	'
	<div id="page-title" class="page-title" data-title="'.$title.'"></div>
	<div class="main-content">
        <section class="section">
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
echo preg_replace('/\r|\n|\t/','',implode('',$topbar).
				  				  implode('',$theme['main']).
				  				  implode('',$footer));
?>