<?php
$this->load->view('include/side_menu');
?>
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title"><?php echo $title;?></h3>
		<div class="box-tool pull-right">
		
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table class="table table-bordered table-striped" id="my-grid" width='100%'>
			<thead>
				<tr class='bg-purple'>
					<th class="text-center">#</th>
					<th class="text-center">IPP</th>
					<th class="text-center">Customer Name</th>
					<th class="text-center">Project Name</th>
					<th class="text-center">Selling Price</th>
					<th class="text-center">Penawaran</th>
					<th class="text-center no-sort">Option</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
	<!-- /.box-body -->
 </div>
  <!-- /.box -->

 	<!-- modal -->
 	<div class="modal fade" id="ModalView2">
		<div class="modal-dialog"  style='width:40%; '>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="head_title2"></h4>
					</div>
					<div class="modal-body" id="view2">
					</div>
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

	$(document).on('click', '.reject', function(e){
		e.preventDefault();
		loading_spinner();
		$("#head_title2").html("<b>REASON REJECT</b>");
		$("#view2").load(base_url + active_controller+'/dialog_reject/'+$(this).data('project_code'));
		$("#ModalView2").modal();
	});

	function DataTables(){
		var dataTable = $('#my-grid').DataTable({
			"processing" : true,
			"serverSide": true,
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
				url : base_url + active_controller+'/getDataJSON',
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

	$(document).on('click', '#reject', function(){
		var project_code		= $('#project_code').val();
		var status				= $('#status').val();
    	var reason_approved		= $("#reason_approved").val();

		if(status=='0'){
			swal({title:"Error Message!", text:'Action belum dipilih ...', type:"warning"});
			$('#reject').prop('disabled',false);
			return false;
		}

		if(reason_approved==''){
			swal({title:"Error Message!", text:'Empty Reason, please input first ...', type:"warning"});
			$('#reject').prop('disabled',false);
			return false;
		}

		swal({
			title: "Are you sure?",
			text: "Reject this data ?",
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
					url			: base_url+active_controller+'/dialog_reject',
					type		: "POST",
          			data		: {
						'project_code' : project_code,
						'status' : status,
						'reason' : reason_approved 
					},
					cache		: false,
					dataType	: 'json',
					success		: function(data){
						if(data.status == 1){
							swal({
									title	: "Save Success!",
									text	: data.pesan,
									type	: "success",
									timer	: 3000,
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
