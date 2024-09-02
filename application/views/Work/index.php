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
		  <a href="<?php echo site_url('work/add') ?>" class="btn btn-md btn-success" id='btn-add'>
			<i class="fa fa-plus"></i> Add
		  </a>
		  <!--
		  <a href="<?php echo site_url('work/ExcelMasterDownload') ?>" target='_blank' class="btn btn-md btn-info">
			<i class="fa fa-file-excel-o"></i> Download
		  </a>
		  -->
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
					<th class="text-center" width='30%'>Process List Name</th>
					<th class="text-center">Total Time (Day)</th>
					<!-- <th class="text-center">Tipe Instalasi</th> -->
					<th class="text-center">Created By</th>
					<th class="text-center">Created Date</th>
					<th class="text-center">Option</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($result as $key => $value) { $key++;
					$detail = "<button type='button' class='btn btn-sm btn-warning detail' title='Detail' data-code_work='".$value['code_work']."'><i class='fa fa-eye'></i></button>";
					$update	= "";
					$delete	= "";
					if($akses_menu['update']=='1'){
						$update	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/edit/'.$value['code_work'])."' class='btn btn-sm btn-primary' title='Edit' data-role='qtip'><i class='fa fa-edit'></i></a>";
					}
					if($akses_menu['delete']=='1'){
						$delete	= "&nbsp;<button class='btn btn-sm btn-danger deleted' title='Delete' data-id='".$value['code_work']."'><i class='fa fa-trash'></i></button>";
					}

					$last_by 	= (!empty($value['updated_by']))?$value['updated_by']:$value['created_by'];
					$last_date = (!empty($value['updated_date']))?$value['updated_date']:$value['created_date'];

					echo "<tr>";
						echo "<td class='text-center'>".$key."</td>";
						echo "<td class='text-left'>".strtoupper($value['category'])."</td>";
						echo "<td class='text-center'>".$value['total_time']."</td>";
						// echo "<td class='text-center'>".strtoupper($value['tipe'])."</td>";
						echo "<td class='text-center'>".$last_by."</td>";
						echo "<td class='text-right'>".date('d-M-Y H:i:s', strtotime($last_date))."</td>";
						echo "<td class='text-center'>".$detail.$update.$delete."</td>";
					echo "</tr>";
				}
				?>
			</tbody>
		</table>
	</div>
	<!-- /.box-body -->
 </div>
  <!-- /.box -->

  <!-- modal -->
	<div class="modal fade" id="ModalView">
		<div class="modal-dialog"  style='width:60%; '>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="head_title"></h4>
					</div>
					<div class="modal-body" id="view">
					</div>
					<div class="modal-footer">
					<!--<button type="button" class="btn btn-primary">Save</button>-->
					<button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- modal -->

<?php $this->load->view('include/footer'); ?>
<script>
	$(document).ready(function(){
		DataTables();
	});

	$(document).on('click', '.detail', function(e){
		e.preventDefault();
		loading_spinner();
		$("#head_title").html("<b>Detail Process List ["+$(this).data('code_work')+"]</b>");
		$("#view").load(base_url + active_controller+'/modalDetail/'+$(this).data('code_work'));
		$("#ModalView").modal();
	});

	function DataTables(){
		var dataTable = $('#my-grid').DataTable({
			// "scrollX": true,
			// "scrollY": "500",
			// "scrollCollapse" : true,
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
				url : base_url +active_controller+'/getDataJSON',
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
		var code_work	= $(this).data('id');
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
					url			: base_url+active_controller+'/hapus/'+code_work,
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
