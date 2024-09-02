<?php
$this->load->view('include/side_menu'); 
//echo"<pre>";print_r($data_menu);
?> 
<form action="#" method="POST" id="form_proses_bro">   
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?php echo $title;?></h3>		
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='form-group row'>			
				<label class='label-control col-sm-3'><b>Name <span class='text-red'>*</span></b></label>
				<div class='col-sm-6'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>              
						<?php
							
							echo form_input(array('id'=>'id_user','name'=>'id_user','type'=>'hidden','value'=>$rows_data[0]->id_user));
							echo form_input(array('id'=>'nm_lengkap','name'=>'nm_lengkap','class'=>'form-control input-sm','value'=>$rows_data[0]->nm_lengkap,'readOnly'=>true));											
						?>
					</div>
							
				</div>
				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-3'><b>Username <span class='text-red'>*</span></b></label>
				<div class='col-sm-6'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>              
						<?php
							echo form_input(array('id'=>'username','name'=>'username','class'=>'form-control input-sm','autocomplete'=>'off','placeholder'=>'Username','value'=>$rows_data[0]->username,'readOnly'=>true));											
						?>
					</div>
							
				</div>
				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-3'><b>Group <span class='text-red'>*</span></b></label>
				<div class='col-sm-6'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>              
						<?php
							$data_group[0]	= 'Select An Option';						
							echo form_dropdown('group_id',$data_group, $rows_data[0]->group_id, array('id'=>'group_id','class'=>'form-control input-sm','disabled'=>true));											
						?>
					</div>
					
				</div>
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-3'><b>Old Password <span class='text-red'>*</span></b></label>
				<div class='col-sm-6'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-key"></i></span>              
						<?php
							echo form_password(array('id'=>'old_password','name'=>'old_password','class'=>'form-control input-sm','autocomplete'=>'off','placeholder'=>'Old Password'));											
						?>
					</div>
				</div>
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-3'><b>New Password <span class='text-red'>*</span></b></label>
				<div class='col-sm-6'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-key"></i></span>              
						<?php
							echo form_password(array('id'=>'new_password','name'=>'new_password','class'=>'form-control input-sm','autocomplete'=>'off','placeholder'=>'New Password'));											
						?>
					</div>
				</div>
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-3'><b>Confirm Password <span class='text-red'>*</span></b></label>
				<div class='col-sm-6'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-key"></i></span>              
						<?php
							echo form_password(array('id'=>'confirm_password','name'=>'confirm_password','class'=>'form-control input-sm','autocomplete'=>'off','placeholder'=>'Old Password'));											
						?>
					</div>
				</div>
			</div>
		</div>
		<div class='box-footer'>
			<?php
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-primary','value'=>'save','content'=>'Save','id'=>'simpan-bro')).' ';
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','value'=>'back','content'=>'Back','onClick'=>'javascript:back()'));
			?>
		</div>
		<!-- /.box-body -->
	 </div>
  <!-- /.box -->
</form>

<?php $this->load->view('include/footer'); ?>
<script>
	$(document).ready(function(){
		$('#user_phone').mask('?999-999-999-999');
		$('#simpan-bro').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var kode_user		= $('#id_user').val();
			var username		= $('#username').val();
			var password_lama	= $('#old_password').val();
			var password_baru	= $('#new_password').val();
			var password_cek	= $('#confirm_password').val();
			
			if(password_lama=='' || password_lama==null || password_lama=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Old Password, please input Old Password first.....',
				  type	: "warning"
				});
				$('#simpan-bro').prop('disabled',false);
				return false;
				
			}else{
				var baseurl=base_url + active_controller +'/Validasi_Password';
				$.ajax({
					url			: baseurl,
					type		: "POST",
					data		: {'id_user':kode_user,'password':password_lama},									
					success		: function(data){
						console.log(data);
						var datas	= $.parseJSON(data);
						if(datas.status == 2){											
							swal({
							  title	: "Error Message!",
							  text	: 'Incorrect Old Password, please input Correct Old Password first.....',
							  type	: "warning"
							});
							$('#simpan-bro').prop('disabled',false);
							return false;
						}
					},
					error: function() {						
						swal({
							  title	: "Error Message!",
							  text	: 'An error occured during process, please try again.......',
							  type	: "warning"
							});
						$('#simpan-bro').prop('disabled',false);
						return false;
					}
				});
			}
			
			if(password_baru=='' || password_baru==null || password_baru=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty New Password, please input New Password first.....',
				  type	: "warning"
				});
				$('#simpan-bro').prop('disabled',false);
				return false;
				
			}
			
			if(password_cek=='' || password_cek==null || password_cek=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Confirm Password, please input Confirm Password first.....',
				  type	: "warning"
				});
				$('#simpan-bro').prop('disabled',false);
				return false;
				
			}
			if(password_baru != password_cek){
				swal({
				  title	: "Error Message!",
				  text	: 'Confirm Password does not match, please input Confirm Password first.....',
				  type	: "warning"
				});
				$('#simpan-bro').prop('disabled',false);
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
				  closeOnConfirm: true,
				  closeOnCancel: false
				},
				function(isConfirm) {
				  if (isConfirm) {
						loading_spinner();
						var formData 	=new FormData($('#form_proses_bro')[0]);
						var baseurl=base_url + active_controller +'/change_password';
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
										  timer	: 7000,
										  showCancelButton	: false,
										  showConfirmButton	: false,
										  allowOutsideClick	: false
										});
									window.location.href = base_url + active_controller;
								}else{
									
									if(data.status == 2){
										swal({
										  title	: "Save Failed!",
										  text	: data.pesan,
										  type	: "warning",
										  timer	: 7000,
										  showCancelButton	: false,
										  showConfirmButton	: false,
										  allowOutsideClick	: false
										});
									}else{
										swal({
										  title	: "Save Failed!",
										  text	: data.pesan,
										  type	: "warning",
										  timer	: 7000,
										  showCancelButton	: false,
										  showConfirmButton	: false,
										  allowOutsideClick	: false
										});
									}
									$('#simpan-bro').prop('disabled',false);
								}
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
								$('#simpan-bro').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#simpan-bro').prop('disabled',false);
					return false;
				  }
			});
		});
	});
	
</script>
