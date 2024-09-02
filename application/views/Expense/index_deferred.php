<?php
$this->load->view('include/side_menu');
?>
<form action="#" method="POST" id="form_ct">
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?php echo $title;?></h3>
			<div class="box-tool pull-right">
			<?php if($akses_menu['create']=='1'){ ?>
			  <button type='button' class="btn btn-md btn-info" id='add'><i class="fa fa-plus"></i> Add Bank</button>
			  <?php } ?>
			</div>
		</div>
		<div class="box-body">
			<div class="table-responsive col-lg-12">
			<table class="table table-bordered table-striped" id="my-grid" width='100%'>
				<thead>
					<tr class='bg-blue'>
						<th class="text-center">Kode Transaksi</th>
						<th class="text-center">No SO</th>
						<th class="text-center">Tanggal</th>
						<th class="text-center">Tipe</th>
						<th class="text-center">Keterangan</th>
						<th class="text-center">Qty</th>
						<th class="text-center">Nilai</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
			</div>
		</div>
	 </div>
	<div class="modal fade" id="ModalView">
		<div class="modal-dialog"  style='width:60%; '>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="head_title"></h4>
				</div>
				<div class="modal-body" id="view"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</form>
<?php $this->load->view('include/footer'); ?>
<script>
	$(document).ready(function(){
		DataTables();
	});
	function DataTables(){
		var dataTable = $('#my-grid').DataTable({
			"processing" : true, "serverSide": true, "stateSave" : true, "bAutoWidth": true, "destroy": true, "responsive": true,
			"oLanguage": {
				"sSearch": "<b>Live Search : </b>",
				"sLengthMenu": "_MENU_ &nbsp;&nbsp;<b>Records Per Page</b>&nbsp;&nbsp;",
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ entries",
				"sInfoFiltered": "(filtered from _MAX_ total entries)",
				"sZeroRecords": "No matching records found",
				"sEmptyTable": "No data available in table",
				"sLoadingRecords": "Please wait - loading...",
				"oPaginate": {
					"sPrevious": "Prev", "sNext": "Next"
				}
			},
			"aaSorting": [[ 1, "asc" ]],
			"columnDefs": [ {
				"targets": 'no-sort',
				"orderable": false,
			}],
			"sPaginationType": "simple_numbers",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 20, 50, 100, 150], [10, 20, 50, 100, 150]],
			"ajax":{
				url : base_url +'index.php/'+active_controller+'/data_side_deferded',
				type: "post",
				data: function(d){
				},
				cache: false,
				error: function(){
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="9">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
</script>
