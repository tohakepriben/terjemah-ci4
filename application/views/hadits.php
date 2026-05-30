<!doctype html>
<html lang="id" translate="no">
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width,initial-scale=1"/>
		<script src="<?=base_url('assets/jquery/jquery.min.js')?>"></script>
		<script src="<?=base_url('assets/FileSaver.js')?>"></script>
	</head>
	<body style="max-width: 768px">
	<select style="width: 300px;font-size:24px">
		<?php $arr = explode(',',$tables); for($i=0;$i<count($arr);$i++): ?>
		<option value="<?=$arr[$i]?>"><?=$i.' - '.$arr[$i]?></option>
		<?php endfor; ?>
	</select><br>
	<div><input type="checkbox" id="chk-jsinterface"/>&nbsp;<label>Cek JsInterface</label></div>
	<button onclick="sedot()">Download</button>
	<button onclick="sedotAll()">Download ALL</button><br>
	<div id="msg" style="color: #4d0000; border: 1px solid gray; width: 100%; min-height: 300px; margin-top: 20px;padding: 10px">
		<div>
			<label>Console:</label>&nbsp;&nbsp;<button onclick="$('#msg>code').html('')">Clear</button>
		</div>
		<code></code>
	</div>
	
	<script>
		var baseUrl = '<?=base_url("hadits/")?>';
		function sedotAll(){
			$('select option').each(function(){
				var tbl = $(this).prop('value');
				var tblIndex = $(this).index();
				var fname = 'data-' + tblIndex + '.js';
				getJson(tbl,tblIndex,fname);
				//addLog(' Tabel:'+tbl+' Index:'+tblIndex+' FName:'+fname);
			});
		}
		function sedot(){
			var tbl = $('select').val();
			var tblIndex = $('select').find(':selected').index();
			var fname = 'data-' + tblIndex + '.js';
			getJson(tbl,tblIndex,fname);
		}
		function getJson(tbl,tblIndex,fname){
			var str,msg;
			addLog(' Tabel:'+tbl+' Index:'+tblIndex+' FName:'+fname);
			$.getJSON(baseUrl+'get/'+tbl, function(data){
				str = "dbKitab["+tblIndex+"] = "+(JSON.stringify(data))+";";
				str += "localforage.setItem('dbKitab"+tblIndex+"',dbKitab["+tblIndex+"],function(){lsSet('dbKitab"+tblIndex+"', 1)});";
				if($('#chk-jsinterface').is(':checked')) str+='if(typeof JsInterface == "undefined") location.href="https://tohakepriben.com";';
				$.getJSON(baseUrl+'get_toc/'+tbl, function(data){
					str += "dbKitabToc["+tblIndex+"] = "+(JSON.stringify(data))+";";
					str += "localforage.setItem('dbKitabToc"+tblIndex+"',dbKitabToc["+tblIndex+"],function(){lsSet('dbKitabToc"+tblIndex+"', 1)});";
					str += "loadData("+(tblIndex+1)+");";
					var blob = new Blob([str], {type: "text/plain;charset=utf-8"});
					saveAs(blob, fname);
				});
			});			
		}
		function addLog(msg){
			var mydate = new Date().toISOString().split('T')[0];
			var mytime = new Date().toISOString().split('T')[1];
			$('#msg>code').html($('#msg>code').html()+'<br>&raquo; '+mydate+' '+mytime+' '+msg);
		}
		function beauty(text){
			return text.replace(/},/gi,`},\n`)+`\n`;
		}
		$(function(){$.ajaxSetup({async:false})});
	</script>
	</body>
</html>