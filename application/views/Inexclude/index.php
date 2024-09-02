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
		  <a href="<?php echo site_url('inexclude/add') ?>" class="btn btn-md btn-success" id='btn-add'>
			<i class="fa fa-plus"></i> Add
		  </a>
		  <?php
			}
		  ?>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table class="table table-bordered table-striped" id="example1" width='100%'>
			<thead>
				<tr class='bg-purple'>
					<th class="text-center">#</th>
					<th class="text-center">Category</th>
					<th class="text-center">Name</th>
					<th class="text-center">Option</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($result as $key => $value) { $key++;

					$update	= "";
					$delete	= "";
					if($akses_menu['update']=='1'){
						$update	= "<a href='".site_url($this->uri->segment(1).'/add/'.$value['id'])."' class='btn btn-sm btn-primary' title='Edit' data-role='qtip'><i class='fa fa-edit'></i></a>";
					}
					if($akses_menu['delete']=='1'){
						$delete	= "&nbsp;<button class='btn btn-sm btn-danger deleted' title='Delete' data-id='".$value['id']."'><i class='fa fa-trash'></i></button>";
					}
					echo "<tr>";
						echo "<td class='text-center'>".$key."</td>";
						echo "<td class='text-left'>".strtoupper($value['category'])."</td>";
						echo "<td class='text-left'>".strtoupper($value['name'])."</td>";
						echo "<td class='text-center'>".$update.$delete."</td>";
					echo "</tr>";
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

	$(document).on('click', '.deleted', function(){
		var id	= $(this).data('id');
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
					url			: base_url+active_controller+'/hapus/'+id,
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
						}
					},
					error: function() {
						swal({
						  title				: "Error Message !",
						  text				: 'An Error Occured During Process. Please try again..',
						  type				: "warning",
						  timer				: 7000,
						  showCancelButton	: false,
						  showConfirmButton	: false,
						  allowOutsideClick	: false
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
