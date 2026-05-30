<!doctype html>
<html lang="id" translate="no">
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width,initial-scale=1"/>
		<title>Pustaka Terjemah: Pilih Kitab</title>
	  <link href="<?=base_url('assets/favicon.png')?>" rel="icon" type="image/png" />	
		<link href="<?=base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
		<script src="<?=base_url('assets/jquery/jquery.min.js')?>"></script>
		<style>
			@media (min-width: 768px){
				.col-sm-6:nth-child(odd){padding-right: 0;}
				.col-sm-6:nth-child(even){padding-left: 0;}
			}
		</style>
	</head>
	<body style="padding-top: 70px">
		<nav class="navbar navbar-default navbar-fixed-top" style="margin-bottom: 5px; min-height: 30px;">
		  <div class="container-fluid" style="padding-right: 0">
		    <div class="navbar-header" style="display: inline-block; margin-top: 4px">
		      <a class="navbar-brand" href="javascript:void(0)" style="color: darkblue; font-weight: bold;">Pilih Kitab</a>
		    </div>
		  </div><!-- /.container-fluid -->
		</nav>
		<div class="row">
			<?php foreach($arr_kitab as $r): ?>
			<div class="col-sm-6">
			<form method="post" style="padding: 0 10px" autocomplete="off">
				<div class="form-group">
			    <label><?=$r['kitab_full']?></label>&nbsp;<small>v<?=$r['versi']?></small>
					<div class="form-group input-group">
	          <input name="kitab" type="hidden" value="<?=$r['kitab']?>" autocomplete="off">
	          <input name="pwd" type="text" class="form-control" placeholder="Password" required="" autocomplete="off">
	          <span class="input-group-btn">
	            <button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-open-file"></i> Buka</button>
	          </span>
	        </div>
				</div>
			</form>
			</div>
			<?php endforeach; ?>
		</div>

		<script src="<?=base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>

	</body>
</html>