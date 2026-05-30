var useBlocking = true, xhr = null;
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
    css: {
      backgroundColor: 'transparent',
      border: 'none'
    },
    //message: '<div class="spinner"></div>',
    message: '<span class="timer">Loading�</span>',
    baseZ: 2000,
    overlayCSS: {
      backgroundColor: 'darkgray',
      opacity: 0.7,
      cursor: 'wait'
    }
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
	var openedModal = $('.modal.in');
	if(openedModal.length){
		openedModal.last().modal('hide');
		return 1;
	}else{
		return 0
	}
}
function getCloseModalCode(idModal){
	return "$('#"+idModal+"').modal('hide')";
}
function simplifyArabic(text) {
  text = text.replace(/[\u0651\u064e\u064b\u064f\u064c\u0650\u064d\u0652\u0653\u0670]/gi, '');
  text = text.replace(/[\u0622\u0623\u0625]/gi, '\u0627');
  return text;
}
function properCase(str) {
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}
function closest(arr,val){
	return Math.max.apply(null, arr.filter(function(v){return v <= val}))
}
function pad(num, size) {
  num = num.toString();
  while (num.length < size) num = "0" + num;
  return num;
}
function matchString(strFind, strObject, multilike, andOr='and'){
	strFind = simplifyArabic(strFind.toLowerCase()), strObject = strObject.toLowerCase();
	if(multilike){
		var spltFind=strFind.split(' ');
		if(andOr==='and'){	//dan
			for(var i=0; i<spltFind.length; i++){
				if(strObject.indexOf(spltFind[i])<0) return false;
			}
			return true;
		}else{							//atau
			for(var i=0; i<spltFind.length; i++){
				if(strObject.indexOf(spltFind[i])>=0) return true;
			}
			return false;
		}
	}
	return strObject.indexOf(strFind)>=0;
}
function markAction(keyword, el){
	if(isAdmin) return;
	keyword=simplifyArabic(keyword);
	var arrKeyword=[];
	var opts = {
		ignorePunctuation: ['"\u0651", "\u064e", "\u064b", "\u064f", "\u064c", "\u0650", "\u064d", "\u0652", "\u0653", "\u0670", "\'", ","'],
		synonyms: {"\u0622": "\u0627", "\u0623": "\u0627", "\u0625": "\u0627", "\u0627": "\u0622", "\u0627": "\u0623", "\u0627": "\u0625"},
		separateWordSearch: true, //default true
	}
	var instance = new Mark(el);
	instance.mark(keyword, opts);
}
function randomInt(min, max) { // min and max included 
  return Math.floor(Math.random() * (max - min + 1) + min)
}
