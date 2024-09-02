<?php
$this->load->view('include/side_menu'); 
?>    
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title"><?=$title;?></h3>
		
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		
	</div>
	<!-- /.box-body -->
 </div>
  <!-- /.box -->

<?php $this->load->view('include/footer'); ?>
<script>
	$(document).ready(function(){
		$('.btn').click(function(){
			$('#spinner').modal('show');
		});
	});
</script>
