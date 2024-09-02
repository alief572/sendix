<?php
$this->load->view('include/side_menu');
//echo"<pre>";print_r($data_menu);
?>
<form action="#" method="POST" id="form_man_power" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?php echo $title;?></h3>
		</div>
		<?php
			$category 		= (!empty($header[0]->category))?$header[0]->category:'';
			$category_code 	= (!empty($header[0]->category_code))?$header[0]->category_code:'';
			$category_awal 	= (!empty($header[0]->category_awal))?$header[0]->category_awal:'';
			$spec 			= (!empty($region[0]['spec']))?$region[0]['spec']:'';
			$tanda 			= (!empty($header[0]->category))?'edit':'';
			$spec_val 		= (!empty($spec))?$spec:'';

			$material_name 	= (!empty($header[0]->material_name))?$header[0]->material_name:'';
			$spec 			= (!empty($header[0]->spec))?$header[0]->spec:'';
			$unit 			= (!empty($header[0]->unit))?$header[0]->unit:'6';

			echo form_input(array('type'=>'hidden','name'=>'tanda_edit','class'=>'form-control input-md'),$tanda);
			echo form_input(array('type'=>'hidden','name'=>'code_group','id'=>'code_group','class'=>'form-control input-md'),$this->uri->segment(3));
		?>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Category <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<select name='category' id='category' class='form-control input-md'>
							<option value='0'>List Empty</option>
						</select>
						<span class="input-group-addon add_plus" id="add">Add</span>
					</div>
				</div>
				<label class='label-control col-sm-2'><b>Spesification <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'spec','name'=>'spec','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Spesification'),$spec);
					?>
				</div>
			</div>
			<div id='add_categoryS'>
				<div class='form-group row'>
					<label class='label-control col-sm-2'>Add Category</label>
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
					 echo form_input(array('type'=>'hidden','id'=>'category_awal','name'=>'category_awal'),'1');
					 echo form_input(array('id'=>'material_name','name'=>'material_name','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Material Name'),$material_name);
					?>
				</div>
				<label class='label-control col-sm-2'><b>Unit <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<select name='unit' id='unit' class='form-control input-md'>
						<option value='0'>Select Unit</option>
						<?php
							foreach($satuan AS $val => $valx){
								$selected = ($unit == $valx['id'])?'selected':'';
								echo "<option value='".$valx['id']."' ".$selected.">".strtoupper(strtolower($valx['unit']))."</option>";
							}
						?>
					</select>
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
	.add_plus{
		cursor : pointer;
		color: white;
		background-color: #605ca8 !important;
	}
	#save_category {
	  color: white;
	  background-color: #605ca8;
	}
</style>
<script>
	$(document).ready(function(){
		$('.maskM').maskMoney();
		$('#add_categoryS').hide();
		
		let category_awal 	= $('#category_awal').val();
		let category2 		= '<?=$category_code?>';
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
					title	: "Error Message !",
					text	: 'Connection Time Out. Please try again..',
					type	: "warning",
					timer	: 3000
				});
				}
			});
		}

		$('#save_rutin').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var category		= $('#category').val();
			var material_name	= $('#material_name').val();
			var spec			= $('#spec').val();

			if(category == '0'){
				swal({
					title	: "Error Message!",
					text	: 'Empty Category , please input first ...',
					type	: "warning"
				});
				$('#save_rutin').prop('disabled',false);
				return false;
			}
			if(material_name == ''){
				swal({
				  	title	: "Error Message!",
				  	text	: 'Empty Material Name, please input first ...',
				  	type	: "warning"
				});
				$('#save_rutin').prop('disabled',false);
				return false;
			}
			if(spec == ''){
				swal({
				  	title	: "Error Message!",
				  	text	: 'Empty Spesification, please input first ...',
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
								else if(data.status == 2 || data.status == 3){
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 3000
									});
								}
								$('#save_rutin').prop('disabled',false);
							},
							error: function() {

								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',
								  type				: "warning",
								  timer				: 3000
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
			var category = $('#add_category').val();
			if(category == ''){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Category Add, please input first ...',
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
										  timer	: 3000
										});
									window.location.href = base_url + active_controller+'/add_new/'+data.code_group;
								}
								else if(data.status == 2 || data.status == 3){
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 3000
									});
								}
								$('#save_category').prop('disabled',false);
							},
							error: function() {

								swal({
								  title		: "Error Message !",
								  text		: 'An Error Occured During Process. Please try again..',
								  type		: "warning",
								  timer		: 3000
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
	});
</script>
