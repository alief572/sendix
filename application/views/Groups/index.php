<?php
$this->load->view('include/side_menu'); 
?>    
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title"><?php echo $title;?></h3>
		<div class="box-tool pull-right">
		<?php
			if($akses_menu['create']=='1'){
		?>
		  <a href="<?php echo site_url('groups/add') ?>" class="btn btn-sm btn-success" id='btn-add'>
			<i class="fa fa-user"></i> ADD GROUP
		  </a>
		  <?php
			}
		  ?>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped"> 
			<thead>
				<tr class='bg-purple'>
					<th class="text-center">No.</th>
					<th class="text-center">Group</th>
					<th class="text-center">Description</th>
					<th class="text-center">Option</th>
				</tr>
			</thead>
			<tbody>
			  <?php 
			  if($row){
					$int	=0;
					foreach($row as $datas){
						$int++;
						
						echo"<tr>";							
							echo"<td align='center'>$int</td>";
							echo"<td align='left'>".$datas->name."</td>";
							echo"<td align='center'>".$datas->descr."</td>";
							echo"<td align='center'>";
								if($akses_menu['update']=='1'){
									echo"<a href='".site_url('groups/edit_group/'.$datas->id)."' class='btn btn-sm btn-warning' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
									echo"&nbsp;<a href='".site_url('groups/access_menu/'.$datas->id)."' class='btn btn-sm btn-primary' title='Menu Access' data-role='qtip'><i class='fa fa-check'></i></a>";
								}									
								
							echo"</td>";
						echo"</tr>";
					}
			  }
			  ?>
			</tbody>
		</table>
	</div>
	<!-- /.box-body -->
 </div>
  <!-- /.box -->

<?php $this->load->view('include/footer'); ?>
<script>
	$(document).ready(function(){
		$('.btn').click(function(){
			loading_spinner();
		});
		
	});
	
	
</script>
