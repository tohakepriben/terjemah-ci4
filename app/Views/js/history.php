<script>
var histories;
function populateHistory(){
	histories = JSON.parse(lsGet('histories')) || [];
	let el='';
	histories.forEach(function(item,idx){
		el += '<tr data-id="'+idx+'"><td onclick="historyPilih(this);"><label>'+item+'</label></td><td style="width:35px">';
  	el += '<button class="btn btn-sm" onclick="historyHapus(this)"><i class="glyphicon glyphicon-trash text-danger"></i></button>';
		el += '</td></tr>';
	});
	if(el==='') el='<tr><td>Tidak ada riwayat pencarian</td></tr>'
	$('#tbl-history').html(el);
}
function historyPilih(el){
	let theTr = $(el).parents('tr');
	let item = $(el).text();
	theTr.siblings('.bg-info').removeClass('bg-info');
	theTr.addClass('bg-info');
	$('[name=key]').val(item);
	$('#modal-history').modal('hide');
	$('#form-cari').submit();
}
function historyAdd(){
	let item = $('[name=key]').val().trim(), idx=histories.indexOf(item);
	if (idx !== -1) histories.splice(idx, 1);
	histories.unshift(item);
	lsSet('histories', JSON.stringify(histories));
	populateHistory();
}
function historyHapus(el){
	let theTr=$(el).parents('tr'), idx=theTr.data('id');
  histories.splice(idx, 1);
	lsSet('histories', JSON.stringify(histories));
	//theTr.remove();
	populateHistory();
}
function historyClear(){
	lsSet('histories', JSON.stringify([]));
	populateHistory();
}

</script>