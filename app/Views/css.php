<style>
@media (min-width: 768px){
	.col-sm-7 {
	   padding-left: 0;
	}	
}
.invert{
	-webkit-filter: invert(1);
  filter: invert(1);
}
.invert1 body{
  opacity: 100%;
}
.invert2 body{
  opacity: 80%;
}
.invert3 body{
  opacity: 60%;
}
.invert4 body{
  opacity: 40%;
}
.bold{
	font-weight: bold;
}
.btn-pressed {
  transform: scale(0.9);
  background-color: #444 !important; /* sedikit gelap */
  box-shadow: inset 0 3px 5px rgba(0,0,0,0.3);
  transition: transform 0.1s ease, background-color 0.1s ease;
  font-weight: bolder; 
}
body{
	padding-top: 60px;
	padding-bottom: 60px;
}
html.admin>body{
	padding-bottom: 120px;
}
html:not(.admin) *{
	user-select: none;;
}
.navbar-brand{
	height: 40px;
  padding: 13px 8px 13px 10px;
}
.modal.allow-scroll .modal-dialog{
    overflow-y: initial !important;
}
.modal.allow-scroll .modal-body{
    max-height: 70vh;
    overflow-y: auto;
}
.jstree-node>a>.jstree-icon{
	background-color: transparent;
  background-image: none;
  background-position: 0 0;
	position: relative;
  top: 1px;
  display: inline-block;
  font-family: 'Glyphicons Halflings';
  font-style: normal;
  font-weight: 400;
  line-height: 1;
  -webkit-font-smoothing: antialiased;
	font-size: .7em;
	transform: rotateY(-180deg);
	margin-right: -6px !important;
}
.jstree-node:not(.jstree-leaf)>a>.jstree-icon{
	color: darkblue;
}
.jstree-node:not(.jstree-leaf)>a>.jstree-icon:before{
	content: "\e043";
	box-sizing: border-box;
}
.jstree-node.jstree-leaf>a>.jstree-icon:before{
	content: "\e022";
	box-sizing: border-box;
}

/*Node 1*/
ul.jstree-container-ul>li>.jstree-anchor{
	width: calc(100% - 20px);
}
/*Node 2*/
ul.jstree-container-ul>li>ul>li>.jstree-anchor{
	width: calc(100% + 5px);
}
/*Node 3*/
ul.jstree-container-ul>li> ul>li>ul>li>.jstree-anchor{
	width: calc(100% + 30px);
}
/*Node 4*/
ul.jstree-container-ul>li>ul>li>ul>li>ul>li>.jstree-anchor{
	width: calc(100% + 55px);
}
/*Node 5*/
ul.jstree-container-ul>li>ul>li>ul>li>ul>li>ul>li>.jstree-anchor{
	width: calc(100% + 78px);
}
/*Node 6*/
ul.jstree-container-ul>li>ul>li>ul>li>ul>li>ul>li>ul>li>.jstree-anchor{
	width: calc(100% + 101px);
}
/*Node 7*/
ul.jstree-container-ul>li>ul>li>ul>li>ul>li>ul>li>ul>li>ul>li>.jstree-anchor{
	width: calc(100% + 125px);
}
/*End Node*/
.jstree-anchor{
	height: auto !important;
	white-space: normal;
}
.jstree-anchor label{
  float: left!important;
  font-size: 12px;
}
.jstree-anchor p{
  font-size: 14px;
  margin: -3px 20px 0 0;
  line-height: initial;
}
.select2-selection{
	height: 46px !important;
	border-radius: 0 !important;
}
.select2-selection__arrow{
	display: none !important;
}
.select2-selection__rendered{
	padding-left: 0 !important;
	padding-right: 0 !important;
	text-align: center !important;
	margin-top: 6px !important;
	font-weight: bold;
}
input[name=key], #caritoc{
	font-weight: bold;;
}
.hasil-pencarian>button{
	margin: 2px;
}
#tbl-bookmark tr td:first-child{
	width: 30px;
}
#content table{
	width: 100%;
	border-collapse: collapse;
}
#content ul, #content ol {
	padding-inline-start: 20px
}
#content>div>hr{
	width: 30%;
  margin-bottom: 10px;
  border-color: gray;
  margin-left: initial;
}
#content table, #content tr, #content td{
	border: 1px solid black;
}
#content>#div-terjemah [lang=ar]{
	font-size: 1.3em
}
#tbl-bookmark tr td:nth-child(1){
	width: 40px;
	padding-left: 5px;
}
#tbl-bookmark tr td:nth-child(2){
	width: calc(100% - 120px);
}
#tbl-bookmark tr td:nth-child(3){
	width: 80px;
	text-align: right;
}
#tbl-bookmark tr{
	border-bottom: 1px solid lightgray;
}
#tbl-bookmark tr:last-child{
	border-bottom: none;
}
#tbl-bookmark tr td button{
	background: none;
	background-color: none;
}
#sel-id{
	padding-left:0;padding-right: 0;vertical-align: middle;text-align: center; font-weight: bold;
}
.footer .common button:not(#sel-id){
	padding: 5px;
}
.footer .common button .glyphicon, #sel-id{
	color: darkslategray;
}
.footer .common button .glyphicon:after{
	display: block;
	font-size: 9px;
	font-weight: bold;
	font-family: sans-serif;
  margin-top: 3px;
}
.footer .common button .glyphicon-search:after{content: "CARI";}
.footer .common button .glyphicon-list:after{content: "DFT. ISI";}
.footer .common button .glyphicon-chevron-left:after{content: "NEXT";}
.footer .common button .glyphicon-chevron-right:after{content: "PREV";}
.footer .common button .glyphicon-bookmark:after{content: "P'NANDA";}
.footer .common button .glyphicon-adjust:after{content: "TEMA";}
</style>