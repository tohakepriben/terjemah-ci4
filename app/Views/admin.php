<!doctype html>
<html lang="id" translate="no" class="admin">
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width,initial-scale=1"/>
		<title>Pustaka Terjemah: <?=$title?></title>
	  <link href="<?=base_url('assets/favicon.png')?>" rel="icon" type="image/png" />	
		<link href="<?=base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
		<link href="<?=base_url('assets/jstree/dist/themes/default/style.min.css')?>" rel="stylesheet">
		<link href="<?=base_url('assets/select2/select2.min.css')?>" rel="stylesheet">
		<link href="<?=base_url('assets/common.css')?>" rel="stylesheet">
		<?= view('css') ?>
		<script src="<?=base_url('assets/jquery/jquery.min.js')?>"></script>
		<script src="<?=base_url('assets/jstree/dist/jstree.min.js')?>"></script>
		<script src="<?=base_url('assets/ckeditor5/build/ckeditor.js')?>"></script>
		<script src="<?=base_url('assets/FileSaver.js')?>"></script>
		<style>
			@font-face{
				font-family: AdobeArabic-Regular;
				src: url(AdobeArabic-Regular.otf) format("truetype")
			}
			.ck-content[lang=ar] p, #txt-toc-text, #div-induk>input{
				font-family: AdobeArabic-Regular,serif
			}
			.ck-content p{
				text-align: justify;
			}
			.ck.tambahan{
				font-size: 16px;
				font-weight: bold;
				color: red;
			}
		</style>
		<script>
			var isAdmin = true;
		  var nash, terjemah;
		  var nextOnSave = false;
		  var promptOnAddPage = false;
			var base_url='<?=base_url()?>', kitab='<?=$kitab?>';
			function getCurHal(){return parseInt($('#sel-id').text());}
			function sedot(tbl){
				$.getJSON(base_url+'main/get_kitab_js/'+tbl, function(data){
					let blob = new Blob([str], {type: "text/plain;charset=utf-8"});
					saveAs(blob, fname);
				});			
			}
		</script>
		<?= view('js/local_storage') ?>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top" style="margin-bottom: 5px; min-height: 30px;">
		  <div class="container-fluid" style="padding-right: 0">
		    <div class="navbar-header" style="display: inline-block; margin-top: 4px">
		      <a class="navbar-brand" href="javascript:void(0)" style="color: darkblue; font-weight: bold;"><?=$title?> 
		      <span id="nav-versi-db" class="small">v.<?=$versidb?></span>
		    	</a>
        	<button class="btn btn-sm" onclick="copyText('—')">—</button>
        	<button class="btn btn-sm" onclick="copyText('ﷺ')">ﷺ</button>
        	<button class="btn btn-sm" onclick="copyText('ٰ')">ٰ</button>
        	<button class="btn btn-sm" onclick="copyText('ٓ')">ٓ</button>
		    </div>
	      <div class="pull-right">
	      	<button class="btn btn-sm btn-outline-light" data-toggle="modal" data-target="#modal-menu"><i class="navbar-brand glyphicon glyphicon-option-vertical" style="color: black;"></i></button>
	      </div>
		  </div><!-- /.container-fluid -->
		</nav>
		<div style="padding-left: 10px; padding-right: 10px; width: 99%">
			<div class="row">
				<div class="col-sm-12 panel-nash">
					<textarea id="nash" dir="rtl" class="form-control" rows="5" style="font-size: 2em"></textarea>
        </div>
				<div class="col-sm-12 panel-terjemah">
					<textarea id="terjemah" class="form-control" rows="5"></textarea>
        </div>
				<div class="col-sm-12" style="padding-top: 5px; text-align: center;">
        	<p class="text-muted text-monospace">Saved: <span class="text-primary" id="last-update"></span>
        	</p>
        	<textarea id="txtar"></textarea>
        	
        </div>
			</div>
		</div>
		<?= view('footer', ['admin'=>TRUE]) ?>
		<?= view('modals', ['admin'=>TRUE]) ?>
		<?= view('modal_db', ['admin'=>TRUE]) ?>
		<script src="<?=base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
		<script src="<?=base_url('assets/blockui/jquery.blockUI.js')?>"></script>
		<script src="<?=base_url('assets/select2/select2.min2.js')?>"></script>
		<script src="<?=base_url('assets/fns.js')?>"></script>
		<?= view('alert') ?>
		<?= view('js/_loader') ?>
		<script>
			document.addEventListener('keyup', function(e){
			    if(e.key === 'F9') updateNash('nextOnSave');
			}, false);
			function initCk(){
				ClassicEditor
			    .create(document.querySelector('#nash'), {
			    		language: {ui: 'id', content: 'ar'}, 
			    		toolbar: {
						    items: ['undo','redo','|','bold','italic','underline','subscript','superscript','specialCharacters','fontColor','fontBackgroundColor','alignment','|','horizontalLine','link','insertTable','imageInsert','mediaEmbed','|','SourceEditing']}
			    	}
			    )
			    .then(newEditor => {
			    	nash = newEditor;
			    	initCKTerjemah();
			    })
			    .catch(error => {console.error( error );});			  
			}
			function initCKTerjemah(){
				ClassicEditor
			    .create(document.querySelector('#terjemah'), {
							toolbar:{items:['undo','redo','textPartLanguage','|','bold','italic','underline','strikethrough','subscript','superscript','specialCharacters','fontColor','fontBackgroundColor','|','alignment','blockQuote','bulletedList','numberedList','outdent','indent','|','horizontalLine','link','insertTable','imageInsert','mediaEmbed','|','codeBlock','sourceEditing']},
							language:'id',
							image:{toolbar:['imageTextAlternative','imageStyle:inline','imageStyle:block','imageStyle:side','linkImage']},
							table:{contentToolbar:['tableColumn','tableRow','mergeTableCells','tableCellProperties','tableProperties']},
			    	}
			    )
			    .then(newEditor => {
			    	terjemah = newEditor;
						$('.panel-nash .ck-toolbar_grouping>.ck-toolbar__items').prepend('<button class="ck ck-button ck-off tambahan" type="button">NASH</button><span class="ck ck-toolbar__separator"></span>');
						$('.panel-terjemah .ck-toolbar_grouping>.ck-toolbar__items').prepend('<button class="ck ck-button ck-off tambahan" type="button">TERJEMAH</button><span class="ck ck-toolbar__separator"></span>');
			    	setTimeout(function(){baca(lastOpenedId, 1)}, 500);
			    })
			    .catch(error => {console.error( error );});
			}
			function repNum(dari, sampai){
				var str=terjemah.getData();
				var cnt=sampai;
				while(cnt>=dari){
					str = str.replace(cnt+'.', (cnt+1)+'.');
					cnt--;
				}
				terjemah.setData(str);
			}
			function repNum2(dari, sampai){
				var str=$('#txtar').val();
				var cnt=sampai;
				while(cnt>=dari){
					str = str.replace(cnt+'.', (cnt+1)+'.');
					cnt--;
				}
				$('#txtar').val(str);
			}
			function copyText(text) {
    		return navigator.clipboard.writeText(text);
			}
			$(function(){
				initCk();
			});
		</script>
		
	</body>
</html>