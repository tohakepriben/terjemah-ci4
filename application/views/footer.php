<div class="footer navbar-fixed-bottom" style="padding: 5px 0 0;background: white;">
	<div class="btn-group btn-group-justified common" role="group" aria-label="Justified button group">
	  <div class="btn-group" role="group">
	    <button type="button" class="btn btn-lg btn-default" data-toggle="modal" data-target="#modal-cari"><i class="glyphicon glyphicon-search"></i></button>
	  </div>
	  <div class="btn-group" role="group">
	    <button type="button" class="btn btn-lg btn-default" data-toggle="modal" data-target="#modal-toc"><i class="glyphicon glyphicon-list"></i></button>
	  </div>
	  <div class="btn-group" role="group">
	    <button type="button" class="btn btn-lg btn-default" onclick="next()"><i class="glyphicon glyphicon-chevron-left"></i></button>
	  </div>
	  <div class="btn-group" role="group">
	  	<button type="button" class="btn btn-lg btn-default" id="sel-id" data-id="" data-max="" data-toggle="modal" data-target="#modal-ke">0</button>
	  </div>
	  <div id="btn-right" class="btn-group" role="group">
	    <button type="button" class="btn btn-lg btn-default" onclick="prev()"><i class="glyphicon glyphicon-chevron-right"></i></button>
	  </div>
	  <div class="btn-group" role="group">
	    <button type="button" class="btn btn-lg btn-default" data-toggle="modal" data-target="#modal-bookmark"><i class="glyphicon glyphicon-bookmark"></i></button>
	  </div>
	  <div class="btn-group" role="group">
	    <button type="button" class="btn btn-lg btn-default" data-toggle="modal" data-target="#modal-tema"><i class="glyphicon glyphicon-adjust"></i></button>
	  </div>
	</div>
	<?php if($admin): ?>
	<div class="btn-group btn-group-justified" role="group" style="margin-top: 5px">
	  <div class="btn-group" role="group">
	    <button type="button" class="btn btn-lg btn-danger" onclick="hapusPage()"><i class="glyphicon glyphicon-minus"></i> Halaman</button>
	  </div>
	  <div class="btn-group" role="group">
	    <button type="button" class="btn btn-lg btn-success" onclick="tambahPage()"><i class="glyphicon glyphicon-plus"></i> Halaman</button>
	  </div>
	  <div class="btn-group" role="group">
	    <button type="button" class="btn btn-lg btn-primary" onclick="updateNash()"><i class="glyphicon glyphicon-floppy-disk"></i> Simpan</button>
	  </div>
	  <div class="btn-group" role="group" id="simpanPlus">
	    <button type="button" class="btn btn-lg btn-info" onclick="updateNash('addpageaftersave')"><i class="glyphicon glyphicon-floppy-disk"></i> Simpan +</button>
	  </div>
	  <div class="btn-group" role="group">
	    <button type="button" class="btn btn-lg btn-warning" onclick="updateDbServer();"><i class="glyphicon glyphicon-upload"></i> Update versi</button>
	  </div>
	</div>
	<script>
		function updateNash(cmd=''){
			var curPage=Number($('#sel-id').text()) || 0;
			if(curPage===0) return;
			remNewLine();
			strNash=nash.getData();
			strTerjemah=terjemah.getData();
			xhr = $.post(base_url+'admin123/update_nash/'+curPage, 
				{kitab: kitab, nash: strNash, terjemah: strTerjemah}, 
				function(data){
					if(data!=1) showAlert('Tidak dapat menyimpan');
					else{
						$('#last-update').text('baru saja');
						if(cmd==='addpageaftersave') tambahPage();
						else if(cmd==='nextOnSave') next();
						else if(nextOnSave) next();
					}
			});
		}
		function gotoEmptyPage(){
			xhr = $.post(base_url+'admin123/getEmptyPage/'+kitab, function(data){
				baca(data, 1);
			});
		}
		function tambahPage(){
			var curPage=Number($('#sel-id').text()) || 0;
			if(promptOnAddPage){
				if(!confirm('Tambah halaman baru setelah halaman '+curPage+'?')) return;				
			}
			xhr = $.post(base_url+'admin123/add_page/'+kitab+'/'+curPage, function(data){
				populateSelId();
				curPage++;
				$("#toc").jstree(true).destroy();
				downloadToc();
				baca(curPage, 1);
				nash.editing.view.focus();
			});
		}
		function hapusPage(){
			var curPage=Number($('#sel-id').text()) || 0;
			if(curPage===0) return;
			if(!confirm('Hapus halaman '+curPage+'?')) return;
			xhr = $.post(base_url+'admin123/rem_page/'+kitab+'/'+curPage, function(data){
				populateSelId();
				curPage--;
				$("#toc").jstree(true).destroy();
				downloadToc();
				baca(curPage, 1);
			});
		}
		function remNewLine(){
			var str = terjemah.getData();
			str = str.replaceAll("‟", "'");
			str = str.replaceAll("„", "'");
			str = str.replaceAll("‚", "'");
			//console.log(str);
			terjemah.setData(str);
		}
		function updateDbServer(){
			$.post(base_url+'admin123/update_db_server/'+kitab, function(data){
				showAlert('Versi database server berhasil diperbaharui menjadi: '+data);
				$('#nav-versi-db').text('v.'+data);
			});
		}
		document.addEventListener("keyup", function(event) {
      // Cek jika tombol Ctrl ditekan
      if (event.ctrlKey) {
        // Cegah perilaku default (misalnya Ctrl+S biasanya menyimpan halaman)
        event.preventDefault();

        // Cek kombinasi tombol
        switch (event.key.toLowerCase()) {
          case "s": case "س": // Ctrl+S
            updateNash();
            break;
//          case "b": // Ctrl+B
//            prev();
//            break;
//          case "n": // Ctrl+N
//            next();
//            break;
        }
      }
  });
	</script>
	<?php endif; ?>
</div>
