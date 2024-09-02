<?php
$this->load->view('include/side_menu');
//echo"<pre>";print_r($data_menu);
?>
<form action="#" method="POST" id="form_work">
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?php echo $title;?></h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Work Name<span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'category','name'=>'category','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Work Name'));
					?>
				</div>
			</div><br>
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title">List Step Process</h3>
				</div>
				<!-- style="overflow-x:auto;" -->
				<div class="box-body" >
					<input type='hidden' name='numberMax' id='numberMax' value='0'>
					<button type="button" id='add_sp' style='width:130px; margin-right:0px; margin-bottom:3px; margin-left:5px; float:right;' class="btn btn-info btn-sm">Add Step Process</button>
					<table id="my-grid" class="table table-striped table-bordered table-hover table-condensed" width="100%">
						<thead id='head_table'>
							<tr class='bg-purple'>
								<th class="text-center" style='width: 15%;'>Work Process</th>
								<th class="text-center" style='width: 78%;'>Specification</th>
								<th class="text-center" style='width: 7%;'>#</th>
							</tr>
						</thead>
						<tbody id='detail_body'></tbody>
						<tbody id='detail_body_empty'>
							<tr>
								<td colspan='3'><b>List Empty ...</b></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class='box-footer'>
			<?php
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-primary','value'=>'save','content'=>'Save','id'=>'save_work')).' ';
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','value'=>'back','content'=>'Back','id'=>'back_work'));
			?>
		</div>
		<!-- /.box-body -->
	 </div>
  <!-- /.box -->
</form>

<?php $this->load->view('include/footer'); ?>
<style type="text/css">
	.chosen-container-active .chosen-single {
	     border: none;
	     box-shadow: none;
	}
	.chosen-container-single .chosen-single {
		height: 34px;
	    border: 1px solid #d2d6de;
	    border-radius: 0px;
	     background: none;
	    box-shadow: none;
	    color: #444;
	    line-height: 32px;
	}
	.chosen-container-single .chosen-single div{
		top: 5px;
	}
	#save_category {
	  color: white;
	  background-color: #605ca8;
	}
	.labDet{
		font-weight: bold;
		margin: 5px 0px 3px 5px;
		color: #0376c7;
	}
</style>
<script>
	$(document).ready(function(){
		var nomor	= 1;
		$('#add_sp').click(function(e){
			e.preventDefault();
			var nilaiAwal	= parseInt($("#numberMax").val());
			var nilaiAkhir	= nilaiAwal + 1;
			$("#numberMax").val(nilaiAkhir);

			AppendBaris(nomor, nilaiAkhir);
			$('#head_table').show();
			$('.chosen_select').chosen({width: '100%'});

			$("#detail_body_empty").hide();
			$('#save_work').show();
		});

		//save
		$('#save_work').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var category	= $('#category').val();

			if(category=='' || category==null){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Work Category, please input first ...',
				  type	: "warning"
				});
				$('#save_work').prop('disabled',false);
				return false;
			}

			swal({
				  title: "Are you sure?",
				  text: "You will not be able to process again this data!",
				  type: "warning",
				  showCancelButton: true,
				  confirmButtonClass: "btn-danger",
				  confirmButtonText: "Yes, Process it!",
				  cancelButtonText: "No, cancel process!",
				  closeOnConfirm: false,
				  closeOnCancel: false
				},
				function(isConfirm) {
				  if (isConfirm) {
						loading_spinner();
						var formData 	= new FormData($('#form_work')[0]);
						var baseurl		= base_url + active_controller +'/add';
						$.ajax({
							url			: baseurl,
							type		: "POST",
							data		: formData,
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
										  timer	: 7000
										});
									window.location.href = base_url + active_controller;
								}
								else if(data.status == 2 || data.status == 3){
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 7000
									});
								}

								$('#save_work').prop('disabled',false);
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
								$('#save_work').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#save_work').prop('disabled',false);
					return false;
				  }
			});
		});



		$('#back_work').click(function(e){
			window.location.href = base_url + active_controller;
		});

	});

	function AppendBaris(intd){
		var nomor	= 1;
		var valuex	= $('#detail_body').find('tr').length;
		if(valuex > 0){
			var akhir	= $('#detail_body tr:last').attr('id');
			var det_id	= akhir.split('_');
			var nomor	= parseInt(det_id[1])+1;
		}

		var Rows	 = 	"<tr id='tr_"+nomor+"'>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<div class='labDet'>Work Process</div><input type='text' name='ListDetail["+nomor+"][work_process]' id='work_process_"+nomor+"' class='form-control input-sm' placeholder='Work Process "+nomor+"'>";
			Rows	+=				"<div class='labDet'>Information</div><textarea name='ListDetail["+nomor+"][information]' id='information_"+nomor+"' class='form-control input-sm' placeholder='Information "+nomor+"'></textarea>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left' style='text-align: left;'>";
			Rows	+=			"<div class='labDet'>Vehicles and Tools</div><select name='ListDetail["+nomor+"][vehicle_tool][]' id='product_vehicle_tool_"+nomor+"' class='chosen_select form-control inline-block' multiple></select>";
			Rows	+=			"<div class='labDet'>APD</div><select name='ListDetail["+nomor+"][apd][]' id='product_apd_"+nomor+"' class='chosen_select form-control inline-block' multiple></select>";
			Rows	+=			"<div class='labDet'>Consumable Non Material</div><select name='ListDetail["+nomor+"][con_nonmat][]' id='product_con_nonmat_"+nomor+"' class='chosen_select form-control inline-block' multiple></select>";
			Rows	+=			"<div class='labDet'>Accommodation</div><select name='ListDetail["+nomor+"][akomodasi][]' id='product_akomodasi_"+nomor+"' class='chosen_select form-control inline-block' multiple></select>";
			Rows	+=			"<div class='labDet'>Man Power</div><select name='ListDetail["+nomor+"][man_power][]' id='product_man_power_"+nomor+"' class='chosen_select form-control inline-block' multiple></select>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='center'>";
			Rows 	+=			"<button type='button' style='min-width:70px;' class='btn btn-danger btn-sm' data-toggle='tooltip' data-placement='bottom' onClick='delRow("+nomor+")' title='Delete Record'>Del Row</button>";
			Rows	+= 		"</td>";
			Rows	+= 	"</tr>";

		$('#detail_body').append(Rows);

		var list_vehicle_tool 	= '#product_vehicle_tool_'+nomor;
		var list_apd 			= '#product_apd_'+nomor;
		var list_con_nonmat 	= '#product_con_nonmat_'+nomor;
		var list_akomodasi 		= '#product_akomodasi_'+nomor;
		var list_man_power 		= '#product_man_power_'+nomor;

		$.ajax({
			url: base_url+'index.php/'+active_controller+'/list_vehicle_tool',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$(list_vehicle_tool).html(data.option).trigger("chosen:updated");
			}
		});

		$.ajax({
			url: base_url+'index.php/'+active_controller+'/list_apd',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$(list_apd).html(data.option).trigger("chosen:updated");
			}
		});

		$.ajax({
			url: base_url+'index.php/'+active_controller+'/list_con_nonmat',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$(list_con_nonmat).html(data.option).trigger("chosen:updated");
			}
		});

		$.ajax({
			url: base_url+'index.php/'+active_controller+'/list_akomodasi',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$(list_akomodasi).html(data.option).trigger("chosen:updated");
			}
		});

		$.ajax({
			url: base_url+'index.php/'+active_controller+'/list_man_power',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$(list_man_power).html(data.option).trigger("chosen:updated");
			}
		});
	}

	function delRow(row){
		$('#tr_'+row).remove();

		var updatemax	=	$("#numberMax").val() - 1;
		$("#numberMax").val(updatemax);

		var maxLine = $("#numberMax").val();
		if(maxLine == 0){
			$("#detail_body_empty").show();
		}
	}
</script>
