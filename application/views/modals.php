<!--modal-menu-->
<div id="modal-menu" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document" style="margin-left: 170px;margin-top: 50px;">
    <div class="modal-content">
    <table class="table" style="margin-bottom: 0">
    	<tr><td>
				<div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
				  <div class="btn-group" role="group">
				    <button type="button" class="btn btn-default" onclick="resizeFont(-1)"><i class="glyphicon glyphicon-text-size" style="font-size:0.7em"></i></button>
				  </div>
				  <div class="btn-group" role="group">
				    <button type="button" class="btn btn-default" onclick="resizeFont(1)"><i class="glyphicon glyphicon-text-size"></i></button>
				  </div>
				</div>
    	</td></tr>
    	<?php if($admin): ?> 
    	<tr onclick="nextOnSave = !nextOnSave; alert('NextOnSave = '+nextOnSave)"><td><i class="glyphicon glyphicon-save-file"></i> Next On Save</td></tr>
    	<tr onclick="fokusNash = !fokusNash; alert('fokusNash = '+fokusNash)"><td><i class="glyphicon glyphicon-save-file"></i> fokusNash</td></tr>
    	<tr onclick="promptOnAddPage = !promptOnAddPage; alert('promptOnAddPage = '+promptOnAddPage)"><td><i class="glyphicon glyphicon-save-file"></i> Prompt On Add Page</td></tr>
    	<tr onclick="$('#simpanPlus').toggleClass('hidden')"><td><i class="glyphicon glyphicon-save-file"></i> Show Save+</td></tr>
    	<tr onclick="gotoEmptyPage()"><td><i class="glyphicon glyphicon-save-file"></i> gotoEmptyPage</td></tr>
    	<tr><td><a href="<?=base_url('admin123/logout')?>" class="text-danger"><i class="glyphicon glyphicon-log-out"></i> Logout</a></td></tr>
    	<?php else: ?>
    	<tr onclick="useHarakat()" id="harakat"><td><i class="glyphicon glyphicon-check"></i> Tampilkan Harakat</td></tr>
    	<tr onclick="$('#modal-db').modal('show');"><td><i class="glyphicon glyphicon-menu-hamburger"></i> Database Kitab</td></tr>
    	<tr onclick="JsInterface.shareApp()"><td><i class="glyphicon glyphicon-share-alt"></i> Share Aplikasi</td></tr>
    	<?php endif; ?>
    	<tr><td><a href="https://play.google.com/store/apps/dev?id=6117885787606978105"><i class="glyphicon glyphicon-new-window"></i> Aplikasi lainnya</a></td></tr>
    </table>
    </div>
  </div>
</div>

<!--modal-tema-->
<div id="modal-tema" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
			<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-adjust"></i> Tema dan Kecerahan</h4>
      </div>
    	<div class="modal-body">
				<div class="btn-group btn-group-justified" data-toggle="buttons">
	        <label class="btn btn-secondary"><input type="radio" name="inverts" id="invert0" value="0"> Cerah</label>
	        <label class="btn btn-secondary"><input type="radio" name="inverts" id="invert1" value="1"> Gelap #1</label>
	        <label class="btn btn-secondary"><input type="radio" name="inverts" id="invert2" value="2"> Gelap #2</label>
	        <label class="btn btn-secondary"><input type="radio" name="inverts" id="invert3" value="3"> Gelap #3</label>
	        <label class="btn btn-secondary"><input type="radio" name="inverts" id="invert4" value="4"> Gelap #4</label>
		    </div>
    	</div>
    </div>
  </div>
</div>

<!--modal-cari-->
<div id="modal-cari" class="modal allow-scroll" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
			<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      	<form id="form-cari">
					<div class="form-group input-group" style="margin-bottom: 0;margin-right: 30px;">
            <span class="input-group-btn">
              <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modal-history"><i class="glyphicon glyphicon-time"></i></button>
            </span>
            <input name="key" type="search" class="form-control" placeholder="Cari..." required autofocus>
            <span class="input-group-btn">
							<button class="btn btn-default" type="button" onclick="$('[name=key]').val('').focus()"><i class="glyphicon glyphicon-erase"></i></button>
              <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </span>
          </div>
        </form>
        <div style="margin-bottom: -10px;">
          Tips: Gunakan operator <code>& (and)</code> dan <code>| (or)</code> untuk memaksimalkan pencarian <button class="btn btn-xs btn-outline-success" id="btn-more"><i class="glyphicon glyphicon-menu-hamburger"></i> Selengkapnya</button>
          <div id="more" class="hidden" style="max-height: 70vh; overflow-y: auto;">
          	Sebagai contoh ada 3 halaman berisi teks sesuai urutan berikut:
          	<ol style="padding-inline-start: 20px;">
          		<li>Sholat puasa dan menikah adalah syariat islam.</li>
          		<li>Sholat 5 waktu wajib bagi setiap mukallaf.</li>
          		<li>Orang yang tidak mempu menikah disunnahkan berpuasa.</li>
          	</ol>
          	Kemudian kita akan mencari dengan kata kunci berikut:
          	<ul style="padding-inline-start: 20px; margin-bottom: 0">
          		<li>
          			<code>sholat nikah</code><br>
          			Mesin akan mencari setiap halaman yang mengandung kata <code>sholat nikah</code>. Maka tidak ada hasilnya karena tidak ada halaman yang memuat dua kata tersebut secara berurutan.
          		</li>
          		<li>
          			<code>sholat | nikah</code><br>
          			Mesin akan mencari setiap halaman yang mengandung kata <code>sholat</code> atau <code>nikah</code>. Hasilnya adalah halaman 1, 2 dan 3.
          		</li>
          		<li>
          			<code>sholat & nikah</code><br>
          			Mesin akan mencari setiap halaman yang mengandung kata <code>sholat</code> dan <code>nikah</code>. Hasilnya hanya halaman 1.
          		</li>
          		<li>
          			<code>puasa & (sholat | nikah)</code><br>
          			Mesin akan mencari setiap halaman yang mengandung kata <code>puasa</code> dan <code>sholat</code>, atau <code>puasa</code> dan <code>nikah</code>. Hasilnya adalah halaman 1 dan 3.
          		</li>
          		<li>
          			<code>(syariat & nikah) | (puasa & nikah)</code><br>
          			Mesin akan mencari setiap halaman yang mengandung kata <code>syariat</code> dan <code>nikah</code>, atau mengandung kata <code>puasa</code> dan <code>nikah</code>. Hasilnya adalah halaman 2 dan 3.
          		</li>
          	</ul>
          </div>
        </div>
      </div>
    	<div class="modal-body hasil-pencarian"></div>
    </div>
  </div>
</div>

<!--modal-toc-->
<div id="modal-toc" class="modal allow-scroll" tabindex="-1" role="dialog" data-editmode="0">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="filter: opacity(0.8)">
			<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-list"></i> Daftar Isi</h4>
      </div>
    	<div class="modal-body" <?php if($admin) echo('style="max-height: 50vh;"'); ?>>
    		<div id="toc" dir="rtl" style="font-size: 1.4em; margin-left: auto;"></div>
    	</div>
    	<div class="modal-footer">
				<div class="form-group input-group" style="margin-bottom: 0;">
          <input id="caritoc" type="search" class="form-control" placeholder="Cari daftar isi" oninput="$('#toc').jstree('search',$('#caritoc').val())">
          <span class="input-group-btn">
            <button class="btn btn-default" onclick="$('#caritoc').val(''); $('#toc').jstree('clear_search');"><i class="glyphicon glyphicon-erase" style="transform: rotateY(180deg)"></i></button>
          </span>
        </div>
        <?php if($admin): ?>
        <div class="col-12" style="margin-top: 10px">
	        <button class="btn btn-warning pull-left" onclick="modeEditToc(this)"><i class="glyphicon glyphicon-unchecked"></i> Mode Edit</button>
					<div class="btn-group hidden" role="group" id="toc-editor">
					  <div class="btn-group" role="group">
	          	<button class="btn btn-success" onclick="tambahToc()"><i class="glyphicon glyphicon-plus"></i> Cabang</button>
					  </div>
					  <div class="btn-group" role="group">
	          	<button class="btn btn-primary" onclick="tambahTocRoot()"><i class="glyphicon glyphicon-plus"></i> Induk</button>
					  </div>
					  <div class="btn-group" role="group">
	          	<button class="btn btn-info" onclick="editToc()"><i class="glyphicon glyphicon-pencil"></i> Edit</button>
					  </div>
					  <div class="btn-group" role="group">
	          	<button class="btn btn-danger" onclick="hapusToc()"><i class="glyphicon glyphicon-minus"></i> Hapus</button>
					  </div>
					  <div class="btn-group" role="group">
	          	<button class="btn btn-default" onclick="$('#toc').jstree(true).destroy();downloadToc()"><i class="glyphicon glyphicon-refresh"></i></button> 
					  </div>
					</div>
        </div>
				<?php endif; ?>
    	</div>
    </div>
  </div>
</div>

<?php if($admin): ?>
<div id="modal-toc-editor" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
			<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-list"></i> <span>Tambah Daftar Isi</span></h4>
      </div>
    	<div class="modal-body">
				<div class="form-group" id="div-induk">
          <label>Induk</label>
          <input type="text" class="form-control" dir="rtl" style="font-size: 20px; font-weight: bold;" readonly="">
	      </div>
				<div class="form-group">
          <label>Teks</label>
          <input id="txt-toc-text" type="text" class="form-control" dir="rtl" style="font-size: 20px; font-weight: bold;">
	      </div>
				<div class="form-group">
          <label>Terjemah</label>
          <input id="txt-toc-terjemah" type="text" class="form-control">
	      </div>
    	</div>
    	<div class="modal-footer">
    		<div class="checkbox pull-left">
			    <label>
			      <input type="checkbox" onclick="reloadOnSaveToc = this.checked"> Reload on add
			    </label>
			  </div>
    		<button class="btn btn-sm btn-primary" id="save-toc">Simpan</button>
    		<button class="btn btn-sm btn-default" data-dismiss="modal">Batal</button>
    	</div>
    </div>
  </div>
</div>
<script>
	var txt='', trj='', reloadOnSaveToc = false;
	function assignValToc(){
		txt=$('#txt-toc-text').val().trim();
		trj=$('#txt-toc-terjemah').val().trim();
		return (txt!='' && trj!='');
	}
	function clearValToc(){
		txt=$('#txt-toc-text').val('');
		trj=$('#txt-toc-terjemah').val('');
	}
	function modeEditToc(ctl){
		if($(ctl).find('.glyphicon').hasClass('glyphicon-unchecked')){
			$(ctl).find('.glyphicon').removeClass('glyphicon-unchecked').addClass('glyphicon-check');
			$('#modal-toc').data('editmode', '1');
			$('#toc-editor').removeClass('hidden');
		}else{
			$(ctl).find('.glyphicon').removeClass('glyphicon-check').addClass('glyphicon-unchecked');
			$('#modal-toc').data('editmode', '0');
			$('#toc-editor').addClass('hidden');
		}
	}
	function hapusToc(){
		if(nodeId==0){
			showAlert('Pilih salah satu daftar isi sebagai induk');
			return;
		}
		if(confirm('Hapus data?')){
			$.post(base_url+'admin123/rem_toc/'+kitab+'/'+$("#toc").jstree(true).get_selected()[0], function(data){
				$("#toc").jstree(true).destroy();
				downloadToc();
			});
		}
	}
	function tambahToc(save=0){
		if(save){
			if(!assignValToc()){
				showAlert('Data belum lengkap!');
				return;
			}
			
			$.post(base_url+'admin123/add_toc/'+kitab, {
					id:getCurHal(), 
					parent:$("#toc").jstree(true).get_selected()[0], 
					text:txt, 
					terjemah:trj
				}, function(data){
				if(reloadOnSaveToc){
					$("#toc").jstree(true).destroy();
					downloadToc();
				}
				$('#modal-toc-editor').modal('hide');
			});
		}
		if(tocExists(getCurHal())){
			showAlert('Halaman ini sudah memiliki daftar isi');
			return;
		}
		if(nodeId==0){
			showAlert('Pilih salah satu daftar isi sebagai induk');
			return;
		}
		clearValToc();
		var spltNodeText=$("#toc").jstree(true).get_node($("#toc").jstree(true).get_selected()).text.replace('</p>', '').split('<p>');
		$('#div-induk').removeClass('hidden');
		$('#div-induk>input').val(spltNodeText[0].split('<label>')[0]);
		$('#save-toc').off('click');
		$('#save-toc').on('click', function(){tambahToc(1)});
		$('#modal-toc-editor h4>span').text('Tambah Cabang Daftar Isi');
		$('#modal-toc-editor').modal('show');
	}
	function tambahTocRoot(save=0){
		if(save){
			if(!assignValToc()){
				showAlert('Data belum lengkap!');
				return;
			}
			$.post(base_url+'admin123/add_toc/'+kitab, {
				id:getCurHal(), 
				parent:0, 
				text:txt, 
				terjemah:trj
				}, function(data){
				if(reloadOnSaveToc){
					$("#toc").jstree(true).destroy();
					downloadToc();
				}
				$('#modal-toc-editor').modal('hide');
			});
		}
		if(getCurHal()<1) return;
		if(tocExists(getCurHal())){
			showAlert('Halaman ini sudah memiliki daftar isi');
			return;
		}
		clearValToc();
		$('#div-induk').removeClass('hidden');
		$('#div-induk>input').val('#');
		$('#save-toc').off('click');
		$('#save-toc').on('click', function(){tambahTocRoot(1)});
		$('#modal-toc-editor h4>span').text('Tambah Induk Daftar Isi');
		$('#modal-toc-editor').modal('show');
	}
	function editToc(save=0){
		if(save){
			if(!assignValToc()){
				showAlert('Data belum lengkap!');
				return;
			}
			$.post(base_url+'admin123/update_toc/'+kitab+'/'+$("#toc").jstree(true).get_selected()[0], {text:txt, terjemah:trj}, function(data){
				if(reloadOnSaveToc){
					$("#toc").jstree(true).destroy();
					downloadToc();
				}
				$('#modal-toc-editor').modal('hide');
			});
		}
		if(nodeId==0){
			showAlert('Pilih salah satu daftar isi yang ingin anda ubah');
			return;
		}
		$('#div-induk').addClass('hidden');
		var spltNodeText=$("#toc").jstree(true).get_node($("#toc").jstree(true).get_selected()).text.replace('</p>', '').split('<p>');
		txt=$('#txt-toc-text').val(spltNodeText[0].split('<label>')[0]);
		trj=$('#txt-toc-terjemah').val(spltNodeText[1]);
		$('#save-toc').off('click');
		$('#save-toc').on('click', function(){editToc(1)});
		$('#modal-toc-editor h4>span').text('Ubah Daftar Isi');
		$('#modal-toc-editor').modal('show');
	}
	function tocExists(id){
		var tocs = JSON.parse(lsGet('toc'));
		for(var h=0; h<tocs.length; h++){
			if(id==tocs[h].id) return true;
		}
		return false;
	}
	$(()=>{
		$('#txt-toc-text, #txt-toc-terjemah').on('keypress', function(event) {
      if (event.key === 'Enter' || event.keyCode === 13) {
      	if($('#txt-toc-text').val()=='') $('#txt-toc-text').focus();
      	else if($('#txt-toc-terjemah').val()=='') $('#txt-toc-terjemah').focus();
        else $('#save-toc').click()
      }
    });
	})
</script>
<?php endif; ?>

<!--modal-history-->
<div id="modal-history" class="modal allow-scroll" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
			<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-time"></i> Riwayat pencarian</h4>
      </div>
    	<div class="modal-body">
    		<div class="table-responsive" style="margin-bottom: 0; border: 0">
    			<table class="table table-striped" width="100%" id="tbl-history"></table>
    		</div>
    	</div>
    	<div class="modal-footer">
    		<button class="btn btn-sm btn-danger" onclick="historyClear()"><i class="glyphicon glyphicon-erase" style="transform: rotateY(180deg)"></i> Clear</button>
    	</div>
    </div>
  </div>
</div>

<!--modal-bookmark-->
<div id="modal-bookmark" class="modal allow-scroll" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
			<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-bookmark"></i> Penanda</h4>
      </div>
    	<div class="modal-body">
    		<div class="table-responsive" style="margin-bottom: 0; border: 0">
    			<table class="table table-striped" width="100%" id="tbl-bookmark"></table>
    		</div>
    	</div>
    	<div class="modal-footer">
    		<button class="btn btn-sm btn-success pull-left" onclick="bmAdd()"><i class="glyphicon glyphicon-plus"></i> Tambah</button>
    		<button class="btn btn-sm btn-danger" onclick="bmClear()"><i class="glyphicon glyphicon-erase" style="transform: rotateY(180deg)"></i> Clear</button>
    	</div>
    </div>
  </div>
</div>

<!--modal-bm-add-->
<div id="modal-bm-add" class="modal" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
			<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-plus"></i> Tambah Penanda</h4>
      </div>
    	<div class="modal-body">
    		<form id="form-bm-add">
					<div class="form-group input-group" style="margin-bottom: 0;">
	          <input id="txt-bm-add" type="text" class="form-control" required="" autofocus="" maxlength="25">
            <span class="input-group-btn">
              <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-floppy-disk"></i></button>
            </span>
          </div>
	      </form>
    	</div>
    </div>
  </div>
</div>

<!--modal-bm-edit-->
<div id="modal-bm-edit" class="modal" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
			<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-pencil"></i> Edit Penanda</h4>
      </div>
    	<div class="modal-body">
    		<form id="form-bm-edit">
    			<input type="hidden" id="txt-bm-id"/>
					<div class="form-group input-group" style="margin-bottom: 0;">
	          <input id="txt-bm-edit" type="text" class="form-control" required="" autofocus="" maxlength="25">
            <span class="input-group-btn">
              <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-floppy-disk"></i></button>
            </span>
          </div>
	      </form>
    	</div>
    </div>
  </div>
</div>
<!--modal-ke-->
<div id="modal-ke" class="modal" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
			<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-folder-open"></i> Ke halaman</h4>
      </div>
    	<div class="modal-body">
      	<form id="form-ke">
					<div class="form-group input-group" style="margin-bottom: 0;">
            <input id="sel-id-input" type="number" class="form-control" placeholder="Ketik halaman" min="1" max="" required autofocus>
            <span class="input-group-btn">
              <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-circle-arrow-right"></i></button>
            </span>
          </div>
        </form>
    	</div>
    </div>
  </div>
</div>

