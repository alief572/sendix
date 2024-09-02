<?php
$this->load->view('include/side_menu');
$ArrRegion = array();
foreach($region AS $val => $valx){
	$ArrRegion[$valx['region_code']] = strtoupper($valx['region']);
}
$ArrRegion[0]	= 'Select An Region Project';


$ArrIPP = array();
foreach($no_ipp AS $val => $valx){
	$ArrIPP[$valx['no_ipp']] = strtoupper($valx['no_ipp']." - ".$valx['nm_customer']." [".$valx['project']."]");
}
$ArrIPP[0]	= 'Select An IPP Number';
// echo"<pre>";
// print_r($ArrRegion);
// echo"</pre>";
?>
<form action="#" method="POST" id="form_work" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?php echo $title;?></h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>IPP Number <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_dropdown('no_ipp', $ArrIPP, '0', array('id'=>'no_ipp','name'=>'no_ipp','class'=>'form-control input-md clSelect'));
					?>
				</div>
				<label class='label-control col-sm-2'><b>Tipe Instalasi <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<select name="tipe[]" id="tipe" class='form-control' multiple>
						<?php
						foreach (get_tipe_instalasi() as $key => $value) {
							echo "<option value='".$value['category']."'>".strtoupper($value['category'])."</option>";
						}
						?>
					</select>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Project Name <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'project_name','name'=>'project_name','class'=>'form-control input-md exPetik','readonly'=>'readonly','placeholder'=>'Project Name'));
					?>
				</div>
				<label class='label-control col-sm-2'><b>Region <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_dropdown('region_code', $ArrRegion, '0', array('id'=>'region_code','name'=>'region_code','class'=>'form-control input-md clSelect'));
					?>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Project Location <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_textarea(array('id'=>'location','name'=>'location','class'=>'form-control input-md exPetik','readonly'=>'readonly','rows'=>'3','placeholder'=>'Project Location'));
					?>
				</div>
				<label class='label-control col-sm-2'><b>Time/Day (Hours) <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'total_time','name'=>'total_time','class'=>'form-control input-md numberFull','autocomplete'=>'off','placeholder'=>'Time Total /day'));
					?>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>BQ Project <span class='text-red'>*</span></b></label>
				<div class='col-sm-10'>
					<div id='alert'></div>
					<table id="my-grid" class="table table-striped table-bordered table-hover table-condensed" width="100%">
						<thead id='head_table_bq'>
							<tr class='bg-purple'>
								<th class="text-center" style='width: 22%;'>Diameter</th>
								<th class="text-center" style='width: 22%;'>Qty</th>
								<th class="text-center" style='width: 22%;'>Satuan</th>
								<th class="text-center" style='width: 22%;'>Total Time (Hours)</th>
								<th class="text-center" style='width: 12%;'>#</th>
							</tr>
						</thead>
						<tbody id='detail_bq'></tbody>
						<tbody id='detail_bq_empty'>
							<tr>
								<td colspan='5'>BQ project empty ...</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td style='vertical-align:middle; text-align:center;'><b></b></td>
								<td>
									<b>Total Qty</b>
									<?php
									 echo form_input(array('id'=>'bq_qty','name'=>'bq_qty','class'=>'form-control input-md','readonly'=>'readonly'),0);
									?>
								</td>
								<td>
									<b>Total Man Power</b>
									<?php
									 echo form_input(array('id'=>'bq_mp','name'=>'bq_mp','class'=>'form-control input-md numberFull','placeholder'=>'Input MP'));
									?>
								</td>
								<td>
									<b>Sum Total Time (Hours)</b>
									<?php
									 echo form_input(array('id'=>'bq_ct','name'=>'bq_ct','class'=>'form-control input-md','readonly'=>'readonly'),0);
									?>
								</td>
								<td>
									<b>Time Est. (days)</b>
									<?php
									 echo form_input(array('id'=>'bq_total','name'=>'bq_total','class'=>'form-control input-md','readonly'=>'readonly'),0);
									?>
								</td>
							</tr>
						</tfoot>
					</table>
					<button type="button" id='add_bq' style='width:100px; margin-right:0px; margin-bottom:3px; margin-left:5px; float:right;' class="btn btn-info btn-sm">Add BQ</button>
				</div>
			</div>
			<br>
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title">List Job Process</h3>
				</div>
				<!-- style="overflow-x:auto;" -->
				<input type='hidden' name='numberMax' id='numberMax' value='0'>
				<input type='hidden' name='numberMaxAk' id='numberMaxAk' value='0'>
				<input type='hidden' name='numberMaxBq' id='numberMaxBq' value='0'>
				<button type="button" id='add_sp' style='width:130px; margin-top:10px; margin-bottom:1px; margin-left:10px; float:left;' class="btn btn-success btn-sm">Add Job Process</button>
				<br><br>
				<div class="box-body">
					<table id='getTabProcess' class="table table-striped table-bordered table-hover table-condensed" width="100%">
							<thead id='head_table'>
								<tr class='bg-blue'>
									<th colspan='2'>&nbsp;&nbsp;&nbsp;Job Process</th>
								</tr>
								<tr class='bg-purple'>
									<th class="text-center" style='width: 50%;'>Job Process</th>
									<th class="text-center" style='width: 50%;'>Detail</th>
								</tr>
							</thead>
							<tbody id='detail_body'></tbody>
							<tbody id='detail_body_empty'>
								<tr>
									<td colspan='2'>Work process empty ...</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title">List Akomodasi</h3>
				</div>
				<div class="box-body hide_ot">
					<table id='getTab' class="table table-striped table-bordered table-hover table-condensed" width="100%" hidden>
						<thead id='head_table'>
							<tr class='bg-blue'>
								<th colspan='6'>&nbsp;&nbsp;&nbsp;Meal & Pocket Money</th>
							</tr>
							<tr class='bg-purple'>
								<th class="text-center" style='width: 30%;'>Level Man Power</th>
								<th class="text-center" style='width: 13%;'>Area</th>
								<th class="text-center" style='width: 12%;'>Total MP</th>
								<th class="text-center" style='width: 12%;'>Total (Day)</th>
								<th class="text-center">Note</th>
								<th class="text-center" style='width: 6%;'>#</th>
							</tr>
						</thead>
						<tbody id='detail_body_meal'></tbody>
						<tbody id='detail_body_meal_empty'>
							<tr>
								<td colspan='6'>Meal & Pocket Money empty ...</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="box-body hide_ot">
					<table id='getTab' class="table table-striped table-bordered table-hover table-condensed" width="100%"hidden>
						<thead id='head_table'>
							<tr class='bg-blue'>
								<th colspan='6'>&nbsp;&nbsp;&nbsp;Overtime</th>
							</tr>
							<tr class='bg-purple'>
								<th class="text-center" style='width: 30%;'>Level Man Power</th>
								<th class="text-center" style='width: 13%;'>Total MP</th>
								<th class="text-center" style='width: 12%;'>Total (Day)</th>
								<th class="text-center" style='width: 12%;'>Total (Hour)</th>
								<th class="text-center">Note</th>
								<th class="text-center" style='width: 6%;'>#</th>
							</tr>
						</thead>
						<tbody id='detail_body_ot'></tbody>
						<tbody id='detail_body_ot_empty'>
							<tr>
								<td colspan='6'>Overtime empty ...</td>
							</tr>
						</tbody>
					</table>
				</div>

				<input type='hidden' name='numberHouse' id='numberHouse' value='0'>
				<!-- <button type="button" id='add_house' style='min-width:130px; margin-top:10px; margin-bottom:1px; margin-left:10px; float:left;' class="btn btn-success btn-sm">Add Housing & Transportation Site</button> -->
				<!-- <br><br> -->
				<div class="box-body">
					<table id='getTab' class="table table-striped table-bordered table-hover table-condensed" width="100%">
							<thead id='head_table'>
								<tr class='bg-blue'>
									<th colspan='6'>&nbsp;&nbsp;&nbsp;Acomodation & Transportation on Site</th>
								</tr>
								<tr class='bg-purple'>
									<th class="text-center" style='width: 30%;'>Item Cost</th>
									<th class="text-center" style='width: 13%;'>Qty</th>
									<th class="text-center" style='width: 12%;'>Total (Day)</th>
									<th class="text-center" style='width: 12%;'>Unit</th>
									<th class="text-center">Note</th>
									<th class="text-center" style='width: 6%;'>#</th>
								</tr>
							</thead>
							<tbody id='detail_body_house'>
								<?php
									foreach ($list_kend as $key => $value) {
										echo "<tr id='trak_$key'>";
										echo 	"<td align='left'  width='10%'>";
										echo		"<select name='ListDetailHouse[$key][code_group]' id='item_costh_$key' class='chosen_select form-control inline-block'>";
														foreach ($list_kend as $key3 => $value3) {
															$sel = ($value3['code_group'] == $value['code_group'])?'selected':'';
															echo "<option value='".$value3['code_group']."' ".$sel.">".strtoupper($value3['spec'])."</option>";
														}
										echo 		"</select>";
										echo 	"</td>";
										echo 	"<td align='left'>";
										echo			"<input type='text' name='ListDetailHouse[$key][qty]' id='qtyh_$key' class='form-control input-md numberFull' placeholder='Qty'>";
										echo 	"</td>";
										echo 	"<td align='left'>";
										echo			"<input type='text' name='ListDetailHouse[$key][value]' id='valueh_$key' class='form-control input-md numberFull' placeholder='Value'>";
										echo 	"</td>";
										echo 	"<td align='left'>";
										echo		"<select name='ListDetailHouse[$key][satuan]' id='satuanh_$key' class='chosen_select form-control inline-block'>";
														foreach ($satuan as $key2 => $value2) {
															echo "<option value='".$value2['category_list']."'>".strtoupper($value2['view_'])."</option>";
														}
										echo 		"</select>";
										echo 	"</td>";
										echo 	"<td align='left'>";
										echo			"<input type='text' name='ListDetailHouse[$key][note]' id='noteh_$key' class='form-control input-md' placeholder='Note'>";
										echo 	"</td>";
										echo 	"<td align='center'>";
										echo		"<button type='button' style='min-width:70px;' class='btn btn-danger btn-sm delRowsH' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
										echo 	"</td>";
										echo "</tr>";
																			}
								?>
							</tbody>
							<!-- <tbody id='detail_body_house_empty'>
								<tr>
									<td colspan='6'>Acomodation & Transportation on Site empty ...</td>
								</tr>
							</tbody> -->
						</table>
					</div>
					<!-- transport -->
					<input type='hidden' name='numberTrans' id='numberTrans' value='0'>
					<button type="button" id='add_trans' style='min-width:130px; margin-top:10px; margin-bottom:1px; margin-left:10px; float:left;' class="btn btn-success btn-sm">Add OPC to Site Transportation</button>
					<br><br>
					<div class="box-body">
						<table id='getTab' class="table table-striped table-bordered table-hover table-condensed" width="100%">
								<thead id='head_table'>
									<tr class='bg-blue'>
										<th colspan='8'>&nbsp;&nbsp;&nbsp;OPC to Site Transportation</th>
									</tr>
									<tr class='bg-purple'>
										<th class="text-center" style='width: 30%;'>Item Cost</th>
										<th class="text-center" style='width: 13%;'>Transportation</th>
										<th class="text-center" style='width: 12%;'>Origin</th>
										<th class="text-center" style='width: 12%;'>Destination</th>
										<th class="text-center" style='width: 10%;'>Total MP (Day)</th>
										<th class="text-center" style='width: 10%;'>Round-Trip</th>
										<th class="text-center" style='width: 17%;'>Note</th>
										<th class="text-center" style='width: 6%;'>#</th>
									</tr>
								</thead>
								<tbody id='detail_body_trans'></tbody>
								<tbody id='detail_body_trans_empty'>
									<tr>
										<td colspan='8'>OPC to Site Transportation empty ...</td>
									</tr>
								</tbody>
							</table>
						</div>
			</div>
			<!-- testing -->
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title">List Testing</h3>
				</div>
				<input type='hidden' name='numberTest' id='numberTest' value='0'>
				<button type="button" id='add_test' style='width:130px; margin-top:10px; margin-bottom:1px; margin-left:10px; float:left;' class="btn btn-success btn-sm">Add Testing</button>
				<br><br>
				<div class="box-body">
					<table id='getTab' class="table table-striped table-bordered table-hover table-condensed" width="100%">
						<thead id='head_table'>
							<tr class='bg-blue'>
								<th colspan='4'>&nbsp;&nbsp;&nbsp;Testing</th>
							</tr>
							<tr class='bg-purple'>
								<th class="text-center" style='width: 30%;'>Item Name</th>
								<th class="text-center" style='width: 13%;'>Qty</th>
								<th class="text-center">Note</th>
								<th class="text-center" style='width: 6%;'>#</th>
							</tr>
						</thead>
						<tbody id='detail_body_test'></tbody>
						<tbody id='detail_body_test_empty'>
							<tr>
								<td colspan='4'>Testing empty ...</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!-- etc -->
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title">List Etc</h3>
				</div>
				<input type='hidden' name='numberEtc' id='numberEtc' value='0'>
				<button type="button" id='add_etc' style='width:130px; margin-top:10px; margin-bottom:1px; margin-left:10px; float:left;' class="btn btn-success btn-sm">Add Etc</button>
				<br><br>
				<div class="box-body">
					<table id='getTab' class="table table-striped table-bordered table-hover table-condensed" width="100%">
						<thead id='head_table'>
							<tr class='bg-blue'>
								<th colspan='4'>&nbsp;&nbsp;&nbsp;Etc</th>
							</tr>
							<tr class='bg-purple'>
								<th class="text-center" style='width: 30%;'>Item Name</th>
								<th class="text-center" style='width: 13%;'>Qty</th>
								<th class="text-center">Note</th>
								<th class="text-center" style='width: 6%;'>#</th>
							</tr>
						</thead>
						<tbody id='detail_body_etc'></tbody>
						<tbody id='detail_body_etc_empty'>
							<tr>
								<td colspan='4'>Etc empty ...</td>
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
<!-- <script src="<?php echo base_url('application/views/Instalation/instalation.js'); ?>"></script> -->
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
	.labAdd{
		font-weight: bold;
		margin: 5px 0px 3px 5px;
		color: #0aa92c;
	}
	.clSelect{
		width: 100%;
	}
	.mbut{
		margin-bottom:7px;
	}
	.spanDel{
		float:right;
	}
	.aAdd{
		margin-top: 10px;
		min-width: 150px;
	}
	.add_del{
		cursor : pointer;
		color: white;
		background-color: #605ca8 !important;
	}

	.cldelete{
		cursor : pointer;
		color: white;
		background-color: #ce1111 !important;
	}
	.widCtr{
		width: 80px !important;
	}
	.widCtrx{
		width: 90px !important;
	}
</style>
<script>
	$(document).ready(function(){
		$(".hide_ot").hide();
		$(document).on('change','#no_ipp', function(){
			$.ajax({
				url: base_url+active_controller+'/getProject/'+$(this).val(),
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data){
					$("#project_name").val(data.project);
					$("#location").val(data.location);
					swal.close();
				},
				error: function() {
					swal({
					title				: "Error Message !",
					text				: 'Connection Time Out. Please try again..',
					type				: "warning",
					timer				: 3000
					});
	      		}
			});
		});

		$(document).on('click','.sh_detail', function(){
			$(this).closest('tr').next('tr').slideToggle();
			var htmL = $(this).html();
			if(htmL == 'SHOW'){ $(this).html('HIDE') }
			if(htmL == 'HIDE'){ $(this).html('SHOW') }
		});
		//save
		$('#save_work').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var no_ipp	= $('#no_ipp').val();
			var project_name	= $('#project_name').val();
			var region_code		= $('#region_code').val();
			var location		= $('#location').val();
			var total_time		= $('#total_time').val();
			var tipe			= $('#tipe').val();

			if(no_ipp=='0'){
				swal({
				  title	: "Error Message!",
				  text	: 'IPP Number not selected, please choose first ...',
				  type	: "warning"
				});
				$('#save_work').prop('disabled',false);
				return false;
			}

			if(project_name=='' || project_name==null){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Project Name, please input first ...',
				  type	: "warning"
				});
				$('#save_work').prop('disabled',false);
				return false;
			}

			if(region_code=='0'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Region, please select first ...',
				  type	: "warning"
				});
				$('#save_work').prop('disabled',false);
				return false;
			}

			if(location=='' || location==null){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Location, please input first ...',
				  type	: "warning"
				});
				$('#save_work').prop('disabled',false);
				return false;
			}

			if(total_time=='' || total_time==null){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Total Time, please input first ...',
				  type	: "warning"
				});
				$('#save_work').prop('disabled',false);
				return false;
			}

			// if(tipe=='0'){
			// 	swal({
			// 	  title	: "Error Message!",
			// 	  text	: 'Empty Tipe Instalasi, please select first ...',
			// 	  type	: "warning"
			// 	});
			// 	$('#save_work').prop('disabled',false);
			// 	return false;
			// }

			var numberMaxBq	= $('#numberMaxBq').val();
			var numberMax		= $('#numberMax').val();

			if(numberMaxBq=='0' || total_time==null){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty List BQ Project, please add first ...',
				  type	: "warning"
				});
				$('#save_work').prop('disabled',false);
				return false;
			}
			if(numberMax=='0' || numberMax==null){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty List Work Process, please add first ...',
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
										  timer	: 3000
										});
									window.location.href = base_url + active_controller;
								}
								else{
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 3000
									});
								}
								$('#save_work').prop('disabled',false);
							},
							error: function() {
								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',
								  type				: "warning",
								  timer				: 3000
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
	});


	//==================================BQ PROJECT=============================================
	$(document).on('keyup','.sum_qty', function(){
		var no 		= $(this).data('no');
		var satuan 	= $('#satuan_'+no).val();
		var qty 	= $('#qty_'+no).val();
		var ct 		= $('#ct_'+no).val();

		if(satuan == 'joint'){
			let CT_time =  ct * qty
			$("#cycletime_"+no).val(CT_time.toFixed(2));
			sum_bq();
		}
		else{
			sum_bq();
		}
	});

	$(document).on('change','.ch_cycletime, .sum_dim', function(){
	  	var no 		= $(this).data('no');
	 	var satuan 	= $('#satuan_'+no).val();
	 	var dim 	= $('#diameter_'+no).val();
	  	var qty 	= $('#qty_'+no).val();

		if(satuan == 'joint'){
			// loading_spinner();
			$("#cycletime_"+no).prop('readonly',true);
			$.ajax({
				url: base_url+active_controller+'/get_cycletime/'+dim,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data){
					var total = (data.cycletime * qty);
					$("#cycletime_"+no).val(total.toFixed(2));
					$("#mp_"+no).val(data.mp);
					$("#ct_"+no).val(data.ct);
					swal.close();
					sum_bq();
				},
				error: function() {
					swal({
						title				: "Error Message !",
						text				: 'Connection Time Out. Please try again..',
						type				: "warning",
						timer				: 3000
					});
				}
			});
		}
		else{
			$("#cycletime_"+no).val('');
			$("#mp_"+no).val('');
			$("#cycletime_"+no).prop('readonly',false);
			sum_bq();
	  	}
	});

	$(document).on('keyup','.sum_ct, #total_time', function(){
	    sum_bq();
	});

	$(document).on('keyup','#bq_mp', function(){
	    sum_bq2();
	});

	var nomorBq	= 1;
	$(document).on('click','#add_bq', function(e){
		e.preventDefault();
		var nilaiAwal	= parseInt($("#numberMaxBq").val());
		var nilaiAkhir	= nilaiAwal + 1;
		$("#numberMaxBq").val(nilaiAkhir);
		loading_spinner();
		AppendBarisBq(nomorBq, nilaiAkhir);
		swal.close();
		$('.chosen_select').chosen({width: '100%'});
		$("#detail_bq_empty").hide();
	});

	function AppendBarisBq(intd){
	  var nomor	= 1;
	  var valuex	= $('#detail_bq').find('tr').length;
	  if(valuex > 0){
	    var akhir	= $('#detail_bq tr:last').attr('id');
	    var det_id	= akhir.split('_');
	    var nomor	= parseInt(det_id[1])+1;
	  }

	  var Rows	 = 	"<tr id='trbq_"+nomor+"'>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=				"<input type='text' name='ListDetailBq["+nomor+"][diameter]' id='diameter_"+nomor+"' data-no='"+nomor+"' class='form-control input-md numberFull dim_ch sum_dim' placeholder='Diameter'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=				"<input type='text' name='ListDetailBq["+nomor+"][qty]' id='qty_"+nomor+"'  data-no='"+nomor+"' class='form-control input-md numberFull dim_ch sum_qty' placeholder='Quantity'>";
	    Rows	+=				"<input type='hidden' name='ListDetailBq["+nomor+"][mp]' id='mp_"+nomor+"' class='man_power'>";
	    Rows	+=				"<input type='hidden' name='ListDetailBq["+nomor+"][ct]' id='ct_"+nomor+"'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left' style='text-align: left;'>";
	    Rows	+=			"<select name='ListDetailBq["+nomor+"][satuan_code]' id='satuan_"+nomor+"' data-no='"+nomor+"' class='chosen_select form-control inline-block ch_cycletime'></select>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=				"<input type='text' name='ListDetailBq["+nomor+"][cycletime]' id='cycletime_"+nomor+"' class='form-control input-md numberOnly sum_ct' readonly placeholder='Cycletime'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='center'>";
	    Rows 	+=			"<button type='button' class='btn btn-danger btn-sm' data-toggle='tooltip' data-placement='bottom' onClick='delRowBq("+nomor+")' title='Delete'><i class='fa fa-trash'></i></button>";
	    Rows	+= 		"</td>";
	    Rows	+= 	"</tr>";

	  $('#detail_bq').append(Rows);

	  var satuan 	= '#satuan_'+nomor;
	  loading_spinner();
	  $.ajax({
	    url: base_url+active_controller+'/list_bq_project',
	    cache: false,
	    type: "POST",
	    dataType: "json",
	    success: function(data){
	      $(satuan).html(data.option).trigger("chosen:updated");
	      swal.close();
	    },
	    error: function() {
	      swal({
	        title				: "Error Message !",
	        text				: 'Connection Time Out. Please try again..',
	        type				: "warning",
	        timer				: 3000,
	        showCancelButton	: false,
	        showConfirmButton	: false,
	        allowOutsideClick	: false
	      });
	    }
	  });
	  number_full();
	}

	function delRowBq(row){
	  $('#trbq_'+row).remove();

	  var updatemax	=	$("#numberMaxBq").val() - 1;
	  $("#numberMaxBq").val(updatemax);

	  var maxLine = $("#numberMaxBq").val();
	  if(maxLine == 0){
	    $("#detail_bq_empty").show();
	  }
	  sum_bq()
	}

	function sum_bq(){
	  var SUM_dim = 0;
	  var SUM_qty = 0;
	  var SUM_ct  = 0;
	  var total_time  = getNum($("#total_time").val());
	  let MP = 0;

	  $(".sum_qty" ).each(function() {
	    SUM_qty += Number(getNum($(this).val()));
	  });
	  $(".sum_ct" ).each(function() {
	    SUM_ct += Number(getNum($(this).val()));
	  });
	  $(".man_power" ).each(function() {
	    MP += Number(getNum($(this).val()));
	  });

	  $("#bq_mp").val(MP)
	  var mp          = MP;

	  var cal_mp = (SUM_ct / mp) / total_time;

	  $('#bq_qty').val(getNum(SUM_qty));
	  $('#bq_ct').val(getNum(SUM_ct.toFixed(2)));
	  $('#bq_total').val(getNum(cal_mp.toFixed(2)));
	}

	function sum_bq2(){
	 	var SUM_qty = 0;
	  	var SUM_ct  = 0;
	  	var total_time  = getNum($("#total_time").val());
	  	var mp          = getNum($("#bq_mp").val());

	  	$(".sum_qty" ).each(function() {
	    	SUM_qty += Number(getNum($(this).val()));
	  	});
	 	 $(".sum_ct" ).each(function() {
	    	SUM_ct += Number(getNum($(this).val()));
	  	});

	  	var cal_mp = (SUM_ct / mp) / total_time;

	  	$('#bq_qty').val(getNum(SUM_qty));
	  	$('#bq_ct').val(getNum(SUM_ct.toFixed(2)));
	  	$('#bq_total').val(getNum(cal_mp.toFixed(2)));
	}
	//==================================END BQ PROJECT==========================================




	$(document).on('keypress keyup blur','.chTime', function(){
	  var no = $(this).data('nomor');
	  var SUM = 0;
	  $(".chTime_"+no).each(function() {
	    SUM += Number($(this).val());
	  });
	  $('.dayT_'+no).val(getNum(SUM));
	});

	$(document).on('keypress keyup blur','.chMPQty', function(){
	  var no1 = $(this).data('no1');
	  var no2 = $(this).data('no2');
	  $('#jml_orangm_'+no1+'_'+no2).val($(this).val());
	  $('#jml_orango_'+no1+'_'+no2).val($(this).val());
	});

	$(document).on('click','.aDel', function(){
	  var del_tr = $(this).data('del_tr');
	  // alert($(this).parent().parent().html());
	  $(this).parent().parent().remove();
	  // alert($(this).find('div').html());
	});

	$(document).on('click','.aDelP', function(){
	  $(this).parent().parent().parent().remove();
	});

	$(document).on('click','.delRows', function(){
	  $(this).parent().parent().remove();

	  var updatemax	=	$("#numberMaxAk").val() - 1;
	  $("#numberMaxAk").val(updatemax);

	  var maxLine = $("#numberMaxAk").val();
	  if(maxLine == 0){
	    $("#detail_body_ak_empty").show();
	  }
	});

	$(document).on('click','.delRowsH', function(){
	  $(this).parent().parent().remove();

	  var updatemax	=	$("#numberHouse").val() - 1;
	  $("#numberHouse").val(updatemax);

	  var maxLine = $("#numberHouse").val();
	  if(maxLine == 0){
	    $("#detail_body_house_empty").show();
	  }
	});

	$(document).on('click','.delRowsT', function(){
	  $(this).parent().parent().remove();

	  var updatemax	=	$("#numberTrans").val() - 1;
	  $("#numberTrans").val(updatemax);

	  var maxLine = $("#numberTrans").val();
	  if(maxLine == 0){
	    $("#detail_body_trans_empty").show();
	  }
	});

	$(document).on('click','.delRowsE', function(){
	  $(this).parent().parent().remove();

	  var updatemax	=	$("#numberEtc").val() - 1;
	  $("#numberEtc").val(updatemax);

	  var maxLine = $("#numberEtc").val();
	  if(maxLine == 0){
	    $("#detail_body_etc_empty").show();
	  }
	});

	//add component
	$(document).on('click','.aAdd', function(){
	  var num1 		= $(this).data('num1');
	  var num2 		= $(this).data('num2');
	  var numlast 	= $(this).data('numlast');
	  var tanda 	= $(this).data('tanda');
	  var tanda2 	= $(this).data('tanda2');
	  loading_spinner();
	  addDropdown(num1, num2, numlast, tanda, tanda2);
	});

	//back
	$('#back_work').click(function(e){
	  window.location.href = base_url + active_controller;
	});

	var nomor	= 1;
	$('#add_sp').click(function(e){
	  e.preventDefault();
	  var tipe			= $('#tipe').val();

	//   if(tipe=='0'){
	// 		swal({
	// 			title	: "Error Message!",
	// 			text	: 'Empty Tipe Instalasi, please select first ...',
	// 			type	: "warning"
	// 		});
	// 		return false;
	// 	}
	  loading_spinner();
	  var nilaiAwal	= parseInt($("#numberMax").val());
	  var nilaiAkhir	= nilaiAwal + 1;
	  $("#numberMax").val(nilaiAkhir);

	  AppendBaris(nomor, nilaiAkhir);

	  $('#head_table').show();
	  $('.chosen_select').chosen({width: '100%'});

	  $("#detail_body_empty").hide();
	  $("#detail_body_meal_empty").hide();
	  $("#detail_body_ot_empty").hide();
	  $('#save_work').show();
	  swal.close();
	});

	//add Housing
	var nomor_house	= 1;
	$('#add_house').click(function(e){
	  e.preventDefault();
	  loading_spinner();
	  var nilaiAwal	= parseInt($("#numberHouse").val());
	  var nilaiAkhir	= nilaiAwal + 1;
	  $("#numberHouse").val(nilaiAkhir);

	  AppendBarisHouse(nomor_house, nilaiAkhir);
	  $('.chosen_select').chosen({width: '100%'});

	  $("#detail_body_house_empty").hide();
	  $('#save_work').show();
	  swal.close();
	});

	//add Trans
	var nomor_trans	= 1;
	$('#add_trans').click(function(e){
	  e.preventDefault();
	  loading_spinner();
	  var nilaiAwal	= parseInt($("#numberTrans").val());
	  var nilaiAkhir	= nilaiAwal + 1;
	  $("#numberTrans").val(nilaiAkhir);

	  AppendBarisTrans(nomor_trans, nilaiAkhir);
	  $('.chosen_select').chosen({width: '100%'});

	  $("#detail_body_trans_empty").hide();
	  $('#save_work').show();
	  swal.close();
	});

	//add Etc
	var nomor_etc	= 1;
	$('#add_etc').click(function(e){
	  e.preventDefault();
	  loading_spinner();
	  var nilaiAwal	= parseInt($("#numberEtc").val());
	  var nilaiAkhir	= nilaiAwal + 1;
	  $("#numberEtc").val(nilaiAkhir);

	  AppendBarisEtc(nomor_etc, nilaiAkhir);
	  $('.chosen_select').chosen({width: '100%'});

	  $("#detail_body_etc_empty").hide();
	  $('#save_work').show();
	  swal.close();
	});


	//add Testing
	var nomor_test	= 1;
	$('#add_test').click(function(e){
	  e.preventDefault();
	  loading_spinner();
	  var nilaiAwal	= parseInt($("#numberTest").val());
	  var nilaiAkhir	= nilaiAwal + 1;
	  $("#numberTest").val(nilaiAkhir);

	  AppendBarisTest(nomor_test, nilaiAkhir);
	  $('.chosen_select').chosen({width: '100%'});

	  $("#detail_body_test_empty").hide();
	  $('#save_work').show();
	  swal.close();
	});

	

	function AppendBaris(intd){
	  var nomor	= 1;
	  let tipe = $('#tipe').val();
	  var valuex	= $('#detail_body').find('tr').length;
	  if(valuex > 0){
	    var akhir	= $('#detail_body tr:last').attr('class');
	    // console.log(akhir);
	    var det_id	= akhir.split('_');
	    var nomor	= parseInt(det_id[1])+1;
	  }

	  var rowCount = parseInt($('#getTabProcess').find('.sh_detail').length) + 1;

	  var Rows	 = 	"<tr class='tr_"+nomor+"'>";
	    Rows	+= 		"<td align='left'  width='50%'>";
	    Rows	+=			"<div class='labDet jobprocess' id='jobprocess_"+rowCount+"'>Job Process "+rowCount+"</div>";
	    Rows	+= 			"<div class='input-group'>";
	    Rows	+=				"<select name='ListDetail["+nomor+"][code_work]' id='code_work_"+nomor+"' class='chosen_select form-control inline-block'></select>";
	    Rows 	+= 				"<span class='input-group-addon cldelete' onClick='delRow("+nomor+")'><i class='fa fa-close'></i></span>";
	    Rows	+= 			"<div>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'  width='50%'>";
		Rows	+=			"<div class='labDet'>Standart Time</div>";					
		Rows	+= 			"<div class='input-group'>";					
	    Rows	+= 				"<input type='text' name='ListDetail["+nomor+"][std_time]' id='std_time_"+nomor+"' class='form-control autoNumeric'>";
		Rows	+= 				"<span class='input-group-addon text-bold sh_detail' style='cursor:pointer; background-color:beige;'></span>";
	    Rows	+= 			"<div>";
	    Rows	+= 		"</td>";
	    Rows	+= 	"</tr>";
		Rows	+= 	"<tr class='tr_"+nomor+"' style='display:none;'>";
	    Rows	+= 		"<td align='left' colspan='2'>";
	    Rows	+= 			"<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>";
	    Rows 	+= 				"<tbody id='detail_bqDet_"+nomor+"'></tbody>";
	    Rows 	+= 			"</table>";
	    Rows	+= 		"</td>";
	    Rows	+= 	"</tr>";

	  var RowsMeal	 = 	"<tr id='trmeal_"+nomor+"'>";
	    RowsMeal	+= 		"<td align='left' colspan='6'>";
	    RowsMeal	+= 			"<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>";
	    RowsMeal 	+= 				"<tbody id='detail_bqMeal_"+nomor+"'></tbody>";
	    RowsMeal 	+= 			"</table>";
	    RowsMeal	+= 		"</td>";
	    RowsMeal	+= 	"</tr>";

	  var RowsOT	 = 	"<tr id='trot_"+nomor+"'>";
	    RowsOT	+= 		"<td align='left' colspan='6'>";
	    RowsOT	+= 			"<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>";
	    RowsOT 	+= 				"<tbody id='detail_bqOT_"+nomor+"'></tbody>";
	    RowsOT 	+= 			"</table>";
	    RowsOT	+= 		"</td>";
	    RowsOT	+= 	"</tr>";

	  $('#detail_body').append(Rows);
	  $('#detail_body_meal').append(RowsMeal);
	  $('#detail_body_ot').append(RowsOT);
	  $('.chosen_select').chosen({ minwidth: '100%' });
	  $('.autoNumeric').autoNumeric();

	  var code_work 	= '#code_work_'+nomor;
	  loading_spinner();
	  //code work
	  $.ajax({
	    url: base_url+active_controller+'/list_work',
	    cache: false,
	    type: "POST",
	    dataType: "json",
	    success: function(data){
	      $(code_work).html(data.option).trigger("chosen:updated");
		  $('.chosen_select').chosen({ minwidth: '100%' });
	      swal.close();
	    },
	    error: function() {
	      swal({
	        title				: "Error Message !",
	        text				: 'Connection Time Out. Please try again..',
	        type				: "warning",
	        timer				: 3000,
	        showCancelButton	: false,
	        showConfirmButton	: false,
	        allowOutsideClick	: false
	      });
	    }
	  });

	  $(document).on('change', code_work, function(){
	    loading_spinner();
	    var codeWork = $(this).val();
		let label_hs = $(this).parent().parent().parent().find('.sh_detail');
		let toogle = $(this).closest('tr').next('tr');
	    $.ajax({
	      url: base_url+active_controller+'/list_work_det/'+codeWork+'/'+nomor,
	      cache: false,
	      type: "POST",
	      dataType: "json",
	      success: function(data){
			toogle.slideDown();
			label_hs.html('HIDE');
	        $("#detail_bqDet_"+data.nomor).html(data.rowx);
	        $("#detail_bqMeal_"+data.nomor).html(data.row_meal);
	        $("#detail_bqOT_"+data.nomor).html(data.overtime);
	        $("#std_time_"+data.nomor).val(data.std_time);
	        $('.chosen_select').chosen({ minwidth: '100%' });
			$(".autoNumeric0").autoNumeric('init', {mDec: '0', aPad: false});
	        swal.close();
	        // AppendBarisBqDet(data.nomor, data.code_work, data.loop);
	      },
	      error: function() {
	        swal({
	          title				: "Error Message !",
	          text				: 'Connection Time Out. Please try again..',
	          type				: "warning",
	          timer				: 3000
	        });
	      }
	    });
	    // AppendBarisBqDet(nomor);
	  });
	}

	function AppendBarisHouse(intd){
	  var nomor	= 1;
	  var valuex	= $('#detail_body_house').find('tr').length;
	  if(valuex > 0){
	    var akhir	= $('#detail_body_house tr:last').attr('id');
	    // console.log(akhir);
	    var det_id	= akhir.split('_');
	    var nomor	= parseInt(det_id[1])+1;
	  }

	  var Rows	 = 	"<tr id='trak_"+nomor+"'>";
	    Rows	+= 		"<td align='left'  width='10%'>";
	    Rows	+=			"<select name='ListDetailHouse["+nomor+"][code_group]' id='item_costh_"+nomor+"' class='chosen_select form-control inline-block'></select>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][qty]' id='qtyh_"+nomor+"' class='form-control input-md numberFull' placeholder='Qty'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][value]' id='valueh_"+nomor+"' class='form-control input-md numberFull' placeholder='Value'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=			"<select name='ListDetailHouse["+nomor+"][satuan]' id='satuanh_"+nomor+"' class='chosen_select form-control inline-block'></select>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][note]' id='noteh_"+nomor+"' class='form-control input-md' placeholder='Note'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='center'>";
	    Rows 	+=			"<button type='button' style='min-width:70px;' class='btn btn-danger btn-sm delRowsH' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
	    Rows	+= 		"</td>";
	    Rows	+= 	"</tr>";

	  $('#detail_body_house').append(Rows);

	  var item_cost 	= '#item_costh_'+nomor;
	  var satuan 	= '#satuanh_'+nomor;
	  loading_spinner();
	  //tempat tinggal
	  $.ajax({
	    url: base_url+active_controller+'/list_tempat_tinggal',
	    cache: false,
	    type: "POST",
	    dataType: "json",
	    success: function(data){
	      $(item_cost).html(data.option).trigger("chosen:updated");
	      swal.close();
	    },
	    error: function() {
	      swal({
	        title				: "Error Message !",
	        text				: 'Connection Time Out. Please try again..',
	        type				: "warning",
	        timer				: 3000,
	        showCancelButton	: false,
	        showConfirmButton	: false,
	        allowOutsideClick	: false
	      });
	    }
	  });
	  //satuan
	  $.ajax({
	    url: base_url+active_controller+'/list_satuan',
	    cache: false,
	    type: "POST",
	    dataType: "json",
	    success: function(data){
	      $(satuan).html(data.option).trigger("chosen:updated");
	      swal.close();
	    },
	    error: function() {
	      swal({
	        title				: "Error Message !",
	        text				: 'Connection Time Out. Please try again..',
	        type				: "warning",
	        timer				: 3000,
	        showCancelButton	: false,
	        showConfirmButton	: false,
	        allowOutsideClick	: false
	      });
	    }
	  });

	  number_full();
	  ex_petik()

	}

	function AppendBarisTrans(intd){
	  var nomor	= 1;
	  var valuex	= $('#detail_body_trans').find('tr').length;
	  if(valuex > 0){
	    var akhir	= $('#detail_body_trans tr:last').attr('id');
	    // console.log(akhir);
	    var det_id	= akhir.split('_');
	    var nomor	= parseInt(det_id[1])+1;
	  }

	  var Rows	 = 	"<tr id='trak_"+nomor+"'>";
	    Rows	+= 		"<td align='left'  width='10%'>";
	    Rows	+=			"<select name='ListDetailTrans["+nomor+"][item_cost]' id='item_costt_"+nomor+"' class='chosen_select form-control inline-block'></select>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=			"<select name='ListDetailTrans["+nomor+"][kendaraan]' id='kendaraant_"+nomor+"' class='chosen_select form-control inline-block'></select>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][asal]' id='asalt_"+nomor+"' class='form-control input-md' placeholder='Origin'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][tujuan]' id='tujuant_"+nomor+"' class='form-control input-md' placeholder='Destination'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][value]' id='valuet_"+nomor+"' class='form-control input-md numberFull' placeholder='Value'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][pulang_pergi]' id='pulang_pergit_"+nomor+"' class='form-control input-md' placeholder='Round-Trip'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][note]' id='notet_"+nomor+"' class='form-control input-md' placeholder='Note'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='center'>";
	    Rows 	+=			"<button type='button' style='min-width:70px;' class='btn btn-danger btn-sm delRowsT' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
	    Rows	+= 		"</td>";
	    Rows	+= 	"</tr>";

	  $('#detail_body_trans').append(Rows);

	  var item_cost 	= '#item_costt_'+nomor;
	  var kendaraan 	= '#kendaraant_'+nomor;
	  loading_spinner();
	  //tempat tinggal
	  $.ajax({
	    url: base_url+active_controller+'/list_tiket',
	    cache: false,
	    type: "POST",
	    dataType: "json",
	    success: function(data){
	      $(item_cost).html(data.option).trigger("chosen:updated");
	      swal.close();
	    },
	    error: function() {
	      swal({
	        title				: "Error Message !",
	        text				: 'Connection Time Out. Please try again..',
	        type				: "warning",
	        timer				: 3000,
	        showCancelButton	: false,
	        showConfirmButton	: false,
	        allowOutsideClick	: false
	      });
	    }
	  });

	  $(document).on('change',item_cost,function(){
	    var ad = $(this).val();

	    $.ajax({
	      url: base_url+active_controller+'/list_sewa_kendaraan/'+ad,
	      cache: false,
	      type: "POST",
	      dataType: "json",
	      success: function(data){
	        $(kendaraan).html(data.option).trigger("chosen:updated");
	        swal.close();
	      },
	      error: function() {
	        swal({
	          title				: "Error Message !",
	          text				: 'Connection Time Out. Please try again..',
	          type				: "warning",
	          timer				: 3000,
	          showCancelButton	: false,
	          showConfirmButton	: false,
	          allowOutsideClick	: false
	        });
	      }
	    });
	  });

	  number_full();
	  ex_petik()

	}

	function AppendBarisEtc(intd){
	  var nomor	= 1;
	  var valuex	= $('#detail_body_etc').find('tr').length;
	  if(valuex > 0){
	    var akhir	= $('#detail_body_etc tr:last').attr('id');
	    // console.log(akhir);
	    var det_id	= akhir.split('_');
	    var nomor	= parseInt(det_id[1])+1;
	  }

	  var Rows	 = 	"<tr id='trak_"+nomor+"'>";
	    Rows	+= 		"<td align='left'  width='10%'>";
	    Rows	+=			"<select name='ListDetailEtc["+nomor+"][code_group]' id='item_coste_"+nomor+"' class='chosen_select form-control inline-block'></select>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=				"<input type='text' name='ListDetailEtc["+nomor+"][qty]' id='qtye_"+nomor+"' class='form-control input-md numberFull' placeholder='Value'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=				"<input type='text' name='ListDetailEtc["+nomor+"][note]' id='notet_"+nomor+"' class='form-control input-md' placeholder='Note'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='center'>";
	    Rows 	+=			"<button type='button' style='min-width:70px;' class='btn btn-danger btn-sm delRowsE' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
	    Rows	+= 		"</td>";
	    Rows	+= 	"</tr>";

	  $('#detail_body_etc').append(Rows);

	  var item_cost 	= '#item_coste_'+nomor;
	  loading_spinner();
	  //tempat tinggal
	  $.ajax({
	    url: base_url+active_controller+'/list_etc',
	    cache: false,
	    type: "POST",
	    dataType: "json",
	    success: function(data){
	      $(item_cost).html(data.option).trigger("chosen:updated");
	      swal.close();
	    },
	    error: function() {
	      swal({
	        title				: "Error Message !",
	        text				: 'Connection Time Out. Please try again..',
	        type				: "warning",
	        timer				: 3000,
	        showCancelButton	: false,
	        showConfirmButton	: false,
	        allowOutsideClick	: false
	      });
	    }
	  });

	  number_full();
	  ex_petik();
	}

	function AppendBarisTest(intd){
	  var nomor	= 1;
	  var valuex	= $('#detail_body_test').find('tr').length;
	  if(valuex > 0){
	    var akhir	= $('#detail_body_test tr:last').attr('id');
	    // console.log(akhir);
	    var det_id	= akhir.split('_');
	    var nomor	= parseInt(det_id[1])+1;
	  }

	  var Rows	 = 	"<tr id='trak_"+nomor+"'>";
	    Rows	+= 		"<td align='left'  width='10%'>";
	    Rows	+=			"<input type='text' name='ListDetailTest["+nomor+"][spec]' class='form-control input-md' placeholder='Item Name'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=				"<input type='text' name='ListDetailTest["+nomor+"][qty]' class='form-control input-md numberFull' placeholder='Value'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='left'>";
	    Rows	+=				"<input type='text' name='ListDetailTest["+nomor+"][note]' class='form-control input-md' placeholder='Note'>";
	    Rows	+= 		"</td>";
	    Rows	+= 		"<td align='center'>";
	    Rows 	+=			"<button type='button' style='min-width:70px;' class='btn btn-danger btn-sm delRowsE' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
	    Rows	+= 		"</td>";
	    Rows	+= 	"</tr>";

	  $('#detail_body_test').append(Rows);

	  number_full();
	  ex_petik();
	}

	function delRow(row){
	  $('.tr_'+row).remove();
	  $('#trmeal_'+row).remove();
	  $('#trot_'+row).remove();

	  var updatemax	=	$("#numberMax").val() - 1;
	  $("#numberMax").val(updatemax);

	  var maxLine = $("#numberMax").val();
	  if(maxLine == 0){
	    $("#detail_body_empty").show();
	    $("#detail_body_meal_empty").show();
	    $("#detail_body_ot_empty").show();
	  }

	  let nomor = 0;
		$(".jobprocess" ).each(function() { nomor++;
			let get_nomor 	= $(this).attr('id');
			let det_id		= get_nomor.split('_');
			let nomor2		= det_id[1];
			console.log(nomor2)
			$('#jobprocess_'+nomor2).html("Job Process "+nomor);
		});
	}

	

	function addDropdown(num1, num2, numlast, tanda, tanda2){
	  // alert(num1+'/'+num2+'/'+numlast+'/'+tanda);
	  $.ajax({
	    url: base_url+active_controller+'/add_dropdown/'+num1+'/'+num2+'/'+numlast+'/'+tanda+'/'+tanda2,
	    cache: false,
	    type: "POST",
	    dataType: "json",
	    success: function(data){
	      $("#"+data.tanda+data.num1+'_'+data.num2).prepend(data.rowx);
	      $('.chosen_select').chosen({minwidth:'100%'});
		  $(".autoNumeric0").autoNumeric('init', {mDec: '0', aPad: false});
	      swal.close();
	    },
	    error: function() {
	      swal({
	        title				: "Error Message !",
	        text				: 'Connection Time Out. Please try again..',
	        type				: "warning",
	        timer				: 3000
	      });
	    }
	  });
	}

	

</script>
