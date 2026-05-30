<script>
var dbKitab=null;
var lastOpenedId = Number(lsGet('lastOpenedId')) || 1, nodeId=0;
var curURL = window.location.href;
var withHarakat = '', noHarakat = '', reMark=0;
var cntInterstitialAds=0,cntRewardAds=0,cntOpenAds=0;
function initFontSize(){
	var fontSize=Number(lsGet('fontSize')) || 0;
	var arabFont=fontSize==0?'1.3':fontSize==1?'1.5':fontSize==2?'1.7':fontSize==3?'1.9':'2.1';
	var indoFont='1.'+fontSize;
	if(isAdmin){
		$('.panel-nash .ck-editor__main').css('font-size', (parseFloat(arabFont)+0.5)+'em');	
		$('.panel-terjemah .ck-editor__main').css('font-size', indoFont+'em');	
	}else{
		$('#div-nash').css('font-size', arabFont+'em');	
		$('#div-terjemah').css('font-size', indoFont+'em');	
	}
}
function resizeFont(val){
	var fontSize=Number(lsGet('fontSize')) || 0;
	if(val==-1 && fontSize==0) return;
	if(val==1 && fontSize==4) return;
	var newFontSize=fontSize+val;
	lsSet('fontSize', newFontSize);
	initFontSize();
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
	$('#div-nash').html(lsGet('harakat')!=0 ? withHarakat : noHarakat);
	reMark==1 && markAction();
}

function populateSelId(){
	if(isAdmin){
		xhr = $.post(base_url+'admin123/get_max_id/'+kitab, function(maxHal){
			$('#sel-id').data('max', maxHal);
			$('#sel-id-input').prop('max', maxHal);
			$('#sel-id-input').prop('placeholder', '1 s/d '+maxHal);
			populateBm(maxHal);
		});
	}else{
		var maxHal = lsGet('selIdMax');
		$('#sel-id').data('max', maxHal);
		$('#sel-id-input').prop('max', maxHal);
		$('#sel-id-input').prop('placeholder', '1 s/d '+maxHal);
		populateBm(maxHal);
	}
}
function populateToc(){
	$('#toc').jstree({
		core : {data : JSON.parse(lsGet('toc'))} ,
		search	: { show_only_matches : true },
		plugins : ['search']
	});
	$('#toc').off('select_node.jstree');
	$('#toc').on('select_node.jstree', function (e, data) {
		nodeId=data.node.id;
		if($('#modal-toc').data('editmode')!='1'){
			baca(data.node.id, 1);
			$('#modal-toc').modal('hide');
			cntInterstitialAds++;
			if(cntInterstitialAds>1){
				cntInterstitialAds=0;
				showInterstitialAds();
			}
		}
	});
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
function showInterstitialAds(){
	if(!isAdmin){
		if(typeof JsInterface !== 'undefined') JsInterface.showInterstitialAds();
	}
}
function showRewardAds(){
	if(!isAdmin){
		if(typeof JsInterface !== 'undefined') JsInterface.showRewardAds();
	}
}
function showOpenAds(){
	if(!isAdmin){
		if(typeof JsInterface !== 'undefined') JsInterface.showOpenAds();
	}
}
function showInterstitialAdsBaca(){
	if(!isAdmin){
		if(typeof JsInterface !== 'undefined'){
			let cnt = parseInt(localStorage.getItem('cntBaca') || 0);
			if(cnt>10){
				JsInterface.showInterstitialAds();
				localStorage.setItem('cntBaca', 0);
			}else{
				localStorage.setItem('cntBaca', ++cnt);
			}
		}
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
	populateSelId();
	populateHistory();
	invert(1);
	if(lsGet('harakat')==0){
		$('#harakat i').removeClass('glyphicon-check');
		$('#harakat i').addClass('glyphicon-unchecked');
	}
	$("input[name='inverts']").on("change", function () {
    lsSet('invert', $(this).val());
    console.log($(this).val());
    invert(0)
  });
	$('#modal-ke, #modal-bm-add, #modal-bm-add').on('shown.bs.modal', function(){		
		$(this).find('input:nth-child(1)').focus();
	});
	$('#form-ke').submit(function(e){
		e.preventDefault();
		var newHal = $('#sel-id-input').val();
		let maxSelIdVal = Number($('#sel-id-input').prop('max'));
		if(newHal<1)newHal=1;
		if(newHal>maxSelIdVal)newHal=maxSelIdVal;
		baca(newHal,1);
		$('#modal-ke').modal('hide');
		$('#sel-id-input').val('');
	});
	setTimeout(function(){
		initFontSize();
	}, (isAdmin?1000:0));
	
	$('#btn-more').click(function(){
		$('#more').toggleClass('hidden');
		$('#btn-more i').removeClass();
		if($('#more').hasClass('hidden')) $('#btn-more i').addClass('glyphicon glyphicon-menu-hamburger');
		else $('#btn-more i').addClass('glyphicon glyphicon-remove');
	});
	$('#form-cari').submit(function(e){
		e.preventDefault();
		var el='', matchCount=0, key=$('[name=key]').val();
		var re = buildRegexp(key);
		if(!$('#more').hasClass('hidden')) $('#btn-more').click();
		if(isAdmin){
			xhr = $.getJSON(base_url+'main/cari/'+kitab, {re: re}, function(data){
					$.each(data, function(index, value){el += '<button class="btn btn-default" onclick="pilihBtn(this);baca('+value+',1,1);'+getCloseModalCode('#modal-cari')+'">'+value+'</button>';});
					if(!data.length) $('.hasil-pencarian').html('<p class="text-danger">Pencarian: <label>'+key+'</label> tidak ada hasil</p>');
					else{
						$('.hasil-pencarian').html(el);
						historyAdd();
					}
				}
			);
		}
		else{
			for(var id=0; id<dbKitab.length; id++){
				let str = dbKitab[id].noharokat+' '+dbKitab[id].terjemah;
				if(simplifyArabic(str).match(re)){
					el += '<button class="btn btn-default" onclick="pilihBtn(this);baca('+(id+1)+',1,1);closeModal();">'+(id+1)+'</button>';
					matchCount++;
				}
			}
			if(matchCount===0) $('.hasil-pencarian').html('<p class="text-danger">Pencarian: <label>'+key+'</label> tidak ada hasil</p>');
			else{
				$('.hasil-pencarian').html(el);
				historyAdd();
			}
		}
		showInterstitialAds();
	});

	$('#form-bm-add').submit(function(e){
		e.preventDefault();
		bmSave(1);
	});
	$('#form-bm-edit').submit(function(e){
		e.preventDefault();
		bmSave(2);
	});
}
$(function(){
	initApp();
	if(isAdmin){
		downloadToc();
	}
	else{
		if(lsGet('db') != 1){
			showAlert('Menyiapkan database. Mohon tunggu sebentar');
			downloadDB();
		}else{
			populateToc();
			localforage.getItem(kitab+'_dbkitab').then(function (value) {
		    dbKitab = value;
				baca(lastOpenedId, 1);
				cekUpdateDB();
			}).catch(function(err) {
		    showAlert('Gagal membuka database. Silakan restart aplikasi anda: '+err);
			});
		}
	}
})

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
  

</script>