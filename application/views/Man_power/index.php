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
		<a href="<?php echo site_url('man_power/add') ?>" class="btn btn-md btn-success" id='btn-add'>
			<i class="fa fa-plus"></i> Add
		</a>
		<?php
			}
		?>
		</div>
	</div>
	<div class="box-body">
		<div>
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#man_power" class='man_power' aria-controls="man_power" role="tab" data-toggle="tab">Man Power</a></li>
				<li role="presentation"><a href="#category" class='category' aria-controls="category" role="tab" data-toggle="tab">Category</a></li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="man_power"><br>
					<table class="table table-bordered table-striped" id="my-grid" width='100%'>
						<thead>
							<tr class='bg-purple'>
								<th class="text-center" width='6%'>#</th>
								<th class="text-center">Category</th>
								<th class="text-center">Sertification</th>
								<th class="text-center">Note</th>
								<th class="text-center no-sort">Last By</th>
								<th class="text-center no-sort">Last Date</th>
								<th class="text-center no-sort" width='10%'>Option</th>
							</tr>
						</thead>
						<tbody></tbody>
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

	
	<!-- /.box-header -->
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
		DataTables();
	});

	$(document).on('click', '#update_cost', function(){
		swal({
		  title: "Update Master ?",
		  text: "Tunggu sampai 'Last Update by ' menunjukan nama user dan update jam sekarang. ",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonClass: "btn-danger",
		  confirmButtonText: "Ya, Update!",
		  cancelButtonText: "Tidak, Batalkan!",
		  closeOnConfirm: true,
		  closeOnCancel: false
		},
		function(isConfirm) {
			if (isConfirm) {
				// loading_spinner();
				$('#spinnerx').show();
				$.ajax({
					url			: base_url+'index.php/insert_select/insert_select_mp',
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
							  timer	: 7000,
							  showCancelButton	: false,
							  showConfirmButton	: false,
							  allowOutsideClick	: false
							});
							$('#spinnerx').hide();
							window.location.href = base_url + active_controller;
						}
						else if(data.status == 0){
							swal({
							  title	: "Save Failed!",
							  text	: data.pesan,
							  type	: "warning",
							  timer	: 7000,
							  showCancelButton	: false,
							  showConfirmButton	: false,
							  allowOutsideClick	: false
							});
							$('#spinnerx').hide();
						}
					},
					error: function() {
						swal({
						  title				: "Error Message !",
						  text				: 'Connection Time Out. Please try again..',
						  type				: "warning",
						  timer				: 7000,
						  showCancelButton	: false,
						  showConfirmButton	: false,
						  allowOutsideClick	: false
						});
						$('#spinnerx').hide();
					}
				});
			} else {
			swal("Cancelled", "Data can be process again :)", "error");
			return false;
			}
		});
	});

	function DataTables(){
		var dataTable = $('#my-grid').DataTable({
			"serverSide": true,
			"processing" : true,
			"stateSave" : true,
			"bAutoWidth": true,
			"destroy": true,
			"responsive": true,
			"aaSorting": [[ 1, "asc" ]],
			"columnDefs": [ {
				"targets": 'no-sort',
				"orderable": false,
			}],
			"sPaginationType": "simple_numbers",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 20, 50, 100, 150], [10, 20, 50, 100, 150]],
			"ajax":{
				url : base_url +active_controller+'/data_side_man_power',
				type: "post",
				data: function(d){
					// d.kode_partner = $('#kode_partner').val()
				},
				cache: false,
				error: function(){
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}

	$(document).on('click', '.deleted', function(){
		var code_group	= $(this).data('code_group');
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
