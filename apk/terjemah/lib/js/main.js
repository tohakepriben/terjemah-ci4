var isOnAndroid, kitab, titleKitab, dbManager, timeOutTryInit;
var useBlocking = true, xhr = null, isPro=1, cntWaitForReview=parseInt(lsGet("cntWaitForReview") || 0), maxHal=0;
function getCurHal(){return parseInt($('#sel-id').text());}
function lsExist(key){return null!==localStorage.getItem(key);}
function lsGet(key){return localStorage.getItem(key);}
function lsSet(key, val){localStorage.setItem(key, val);}
function lsRem(key){localStorage.removeItem(key);}
function setPro(){isPro=1;$('html').addClass('pro');return isPro}
function setMaxHal(){dbManager.countAll().then(cnt=>maxHal=cnt)}
function getCurVersiDb(){return lsGet('curVersiDb') || '1990-01-01'}
function tryInit(){
	let btnTry = `<div><h2>Gagal memuat data!</h2><p>Aplikasi memerlukan koneksi internet untuk mengambil data dari server. Ini hanya sekali saja, setelah berhasil, anda dapat menggunakan aplikasi secara offline. Pastikan koneksi internet lancar dan coba kembali.</p><p><button class="btn btn-lg btn-primary btn-block" onclick="resetAndReload()">Coba lagi</button></p></div>`;
	timeOutTryInit = setTimeout(function(){$("#div-terjemah").html(btnTry)},20000)
}
$.fn.center = function () {
  this.css("position", "absolute");
  this.css("top", ($(window).height() - this.height()) / 2 + $(window).scrollTop() + "px");
  this.css("left", ($(window).width() - this.width()) / 2 + $(window).scrollLeft() + "px");
  return this;
}

//blockUI
function loading() {
	if(!useBlocking) return;
  $.blockUI({
    css: {backgroundColor: 'transparent',border: 'none'},
    message: '<span class="timer">Loading�</span>',
    baseZ: 2000,
    overlayCSS: {backgroundColor: 'darkgray',opacity: 0.7,cursor: 'wait'}
  });
  $('.blockUI.blockMsg').center();
}
function loaded() {
  $.unblockUI();
}
function errorAjax(){
  showAlert('Gagal mendapatkan data dari server');
}
$(document).ajaxStart(loading).ajaxStop(loaded).ajaxError(errorAjax);
function closeModal(){
	if($('.select2-container--open').length){
		$("#sel-id").select2("close");
		return 1;
	}
	if($('.blockOverlay').length){
		xhr.abort();
		return 1;
	}
	let openedModal = $('.modal.in');
	if(openedModal.length){
		openedModal.last().modal('hide');
		return 1;
	}
	if(cntWaitForReview>10){
		cntWaitForReview=0;
		if(isOnAndroid) JsInterface.reviewApp();
		return 1
	}
	
	lsSet("cntWaitForReview", ++cntWaitForReview);
	return 0
}
function getCloseModalCode(idModal){
	return "$('"+idModal+"').modal('hide');";
}
function simplifyArabic(text,replaceHamzaWithAlif=1) {
  text = text.replace(/[\u0651\u064e\u064b\u064f\u064c\u0650\u064d\u0652\u0653\u0670]/gi, '');
  if(replaceHamzaWithAlif) text = text.replace(/[\u0622\u0623\u0625]/gi, '\u0627');
  return text;
}
function properCase(str) {
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
};
function closest(arr,val){
	return Math.max.apply(null, arr.filter(function(v){return v <= val}))
}
function pad(num, size) {
  num = num.toString();
  while (num.length < size) num = "0" + num;
  return num;
}
function markAction(keyword, el){
	keyword=simplifyArabic(keyword);
	let arrKeyword=[];
	let opts = {
		ignorePunctuation: ['"\u0651", "\u064e", "\u064b", "\u064f", "\u064c", "\u0650", "\u064d", "\u0652", "\u0653", "\u0670", "\'", ","'],
		synonyms: {"\u0622": "\u0627", "\u0623": "\u0627", "\u0625": "\u0627", "\u0627": "\u0622", "\u0627": "\u0623", "\u0627": "\u0625"},
		separateWordSearch: true, //default true
	}
	let instance = new Mark(el);
	instance.mark(keyword, opts);
}
function randomInt(min, max) { // min and max included 
  return Math.floor(Math.random() * (max - min + 1) + min)
}

function showAlert(text){
	if (typeof JsInterface !== "undefined") { 
		JsInterface.showToast(text.replace('<br>', '\n'));
	}else{
		$('#modal-alert .modal-body').html(text);
		$('#modal-alert').modal('show');
	}
}

var lastOpenedId = Number(lsGet('lastOpenedId')) || 1;
var curURL = window.location.href;
var withHarakat = '', noHarakat = '', reMark=0;
var cntInterstitialAds=0,cntRewardAds=0,cntOpenAds=0;
function getCurFontSize(){
	return Number(lsGet('fontSize') || 3);
}
function initFontSize(){
	let fontSize=getCurFontSize();
	let arabFont=fontSize==0?'1.3':fontSize==1?'1.5':fontSize==2?'1.7':fontSize==3?'1.9':'2.1';
	let indoFont=fontSize==0?'1':fontSize==1?'1.15':fontSize==2?'1.30':fontSize==3?'1.45':'1.50';
		$('#div-nash').css('font-size', arabFont+'em');	
		$('#div-terjemah').css('font-size', indoFont+'em');	
}
function resizeFont(val){
	let fontSize=getCurFontSize();
	if(val==-1 && fontSize==0) return;
	if(val==1 && fontSize==4) return;
	let newFontSize=fontSize+val;
	lsSet('fontSize', newFontSize);
	initFontSize();
}
function invertColor(){
	$('html').toggleClass('invert');
	lsSet('invert', $('html').hasClass('invert'));
}
function pilihBtn(el){
	$('.hasil-pencarian .btn-info').removeClass('btn-info');
	$(el).addClass('btn-info');
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
	baca(lastOpenedId,1,reMark)
}

function populateSelId(){
	dbManager.countAll().then(cnt=>{
		let maxHal = cnt;
		$('#sel-id').data('max', maxHal);
		$('#sel-id-input').prop('max', maxHal);
		$('#sel-id-input').prop('placeholder', '1 s/d '+maxHal);
		populateBm(maxHal);		
	})
}
function populateToc(){
	let arrToc = JSON.parse(lsGet('toc'));
	arrToc.forEach(item => {
  	item.text = item.text+'<label>'+item.id+'</label><p>'+item.terjemah+'</p>';
	});
	$('#toc').jstree({
		core : {data : arrToc} ,
		search	: { show_only_matches : true },
		plugins : ['search']
	});
	$('#toc').off('select_node.jstree');
	$('#toc').on('select_node.jstree', function (e, data) {
		let nodeId = Number(data.node.id);
		console.log(nodeId);
		baca(nodeId, 1);
		$('#modal-toc').modal('hide');
		cntInterstitialAds++;
		if(cntInterstitialAds>1){
			cntInterstitialAds=0;
			showInterstitialAds();
		}
	});
}

function buildRegexp(str){
	let buka = '(?=.*', tutup = ')';
	let strRegexp = '';
	let res = fixStrForRegexp(str); 
	let splt = res.split(' ');
	let strs='';
	for(let i = 0; i<splt.length; i++){
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
	let res = str; 
	res = res.replace(/[(]/g, " ( ");
	res = res.replace(/[)]/g, " ) ");
	res = res.replace(/[|]/g, " | ");
	res = res.replace(/[&]/g, " & ");
	res = res.replace(/\s\s+/g, ' ');
	return res;
}
function showInterstitialAds(){
	if(isPro || !isOnAndroid) return;
	JsInterface.showInterstitialAds();
}
function showRewardAds(){
		if(isOnAndroid) JsInterface.showRewardAds();
}
function showOpenAds(){
		if(isOnAndroid) JsInterface.showOpenAds();
}
function showBtnOffer(){
	let el = '<button class="btn btn-block btn-lg btn-primary removeAds" onclick="JsInterface.removeAds()"><i class="glyphicon glyphicon-star"></i> Hapus Iklan <i class="glyphicon glyphicon-star"></i></button>';
	$("#div-terjemah").append(el);
}
function showInterstitialAdsBaca(){
		if(isOnAndroid){
			if(isPro) return;
			let cnt = parseInt(localStorage.getItem('cntBaca') || 0);
			if(cnt>10){
				if(Math.random() >= 0.5) JsInterface.showInterstitialAds(); else showBtnOffer();
				localStorage.setItem('cntBaca', 0);
			}
			else localStorage.setItem('cntBaca', ++cnt);
		}
}
function invert(init=0){
	let invertVal = lsGet('invert') || 0;
	$('html').removeClass('invert invert1 invert2 invert3 invert4');
	if(invertVal > 0) $('html').addClass('invert invert'+invertVal);
	if(!init) return;
	$('#modal-tema .modal-body .btn-group .btn').removeClass('active');
  $("#modal-tema .modal-body #invert"+invertVal).prop("checked", true).parent().addClass("active");
}
function initApp(){
	setMaxHal();
	populateToc();
	populateSelId();
	populateHistory();
	baca(lastOpenedId, 1);
	lsSet("dbWasLoaded",1);
	if(lsGet('harakat')==0){$('#harakat i').removeClass('glyphicon-check');$('#harakat i').addClass('glyphicon-unchecked');}
	invert(1);
	$('#cekUpdate').click(function(){location.href=JsInterface.getAppUrl()});
	$('#modal-ke, #modal-bm-add, #modal-bm-add').on('shown.bs.modal', function(){$(this).find('input:nth-child(1)').focus()});
	$('#form-ke').submit(function(e){
		e.preventDefault();
		let newHal = Number($('#sel-id-input').val());
		let maxSelIdVal = Number($('#sel-id-input').prop('max'));
		if(newHal<1)newHal=1;
		if(newHal>maxSelIdVal)newHal=maxSelIdVal;
		baca(newHal,1);
		$('#modal-ke').modal('hide');
		setTimeout(()=>$('#sel-id-input').val(''),1000)
	});
	$("input[name='inverts']").on("change", function () {
    lsSet('invert', $(this).val());
    console.log($(this).val());
    invert(0)
  });
	$('#btn-more').click(function(){
		$('#more').toggleClass('hidden');
		$('#btn-more i').removeClass();
		if($('#more').hasClass('hidden')) $('#btn-more i').addClass('glyphicon glyphicon-menu-hamburger');
		else $('#btn-more i').addClass('glyphicon glyphicon-remove');
	});
	$('#form-cari').submit(function(e){
		e.preventDefault();
		const key=$('[name=key]').val();
		const re = buildRegexp(simplifyArabic(key));
		if(!$('#more').hasClass('hidden')) $('#btn-more').click();
		dbManager.getAll().then((arrData)=>{
			let el='', matchCount=0;
			arrData.map(data => {
				const str = simplifyArabic(`${data.nash} ${data.terjemah}`);
				if(str.match(re)){
					el += '<button class="btn btn-default" onclick="pilihBtn(this);baca('+(data.id)+',1,1);closeModal();">'+(data.id)+'</button>';
					matchCount++;
				}				
			});
			if(matchCount>0) {
				$('.hasil-pencarian').html(el);
				historyAdd();
			} else $('.hasil-pencarian').html('<p class="text-danger">Pencarian: <label>'+key+'</label> tidak ada hasil</p>');			
			showInterstitialAds();
		})
	});

	$('#form-bm-add').submit(function(e){
		e.preventDefault();
		bmSave(1);
	});
	$('#form-bm-edit').submit(function(e){
		e.preventDefault();
		bmSave(2);
	});

	let usageCount = Number(lsGet('usageCount') || 0) + 1;
	if(usageCount<10){
		lsSet('usageCount',usageCount);
	}else{
		$('#modal-iklan').modal('show');
		$('#moreApp').removeClass('hidden');
	}
	$('#curVersiDb').html('Versi Db: '+getCurVersiDb());
	if(isOnAndroid){
		$('#versiApp').html('Versi App: '+JsInterface.getAppVersionCode());
	}
}

$(function(){
	isOnAndroid = (typeof JsInterface !== 'undefined');
	kitab=isOnAndroid?JsInterface.getKitabName():'fathul_muin';
	titleKitab=isOnAndroid?JsInterface.getTitleKitab():'Terjemah Fathul Muin';
	dbManager = new DbManager(kitab, kitab);
	$('#titleKitab').html(titleKitab);
	initFontSize();
	if(lsGet('dbWasLoaded')==1) initApp();
	checkUpdateDb()
})
function resetAndReload(){
	localStorage.clear();
	location.reload();
}
function checkUpdateDb(){
	try {
		let s2 = document.createElement("script");
		s2.src = "https://terjemah.tohakepriben.com/main/cek_update_js/"+kitab+"/"+getCurVersiDb()+"?"+Math.random();
		document.body.appendChild(s2);
	}
	catch(err) {
		console.log(err);
	}
}



var touchstartX = 0, touchstartY = 0, touchendX = 0, touchendY = 0;
document.addEventListener('touchstart', e => {
  touchstartX = e.changedTouches[0].screenX
  touchstartY = e.changedTouches[0].screenY
})
document.addEventListener('touchend', e => {
  touchendY = e.changedTouches[0].screenY;
  let distanceY = touchendY - touchstartY;
  if (distanceY>50 || distanceY<-50) return;
  	
  touchendX = e.changedTouches[0].screenX;
  let distanceX = touchendX - touchstartX;
  if (distanceX>60) next();
  if (distanceX<-60) prev();
})
  

// BACA
function next(){
	let nextSelIdVal = Number($('#sel-id').text())+1;
	let maxSelIdVal = Number($('#sel-id').data('max'));
	if(nextSelIdVal<=maxSelIdVal){
		document.querySelector('#sel-id').textContent=nextSelIdVal;
		baca(nextSelIdVal,1);
	}
}
function prev(){
	let prevSelIdVal = Number($('#sel-id').text())-1;
	if(prevSelIdVal>=1){
		document.querySelector('#sel-id').textContent=prevSelIdVal;
		baca(prevSelIdVal,1);
	}
}
function getClosestToc(id){
	if(typeof tc === 'undefined'){
		myArr = JSON.parse(lsGet('toc')), tc=[];
		for(let i=0; i<myArr.length; i++){
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
	dbManager.get(id).then(data=>{
		try{
			let withHarakatOrNot = Number(lsGet('harakat')||1)==1 ? data.nash : simplifyArabic(data.nash,0);
			bacaAction(withHarakatOrNot, data.terjemah, changeSelId, markText);
			$('#toc-title').text(getClosestToc(id));
			lsSet('lastOpenedId', id);
			lastOpenedId=id;
			if(changeSelId)document.querySelector('#sel-id').textContent=id;
		} catch (e) {
			console.error(e);
			$('#div-terjemah').html(`<p>Ada masalah dengan database. Silakan klik tombol di bawah ini. Jika masih gagal silakan restart aplikasi.</p><button class="btn btn-block btn-primary" onclick="resetAndReload()">Reload Data</button>`);
			$('#div-terjemah').removeClass('hidden');
		}
	})
}
function bacaAction(strNash, strTerjemah, changeSelId, markText){
	if(strNash){
		strNash.replace('\n','<br>');
		$('#div-nash').html(strNash);
		$('#div-nash').removeClass('hidden');
	}else{
		$('#div-nash').addClass('hidden');
	}
	
	if(strTerjemah){
		strTerjemah.replace('\n','<br>');
		$('#div-terjemah').html(strTerjemah);
		$('#div-terjemah').removeClass('hidden');
	}else{
		$('#div-terjemah').addClass('hidden');
	}

	let keywords=document.querySelector('[name=key]').value;
	keywords=fixStrForRegexp(keywords);
	markText && markAction(keywords, document.querySelector("#content"));
	reMark = markText;
	showInterstitialAdsBaca();
}

// BM
function populateBm(max){
	max=Number(max);
	let el='';
	for(let i=1; i <= max; i++){
		if(lsExist('bm_'+i)){
			let text=lsGet('bm_'+i);
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
		let curId = $(this).find('td:nth-child(1)').text();
		lsRem('bm_'+curId);
		$(this).remove();
	});
}

// HISTORY
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
async function xxx() {
  try {
    await dbManager.clearAll();
    if (dbManager.db) dbManager.db.close();
    localStorage.clear();
    sessionStorage.clear();
    await new Promise((resolve, reject) => {
      const req = indexedDB.deleteDatabase(dbManager.databaseName);
      req.onsuccess = () => resolve();
      req.onerror = (e) => reject(e.target.error);
    });
    window.location.reload();
  } catch (error) {
    console.error("Factory reset failed:", error);
  }
}
function factoryReset() {
	xxx().then(()=>console.log("Reset berhasil"))
}

//if(typeof JsInterface === 'undefined') location.href='https://play.google.com/store/apps/dev?id=6117885787606978105';
