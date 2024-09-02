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
		  	<a href="<?php echo site_url('consumable/add') ?>" class="btn btn-md btn-success" id='btn-add'>
				<i class="fa fa-plus"></i> Add
		  	</a>
		  	<?php
			}
		?>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
	<div>
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#man_power" class='man_power' aria-controls="man_power" role="tab" data-toggle="tab">Consumable</a></li>
				<li role="presentation"><a href="#category" class='category' aria-controls="category" role="tab" data-toggle="tab">Category</a></li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="man_power"><br>
					<table class="table table-bordered table-striped" id="example1" width='100%'>
						<thead>
							<tr class='bg-purple'>
								<th class="text-center">#</th>
								<th class="text-center">Category</th>
								<th class="text-center">Material Name</th>
								<th class="text-center">Spesification</th>
								<th class="text-center">Unit</th>
								<th class="text-center">Last By</th>
								<th class="text-center">Last Date</th>
								<th class="text-center">Option</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($data_result as $key => $value) { $key++;
								$update	= "";
								$delete	= "";

								if($akses_menu['update']=='1'){
									$update	= "<a href='".site_url($this->uri->segment(1).'/add/'.$value['code_group'])."' class='btn btn-sm btn-primary' title='Edit' data-role='qtip'><i class='fa fa-edit'></i></a>";
								}
								if($akses_menu['delete']=='1'){
									$delete	= "&nbsp;<button class='btn btn-sm btn-danger deleted' title='Delete' data-code_group='".$value['code_group']."'><i class='fa fa-trash'></i></button>";
								}

								$last_create  = (!empty($value['updated_by']))?$value['updated_by']:$value['created_by'];
								$last_date = (!empty($value['updated_date']))?$value['updated_date']:$value['created_date'];
								echo "<tr>";
									echo "<td class='text-center'>".$key."</td>";
									echo "<td class='text-left'>".strtoupper(get_name('con_nonmat_category','category','id',$value['category_code']))."</td>";
									echo "<td class='text-left'>".strtoupper($value['material_name'])."</td>";
									echo "<td class='text-left'>".strtoupper($value['spec'])."</td>";
									echo "<td class='text-center'>".strtoupper(get_name('unit','unit','id',$value['unit']))."</td>";
									echo "<td class='text-center'>".$last_create."</td>";
									echo "<td class='text-center'>".date('d-M-Y H:i:s', strtotime($last_date))."</td>";
									echo "<td class='text-center'>".$update.$delete."</td>";
								echo "</tr>";
							}
							?>
						</tbody>
					</table>
				</div>
				<div role="tabpanel" class="tab-pane" id="category"><br>
					<table class="table table-bordered table-striped" id="example1" width='100%'>
						<thead>
							<tr class='bg-purple'>
								<th class="text-center">#</th>
								<th class="text-center">Category</th>
								<th class="text-center">Informasi</th>
								<th class="text-center">Option</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($category as $key => $value) { $key++;
								$update = "";
								$delete = "";
								if($akses_menu['update']=='1'){
									$update	= "<a href='".site_url($this->uri->segment(1).'/edit_category/'.$value['id'])."' class='btn btn-sm btn-primary' title='Edit' data-role='qtip'><i class='fa fa-edit'></i></a>";
								}
								if($akses_menu['delete']=='1'){
									$delete	= "&nbsp;<button class='btn btn-sm btn-danger deleted2' title='Delete' data-id='".$value['id']."'><i class='fa fa-trash'></i></button>";
								  }
								echo "<tr>";
									echo "<td class='text-center'>".$key."</td>";
									echo "<td>".strtoupper($value['category'])."</td>";
									echo "<td>".strtoupper($value['information'])."</td>";
									echo "<td class='text-center'>".$update.$delete."</td>";
								echo "</tr>";
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
	</div>
	<!-- /.box-body -->
 </div>
  <!-- /.box -->

<?php $this->load->view('include/footer'); ?>
<style>
	.chosen-container{
		width: 100% !important;
		text-align : left !important;
	}
</style>
<script>
	$(document).ready(function(){
		// DataTables();
	});

	$(document).on('click', '.deleted', function(){
		var code_group	= $(this).data('code_group');
		// alert(bF);
		// return false;
		swal({
			title: "Are you sure?",
			text: "Delete this data ?",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Yes, Process it!",
			cancelButtonText: "No, cancel process!",
			closeOnConfirm: true,
			closeOnCancel: false
		},
		function(isConfirm) {
			if (isConfirm) {
				loading_spinner();
				$.ajax({
					url			: base_url+active_controller+'/hapus/'+code_group,
					type		: "POST",
					cache		: false,
					dataType	: 'json',
					processData	: false,
					contentType	: false,
					success		: function(data){
						if(data.status == 1){
							swal({
								  title	: "Save Success!",
								  text	: data.pesan,
								  type	: "success",
								  timer	: 3000
								});
							window.location.href = base_url + active_controller;
						}
						else if(data.status == 0){
							swal({
							  title	: "Save Failed!",
							  text	: data.pesan,
							  type	: "warning",
							  timer	: 3000
							});
						}
					},
					error: function() {
						swal({
						  title	: "Error Message !",
						  text	: 'An Error Occured During Process. Please try again..',
						  type	: "warning",
						  timer	: 3000
						});
					}
				});
			} else {
			swal("Cancelled", "Data can be process again :)", "error");
			return false;
			}
		});
	});

	$(document).on('click', '.deleted2', function(){
		var id	= $(this).data('id');
		swal({
			title: "Are you sure?",
			text: "Delete this data ?",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Yes, Process it!",
			cancelButtonText: "No, cancel process!",
			closeOnConfirm: true,
			closeOnCancel: false
		},
		function(isConfirm) {
			if (isConfirm) {
				loading_spinner();
				$.ajax({
					url			: base_url+active_controller+'/hapus_category/'+id,
					type		: "POST",
					cache		: false,
					dataType	: 'json',
					processData	: false,
					contentType	: false,
					success		: function(data){
						if(data.status == 1){
							swal({
								  title	: "Save Success!",
								  text	: data.pesan,
								  type	: "success",
								  timer	: 3000
								});
							window.location.href = base_url + active_controller;
						}
						else if(data.status == 0){
							swal({
							  title	: "Save Failed!",
							  text	: data.pesan,
							  type	: "warning",
							  timer	: 3000
							});
						}
					},
					error: function() {
						swal({
						  title				: "Error Message !",
						  text				: 'An Error Occured During Process. Please try again..',
						  type				: "warning",
						  timer				: 3000
						});
					}
				});
			} else {
			swal("Cancelled", "Data can be process again :)", "error");
			return false;
			}
		});
	});

</script>
