<?php
$this->load->view('include/side_menu');
//echo"<pre>";print_r($data_menu);

$ArrSatuan = array();
foreach($satuan AS $val => $valx){
	$ArrSatuan[$valx['unit']] = strtoupper($valx['unit']);
}
?>
<form action="#" method="POST" id="form_man_power" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?php echo $title;?></h3>
		</div>
		<?php
			$category 	= (!empty($header[0]->category))?$header[0]->category:'';
			$category_code 	= (!empty($header[0]->category_code))?$header[0]->category_code:'';
			$category_awal 	= (!empty($header[0]->category_awal))?$header[0]->category_awal:'';
			$spec 			= (!empty($region[0]['spec']))?$region[0]['spec']:'';
			$tanda 			= (!empty($header[0]->category))?'edit':'';
			$spec_val 	= (!empty($spec))?$spec:'';

			$material_name 	= (!empty($header[0]->material_name))?$header[0]->material_name:'';
			$general_name 	= (!empty($header[0]->general_name))?$header[0]->general_name:'';
			$spec 	= (!empty($header[0]->spec))?$header[0]->spec:'';
			$brand 	= (!empty($header[0]->brand))?$header[0]->brand:'';

			$min_order 	= (!empty($header[0]->min_order))?$header[0]->min_order:'';
			$order_opt 	= (!empty($header[0]->order_opt))?$header[0]->order_opt:'';
			$order_point 	= (!empty($header[0]->order_point))?$header[0]->order_point:'';
			$order_point_date 	= (!empty($header[0]->order_point_date))?$header[0]->order_point_date:'';

			$safety_stock 	= (!empty($header[0]->safety_stock))?$header[0]->safety_stock:'';
			$lead_time 	= (!empty($header[0]->lead_time))?$header[0]->lead_time:'';
			$max_stock 	= (!empty($header[0]->max_stock))?$header[0]->max_stock:'';
			$konsumsi 	= (!empty($header[0]->konsumsi))?$header[0]->konsumsi:'';
			$note 	= (!empty($header[0]->note))?$header[0]->note:'';

			echo form_input(array('type'=>'hidden','name'=>'tanda_edit','class'=>'form-control input-md'),$tanda);
			echo form_input(array('type'=>'hidden','name'=>'code_group','id'=>'code_group','class'=>'form-control input-md'),$this->uri->segment(3));
		?>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Inventory Type <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<select name='category_awal' id='category_awal' class='form-control input-md'>
						<option value='0'>Select Inventory Type </option>
						<?php
							foreach($cateMPUtama AS $val => $valx){
								$selected = ($category_awal == $valx['id'])?'selected':'';
								echo "<option value='".$valx['id']."' ".$selected.">".strtoupper(strtolower($valx['category']))."</option>";
							}
						?>
					</select>
				</div>

				<label class='label-control col-sm-2'><b>Category <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
					<select name='category' id='category' class='form-control input-md'>
						<option value='0'>List Empty</option>
					</select>
					 <span class="input-group-addon add_plus" id="add">Add</span>
					 </div>
				</div>
			</div>
			<div id='add_categoryS'>
				<div class='form-group row'>
					<label class='label-control col-sm-2'>Add Category Barang</label>
					<div class='col-sm-3'>
						<div class="form-group">
							<label class='label-control'><b>Category<span class='text-red'>*</span></b></label>
							<select name='add_category_awal' id='add_category_awal' class='form-control input-md'>
								<option value=''>Select Category </option>
								<?php
									foreach($cateMPUtama AS $val => $valx){
										echo "<option value='".$valx['id']."'>".strtoupper(strtolower($valx['category']))."</option>";
									}
								?>
							</select>
						</div>
					</div>
					<div class='col-sm-3'>
						<div class="form-group">
							<label class='label-control'><b>Category Barang<span class='text-red'>*</span></b></label>
							<?php
							 echo form_input(array('id'=>'add_category','name'=>'add_category','class'=>'form-control input-md numAlfa','autocomplete'=>'off','placeholder'=>'Category'));
							?>
						</div>
					</div>
					<div class='col-sm-4'>
						<div class="form-group">
							<label class='label-control'><b>Information</b></label>
							<div class="input-group">
							<?php
							 echo form_input(array('id'=>'information','name'=>'information','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Information'));
							?>
							<span class="input-group-addon add_plus" id="save_category"><b>Save Category</b></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Material Name <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'material_name','name'=>'material_name','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Material Name'),$material_name);
					?>
				</div>
				<label class='label-control col-sm-2'><b>General Name <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'general_name','name'=>'general_name','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'General Name'),$general_name);
					?>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Spesification/Sertification <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'spec','name'=>'spec','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Spesification/Sertification'),$spec);
					?>
				</div>
				<label class='label-control col-sm-2'><b>Brand <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'brand','name'=>'brand','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Brand'),$brand);
					?>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Minimal Order Stock <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'min_order','name'=>'min_order','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Minimal Order Stock'),$min_order);
					?>
				</div>
				<label class='label-control col-sm-2'><b>Consumption (Month) <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'konsumsi','name'=>'konsumsi','class'=>'form-control input-md numberFull','autocomplete'=>'off','placeholder'=>'Consumption'),$konsumsi);
					?>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Safety Stock (Day) <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'safety_stock','name'=>'safety_stock','class'=>'form-control input-md numberFull','autocomplete'=>'off','placeholder'=>'Safety Stock'),$safety_stock);
					?>
				</div>
				<label class='label-control col-sm-2'><b>Lead Time (Day) <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'lead_time','name'=>'lead_time','class'=>'form-control input-md numberFull','autocomplete'=>'off','placeholder'=>'Lead Time'),$lead_time);
					?>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Maximum Stock <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'max_stock','name'=>'max_stock','readonly'=>'readonly','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Maximum Stock'),$max_stock);
					?>
				</div>
				<label class='label-control col-sm-2'><b>Re-Order Point <span class='text-red'>*</span></b></label>
				<div class='col-sm-1'>
					<select name='order_opt' id='order_opt' class='form-control input-md'>
						<option value='qty' <?= ($order_opt == 'qty')?'selected':'';?>>Qty</option>
						<option value='date' <?= ($order_opt == 'date')?'selected':'';?>>Date</option>
					</select>
				</div>
				<div class='col-sm-3'>
					<?php
					 echo form_input(array('id'=>'order_point','name'=>'order_point','class'=>'form-control input-md','readonly'=>'readonly','placeholder'=>'Qty'),$order_point);
					 echo form_input(array('id'=>'order_point_date','name'=>'order_point_date','data-role'=>'datepicker','class'=>'form-control input-md','readonly'=>'readonly','placeholder'=>'Select Date'),$order_point_date);

					?>
				</div>

			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Note</b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'note','name'=>'note','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Note'),$note);
					?>
				</div>
			</div>
			<div class='form-group row'>
				<div class='col-sm-6'>
					<button type="button" id='konversi' style='width:100px; margin-top:10px; margin-bottom:1px; margin-left:10px; float:left;' class="btn btn-success btn-sm">Add</button>
					<br><br>
					<input type="hidden" id="num_konversi" value="0">
					<div class="box-body">
						<table class="table table-striped table-bordered table-hover table-condensed" width="100%">
								<thead id='head_table'>
									<tr class='bg-purple'>
										<th colspan='4' class="text-center">&nbsp;&nbsp;&nbsp;UNIT AND CONVERSION</th>
									</tr>
									<tr class='bg-purple'>
										<th class="text-center" style='width: 28%;'>Unit Material</th>
										<th class="text-center" style='width: 28%;'>Conversion Value</th>
										<th class="text-center" style='width: 28%;'>Smallest Unit</th>
										<th class="text-center" style='width: 16%;'>#</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if(!empty($konversi)){
										$no=0;
										foreach($konversi AS $val => $valx){$no++;
											echo	"<tr id='trcn_".$no."'>";
											echo 		"<td align='left' style='text-align: left;'>";
											echo form_dropdown('ListKonversi[0'.$no.'][unit_material]', $ArrSatuan, $valx['unit_material'], array('class'=>'form-control input-md clSelect'));
											echo 		"</td>";
											echo 		"<td align='left'>";
											echo				"<input type='number' name='ListKonversi[0".$no."][value]' class='form-control input-md' placeholder='Conversion Value' value='".$valx['value']."'>";
											echo 		"</td>";
											echo		"<td align='left' style='text-align: left;'>";
											echo form_dropdown('ListKonversi[0'.$no.'][small_unit]', $ArrSatuan, $valx['small_unit'], array('class'=>'form-control input-md clSelect'));
											echo 		"</td>";
											echo 		"<td align='center'>";
											echo			"<button type='button' style='min-width:70px;' class='btn btn-danger btn-sm delRow' data-tanda='konversi' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
											echo 		"</td>";
											echo 	"</tr>";
										}
									}
									?>
								</tbody>
								<tbody id='detail_body_uc'></tbody>
								<tbody id='detail_body_konversi'>
									<tr>
										<td colspan='4'>Add Unit and Conversion empty ...</td>
									</tr>
								</tbody>
							</table>
						</div>
				</div>
				<div class='col-sm-6'>
					<button type="button" id='material' style='width:100px; margin-top:10px; margin-bottom:1px; margin-left:10px; float:left;' class="btn btn-success btn-sm">Add</button>
					<br><br>
					<input type="hidden" id="num_material" value="0">
					<div class="box-body">
						<table class="table table-striped table-bordered table-hover table-condensed" width="100%">
								<thead id='head_table'>
									<tr class='bg-purple'>
										<th colspan='2' class="text-center">&nbsp;&nbsp;&nbsp;SIMILAR MATERIAL</th>
									</tr>
									<tr class='bg-purple'>
										<th class="text-center" style='width: 84%;'>Material Name</th>
										<th class="text-center" style='width: 16%;'>#</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if(!empty($material)){
										$no=0;
										foreach($material AS $val => $valx){$no++;
											echo	"<tr id='trcn_".$no."'>";
											echo 		"<td align='left'>";
											echo				"<input type='text' name='ListMaterial[0".$no."][material]' class='form-control input-md' placeholder='Material Name' value='".$valx['value']."'>";
											echo 		"</td>";
											echo 		"<td align='center'>";
											echo			"<button type='button' style='min-width:70px;' class='btn btn-danger btn-sm delRow' data-tanda='material' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
											echo 		"</td>";
											echo 	"</tr>";
										}
									}
									?>
								</tbody>
								<tbody id='detail_body_mat'></tbody>
								<tbody id='detail_body_material'>
									<tr>
										<td colspan='2'>Add Similar Material empty ...</td>
									</tr>
								</tbody>
							</table>
						</div>
				</div>
			</div>
			<div class='form-group row'>
				<div class='col-sm-6'>
					<div class="box-body">
						<table class="table table-striped table-bordered table-hover table-condensed" width="100%">
								<thead>
									<tr class='bg-purple'>
										<th class="text-center">&nbsp;&nbsp;&nbsp;ALTERNATIVE SUPPLIER</th>
									</tr>
									<tr class='bg-purple'>
										<th class="text-center">Supplier Name</th>
									</tr>
								</thead>
								<tbody id='detail_body_sup'>
									<tr>
										<td><select name='ListSupplier[]' id='list_supplier' class='chosen_select form-control inline-block' multiple></select></td>
									</tr>
								</tbody>
							</table>
						</div>
				</div>
			</div>
		</div>
		<div class='box-footer'>
			<?php
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-primary','value'=>'save','content'=>'Save','id'=>'save_rutin')).' ';
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','value'=>'back','content'=>'Back','id'=>'back_man_power'));
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
	#category_chosen{
		width: 100% !important;
	}
	.add_plus{
		cursor : pointer;
		color: white;
		background-color: #605ca8 !important;
	}
	.maskM{
		text-align:right;
	}
	#save_category {
	  color: white;
	  background-color: #605ca8;
	}
	#order_point_date {
	  cursor: pointer;
	}
</style>
<script>
	$(document).ready(function(){
		$('.maskM').maskMoney();
		$('#add_categoryS').hide();
		$('#order_point_date').hide();

		var opt = $("#order_opt").val();
	  if(opt == 'qty' || opt == ''){
	    $('#order_point').show();
	    $('#order_point_date').hide();
	  }
	  if(opt == 'date'){
	    $('#order_point').hide();
	    $('#order_point_date').show();
	  }

		$('#order_opt').change(function(e){
			var opt = $(this).val();
			if(opt == 'qty'){
				$('#order_point').show();
				$('#order_point_date').hide();
			}
			if(opt == 'date'){
				$('#order_point').hide();
				$('#order_point_date').show();
			}
		});

		$("#safety_stock,#lead_time,#konsumsi").on("keypress keyup blur", function (event) {
			order_point();
		});

		//list SUPPLIER
		var code_g = $("#code_group").val();
		// alert(code_g);
		$.ajax({
			url: base_url+'index.php/'+active_controller+'/list_supplier/'+code_g,
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$("#list_supplier").html(data.option).trigger("chosen:updated");
			}
		});

		let category_awal = $('#category_awal').val();
		let category2 = '<?=$category_code?>';
		if(category_awal != '0'){
			let category = $('#category');
			$.ajax({
				url: base_url+active_controller+'/get_category2/'+category_awal+'/'+category2,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data){
					$(category).html(data.option).trigger("chosen:updated");
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


		// tabAdd

		$('#add').click(function(e){
			$('#add_categoryS').slideToggle("slow");
			var htmL = $(this).html();
			if(htmL == 'Add'){
				$(this).html('Close')
			}
			if(htmL == 'Close'){
				$(this).html('Add')
			}
		});

		$(document).on('change', '#category_awal', function(){
			var category_awal = $(this).val();
			var category = $("#category");
			loading_spinner();
			$.ajax({
				url: base_url+active_controller+'/get_category/'+category_awal,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data){
					$(category).html(data.option).trigger("chosen:updated");
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

		$('#save_rutin').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var category_awal	= $('#category_awal').val();
			var category			= $('#category').val();

			var material_name	= $('#material_name').val();
			var general_name	= $('#general_name').val();
			var spec					= $('#spec').val();
			var brand					= $('#brand').val();
			var min_order			= $('#min_order').val();

			var safety_stock	= $('#safety_stock').val();
			var lead_time			= $('#lead_time').val();
			var max_stock			= $('#max_stock').val();
			var konsumsi			= $('#konsumsi').val();

			if(category_awal=='0'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Category, please input first ...',
				  type	: "warning"
				});
				$('#save_rutin').prop('disabled',false);
				return false;
			}
			if(category=='0'){
				swal({
					title	: "Error Message!",
					text	: 'Empty Category Barang , please input first ...',
					type	: "warning"
				});
				$('#save_rutin').prop('disabled',false);
				return false;
			}

			if(material_name=='' || material_name==null || material_name=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Material Name, please input first ...',
				  type	: "warning"
				});
				$('#save_rutin').prop('disabled',false);
				return false;
			}
			if(general_name=='' || general_name==null || general_name=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty General Name, please input first ...',
				  type	: "warning"
				});
				$('#save_rutin').prop('disabled',false);
				return false;
			}
			if(spec=='' || spec==null || spec=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Spesification/Sertification, please input first ...',
				  type	: "warning"
				});
				$('#save_rutin').prop('disabled',false);
				return false;
			}
			if(brand=='' || brand==null || brand=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Brand, please input first ...',
				  type	: "warning"
				});
				$('#save_rutin').prop('disabled',false);
				return false;
			}
			if(min_order=='' || min_order==null || min_order=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Minimal Order Stock, please input first ...',
				  type	: "warning"
				});
				$('#save_rutin').prop('disabled',false);
				return false;
			}
			if(safety_stock=='' || safety_stock==null || safety_stock=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Safety Stock, please input first ...',
				  type	: "warning"
				});
				$('#save_rutin').prop('disabled',false);
				return false;
			}
			if(lead_time=='' || lead_time==null || lead_time=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Lead Time, please input first ...',
				  type	: "warning"
				});
				$('#save_rutin').prop('disabled',false);
				return false;
			}
			if(max_stock=='' || max_stock==null || max_stock=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Maximum Stock, please input first ...',
				  type	: "warning"
				});
				$('#save_rutin').prop('disabled',false);
				return false;
			}
			if(konsumsi=='' || konsumsi==null || konsumsi=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Consumption, please input first ...',
				  type	: "warning"
				});
				$('#save_rutin').prop('disabled',false);
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
						var formData 	= new FormData($('#form_man_power')[0]);
						var baseurl		= base_url + active_controller +'/add_new';
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

								$('#save_rutin').prop('disabled',false);
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
								$('#save_rutin').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#save_rutin').prop('disabled',false);
					return false;
				  }
			});
		});

		$('#save_category').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var category	= $('#add_category').val();
			// alert(category);
			if(category=='' || category==null){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Akomodasi Category Add, please input first ...',
				  type	: "warning"
				});
				$('#save_category').prop('disabled',false);
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
						var formData 	= new FormData($('#form_man_power')[0]);
						var baseurl		= base_url + active_controller +'/add_category';
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
									window.location.href = base_url + active_controller+'/add_new/'+data.code_group;
								}
								else if(data.status == 2 || data.status == 3){
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 7000
									});
								}
								$('#save_category').prop('disabled',false);
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
								$('#save_category').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#save_category').prop('disabled',false);
					return false;
				  }
			});
		});


		$('#back_man_power').click(function(e){
			window.location.href = base_url + active_controller;
		});

		//Add Material
		var nomorMat	= 1;
		$('#material').click(function(e){
			e.preventDefault();
			var nilaiAwal	= parseInt($("#num_material").val());
			var nilaiAkhir	= nilaiAwal + 1;
			$("#num_material").val(nilaiAkhir);
			loading_spinner();
			AppendMaterial(nomorMat);
			swal.close();
			$('.chosen_select').chosen({width: '100%'});
			$("#detail_body_material").hide();
		});

		//Add Unit Convert
		var nomorMat	= 1;
		$('#konversi').click(function(e){
			e.preventDefault();
			var nilaiAwal	= parseInt($("#num_konversi").val());
			var nilaiAkhir	= nilaiAwal + 1;
			$("#num_konversi").val(nilaiAkhir);
			loading_spinner();
			AppendConvert(nomorMat);
			swal.close();
			$('.chosen_select').chosen({width: '100%'});
			$("#detail_body_konversi").hide();
		});

		$(document).on('click','.delRow', function(){
			var tanda = $(this).data('tanda');
			var num = $("#num_"+tanda).val();
			var min = num - 1;
			$("#num_"+tanda).val(min);
			$(this).parent().parent().remove();

			var maxLine = $("#num_"+tanda).val();
			if(maxLine == 0){
				$("#detail_body_"+tanda).show();
			}
		});

	});

	function AppendMaterial(intd){
		var nomor	= 1;
		var valuex	= $('#detail_body_mat').find('tr').length;
		if(valuex > 0){
			var akhir	= $('#detail_body_mat tr:last').attr('id');
			var det_id	= akhir.split('_');
			var nomor	= parseInt(det_id[1])+1;
		}

		var Rows	 = 	"<tr id='trmat_"+nomor+"'>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListMaterial["+nomor+"][material]' id='material_"+nomor+"' class='form-control input-md' placeholder='Material Name'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='center'>";
			Rows 	+=			"<button type='button' style='min-width:70px;' class='btn btn-danger btn-sm delRow' data-tanda='material' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
			Rows	+= 		"</td>";
			Rows	+= 	"</tr>";

		$('#detail_body_mat').append(Rows);
	}

	function AppendConvert(intd){
		var nomor	= 1;
		var valuex	= $('#detail_body_uc').find('tr').length;
		if(valuex > 0){
			var akhir	= $('#detail_body_uc tr:last').attr('id');
			var det_id	= akhir.split('_');
			var nomor	= parseInt(det_id[1])+1;
		}

		var Rows	 = 	"<tr id='trcn_"+nomor+"'>";
			Rows	+= 		"<td align='left' style='text-align: left;'>";
			Rows	+=			"<select name='ListKonversi["+nomor+"][unit_material]' id='unit_material_"+nomor+"' class='chosen_select form-control inline-block'></select>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='number' name='ListKonversi["+nomor+"][value]' id='value_"+nomor+"' class='form-control input-md' placeholder='Conversion Value'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left' style='text-align: left;'>";
			Rows	+=			"<select name='ListKonversi["+nomor+"][small_unit]' id='small_unit_"+nomor+"' class='chosen_select form-control inline-block'></select>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='center'>";
			Rows 	+=			"<button type='button' style='min-width:70px;' class='btn btn-danger btn-sm delRow' data-tanda='konversi' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
			Rows	+= 		"</td>";
			Rows	+= 	"</tr>";

		$('#detail_body_uc').append(Rows);

		var unit_material 	= '#unit_material_'+nomor;
		var small_unit 	= '#small_unit_'+nomor;

		loading_spinner();
		$.ajax({
			url: base_url+'index.php/'+active_controller+'/list_satuan',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$(unit_material).html(data.option).trigger("chosen:updated");
				$(small_unit).html(data.option).trigger("chosen:updated");
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

	}

	function order_point(){
		var safety_stock 		= getNum($("#safety_stock").val());
		var lead_time 			= getNum($("#lead_time").val());
		var konsumsi 				= getNum($("#konsumsi").val()) / 30;
		var konsumsi_day 		= getNum($("#konsumsi").val());
		var hitung 				= (safety_stock * konsumsi) + (lead_time * konsumsi);
		var max_stock			= hitung + konsumsi_day;
		$("#order_point").val(hitung.toFixed(2));
		$("#max_stock").val(max_stock.toFixed(2));
	}
</script>
