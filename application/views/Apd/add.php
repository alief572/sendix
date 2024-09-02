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
			$category 	= (!empty($region[0]['category']))?$region[0]['category']:'';
			$spec 		= (!empty($region[0]['spec']))?$region[0]['spec']:'';
			$tanda 		= (!empty($region[0]['spec']))?'edit':'';
			$spec_val 	= (!empty($spec))?$spec:'';

			echo form_input(array('type'=>'hidden','name'=>'tanda_edit','class'=>'form-control input-md'),$tanda);
			echo form_input(array('type'=>'hidden','name'=>'code_group','class'=>'form-control input-md'),$this->uri->segment(3));
		?>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Akomodasi Category <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
					<select name='category' id='category' class='form-control input-md'>
						<option value=''>Select Akomodasi Category </option>
						<?php
							foreach($cateMP AS $val => $valx){
								$selected = ($category == $valx['category'])?'selected':'';
								echo "<option value='".$valx['category']."' ".$selected.">".strtoupper(strtolower($valx['category']))."</option>";
							}
						?>
					</select>
					 <span class="input-group-addon add_plus" id="add">Add</span>
					 </div>
				</div>
				<label class='label-control col-sm-2'><b>Spesification/Sertification <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'spec','name'=>'spec','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Spesification/Sertification'),$spec_val);
					?>
				</div>
			</div>
			<div id='add_categoryS'>
				<div class='form-group row'>
					<label class='label-control col-sm-2'>Add Category Akomodasi</label>
					<div class='col-sm-4'>
						<div class="form-group">
							<label class='label-control'><b>Category <span class='text-red'>*</span></b></label>
							<?php
							 echo form_input(array('id'=>'add_category','name'=>'add_category','class'=>'form-control input-md numAlfa','autocomplete'=>'off','placeholder'=>'Category'));
							?>
						</div>
					</div>
					<div class='col-sm-6'>
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
				<!--
				<div class='form-group row'>
					<label class='label-control col-sm-2'></label>
					<div class='col-sm-10'>
						<table class="table table-bordered table-striped" id='tabAdd'>
							<thead>
								<tr class='bg-purple'>
									<th class="text-center">No</th>
									<th class="text-center">Category</th>
									<th class="text-center">Information</th>
							</thead>
							<tbody>
								<?php
								// $no=0;
								// foreach($cateMP AS $val => $valx){ $no++;
									// echo "<tr>";
										// echo "<td align='center'>".$no."</td>";
										// echo "<td align='left'>".$valx['category']."</td>";
										// echo "<td align='left'>".$valx['information']."</td>";
									// echo "</tr>";
								// }
								?>
							</tbody>
						</table>
					</div>
				</div>
				-->
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Standart Rate <span class='text-red'>*</span></b></label>
			</div>
			<?php
			$nomor = 0;
			foreach($region AS $valR => $valxR){
				$rate = (!empty($valxR['rate']))?$valxR['rate']:'';
				$id = (!empty($valxR['id']))?$valxR['id']:'';
				$nomor++;
				?>
				<div class='form-group row'>
					<label class='label-control col-sm-2'></label>
					<label class='label-control col-sm-2'><?= ucwords(strtolower($valxR['region']));?></label>
					<div class='col-sm-2'>
						<?php
							echo form_input(array('type'=>'hidden','id'=>$valxR['region_code'],'name'=>'DetailData['.$nomor.'][region]','class'=>'form-control input-md'),$valxR['region']);
							echo form_input(array('id'=>'rate_'.$valxR['region_code'],'name'=>'DetailData['.$nomor.'][rate]','class'=>'form-control input-md maskM','placeholder'=>'Rate '.ucwords(strtolower($valxR['region'])),'placeholder'=>'Rate '.ucwords(strtolower($valxR['region'])),'data-decimal'=>'.','data-thousand'=>'','data-precision'=>'0','data-allow-zero'=>false),$rate);
							echo form_input(array('type'=>'hidden','name'=>'DetailData['.$nomor.'][id]','class'=>'form-control input-md'),$id);

						?>
					</div>
				</div>
			<?php } ?>
		</div>
		<div class='box-footer'>
			<?php
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-primary','value'=>'save','content'=>'Save','id'=>'save_man_power')).' ';
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
</style>
<script>
	$(document).ready(function(){
		$('.maskM').maskMoney();
		$('#add_categoryS').hide();

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

		$('#save_man_power').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var category	= $('#category').val();
			var spec		= $('#spec').val();

			if(category=='' || category==null){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Akomodasi Category, please input first ...',
				  type	: "warning"
				});
				$('#save_man_power').prop('disabled',false);
				return false;
			}
			if(spec=='' || spec==null || spec=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Spesification/Sertification, please input first ...',
				  type	: "warning"
				});
				$('#save_man_power').prop('disabled',false);
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

								$('#save_man_power').prop('disabled',false);
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
								$('#save_man_power').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#save_man_power').prop('disabled',false);
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
									window.location.href = base_url + active_controller+'/add/'+data.code_group;
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


	});
</script>
