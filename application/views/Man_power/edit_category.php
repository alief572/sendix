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
			$category 	= (!empty($header[0]['category']))?$header[0]['category']:'';
			$information 		= (!empty($header[0]['information']))?$header[0]['information']:'';
			echo form_input(array('type'=>'hidden','name'=>'id','class'=>'form-control input-md'),$this->uri->segment(3));
		?>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Category <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
                    <?php
					 echo form_input(array('id'=>'category','name'=>'category','class'=>'form-control input-md','placeholder'=>'category'),$category);
					?>
				</div>
				<label class='label-control col-sm-2'><b>Information </b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'information','name'=>'information','class'=>'form-control input-md','placeholder'=>'information'),$information);
					?>
				</div>
			</div>
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

		$('#save_man_power').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var category	= $('#category').val();

			if(category=='' ){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Category, please input first ...',
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
						var baseurl		= base_url + active_controller +'/edit_category';
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

		$('#back_man_power').click(function(e){
			window.location.href = base_url + active_controller;
		});


	});
</script>
