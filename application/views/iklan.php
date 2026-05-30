<div id="modal-iklan" class="modal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
			<div class="modal-header">
        <h4 class="modal-title"><i class="glyphicon glyphicon-cloud-download"></i>&nbsp;&nbsp;Aplikasi Lainnya</h4>
      </div>
    	<div class="modal-body">
			  <div id="myCarousel" class="carousel slide" data-ride="carousel">

			    <!-- Wrapper for slides -->
			    <div class="carousel-inner">
			    	<div class="item active">
			    		<a href="https://play.google.com/store/apps/details?id=com.tohakepriben.kamus">
			    			<img src="<?=base_url('assets/iklan/kmtq.png')?>">
			    		</a>
			    	</div>
			    	<div class="item">
			    		<a href="https://play.google.com/store/apps/details?id=com.tohakepriben.kalkulatorwaris">
			    			<img src="<?=base_url('assets/iklan/kalkulator-waris.png')?>">
			    		</a>
			    	</div>
			    	<div class="item">
			    		<a href="https://play.google.com/store/apps/details?id=com.tohakepriben.majmunadhom">
			    			<img src="<?=base_url('assets/iklan/majmu-nadhom.png')?>">
			    		</a>
			    	</div>
			    	<div class="item">
			    		<a href="https://play.google.com/store/apps/details?id=com.tohakepriben.kutubhadits">
			    			<img src="<?=base_url('assets/iklan/kutub-hadits.png')?>">
			    		</a>
			    	</div>
			    	<?php if($kitab != 'fathul_qorib'): ?>
			    	<div class="item">
			    		<a href="https://play.google.com/store/apps/details?id=com.tohakepriben.fathulqorib">
			    			<img src="<?=base_url('assets/iklan/fathul-qorib.png')?>">
			    		</a>
			    	</div>
			    	<?php endif; ?>
<!--			    	<div class="item hidden">
			    		<a href="https://play.google.com/store/apps/dev?id=6117885787606978105">
			    			<img src="<?=base_url('assets/iklan/lainnya.png')?>">
			    		</a>
			    	</div>
-->			    </div>

			    <!-- Left and right controls -->
			    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
			      <span class="glyphicon glyphicon-chevron-left"></span>
			      <span class="sr-only">Prev</span>
			    </a>
			    <a class="right carousel-control" href="#myCarousel" data-slide="next">
			      <span class="glyphicon glyphicon-chevron-right"></span>
			      <span class="sr-only">Next</span>
			    </a>
			  </div>

    	</div>
    </div>
  </div>
</div>
<script>
$(function(){
	$('#modal-iklan').modal('show');
})
</script>