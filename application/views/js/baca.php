<script>
var fokusNash = false;
function next(){
	let nextSelIdVal = Number($('#sel-id').text())+1;
	let maxSelIdVal = Number($('#sel-id').data('max'));
	if(nextSelIdVal<=maxSelIdVal){
		document.querySelector('#sel-id').textContent=nextSelIdVal;
		baca(nextSelIdVal);
	}
}
function prev(){
	var prevSelIdVal = Number($('#sel-id').text())-1;
	if(prevSelIdVal>=1){
		document.querySelector('#sel-id').textContent=prevSelIdVal;
		baca(prevSelIdVal);
	}
}
function getClosestToc(id){
	if(typeof tc === 'undefined'){
		myArr = JSON.parse(lsGet('toc')), tc=[];
		for(var i=0; i<myArr.length; i++){
			tc.push(Number(myArr[i].id))
		}
	}
	return myArr.filter(
		function(value){
			return value.id == closest(tc, id); 
		}
	)[0].text.split('<label>')[0];
}
function baca(id, changeSelId=0, markText=0){
	if(isAdmin){
		xhr = $.getJSON(base_url+'main/baca/'+kitab+'/'+id, function(data){
			$.each(data, function(){
				bacaAction(this.nash, this.terjemah, changeSelId, markText);
				$('#last-update').text(this.updated);
			});
		});		
	}else{
		withHarakat = dbKitab[id-1].nash;
		noHarakat = dbKitab[id-1].noharokat;
		bacaAction(lsGet('harakat')!=0 ? withHarakat : noHarakat, dbKitab[id-1].terjemah, changeSelId, markText);
		$('#toc-title').text(getClosestToc(id));
	}
	lsSet('lastOpenedId', id);
	if(changeSelId)document.querySelector('#sel-id').textContent=id;
}
function bacaAction(strNash, strTerjemah, changeSelId, markText){
	strNash = strNash==undefined  ? '' :  strNash.replace('\n','<br>');
	strTerjemah = strTerjemah==null ? strTerjemah : strTerjemah.replace('\n','<br>');
	if(isAdmin){
		nash.setData(strNash);
		terjemah.setData(strTerjemah==null ? '' : strTerjemah);
		if(fokusNash) nash.editing.view.focus();
	}else{
		$('#div-nash').html(strNash);
		$('#div-terjemah').html(strTerjemah);
		var keywords=document.querySelector('[name=key]').value;
		keywords=fixStrForRegexp(keywords);
		markText && markAction(keywords, document.querySelector("#content"));
		reMark = markText;
	}	
	showInterstitialAdsBaca();
}
</script>