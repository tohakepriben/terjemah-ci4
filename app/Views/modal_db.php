<div id="modal-db" class="modal allow-scroll" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
			<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-menu-hamburger" style="font-weight:bolder"></i> Database Kitab</h4>
      </div>
    	<div class="modal-body">
    		<label>Versi Database</label>
    		<table>
    			<tr>
    				<td width="100px">Versi lokal</td>
    				<td id="versi-db-lokal"></td>
    			</tr>
    			<tr>
    				<td width="100px">Versi server</td>
    				<td id="versi-db-server"></td>
    			</tr>
    		</table>
    		<p id="info-db"></p>
    	</div>
    	<div class="modal-footer">
    		<button id="btn-reload-db" class="btn btn-danger pull-left" onclick="localStorage.clear(); location.reload()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
    		<button id="btn-update-db" class="btn btn-primary hidden" onclick="updateDB();"><i class="glyphicon glyphicon-upload"></i> Perbaharui</button>
    		<button class="btn btn-default" data-dismiss="modal">Keluar</button>
    	</div>
    </div>
  </div>
</div>
<script>
	var versiDB=lsGet('versiDB'), newVersiDB='';
	function updateDB(){
		showAlert('Memperbaharui database kitab. Mohon tunggu sebentar');
		downloadDB(newVersiDB);
	}
	function cekUpdateDB(){
		versiDB=lsGet('versiDB');
		useBlocking=false;
		$('#versi-db-lokal').text(': '+versiDB);
		$.post(base_url+'main/cek_update_db/'+kitab, function(data){
			newVersiDB=data;
			$('#versi-db-server').text(': '+newVersiDB);
			if(versiDB !== newVersiDB){
				var msg='Database lokal anda sudah kadaluarsa.<br>Silakan perbaharui segera';
				$('#btn-update-db').removeClass('hidden');
				$('#btn-reload-db').addClass('hidden');
				$('#info-db').addClass('text-danger');
				$('#info-db').html(msg);
				showAlert(msg);
			}else{
				$('#info-db').addClass('text-success');
				$('#info-db').html('Database lokal anda sudah sesuai dengan database server');
			}
		});
		useBlocking=true;
	}
	function downloadDB(newVersi=''){
		xhr = $.getJSON(base_url+'main/get_kitab/'+kitab, function(data){
			lsSet('selIdMax', data.length);
			dbKitab = data;
			localforage.setItem(kitab+'_dbkitab', data).then(function (value) {
		    showAlert('Download kitab berhasil');
		    lsSet('db', 1);
				populateSelId();
				setTimeout(function(){baca(lastOpenedId,1)}, 2000);
				if(newVersi!==''){
					$('#btn-update-db').addClass('hidden');
					$('#info-db').addClass('hidden');
					$('#versi-db-lokal').text(': '+newVersi);
				}
			}).catch(function(err) {
		    showAlert('Download kitab GAGAL: '+err);
		    lsSet('db', 0);
			});
		});
		xhr = $.post(base_url+'main/cek_update_db/'+kitab, function(data){
			lsSet('versiDB', data);
			cekUpdateDB();
		});
		downloadToc();
	}
	function downloadToc(){
		xhr = $.getJSON(base_url+'main/get_toc/'+kitab, function(data){
			$('#toc').jstree({
				core : {data : data},
				search	: { show_only_matches : true },
				plugins : ['search']
			});
			lsSet('toc', JSON.stringify(data));
			populateToc();	
		});
	}
</script>