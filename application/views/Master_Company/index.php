<?php

$this->load->view('include/side_menu'); 
?> 
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title"><?php echo $title;?></h3>	
		
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<!-- <table class="table table-striped table-bordered" id="example1"> -->
		<table id="my-grid" class="table table-striped table-bordered table-hover table-condensed" width="100%">
			<thead>
				<tr class='bg-blue'>
					<td class="text-center">No</td>
					<td class="text-center">Company</td>
					<!--<td class="text-center">Address</td>-->
					<td class="text-center">Phone</td>
					<td class="text-center">Fax</td>
					<td class="text-center">Website</td>
					<td class="text-center">Action</td>
				</tr>
			</thead>
			<tbody>
				<?php 
				$i=0;
				foreach($rows_data as $val){
					$i++;
				?>
				<tr>
					<td class='text-center'><?php echo $i ?></td>
					<td class='text-left'><?php echo $val->nm_perusahaan ?></td>
					<!--<td class='text-left'><?php echo $val->alamat ?></td>-->
					<td class='text-center'><?php echo $val->no_telp ?></td>
					<td class='text-center'><?php echo $val->fax ?></td>
					<td class='text-center'><?php echo $val->website ?></td>
					
					<?php 
						
						echo"<td align='center'>";
							if($akses_menu['read']=='1'){
								echo"&nbsp;<a href='".site_url('company_master/view/'.$val->ididentitas)."' class='btn btn-sm btn-info' title='View Data' data-role='qtip' id='btn-view'><i class='fa fa-search'></i></a>";
							}
							if($akses_menu['update']=='1'){
								echo"&nbsp;<a href='".site_url('company_master/edit/'.$val->ididentitas)."' class='btn btn-sm bg-purple' title='Edit Data' data-role='qtip' id='btn-edit'><i class='fa fa-edit'></i></a>";
								
							}									
							
						echo"</td>";
					?>
				</tr>
				
				<?php 
				} 
				?>
			</tbody>
		</table>						
	</div>
 </div>
  <?php $this->load->view('include/footer'); ?>
<script>
$(document).ready(function(){
	// DataTables();
	$('#my-grid').DataTable();
	$('#btn-edit, #btn-view').click(function(){
		loading_spinner();
	}
});

</script>
