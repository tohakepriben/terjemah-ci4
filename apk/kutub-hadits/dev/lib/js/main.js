function showInterstitialAds(){
	if(versi!==0){
		if(typeof JsInterface === 'undefined') return console.log('showInterstitialAds not available');
		JsInterface.showInterstitialAds();
	}
}
function showInterstitialAdsBaca(){
	if(versi!==0){
		if(typeof JsInterface === 'undefined') return console.log('showInterstitialAds not available');
		let cnt = parseInt(localStorage.getItem('cntBaca') || 0);
		if(cnt>10){
			JsInterface.showInterstitialAds();
			localStorage.setItem('cntBaca', 0);
		}else{
			localStorage.setItem('cntBaca', ++cnt);
		}
	}
}
function setIdxKitab(newId){
	newId=Number(newId);
	localStorage.setItem('idxKitab',newId);
	idxKitab=newId;
}
$.fn.center = function () {
  this.css("position", "absolute");
  this.css("top", ($(window).height() - this.height()) / 2 + $(window).scrollTop() + "px");
  this.css("left", ($(window).width() - this.width()) / 2 + $(window).scrollLeft() + "px");
  return this;
}
function loading(center=0){
	document.getElementById('blockOverlay').style.display = 'block';
	document.getElementById('blockPage').style.display = 'block';
	if(center) $('#blockPage').center();
}
function loaded(){$('#blockOverlay, #blockPage').css('display', 'none');}
function resizeFont(val){
	var fontSize=Number(localStorage.getItem('fontSize')) || 1;
	if(val==-1 && fontSize==1) return;
	if(val==1 && fontSize==5) return;
	var newFontSize=fontSize+val;
	localStorage.setItem('fontSize', newFontSize);
	initFontSize();
}
function initFontSize(){
	var fontSize=Number(localStorage.getItem('fontSize')) || 1;
	$('#content').removeClass().addClass('f'+fontSize);	
}
function bold(str){
	let regx = /"([^"]+)"/g; //match double quotes
	let regx2 = /(["'])(.*?)\1/g; //match single/double quotes
	return str.replace(regx, '<b>&quot;$1&quot;</b>');
}
function italic(str){
	let regx = /\[([^\]]+)\]/g;
	return str.replace(regx, '<i>$1</i>');
}
function getKitabTitle(id){
	switch(id){
		case 0:return 'صحيح البخاري';
		case 1:return 'صحيح مسلم';
		case 2:return 'سنن النسائي';
		case 3:return 'سنن أبي داود';
		case 4:return 'سنن الترمذي';
		case 5:return 'سنن إبن ماجه';
		case 6:return 'الموطأ مالك';
		case 7:return 'مسند الشافعي';
		case 8:return 'مسند أحمد';
		case 9:return 'مسند الدارمي';
		case 10:return 'رياض الصالحين';
		default:return '';
	}
}
function ganti(ctl){
	var ctlId = $(ctl).attr('id').split('-')[1];
	if(ctlId==idxKitab) return;
	$('#modal-pilih-kitab').modal('hide');
	gantiAction(ctlId, 1);
}
function gantiAction(idKitab, bacaKitab=0){
	setIdxKitab(idKitab);
	$('.tbl-kitab tr').css('color', 'inherit');
	let maxHal = dbKitab[idKitab].length;
	let selKitabIdx = $('#idx-'+idKitab);
	selKitabIdx.css('color', 'darkgrey');
	$('#sel-id').data('max', maxHal);
	$('#sel-id-input').prop('max', maxHal);
	$('#sel-id-input').prop('placeholder', '1 s/d '+maxHal);
	if(bacaKitab) baca(idKitab, lsGet('lastOpenedId') ,1);
	populateBm();
	showInterstitialAds();
}
function next(){
	let nextSelIdVal = Number($('#sel-id').text())+1;
	let maxSelIdVal = Number($('#sel-id').data('max'));
	if(nextSelIdVal<=maxSelIdVal){
		document.querySelector('#sel-id').textContent=nextSelIdVal;
		baca(idxKitab, nextSelIdVal);
	}
}
function prev(){
	var prevSelIdVal = Number($('#sel-id').text())-1;
	if(prevSelIdVal>=1){
		document.querySelector('#sel-id').textContent=prevSelIdVal;
		baca(idxKitab, prevSelIdVal);
	}
}
function baca(idKitab, hal, changeSelId=0, markText=0){
	hal=Number(hal);
	if(hal<1) hal=1;
	if(idxKitab!=idKitab){
		gantiAction(idKitab,0);
	}
	lsSet('lastOpenedId', hal);
	bacaAction(lsGet('harakat')!=0 ? dbKitab[idKitab][hal-1].nd : simplifyArabic(dbKitab[idKitab][hal-1].nd), changeSelId, markText);
	if(changeSelId)document.querySelector('#sel-id').textContent=hal;
}
function bacaAction(strNash, changeSelId, markText){
	$('#content').html(italic(strNash));
	var keywords=document.querySelector('[name=key]').value;
	keywords=fixStrForRegexp(keywords);
	markText && markAction(keywords, document.querySelector("#container"));
	reMark = markText;
	document.querySelector('#content').scrollTop=0;
	document.querySelector('a.navbar-brand>span').innerHTML=getKitabTitle(idxKitab);
	loaded();
	if(markText){
		$('#container mark:nth-child(1)').prop('id', 'mark1');
		location.href='#mark1';
	}
	showInterstitialAdsBaca();
}
function invertColor(){
	$('html').toggleClass('invert');
	lsSet('invert', $('html').hasClass('invert'));
}
function closeModal(){
	var openedModal = $('.modal.in');
	if(openedModal.length){
		openedModal.last().modal('hide');
		return 1;
	}else{
		return 0
	}
}
function getCloseModalCode(idModal){
	return "$('"+idModal+"').modal('hide');";
}
function simplifyArabic(text) {
  text = text.replace(/[\u0651\u064e\u064b\u064f\u064c\u0650\u064d\u0652\u0653\u0670\u0640]/gi, '');
  text = text.replace(/[\u0622\u0623\u0625]/gi, '\u0627');
  return text;
}
function matchString(strFind, strObject, multilike, andOr='and'){
	strFind = simplifyArabic(strFind.toLowerCase()), strObject = strObject.toLowerCase();
	if(multilike){
		var spltFind=strFind.split(' ');
		if(andOr==='and'){	//dan
			for(var i=0; i<spltFind.length; i++) if(strObject.indexOf(spltFind[i])<0) return false;
			return true;
		}else{							//atau
			for(var i=0; i<spltFind.length; i++) if(strObject.indexOf(spltFind[i])>=0) return true;
			return false;
		}
	}
	return strObject.indexOf(strFind)>=0;
}
function markAction(keyword, el){
	keyword=simplifyArabic(keyword);
	var arrKeyword=[];
	var opts = {
		ignorePunctuation: ['"\u0651", "\u064e", "\u064b", "\u064f", "\u064c", "\u0650", "\u064d", "\u0652", "\u0653", "\u0670", "\u0640", "\'", ","'],
		synonyms: {"\u0622": "\u0627", "\u0623": "\u0627", "\u0625": "\u0627", "\u0627": "\u0622", "\u0627": "\u0623", "\u0627": "\u0625"},
		separateWordSearch: true, //default true
	}
	var instance = new Mark(el);
	instance.mark(keyword, opts);
}

var s2 = document.createElement('script');
if(versi==0) s2.src = 'https://tohakepriben.com/checkupdate/com.tohakepriben.kutubhaditspro.js?'+Math.random();
else s2.src = 'https://tohakepriben.com/checkupdate/com.tohakepriben.kutubhadits.js?'+Math.random();

var reMark=0;
function pilihBtn(el){
	$('.panel-collapse .btn-info').removeClass('btn-info');
	$(el).addClass('btn-info');
	closeModal();
	if(++cntInterstitialAds > 2){
		cntInterstitialAds=0;
		showInterstitialAds();
	}
}
function useHarakat(){
	if($('#harakat i').hasClass('glyphicon-unchecked')){
		$('#harakat i').removeClass('glyphicon-unchecked');
		$('#harakat i').addClass('glyphicon-check');
		lsSet('harakat', 1);
	}else{
		$('#harakat i').removeClass('glyphicon-check');
		$('#harakat i').addClass('glyphicon-unchecked');
		lsSet('harakat', 0);
	}
	baca(idxKitab,lsGet('lastOpenedId'), 0, reMark);
}
function buildRegexp(str){
	let buka = '(?=.*', tutup = ')';
	var strRegexp = '';
	var res = fixStrForRegexp(str); 
	var splt = res.split(' ');
	var strs='';
	for(var i = 0; i<splt.length; i++){
		if(splt[i].match(/[()|&]/g)){
  		if(strs!=''){
  			strRegexp += buka + strs.trim() + tutup;
	      strs='';
			}
  		strRegexp += splt[i];
    }else{
    	strs += splt[i] + ' ';
    	if(i==splt.length-1)strRegexp += buka + strs.trim() + tutup;
    }
	}
	if(strRegexp=='')strRegexp += buka + strs.trim() + tutup;
	strRegexp = '^'+strRegexp+'.*$';
	strRegexp = strRegexp.replace(/[&]/g, "");
	let re = new RegExp(strRegexp, 'im');
	return re;
}
function fixStrForRegexp(str){
	var res = str; 
	res = res.replace(/[(]/g, " ( ");
	res = res.replace(/[)]/g, " ) ");
	res = res.replace(/[|]/g, " | ");
	res = res.replace(/[&]/g, " & ");
	res = res.replace(/\s\s+/g, ' ');
	return res;
}
function loadData(id){
	if(id>10){
		gantiAction(idxKitab,1);
		$('#modal-warning').modal('hide');
		return;
	}
  localforage.getItem('dbKitab'+id, function(err, readValue) {
  	if(readValue==null){
			console.log('Data'+id+' belum tersedia');
			var s = document.createElement('script');
			s.src = 'data/data-'+id+'.js';
			document.body.appendChild(s);			
		}else{
			console.log('Data'+id+' sudah tersedia');
	  	dbKitab[id] = readValue;
	    localforage.getItem('dbKitabToc'+id, function(errToc, readValueToc) {
	    	dbKitabToc[id] = readValueToc;
		    loadData(id+1);
	    });
		}
  });
}
function populateBm(){
	var el='', matchCount=0,
	accHref = '<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a class="rtl" data-toggle="collapse" data-parent="#hasil-bm" href="#collapsebm',
	accTitle = '" class="collapsed" aria-expanded="false"><i class="glyphicon glyphicon-book"></i> ',
	accTarget = '</a></h4></div><div id="collapsebm',
	accContent = '" class="panel-collapse collapse rtl" aria-expanded="false"><div class="panel-body">',
	accEnd = '</div></div></div>';
	
	for(var z=0;z<dbKitab.length;z++){
		var hasilCurKitab='',cntHasilCurKitab=0;
		console.log('Cek kitab '+getKitabTitle(z));
		for(var id=0; id<dbKitab[z].length; id++){
			var text=localStorage.getItem('bm_'+z+'_'+id);
			if(text!=null){
				console.log(text);
				hasilCurKitab += '<button class="btn btn-default" data-idkitab="'+z+'" data-hal="'+id+'" data-text="'+text+'" onclick="pilihBtn(this); baca('+z+','+(id+1)+',1,1);">['+(id+1)+'] '+text+'</button>';
				cntHasilCurKitab++;
			}
		}
		if(cntHasilCurKitab>0)el+=accHref+z+accTitle+getKitabTitle(z)+' ('+cntHasilCurKitab+')'+accTarget+z+accContent+hasilCurKitab+accEnd;
	}
	$('#hasil-bm').html(el);
}
function bmAdd(){
	let curHal=Number($('#sel-id').text());
	let text=localStorage.getItem('bm_'+idxKitab+'_'+(curHal-1));
	if(text!=null){
		showAlert('Penanda sudah ada');
		return;
	}
	$('#txt-bm-add').val('');
	$('#txt-bm-add').attr('placeholder', 'Untuk '+getKitabTitle(idxKitab)+' hal. '+curHal);
	$('#modal-bm-add').modal('show');
}
function bmEdit(){
	if($('#hasil-bm .btn-info').length==0){
		showAlert('Pilih salah satu data!');
		return;
	}
	let idkitab = $('#hasil-bm .btn-info').data('idkitab');
	let hal = $('#hasil-bm .btn-info').data('hal');
	let text = $('#hasil-bm .btn-info').data('text');
	$('#txt-bm-edit').val(text);
	$('#modal-bm-edit').modal('show');
}
function bmHapus(){
	if($('#hasil-bm .btn-info').length==0){
		showAlert('Pilih salah satu data!');
		return;
	}
	let idkitab = $('#hasil-bm .btn-info').data('idkitab');
	let hal = $('#hasil-bm .btn-info').data('hal');
	localStorage.removeItem('bm_'+idkitab+'_'+hal);
	$('#hasil-bm .btn-info').remove();
	$('#hasil-bm>.panel').each(function(){
		if($(this).find('.btn').length==0)$(this).remove();
	});
}
function bmClear(){
	$('#hasil-bm .btn').each(function(){
		let idkitab = $(this).data('idkitab');
		let hal = $(this).data('hal');
		localStorage.removeItem('bm_'+idkitab+'_'+hal);
	});
	$('#hasil-bm .panel').remove();
}
function cariAction(){
	var el='', matchCount=0,
	accHref = '<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a class="rtl" data-toggle="collapse" data-parent="#hasil-pencarian" href="#collapse',
	accTitle = '" class="collapsed" aria-expanded="false"><i class="glyphicon glyphicon-book"></i> ',
	accTarget = '</a></h4></div><div id="collapse',
	accContent = '" class="panel-collapse collapse rtl" aria-expanded="false"><div class="panel-body">',
	accEnd = '</div></div></div>';

	var re = buildRegexp($('[name=key]').val());
	
	for(var z=0;z<dbKitab.length;z++){
		if($('.checkSingleKitab').eq(z).is(':checked')){
			var hasilCurKitab='',cntHasilCurKitab=0;
			for(var id=0; id<dbKitab[z].length; id++){
				if(simplifyArabic(dbKitab[z][id].nd).match(re)){
					hasilCurKitab += '<button class="btn btn-default" onclick="pilihBtn(this); baca('+z+','+(id+1)+',1,1);">'+(id+1)+'</button>';
					matchCount++;cntHasilCurKitab++;
				}
			}
			if(cntHasilCurKitab>0)el+=accHref+z+accTitle+getKitabTitle(z)+' ('+cntHasilCurKitab+')'+accTarget+z+accContent+hasilCurKitab+accEnd;
		}
	}
	if(matchCount===0) $('#hasil-pencarian').html('<p class="text-danger">Tidak ada hasil</p>');
	else $('#hasil-pencarian').html(el);
	loaded();	
}
function showAlert(str){
	if(typeof JsInterface !== "undefined") JsInterface.showToast(str);
	else alert(str);
}
$(function(){
	//if(typeof JsInterface=="undefined")location.href="//tohakepriben.com";
	if(versi==0){ //pro
		$('.navbar-fixed-bottom').addClass('pro');
		$('#pro-sign').removeClass('hidden');
		$('#content').removeClass('noselect');
		$('#mnu-update').prop('href', $('#mnu-update').prop('href')+'pro');
	}else{ //free
		timeToShow > 5 ? $('#mnu-getpro').removeClass('hidden') : localStorage.setItem('timeToShow', ++timeToShow);
	}
	$('#blockPage').center();
	loadData(0);
	document.body.appendChild(s2);
	initFontSize();
	$('#modal-bm-add, #modal-bm-edit').on('shown.bs.modal', function(){
		$(this).find('input').focus();
	});
	$('#frm-bm-add').submit(function(e){
		e.preventDefault();
		let text=$('#txt-bm-add').val();
		if(text.trim()==''){
			showAlert('Tentukan nama penanda!');
			return;
		}
		let idkitab = idxKitab;
		let hal = Number($('#sel-id').text())-1;
		localStorage.setItem('bm_'+idkitab+'_'+hal, text);
		populateBm();
		closeModal();
	});
	$('#frm-bm-edit').submit(function(e){
		e.preventDefault();
		let text=$('#txt-bm-edit').val();
		if(text.trim()==''){
			showAlert('Tentukan nama penanda!');
			return;
		}
		let idkitab = $('#hasil-bm .btn-info').data('idkitab');
		let hal = $('#hasil-bm .btn-info').data('hal');
		localStorage.setItem('bm_'+idkitab+'_'+hal, text);
		populateBm();
		closeModal();
	});
	$('#modal-ke').on('shown.bs.modal', function(){
		$(this).find('input').val('');
		$(this).find('input').focus();
	});
	$('#form-ke').submit(function(e){
		e.preventDefault();
		var newHal = $('#sel-id-input').val();
		let maxSelIdVal = Number($('#sel-id-input').prop('max'));
		if(newHal<1)newHal=1;
		if(newHal>maxSelIdVal)newHal=maxSelIdVal;
		baca(idxKitab, newHal,1);
		closeModal();
	});
  $('#checkedAllKitab').change(function(){
    if(this.checked)$('.checkSingleKitab').each(function(){this.checked=true;})              
    else $('.checkSingleKitab').each(function(){this.checked=false;})              
  });

  $('.checkSingleKitab').click(function () {
    if ($(this).is(':checked')){
      var isAllChecked = 0;
      $('.checkSingleKitab').each(function(){if(!this.checked)isAllChecked = 1;})              
      if(isAllChecked == 0) $('#checkedAllKitab').prop('checked', true);
    }else {
      $('#checkedAllKitab').prop('checked', false);
    }
  });
	$('#form-cari').submit(function(e){
		e.preventDefault();
		loading();
		setTimeout(function(){
			console.log('Mulai mencari...');
			cariAction();
		}, 100);
		showInterstitialAds();
	});

	if(lsGet('invert')=='true')$('html').addClass('invert');
	if(lsGet('harakat')==0){
		$('#harakat i').removeClass('glyphicon-check');
		$('#harakat i').addClass('glyphicon-unchecked');
	}
	$('#btn-more').click(function(){
		$('#more').toggleClass('hidden');
		$('#btn-more i').removeClass();
		if($('#more').hasClass('hidden')) $('#btn-more i').addClass('glyphicon glyphicon-menu-hamburger');
		else $('#btn-more i').addClass('glyphicon glyphicon-remove');
	});
	$('#modal-warning').modal('show');
})
