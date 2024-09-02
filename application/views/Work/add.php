<?php
$this->load->view('include/side_menu');
//echo"<pre>";print_r($data_menu);
?>
<form action="#" method="POST" id="form_work" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?php echo $title;?></h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Job Name <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'category','name'=>'category','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Job Name'));
					?>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Total Time (Day) <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'total_time','name'=>'total_time','class'=>'form-control input-md autoNumeric','placeholder'=>'Total Time','data-decimal'=>'.', 'data-thousand'=>'', 'data-precision'=>'0', 'data-allow-zero'=>''));
					?>
				</div>
				<label class='label-control col-sm-2' hidden><b>Tipe Instalasi <span class='text-red'>*</span></b></label>
				<div class='col-sm-4' hidden>
					<select name="tipe" id="tipe" class='form-control'>
						<option value="0">Pilih Tipe</option>
						<option value="above" selected>ABOVE GROUND</option>
						<option value="under">UNDER GROUND</option>
					</select>
				</div>
			</div><br>
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title">Resource</h3>
				</div>
				<div class="box-body" >
					<table id="my-grid" class="table table-striped table-bordered table-hover table-condensed" width="100%">
						<thead>
							<tr class='bg-purple'>
								<th class="text-center" style='width: 100%;'>Heavy Equipment</th>
								<th class="text-center" style='width: 50%;' hidden>Tools</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><select name='List_equipment[]' id='product_equipment' class='chosen_select form-control inline-block' multiple></select></td>
								<td hidden><select name='Listvehicle_tool[]' id='product_vehicle_tool' class='chosen_select form-control inline-block' multiple></select></td>
							</tr>
						</tbody>
						<thead hidden>
							<tr class='bg-purple'>
								<th class="text-center" style='width: 50%;'>Consumable & APD</th>
								<th class="text-center" style='width: 50%;'>Man Power</th>
							</tr>
						</thead>
						<tbody hidden>
							<tr>
								<td><select name='Listcon_nonmat[]' id='product_con_nonmat' class='chosen_select form-control inline-block' multiple></select></td>
								<td><select name='Listman_power[]' id='product_man_power' class='chosen_select form-control inline-block' multiple></select></td>
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

<script>
	$(document).ready(function(){
		$('.autoNumeric').autoNumeric();
		$('.chosen_select').chosen({width: '100%'});

		// $.ajax({
		// 	url: base_url+active_controller+'/list_vehicle_tool',
		// 	cache: false,
		// 	type: "POST",
		// 	dataType: "json",
		// 	success: function(data){
		// 		$("#product_vehicle_tool").html(data.option).trigger("chosen:updated");
		// 	}
		// });

		$.ajax({
			url: base_url+active_controller+'/list_heavy_equipment',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$("#product_equipment").html(data.option).trigger("chosen:updated");
			}
		});

		// $.ajax({
		// 	url: base_url+active_controller+'/list_con_nonmat',
		// 	cache: false,
		// 	type: "POST",
		// 	dataType: "json",
		// 	success: function(data){
		// 		$("#product_con_nonmat").html(data.option).trigger("chosen:updated");
		// 	}
		// });

		// $.ajax({
		// 	url: base_url+active_controller+'/list_man_power',
		// 	cache: false,
		// 	type: "POST",
		// 	dataType: "json",
		// 	success: function(data){
		// 		$("#product_man_power").html(data.option).trigger("chosen:updated");
		// 	}
		// });

		//save
		$('#save_work').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var category	= $('#category').val();
			var total_time	= $('#total_time').val();
			var tipe	= $('#tipe').val();

			if(category=='' || category==null){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Work Name, please input first ...',
				  type	: "warning"
				});
				$('#save_work').prop('disabled',false);
				return false;
			}

			if(total_time=='0' || total_time=='' || total_time==null){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Total Time, please input first ...',
				  type	: "warning"
				});
				$('#save_work').prop('disabled',false);
				return false;
			}

			if(tipe=='0'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Tipe Instalasi, please input first ...',
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
</script>
