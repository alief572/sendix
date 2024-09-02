<?php
$this->load->view('include/side_menu');

$arrInclude 	= (!empty($header[0]->include_check))?json_decode($header[0]->include_check):array();
$arrExclude 	= (!empty($header[0]->exclude_check))?json_decode($header[0]->exclude_check):array();
$arrIncludeTxt 	= (!empty($header[0]->include_text))?json_decode($header[0]->include_text):array();
$arrExcludeTxt 	= (!empty($header[0]->exclude_text))?json_decode($header[0]->exclude_text):array();

$tgl_quo 		= (!empty($header[0]->tgl_quo))?$header[0]->tgl_quo:date('Y-m-d');
?>
<form action="#" method="POST" id="form_work" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?php echo $title;?></h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>IPP</b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'no_ipp','name'=>'no_ipp','class'=>'form-control input-sm','readonly'=>'true'),strtoupper($header[0]->no_ipp));
					?>
				</div>
                <label class='label-control col-sm-2'><b>Project Name</b></label>
				<div class='col-sm-4'>
					<?php
                        echo form_input(array('type'=>'hidden','id'=>'project_code','name'=>'project_code','class'=>'form-control input-sm','placeholder'=>'Project Name'),strtoupper($header[0]->project_code));
                        echo form_input(array('id'=>'project_name','name'=>'project_name','class'=>'form-control input-sm','placeholder'=>'Project Name'),strtoupper((!empty($header[0]->project_name_quo)?$header[0]->project_name_quo:$header[0]->project_name)));
					?>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Project Location</b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_textarea(array('id'=>'location','name'=>'location','class'=>'form-control input-sm','rows'=>'3','placeholder'=>'Project Location'),strtoupper((!empty($header[0]->location_quo)?$header[0]->location_quo:$header[0]->location)));
					?>
				</div>
				<label class='label-control col-sm-2'><b>Customer Name</b></label>
				<div class='col-sm-4'>
					<?php
                         echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readonly'=>'readonly'),strtoupper(get_name('ipp_header','nm_customer','no_ipp',$header[0]->no_ipp)));
					?>
				</div>
			</div>

			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Reff No <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
                         echo form_input(array('id'=>'reff_no','name'=>'reff_no','class'=>'form-control input-sm'),$header[0]->reff_no);
					?>
				</div>
				<label class='label-control col-sm-2'><b>Subject <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
                         echo form_input(array('id'=>'subject','name'=>'subject','class'=>'form-control input-sm'),$header[0]->subject);
					?>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Tgl Penawaran <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
                         echo form_input(array('id'=>'tgl_quo','name'=>'tgl_quo','class'=>'form-control input-sm datepicker','readonly'=>'true'),$tgl_quo);
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
								<th class="text-center" width='4%'>#</th>
								<th class="text-center">Description</th>
								<th class="text-center" width='10%'>DN (mm)</th>
								<th class="text-center" width='10%'>DN (inch)</th>
								<th class="text-center" width='10%'>Qty Joint</th>
								<th class="text-center" width='10%'>Dia/Inch</th>
								<th class="text-center" width='15%'>Harga Dia/Inch</th>
								<th class="text-center" width='15%'>Total Harga</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$sub_tot_dia = 0;
							foreach ($detail_bq as $key => $value) { $key++;
								$harga_dia = (!empty($value['harga_dia']))?$value['harga_dia']:$header[0]->harga_dia_inch;
								$total_dia = $harga_dia * $value['day_in'];

								$sub_tot_dia += $total_dia;

								echo "<tr>";
									echo "<td align='center'>".$key."</td>";
									echo "<td align='left'>";
										echo "<input type='text' name='ListDetailBq[$key][desc]' class='form-control input-sm' value='".$value['desc']."'>";
									echo "</td>";
									echo "<td align='left'>";
										echo "<input type='hidden' name='ListDetailBq[$key][id]' class='form-control input-sm' readonly value='".$value['id']."'>";
										echo "<input type='text' name='ListDetailBq[$key][diameter]' class='form-control input-sm text-center' readonly value='".$value['diameter']."'>";
									echo "</td>";
									echo "<td align='left'>";
										echo "<input type='text' name='ListDetailBq[$key][diameter2]' class='form-control input-sm text-center' readonly value='".$value['diameter2']."'>";
									echo "</td>";
									echo "<td align='left'>";
										echo "<input type='text' name='ListDetailBq[$key][qty]' class='form-control input-sm text-center' readonly value='".$value['qty']."'>";
									echo "</td>";
									echo "<td align='left'>";
										echo "<input type='text' name='ListDetailBq[$key][day_in]' class='form-control input-sm text-center' readonly value='".$value['day_in']."'>";
									echo "</td>";
									echo "<td align='left'>";
										echo "<input type='text' name='ListDetailBq[$key][harga_dia]' class='form-control input-sm text-right' readonly value='".number_format($harga_dia)."'>";
									echo "</td>";
									echo "<td align='left'>";
										echo "<input type='text' name='ListDetailBq[$key][total_harga]' class='form-control input-sm text-right' readonly value='".number_format($total_dia)."'>";
									echo "</td>";
								echo "</tr>";
							}
							echo "<tr>";
								echo "<td></td>";
								echo "<td colspan='6'><b>TOTAL HARGA DIA/INCH</b></td>";
								echo "<td align='right'><input type='text' name='total_dia_inch' class='form-control input-sm text-right' readonly value='".number_format($sub_tot_dia)."'></td>";
							echo "</tr>";
						?>
						</tbody>
						
					</table>
				</div>
			</div>

			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>BQ Project</b></label>
				<div class='col-sm-10'>
					<button type="button" id='add_test' style='margin-top:10px; margin-bottom:3px; margin-left:0px; float:right;' class="btn btn-success btn-sm">Add</button>
					<table id='getTab' class="table table-striped table-bordered table-hover table-condensed" width="100%">
						<thead id='head_table'>
							<tr class='bg-purple'>
								<th class="text-center">Description</th>
								<th class="text-center" style='width: 10%;'>Qty</th>
								<th class="text-center" style='width: 10%;'>Satuan</th>
								<th class="text-center" style='width: 15%;'>Harga Satuan</th>
								<th class="text-center" style='width: 15%;'>Total Harga</th>
								<th class="text-center" style='width: 5%;'>#</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(!empty($detail_quo)){
								foreach ($detail_quo as $key => $value) {
									$key++;
									echo 	"<tr>";
									echo 		"<td align='left'>";
									echo			"<input type='text' name='ListDetail[0".$key."][desc]' class='form-control input-sm' placeholder='Description' value='".$value['desc']."'>";
									echo			"<input type='hidden' name='ListDetail[0".$key."][id]' value='".$value['id']."'>";
									echo 		"</td>";
									echo 		"<td align='left'>";
									echo			"<input type='text' name='ListDetail[0".$key."][qty]' class='form-control input-sm text-center autoNumeric0 qty_quo' placeholder='Qty' value='".$value['qty']."'>";
									echo 		"</td>";
									echo 		"<td align='left'>";
									echo			"<input type='text' name='ListDetail[0".$key."][satuan]' class='form-control input-sm text-center' placeholder='Satuan' value='".$value['satuan']."'>";
									echo 		"</td>";
									echo 		"<td align='left'>";
									echo			"<input type='text' name='ListDetail[0".$key."][harga_satuan]' class='form-control input-sm text-right autoNumeric0 harga_quo' placeholder='Harga Satuan' value='".$value['harga_satuan']."'>";
									echo 		"</td>";
									echo 		"<td align='left'>";
									echo			"<input type='text' name='ListDetail[0".$key."][total_harga]' class='form-control input-sm text-right autoNumeric0' placeholder='Total Harga' readonly value='".$value['total_harga']."'>";
									echo 		"</td>";
									echo 		"<td align='center'>";
									echo			"<button type='button'  class='btn btn-danger btn-sm delete_test' data-toggle='tooltip' data-placement='bottom' title='Delete'><i class='fa fa-trash'></i></button>";
									echo 		"</td>";
									echo 	"</tr>";
								}
							}
								?>
						</tbody>
						<tbody id='detail_body_test'></tbody>
					</table>
				</div>
			</div>

			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Total exclude VAT 10%</b></label>
				<div class='col-sm-4'><b>
					<?php
                         echo form_input(array('id'=>'rate_budget','name'=>'rate_budget','class'=>'form-control input-sm','readonly'=>'true'),number_format($header[0]->rate_budget));
					?>
					</b>
				</div>
			</div>

			<!-- include exclude -->
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Include Exclude <span class='text-red'>*</span></b></label>
				<div class='col-sm-5'>
					<table id='getTab' class="table table-striped table-bordered table-hover table-condensed" width="100%">
						<thead id='head_table'>
							<tr class='bg-purple'>
								<th class="text-center">Item Include</th>
								<th class="text-center" width='20%'>#</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($list_include as $key => $value) {
									$checked = (in_array($value['id'], $arrInclude))?'checked':'';
									echo "<tr>";
										echo "<td>".strtoupper($value['name'])."</td>";
										echo "<td><center><input type='checkbox' name='check_include[]' ".$checked." class='chk_personal_in' value='".$value['id']."'></center></td>";
									echo "</tr>";
								}
								foreach ($arrIncludeTxt as $value) {
									if(!empty($value)){
										echo "<tr>";
											echo "<td><input type='text' class='form-control input-sm text-left' name='text_include[]' placeholder='Include desc' value='".$value."'></td>";
											echo "	<td align='center'>
														<button type='button' class='btn btn-sm btn-success copy_include' title='Add'><i class='fa fa-plus'></i></button>
														<button type='button' class='btn btn-sm btn-danger delete_test' style='margin-left:5px;' title='Delete'><i class='fa fa-trash'></i></button>
													</td>";
										echo "</tr>";
									}
								}
							?>
							<tr>
								<td>
									<input type='text' class='form-control input-sm text-left' name='text_include[]' placeholder='Include desc'>
								</td>
								<td align='center'><button type='button' class='btn btn-sm btn-success copy_include' title='Add'><i class='fa fa-plus'></i></button></td>
							</tr>
						</tbody>
						<tbody id='detail_body_IE'></tbody>
					</table>
				</div>
				<div class='col-sm-5'>
					<table id='getTab' class="table table-striped table-bordered table-hover table-condensed" width="100%">
						<thead id='head_table'>
							<tr class='bg-purple'>
								<th class="text-center">Item Exclude</th>
								<th class="text-center" width='20%'>#</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($list_exclude as $key => $value) {
									$checked = (in_array($value['id'], $arrExclude))?'checked':'';
									echo "<tr>";
										echo "<td>".strtoupper($value['name'])."</td>";
										echo "<td><center><input type='checkbox' name='check_exclude[]' ".$checked." class='chk_personal_ex' value='".$value['id']."'></center></td>";
									echo "</tr>";
								}
								foreach ($arrExcludeTxt as $value) {
									if(!empty($value)){
										echo "<tr>";
											echo "<td><input type='text' class='form-control input-sm text-left' name='text_exclude[]' placeholder='Include desc' value='".$value."'></td>";
											echo "	<td align='center'>
														<button type='button' class='btn btn-sm btn-success copy_exclude' title='Add'><i class='fa fa-plus'></i></button>
														<button type='button' class='btn btn-sm btn-danger delete_test' style='margin-left:5px;' title='Delete'><i class='fa fa-trash'></i></button>
													</td>";
										echo "</tr>";
									}
								}
							?>
							<tr>
								<td>
									<input type='text' class='form-control input-sm text-left' name='text_exclude[]' placeholder='Exclude desc'>
								</td>
								<td align='center'><button type='button' class='btn btn-sm btn-success copy_exclude' title='Add'><i class='fa fa-plus'></i></button></td>
							</tr>
						</tbody>
						<tbody id='detail_body_IE2'></tbody>
					</table>
				</div>
			</div>

			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Nilai Penawaran</b></label>
				<div class='col-sm-4'><b>
					<?php
                         echo form_input(array('id'=>'nilai_penawaran','name'=>'nilai_penawaran','class'=>'form-control input-sm autoNumeric0'),number_format($header[0]->nilai_penawaran));
					?>
					</b>
				</div>
			</div>


		</div>
		<div class='box-footer'>
			<?php
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-primary','value'=>'save','content'=>'Save','id'=>'save_work')).' ';
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','value'=>'back','content'=>'Back','id'=>'back_work'));
			?>
		</div>
	</div>
</form>

<?php $this->load->view('include/footer'); ?>
<style>
	.datepicker{
		cursor: pointer;
	}
</style>
<script>
	$(document).ready(function(){
		$('.datepicker').datepicker({
			dateFormat : 'yy-mm-dd'
		});
		$(".autoNumeric0").autoNumeric('init', {mDec: '0', aPad: false});

		$('#save_work').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var reff_no	= $('#reff_no').val();
			var subject		= $('#subject').val();
			var tgl_quo		= $('#tgl_quo').val();

			if(reff_no==''){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Reff No, please input first ...',
				  type	: "warning"
				});
				$('#save_work').prop('disabled',false);
				return false;
			}

			if(subject==''){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Subject, please input first ...',
				  type	: "warning"
				});
				$('#save_work').prop('disabled',false);
				return false;
			}

			if(tgl_quo==''){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Tgl Penawaran, please input first ...',
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

		$('#back_work').click(function(e){
			window.location.href = base_url + active_controller;
		});

		$(document).on('click','.copy_include', function(){
			var Rows	= "<tr>";
				Rows	+= "<td>";
				Rows	+= "<input type='text' class='form-control input-sm text-left' name='text_include[]' placeholder='Include desc'></td>";
				Rows	+= "<td align='center'>";
				Rows	+= "<button type='button' class='btn btn-sm btn-success copy_include' title='Add'><i class='fa fa-plus'></i></button>";
				Rows	+= "&nbsp;<button type='button' class='btn btn-sm btn-danger delete_test' style='margin-left:5px;' title='Delete'><i class='fa fa-trash'></i></button>";
				Rows	+= "</td>";
				Rows	+= "</tr>";
			
			$(this).parent().parent().after(Rows);
		});

		$(document).on('click','.copy_exclude', function(){
			var Rows	= "<tr>";
				Rows	+= "<td>";
				Rows	+= "<input type='text' class='form-control input-sm text-left' name='text_exclude[]' placeholder='Exclude desc'></td>";
				Rows	+= "<td align='center'>";
				Rows	+= "<button type='button' class='btn btn-sm btn-success copy_exclude' title='Add'><i class='fa fa-plus'></i></button>";
				Rows	+= "&nbsp;<button type='button' class='btn btn-sm btn-danger delete_test' style='margin-left:5px;' title='Delete'><i class='fa fa-trash'></i></button>";
				Rows	+= "</td>";
				Rows	+= "</tr>";
			
			$(this).parent().parent().after(Rows);
		});

		$(document).on('click','.delete_test', function(){
			$(this).parent().parent().remove();
		});

		$(document).on('keypress keyup blur', '.qty_quo, .satuan_quo', function(){
			var rate 		= getNum($(this).parent().parent().find("td:nth-child(4) input").val().split(",").join(""));
			var qty 		= getNum($(this).parent().parent().find("td:nth-child(2) input").val().split(",").join(""));
			var rate_total 	= $(this).parent().parent().find("td:nth-child(5) input");
			var rate_t 		= (rate * qty);
			$(rate_total).val(number_format(rate_t));
		});

		//add bq
		$('#add_test').click(function(e){
			e.preventDefault();
			AppendBarisTest();
		});
	});

	function AppendBarisTest(){
		var valuex	= $('#detail_body_test').find('tr').length;
		var nomor	= valuex + 1;
		
		var Rows = 	"<tr>";
			Rows += 	"<td align='left'>";
			Rows +=			"<input type='text' name='ListDetailAdd["+nomor+"][desc]' class='form-control input-sm' placeholder='Description'>";
			Rows += 	"</td>";
			Rows += 	"<td align='left'>";
			Rows +=			"<input type='text' name='ListDetailAdd["+nomor+"][qty]' class='form-control input-sm text-center autoNumeric0 qty_quo' placeholder='Qty'>";
			Rows += 	"</td>";
			Rows += 	"<td align='left'>";
			Rows +=			"<input type='text' name='ListDetailAdd["+nomor+"][satuan]' class='form-control input-sm text-center' placeholder='Satuan'>";
			Rows += 	"</td>";
			Rows += 	"<td align='left'>";
			Rows +=			"<input type='text' name='ListDetailAdd["+nomor+"][harga_satuan]' class='form-control input-sm text-right autoNumeric0 satuan_quo' placeholder='Harga Satuan'>";
			Rows += 	"</td>";
			Rows += 	"<td align='left'>";
			Rows +=			"<input type='text' name='ListDetailAdd["+nomor+"][total_harga]' class='form-control input-sm text-right autoNumeric0' readonly>";
			Rows += 	"</td>";
			Rows += 	"<td align='center'>";
			Rows +=			"<button type='button'  class='btn btn-danger btn-sm delete_test' data-toggle='tooltip' data-placement='bottom' title='Delete'><i class='fa fa-trash'></i></button>";
			Rows += 	"</td>";
			Rows += "</tr>";

		$('#detail_body_test').append(Rows);
		$(".autoNumeric0").autoNumeric('init', {mDec: '0', aPad: false});
	}
</script>
