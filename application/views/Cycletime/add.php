<?php
$this->load->view('include/side_menu');

$tanda 		= (!empty($this->uri->segment(3)))?'edit':'';
$diameter 	= (!empty($data))?$data[0]['diameter']:'';
$pressure 	= (!empty($data))?$data[0]['pressure']:'';
$liner 		= (!empty($data))?$data[0]['liner']:'';
$mp 		= (!empty($data))?$data[0]['mp']:'';
$ct 		= (!empty($data))?$data[0]['ct']:'';
$mh 		= (!empty($data))?$data[0]['mh']:'';

?>
<form action="#" method="POST" id="form_process" autocomplete="off">
	<?php
	echo form_input(array('type'=>'hidden','name'=>'tanda_edit','class'=>'form-control input-md'),$tanda);
	echo form_input(array('type'=>'hidden','name'=>'id','class'=>'form-control input-md'),$this->uri->segment(3));
	?>
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?php echo $title;?></h3>
		</div>
		<div class="box-body">
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Diameter <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
          		<?php
					 echo form_input(array('id'=>'diameter','name'=>'diameter','class'=>'form-control input-md autoNumeric0','placeholder'=>'Diameter'),$diameter);
					?>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Pressure <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<select name="pressure" id="pressure">
						<option value="0">Select Pressure</option>
						<?php
						foreach (api_get_pressure() as $key => $value) {
							$sel = ($pressure == $value['name'])?'selected':'';
							echo "<option value='".$value['name']."' ".$sel.">".$value['data2']."</option>";
						}
						?>
					</select>
				</div>
				<label class='label-control col-sm-2'><b>Liner <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<select name="liner" id="liner">
						<option value="0">Select Liner</option>
						<?php
						foreach (api_get_liner() as $key => $value) {
							$sel = ($liner == $value['name'])?'selected':'';
							echo "<option value='".$value['name']."' ".$sel.">".$value['data2']."</option>";
						}
						?>
					</select>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Man Power <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
          		<?php
					 echo form_input(array('id'=>'mp','name'=>'mp','class'=>'form-control input-md autoNumeric0','placeholder'=>'Man Power'),$mp);
					?>
				</div>
				<label class='label-control col-sm-2'><b>Total Time (Hours) <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
          		<?php
					 echo form_input(array('id'=>'ct','name'=>'ct','class'=>'form-control input-md autoNumeric','placeholder'=>'Total Time'),$ct);
					?>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Man Hours</b></label>
				<div class='col-sm-4'>
          		<?php
					 echo form_input(array('id'=>'mh','name'=>'mh','class'=>'form-control input-md','placeholder'=>'Man Hours','readonly'=>true),$mh);
					?>
				</div>
			</div>
		</div>
		<div class='box-footer'>
			<?php
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-primary','value'=>'save','content'=>'Save','id'=>'saved')).' ';
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','value'=>'back','content'=>'Back','id'=>'back'));
			?>
		</div>
	</div>
</form>

<?php $this->load->view('include/footer'); ?>
<script>
	$(document).ready(function(){
		
		$('#saved').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var diameter	= $('#diameter').val();
			var pressure	= $('#pressure').val();
			var liner		= $('#liner').val();
			var mp			= $('#mp').val();
			var ct			= $('#ct').val();

			if(diameter==''){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty diameter, please input first ...',
				  type	: "warning"
				});
				$('#saved').prop('disabled',false);
				return false;
			}
			if(pressure=='0'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty pressure, please input first ...',
				  type	: "warning"
				});
				$('#saved').prop('disabled',false);
				return false;
			}
			if(liner=='0'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty liner, please input first ...',
				  type	: "warning"
				});
				$('#saved').prop('disabled',false);
				return false;
			}
			if(mp==''){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty man power, please input first ...',
				  type	: "warning"
				});
				$('#saved').prop('disabled',false);
				return false;
			}
			if(ct==''){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty total time, please input first ...',
				  type	: "warning"
				});
				$('#saved').prop('disabled',false);
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
						var formData 	= new FormData($('#form_process')[0]);
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

								$('#saved').prop('disabled',false);
							},
							error: function() {

								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',
								  type				: "warning",
								  timer				: 3000,
								  showCancelButton	: false,
								  showConfirmButton	: false,
								  allowOutsideClick	: false
								});
								$('#saved').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#saved').prop('disabled',false);
					return false;
				  }
			});
		});

		$('#back').click(function(e){
			window.location.href = base_url + active_controller;
		});

		$(document).on('keyup','#mp, #ct',function(){
			getManHours();
		});

		let getManHours = () => {
			let mp = getNum($('#mp').val().split(",").join(""))
			let ct = getNum($('#ct').val().split(",").join(""))
			let mh = mp * ct

			$('#mh').val(number_format(mh,2))
		}
	});
</script>
