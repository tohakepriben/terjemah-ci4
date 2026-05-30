<script>
function lsExist(key){
	var fixKey=kitab+'_'+key;
	return null!==localStorage.getItem(fixKey);
}
function lsGet(key){
	var fixKey=kitab+'_'+key;
	return localStorage.getItem(fixKey);
}
function lsSet(key, val){
	var fixKey=kitab+'_'+key;
	localStorage.setItem(fixKey, val);
}
function lsRem(key){
	var fixKey=kitab+'_'+key;
	localStorage.removeItem(fixKey);
}
</script>