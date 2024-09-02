<?php
$this->load->view('include/side_menu');
//echo"<pre>";print_r($data_menu);
$sel_a = ($header[0]->tipe == 'above')?'selected':'';
$sel_u = ($header[0]->tipe == 'under')?'selected':'';

?>
<form action="#" method="POST" id="form_work">
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?php echo $title;?></h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Work Category <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					echo form_input(array('id'=>'category','name'=>'category','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Work Category'),strtoupper($header[0]->category));
					echo form_input(array('type'=>'hidden','id'=>'code_work','name'=>'code_work','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Work Category'),$header[0]->code_work);
					?>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Total Time (Day) <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'total_time','name'=>'total_time','class'=>'form-control input-md autoNumeric','placeholder'=>'Total Time'),$header[0]->total_time);
					?>
				</div>
				<label class='label-control col-sm-2' hidden><b>Tipe Instalasi <span class='text-red'>*</span></b></label>
				<div class='col-sm-4' hidden>
					<select name="tipe" id="tipe" class='form-control'>
						<option value="0">Pilih Tipe</option>
						<option value="above" <?=$sel_a;?>>ABOVE GROUND</option>
						<option value="under" <?=$sel_u;?>>UNDER GROUND</option>
					</select>
				</div>
			</div>
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">Resource</h3>
				</div>
				<table id="my-grid" class="table table-striped table-bordered table-hover table-condensed" width="100%">
					<thead>
						<tr class='bg-purple'>
							<th class="text-center" style='width: 100%;'>Heavy Equipment</th>
							<th class="text-center" style='width: 50%;' hidden>Tools</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<select name='List_equipment[]' id='product_equipment' class='chosen_select form-control inline-block' multiple>
									<?php
										foreach($heavy_equip as $row)	{
											$sel2 = (isset($heavyx) && in_array($row->code_group, $heavyx))?'selected':'';
											echo "<option value='".$row->code_group."' $sel2>".strtoupper($row->category." - ".$row->spec)."</option>";
										}
									?>
								</select>
							</td>
							<td hidden>
								<select name='Listvehicle_tool[]' id='product_vehicle_tool' class='chosen_select form-control inline-block' multiple>
								<?php
									foreach($vehicle as $row)	{
										$sel1 = (isset($vehiclex) && in_array($row->code_group, $vehiclex))?'selected':'';
										 echo "<option value='".$row->code_group."' $sel1>".strtoupper($row->category." - ".$row->spec)."</option>";
									}
								?>
								</select>
							</td>
						</tr>
					</tbody>
					<thead hidden>
						<tr class='bg-purple'>
							<th class="text-center" style='width: 50%;'>Consumable & APD</th>
							<th class="text-center" style='width: 50%;'>Man Power</th>
						</tr>
					</thead>
					<tbody  hidden>
						<tr>
							<td>
								<select name='Listcon_nonmat[]' id='product_con_nonmat' class='chosen_select form-control inline-block' multiple>
								<?php
									foreach($consumable as $row)	{
										$sel2 = (isset($consumablex) && in_array($row->code_group, $consumablex))?'selected':'';
										 echo "<option value='".$row->code_group."' $sel2>".strtoupper($row->category." - ".$row->spec)."</option>";
									}
								?>
							</select>
						</td>
							<td>
								<select name='Listman_power[]' id='product_man_power' class='chosen_select form-control inline-block' multiple>
								<?php
									foreach($man_power as $row)	{
										$sel3 = (isset($man_powerx) && in_array($row->code_group, $man_powerx))?'selected':'';
										 echo "<option value='".$row->code_group."' $sel3>".strtoupper($row->category." - ".$row->spec)."</option>";
									}
								?>
							</select>
						</td>
						</tr>
					</tbody>
				</table>
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
				  text	: 'Empty Work Category, please input first ...',
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

		$('#back_work').click(function(e){
			window.location.href = base_url + active_controller;
		});

	});
</script>
