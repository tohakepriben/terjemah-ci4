<div id="modal-alert" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
			<div class="modal-header" style="padding: 5px 15px 5px 15px">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Pesan</h4>
      </div>
    	<div class="modal-body">
    	
    	</div>
    	<div class="modal-footer">
    		<button class="btn btn-sm btn-default" data-dismiss="modal" aria-label="Close">Ok</button>
    	</div>
    </div>
  </div>
</div>
<script>
	function showAlert(text){
		if (typeof JsInterface !== "undefined") { 
			JsInterface.showToast(text.replace('<br>', '\n'));
		}else{
			$('#modal-alert .modal-body').html(text);
			$('#modal-alert').modal('show');
		}
	}
</script>
