<!doctype html>
<html lang="id" translate="no">
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width,initial-scale=1"/>
		<title>Pustaka Terjemah: <?=$title?></title>
	  <link href="<?=base_url('assets/favicon.png')?>" rel="icon" type="image/png" />	
		<link href="<?=base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
		<link href="<?=base_url('assets/jstree/dist/themes/default/style.min.css')?>" rel="stylesheet">
		<link href="<?=base_url('assets/common.css')?>" rel="stylesheet">
		<?= view('css') ?>
		<script src="<?=base_url('assets/jquery/jquery.min.js')?>"></script>
		<script src="<?=base_url('assets/jstree/dist/jstree.min.js')?>"></script>
		<script src="<?=base_url('assets/localForage-1.10.0/dist/localforage.min.js')?>"></script>
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
		<style>
			#div-terjemah p{
				margin: 0 0 5px !important;
    		line-height: 1.2em;
			}
			#div-terjemah img{
				width: 100%;
			}
			mark{
		    background: orange;
		    color: black;
			}
			sup{
				color: red;
				font-weight: bold;
			}
		</style>
		<script>
			var isAdmin = false;
			var base_url='<?=base_url()?>', kitab='<?=$kitab?>';
			function getCurHal(){return parseInt($('#sel-id').text());}

			$(function(){
				const ctlContent = document.querySelector('#content');
				ctlContent.addEventListener('copy', (event) => {
					var kitab_propercase=properCase(kitab.replace('_',' '));
					var url_play_store='https://play.google.com/store/apps/details?id=com.tohakepriben.'+kitab.replace('_','');
				  const pagelink = '\nUnduh Aplikasi Android: Terjemah Kitab '+kitab_propercase+' di alamat '+url_play_store;
				  event.clipboardData.setData('text', document.getSelection() + pagelink);
				  event.preventDefault();
				});
			})
		</script>
		<?= view('js/local_storage') ?>
	</head>
	<body style="padding-top: 90px">
		<nav class="navbar navbar-default navbar-fixed-top" style="min-height: 30px;">
		  <div class="container-fluid" style="padding-right: 0">
		    <div class="navbar-header" style="display: inline-block; margin-top: 4px">
		      <a class="navbar-brand" href="javascript:void(0)" style="color: darkblue; font-weight: bold;"><?=$title?></a>
		    </div>
	      <div class="pull-right"><button class="btn btn-sm btn-outline-light" data-toggle="modal" data-target="#modal-menu"><i class="navbar-brand glyphicon glyphicon-option-vertical" style="color: black;"></i></button></div>
		  </div><!-- /.container-fluid -->
		  <div id="toc-title" class="col-12 text-danger" dir="rtl" style="text-align: center; font-size: 20px; border-top: 1px solid #e7e7e7;"></div>
		</nav>
		<div id="content" style="padding:5px; border:0">
			<div id="div-nash" dir="rtl" style="text-align: justify"></div>
			<hr style="margin: 8px 5px">
			<div id="div-terjemah" style="text-align: justify"></div>
		</div>
		<?= view('footer', ['admin'=>FALSE]) ?>
		<?= view('modals', ['admin'=>FALSE]) ?>
		<?= view('modal_db', ['admin'=>FALSE]) ?>
		<?= view('iklan', ['admin'=>FALSE]) ?>

		<script src="<?=base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
		<script src="<?=base_url('assets/blockui/jquery.blockUI.js')?>"></script>
		<script src="<?=base_url('assets/markjs/jquery.mark.min.js')?>"></script>
		<script src="<?=base_url('assets/fns.js')?>"></script>
		<?= view('alert') ?>
		<?= view('js/_loader') ?>
	</body>
</html>