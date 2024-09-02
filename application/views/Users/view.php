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
							echo form_input(array('id'=>'nm_lengkap','name'=>'nm_lengkap','class'=>'form-control input-sm','autocomplete'=>'off','readOnly'=>true,'value'=>$rows_data[0]->nm_lengkap));											
						?>
					</div>
							
				</div>
				<label class='label-control col-sm-2'><b>Address</b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>

						 <?php
							// echo form_hidden('id',$row[0]->kode_divisi);
							echo form_textarea(array('id'=>'user_address','name'=>'user_address','class'=>'form-control input-sm','rows'=>'2','cols'=>'75','readOnly'=>true,'value'=>$rows_data[0]->alamat));
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
							echo form_dropdown('user_province',$rows_province, $rows_data[0]->kota, array('id'=>'user_province','class'=>'form-control input-sm','disabled'=>true));
						 ?>
					</div>
							
				</div>
				<label class='label-control col-sm-2'><b>Phone <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-phone"></i></span>

						 <?php
						 echo form_input(array('id'=>'user_phone','name'=>'user_phone','class'=>'form-control input-sm','readOnly'=>true,'value'=>$rows_data[0]->hp));
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
							 echo form_input(array('id'=>'user_email','name'=>'user_email','class'=>'form-control input-sm','readOnly'=>true,'value'=>$rows_data[0]->email));
						 ?>
					</div>
							
				</div>
				<label class='label-control col-sm-2'><b>Work Location <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-building"></i></span>

						 <?php
						 $rows_branch['HO']		='Head Office';
						 echo form_dropdown('kdcab',$rows_branch, $rows_data[0]->kdcab, array('id'=>'kdcab','class'=>'form-control input-sm','disabled'=>true));
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
							echo form_input(array('id'=>'username','name'=>'username','class'=>'form-control input-sm','autocomplete'=>'off','readOnly'=>true,'value'=>$rows_data[0]->username));											
						?>
					</div>
							
				</div>
				<label class='label-control col-sm-2'><b>Group <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
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
				
						
				<label class='label-control col-sm-2'><b> Status</b></label>
				<div class='col-sm-4'>
				<?php
				if($rows_data[0]->st_aktif == 1){
					$Label	= 'ACTIVE';
					$Class	= 'green';
				}else{
					if($rows_data[0]->deleted==1){
						$Label	= 'DELETED';
						$Class	= 'red';
					}else{
						$Label	= 'INACTIVE';
						$Class	= 'maroon';
					}
				}
				echo "<span class='badge bg-".$Class."'>".$Label."</span>";
				
				?>				
				</div>
				<label class='label-control col-sm-2'><b>Department <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-home"></i></span>
						 <?php
						 $deptid=$rows_data[0]->department_id;
						 if($deptid=='')$deptid=0;
						 $department[0]='Select An Department';
							echo form_dropdown('department_id',$department, $deptid, array('id'=>'department_id','class'=>'form-control input-md','disabled'=>true));
						 ?>
					</div>
				</div>
			</div>
						
		</div>
		<div class='box-footer'>
			<?php
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
		
	});
	
</script>
