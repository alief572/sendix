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
				<label class='label-control col-sm-2'><b>Work Category<span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					echo form_input(array('id'=>'category','name'=>'category','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Work Category'),$header[0]->category);
					echo form_input(array('type'=>'hidden','id'=>'code_work','name'=>'code_work','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Work Category'),$header[0]->code_work);
					?>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Total Time</b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'total_time','name'=>'total_time','class'=>'form-control input-md','readonly'=>'readonly','placeholder'=>'Total Time'),$header[0]->total_time);
					?>
				</div>
			</div><br>
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">List Work Edit</h3>
				</div>
				<div class="box-body" >
					<table id="my-grid" class="table table-striped table-bordered table-hover table-condensed" width="100%">
						<thead>
							<tr>
								<th colspan='4'>A. STEP PROCESS</th>
							</tr>
							<tr class='bg-purple'>
								<th class="text-center" style='width: 30%;'>Step Process</th>
								<th class="text-center" style='width: 15%;'>Time Process /day</th>
								<th class="text-center" style='width: 48%;'>Information</th>
								<th class="text-center" style='width: 7%;'>#</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no=0;
							foreach($detail AS $val => $valx){
								$no++;
								$qDet1 = "SELECT * FROM work_detail_akomodasi WHERE code_work_detail='".$valx['code_work_detail']."' AND deleted='N'";
								$qDet2 = "SELECT * FROM work_detail_apd WHERE code_work_detail='".$valx['code_work_detail']."' AND deleted='N'";
								$qDet3 = "SELECT * FROM work_detail_con_nonmat WHERE code_work_detail='".$valx['code_work_detail']."' AND deleted='N'";
								$qDet4 = "SELECT * FROM work_detail_man_power WHERE code_work_detail='".$valx['code_work_detail']."' AND deleted='N'";
								$qDet5 = "SELECT * FROM work_detail_vehicle_tool WHERE code_work_detail='".$valx['code_work_detail']."' AND deleted='N'";

								$restDet1 = $this->db->query($qDet1)->result_array();
								$restDet2 = $this->db->query($qDet2)->result_array();
								$restDet3 = $this->db->query($qDet3)->result_array();
								$restDet4 = $this->db->query($qDet4)->result_array();
								$restDet5 = $this->db->query($qDet5)->result_array();

								$ArrData1 = array();
								foreach($restDet1 as $vaS => $vaA){
								   $ArrData1[] = $vaA['code_group'];
								}
								$ArrData1 = implode("," ,$ArrData1);
								$ArrData1x = explode("," ,$ArrData1);

								$ArrData2 = array();
								foreach($restDet2 as $vaS => $vaA){
								   $ArrData2[] = $vaA['code_group'];
								}
								$ArrData2 = implode("," ,$ArrData2);
								$ArrData2x = explode("," ,$ArrData2);

								$ArrData3 = array();
								foreach($restDet3 as $vaS => $vaA){
								   $ArrData3[] = $vaA['code_group'];
								}
								$ArrData3 = implode("," ,$ArrData3);
								$ArrData3x = explode("," ,$ArrData3);

								$ArrData4 = array();
								foreach($restDet4 as $vaS => $vaA){
								   $ArrData4[] = $vaA['code_group'];
								}
								$ArrData4 = implode("," ,$ArrData4);
								$ArrData4x = explode("," ,$ArrData4);

								$ArrData5 = array();
								foreach($restDet5 as $vaS => $vaA){
								   $ArrData5[] = $vaA['code_group'];
								}
								$ArrData5 = implode("," ,$ArrData5);
								$ArrData5x = explode("," ,$ArrData5);
								?>
								<tr>
									<td>
										<div class='labDet'>Work Process <?=$no;?></div><input type='text' name='ListDetail[0<?=$no;?>][work_process]' id="work_process_<?=$no;?>" class='form-control input-sm' placeholder='Work Process <?=$no;?>' value='<?=$valx['work_process'];?>'>
										<div class='labDet'>Information <?=$no;?></div><textarea name='ListDetail[0<?=$no;?>][information]' id='information_<?=$no;?>' class='form-control input-sm' placeholder='Information <?=$no;?>'><?=$valx['information'];?></textarea>
									</td>
									<td>
											<div class='labDet'>Vehicles and Tools</div><select name='ListDetail[0<?=$no;?>][vehicle_tool][]' class='chosen_select form-control inline-block' multiple>
											<?php
												foreach($vehicle as $row)	{
													$sel5 = (isset($ArrData5x) && in_array($row->code_group, $ArrData5x))?'selected':'';
													 echo "<option value='".$row->code_group."' $sel5>".strtoupper($row->category." - ".$row->spec)."</option>";
												}
											?>
											</select>
											<div class='labDet'>APD</div><select name='ListDetail[0<?=$no;?>][apd][]' class='chosen_select form-control inline-block' multiple>
												<?php
													foreach($apd as $row)	{
															$sel2 = (isset($ArrData2x) && in_array($row->code_group, $ArrData2x))?'selected':'';
														 echo "<option value='".$row->code_group."' $sel2>".strtoupper($row->category." - ".$row->spec)."</option>";
													}
												?>
											</select>
											<div class='labDet'>Consumable Non Material</div><select name='ListDetail[0<?=$no;?>][con_nonmat][]' class='chosen_select form-control inline-block' multiple>
												<?php
													foreach($consumable as $row)	{
														$sel3 = (isset($ArrData3x) && in_array($row->code_group, $ArrData3x))?'selected':'';
														 echo "<option value='".$row->code_group."' $sel3>".strtoupper($row->category." - ".$row->spec)."</option>";
													}
												?>
											</select>
											<div class='labDet'>Accommodation</div><select name='ListDetail[0<?=$no;?>][akomodasi][]' class='chosen_select form-control inline-block' multiple>
												<?php
													foreach($akomodasi as $row)	{
														$sel1 = (isset($ArrData1x) && in_array($row->code_group, $ArrData1x))?'selected':'';
														 echo "<option value='".$row->code_group."' $sel1>".strtoupper($row->category." - ".$row->spec)."</option>";
													}
												?>
											</select>
											<div class='labDet'>Man Power</div><select name='ListDetail[0<?=$no;?>][man_power][]' class='chosen_select form-control inline-block' multiple>
												<?php
													foreach($man_power as $row)	{
															$sel4 = (isset($ArrData4x) && in_array($row->code_group, $ArrData4x))?'selected':'';
														 echo "<option value='".$row->code_group."' $sel4>".strtoupper($row->category." - ".$row->spec)."</option>";
													}
												?>
											</select>
									</td>
									<td align='center'>
										<button type='button' style='min-width:70px;' class='btn btn-danger btn-sm aDel' data-id_work_process='<?=$valx['id'];?>' data-tanda='det' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Delete</button>
									</td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title">List Work Add</h3>
				</div>
				<!-- style="overflow-x:auto;" -->
				<div class="box-body" >
					<input type='hidden' name='numberMax' id='numberMax' value='0'>
					<button type="button" id='add_sp' style='width:130px; margin-right:0px; margin-bottom:3px; margin-left:5px; float:right;' class="btn btn-info btn-sm">Add Work Process</button>
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
	.maskM{
		text-align:center;
	}
	.spanDel{
		float:right;
	}
</style>
<script>
	$(document).ready(function(){
		$('.maskM').maskMoney();
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
						var baseurl		= base_url + active_controller +'/edit';
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

		//delete
		$('.aDel').click(function(e){
			e.preventDefault();
			var code_work		= $('#code_work').val();
			var tanda			= $(this).data('tanda');
			var id_work_process = $(this).data('id_work_process');

			// alert(code_work);
			// alert(tanda);
			// alert(id_work_process);
			// return false;


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
						var baseurl		= base_url + active_controller +'/delete_detail/'+code_work+'/'+tanda+'/'+id_work_process;
						$.ajax({
							url			: baseurl,
							type		: "POST",
							cache		: false,
							dataType	: 'json',
							processData	: false,
							contentType	: false,
							success		: function(data){
								if(data.status == 1){
									swal({
										  title	: "Delete Success!",
										  text	: data.pesan,
										  type	: "success",
										  timer	: 7000
										});
									window.location.href = base_url + active_controller +'/edit/'+data.code_work;
								}
								else if(data.status == 2){
									swal({
									  title	: "Delete Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 7000
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
								$('#save_work').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
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
