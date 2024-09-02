<?php
$this->load->view('include/side_menu');

$tanda 		= (!empty($this->uri->segment(3)))?'edit':'';
$include 	= (!empty($data[0]['category']) AND $data[0]['category'] == 'include')?'selected':'';
$exclude 	= (!empty($data[0]['category']) AND $data[0]['category'] == 'exclude')?'selected':'';
$name   	= (!empty($data))?$data[0]['name']:'';

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
				<label class='label-control col-sm-2'><b>Category <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
          		    <select name="category" id="category" class='form-control'>
                        <option value="0">Select An Option</option>
                        <option value="include" <?=$include;?>>INCLUDE</option>
                        <option value="exclude" <?=$exclude;?>>EXCLUDE</option>
                    </select>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Name</b></label>
				<div class='col-sm-4'>
          		<?php
					 echo form_input(array('id'=>'name','name'=>'name','class'=>'form-control input-md','placeholder'=>'Name'),$name);
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
			var category	= $('#category').val();
			var name	= $('#name').val();

			if(category=='0'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty catgeory, please input first ...',
				  type	: "warning"
				});
				$('#saved').prop('disabled',false);
				return false;
			}
			if(name==''){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty name, please input first ...',
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
	});
</script>
