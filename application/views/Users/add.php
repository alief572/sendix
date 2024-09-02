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
				<label class='label-control col-sm-2'><b>Name <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<?php
							echo form_input(array('id'=>'nm_lengkap','name'=>'nm_lengkap','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Full Name'));
						?>
					</div>

				</div>
				<label class='label-control col-sm-2'><b>Group User</b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<?php
							$data_group[0]	= 'Select An Option';
							echo form_dropdown('group_id',$data_group, '0', array('id'=>'group_id','class'=>'form-control input-md'));
						?>
					  </div>

				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Province <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-home"></i></span>

						 <?php
						 $rows_province[0]='Select An Province';
							echo form_dropdown('user_province',$rows_province, 0, array('id'=>'user_province','class'=>'form-control input-md'));
						 ?>
					</div>

				</div>
				<label class='label-control col-sm-2'><b>Phone <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-phone"></i></span>

						 <?php
						 echo form_input(array('id'=>'user_phone','name'=>'user_phone','class'=>'form-control input-md','placeholder'=>'User Phone'));
						?>
					</div>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Email <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-envelope"></i></span>

						 <?php
							 echo form_input(array('id'=>'user_email','name'=>'user_email','class'=>'form-control input-md','placeholder'=>'User Email'));
						 ?>
					</div>

				</div>
				<label class='label-control col-sm-2'><b>Branch <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-building"></i></span>

						 <?php
						 $branch[0]		='Select An Branch';
						 echo form_dropdown('branch',$branch, '0', array('id'=>'branch','class'=>'form-control input-md'));
						?>
					</div>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Username <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<?php
							echo form_input(array('id'=>'username','name'=>'username','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Username'));
						?>
					</div>

				</div>
				<label class='label-control col-sm-2'><b>Work Location <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-building"></i></span>
						<select name='kdcab' id='kdcab' class='form-control input-md'>
							<option value=''>Empty List</option>
						</select>
					</div>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Password <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-key"></i></span>
						<?php
							echo form_password(array('id'=>'password','name'=>'password','class'=>'form-control input-md','autocomplete'=>'off','placeholder'=>'Password'));
						?>
					</div>

				</div>
				<label class='label-control col-sm-2'><b>Address <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
						 <?php
							echo form_textarea(array('id'=>'user_address','name'=>'user_address','class'=>'form-control input-md','rows'=>'2','cols'=>'75','placeholder'=>'User Address'));
						?>
					</div>

				</div>
				<label class='label-control col-sm-2'><b> </b></label>
				<div class='col-sm-4'>
				<?php
				/*
					$active		= True;
					$data = array(
							'name'          => 'st_aktif',
							'id'            => 'st_aktif',
							'value'         => '1',
							'checked'       => $active,
							'class'         => 'input-sm'
					);

					echo form_checkbox($data).'&nbsp;&nbsp;Yes';
					*/
				?>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Department <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-home"></i></span>
						 <?php
						 $department[0]='Select An Department';
							echo form_dropdown('department_id',$department, 0, array('id'=>'department_id','class'=>'form-control input-md','required'=>'required'));
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
<style>
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
	#user_province_chosen{
		width: 100% !important;
	}
	#branch_chosen{
		width: 100% !important;
	}
	#kdcab_chosen{
		width: 100% !important;
	}
	#group_id_chosen{
		width: 100% !important;
	}
</style>
<script>
	$(document).ready(function(){
		$('#user_phone').mask('?9999-9999-99999');
		$(document).on('change', '#branch', function(e){
			e.preventDefault();
			$.ajax({
				url: base_url +'index.php/'+ active_controller+'/getWorkLocation',
				cache: false,
				type: "POST",
				data: "branch="+this.value,
				dataType: "json",
				success: function(data){
					$("#kdcab").html(data.option).trigger("chosen:updated");
				}
			});
		});

		$('#simpan-bro').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var nama		= $('#nm_lengkap').val();
			var username	= $('#username').val();
			var group		= $('#group_id').val();
			var password	= $('#password').val();
			var phone		= $('#user_phone').val();
			var email		= $('#user_email').val();
			if(nama=='' || nama==null || nama=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Fullname, please input Fullname first.....',
				  type	: "warning"
				});
				$('#simpan-bro').prop('disabled',false);
				return false;

			}



			if(phone=='' || phone==null || phone=='-' || phone.length < 10){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty User Phone, please input User Phone first.....',
				  type	: "warning"
				});
				$('#simpan-bro').prop('disabled',false);
				return false;

			}

			if(email=='' || email==null || email=='-' || validateEmail(email) == false){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty / Incorrect User Email, please input User Email first.....',
				  type	: "warning"
				});
				$('#simpan-bro').prop('disabled',false);
				return false;

			}

			if(username=='' || username==null || username=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Username, please input username first.....',
				  type	: "warning"
				});
				$('#simpan-bro').prop('disabled',false);
				return false;

			}

			if(password=='' || password==null || password=='-'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Password, please input password first.....',
				  type	: "warning"
				});
				$('#simpan-bro').prop('disabled',false);
				return false;
			}

			if(group=='' || group==null || group=='0'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Group, please choose group first.....',
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
				  closeOnConfirm: false,
				  closeOnCancel: false
				},
				function(isConfirm) {
				  if (isConfirm) {
						loading_spinner();
						var formData 	=new FormData($('#form_proses_bro')[0]);
						var baseurl=base_url + active_controller +'/register_user';
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
								}else{

									if(data.status == 2){
										swal({
										  title	: "Save Failed!",
										  text	: data.pesan,
										  type	: "warning",
										  timer	: 7000
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
	function validateEmail(sEmail) {
		var numericExpression = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
		if (sEmail.match(numericExpression)) {
			return true;
		}
		else {
			return false;
		}
	}
</script>
