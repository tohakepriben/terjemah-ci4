<script>
function populateBm(max){
	max=Number(max);
	var el='';
	for(var i=1; i < max; i++){
		if(lsExist('bm_'+i)){
			var text=lsGet('bm_'+i);
			el += '<tr data-id="'+i+'"><td>'+i+'</td><td onclick="bmPilih(this);">'+text+'</td><td>';
			el += '<button class="btn btn-sm" onclick="bmEdit(this)"><i class="glyphicon glyphicon-pencil text-primary"></i></button>';
    	el += '<button class="btn btn-sm" onclick="bmHapus(this)"><i class="glyphicon glyphicon-trash text-danger"></i></button>';
			el += '</td></tr>';
		}
	}
	$('#tbl-bookmark').html(el);
}
function bmPilih(el){
	let theTr = $(el).parents('tr');
	theTr.siblings('.bg-info').removeClass('bg-info');
	theTr.addClass('bg-info');
	baca(theTr.data('id'), 1);
	$('#modal-bookmark').modal('hide');
}
function bmAdd(){
	curId=$('#sel-id').text();
	if(lsExist('bm_'+curId)){
		showAlert('Penanda sudah ada');
		return;
	}
	$('#txt-bm-add').val('');
	$('#txt-bm-add').attr('placeholder', 'Untuk hal. '+curId);
	$('#modal-bm-add').modal('show');
}
function bmEdit(el){
	let theTr = $(el).parents('tr');
	$('#txt-bm-id').val(theTr.data('id'));
	$('#txt-bm-edit').val(theTr.find('td:nth-child(2)').text());
	$('#modal-bm-edit').modal('show');
}
function bmSave(mode){
	curId=mode==1?$('#sel-id').text():$('#txt-bm-id').val();
	text=mode==1?$('#txt-bm-add').val():$('#txt-bm-edit').val();
	lsSet('bm_'+curId, text);
	populateBm($('#sel-id').data('max'));
	$('#modal-bm-'+(mode==1?'add':'edit')).modal('hide');
}
function bmHapus(el){
	let theTr = $(el).parents('tr');
	lsRem('bm_'+theTr.data('id'));
	theTr.remove();
}
function bmClear(){
	$('#tbl-bookmark tr').each(function(){
		var curId = $(this).find('td:nth-child(1)').text();
		lsRem('bm_'+curId);
		$(this).remove();
	});
}
</script>