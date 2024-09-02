<?php
$this->load->view('include/side_menu'); 

?> 
<form action="<?= site_url(strtolower($this->uri->segment(1).'/'.$action))?>" method="POST" id="form_proses_bro">   
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?=$title;?></h3>		
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Company <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					 <?php
						echo form_hidden('ididentitas',$rows_data[0]->ididentitas);
						echo form_input(array('id'=>'company_name','name'=>'company_name','class'=>'form-control input-md','value'=>$rows_data[0]->nm_perusahaan));
					?>
				</div>
				<label class='label-control col-sm-2'><b>Province <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					 <?php
						echo form_dropdown('company_province',$rows_province, $rows_data[0]->kota, array('id'=>'company_province','class'=>'form-control input-md'));
					 ?>		
				</div>
				
			</div>
			
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Phone <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					 <?php
						echo form_input(array('id'=>'company_phone','name'=>'company_phone','class'=>'form-control input-md','value'=>$rows_data[0]->no_telp));
					?>	
				</div>
				<label class='label-control col-sm-2'><b>Fax <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					 <?php
					 echo form_input(array('id'=>'company_fax','name'=>'company_fax','class'=>'form-control input-md','value'=>$rows_data[0]->fax));
					?>		
				</div>	
			</div>
			
			<div class='form-group row'>			
				
				<label class='label-control col-sm-2'><b>Website</b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'company_web','name'=>'company_web','class'=>'form-control input-md','value'=>$rows_data[0]->website));
					?>	
				</div>
				<label class='label-control col-sm-2'><b>Address <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					 <?php
						echo form_textarea(array('id'=>'company_address','name'=>'company_address','class'=>'form-control input-md','rows'=>'3','cols'=>'75','value'=>$rows_data[0]->alamat));
					?>
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
	#company_province_chosen{
		width: 100% !important;
	}
</style>
<script>

$(document).ready(function(){
	$('#company_phone, #company_fax').mask('?999-99999999');
	$('#simpan-bro').click(function(e){
		$(this).prop('disabled',true);
		 e.preventDefault();
		 var nama		= $('#company_name').val();
		 var alamat		= $('#company_address').val();
		 var propinsi	= $('#company_province').val();
		 
		 if(nama == '' || nama=='-' || nama==null){
			swal({
			  title	: "Error Message!",
			  text	: 'Empty Company Name. Please Input Company Name First.......',
			  type	: "warning"
			});
			$('#simpan-bro').prop('disabled',false);
			return false;
		}
		 if(alamat == '' || alamat=='-' || alamat==null){
			swal({
			  title	: "Error Message!",
			  text	: 'Empty Company Address. Please Input Company Address First.......',
			  type	: "warning"
			});
			$('#simpan-bro').prop('disabled',false);
			return false;
		}
		
		 if(propinsi == '' || propinsi=='-' || propinsi==null){
			swal({
			  title	: "Error Message!",
			  text	: 'Empty Province Address. Please Input Province Address First.......',
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
					var formData 	= new FormData($('#form_proses_bro')[0]);
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
									  timer	: 15000,
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
									  type	: "danger",
									  timer	: 10000,
									  showCancelButton	: false,
									  showConfirmButton	: false,
									  allowOutsideClick	: false
									});
								}else{
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 10000,
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
