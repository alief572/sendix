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
						echo form_input(array('id'=>'company_name','name'=>'company_name','class'=>'form-control input-md', 'readonly'=>'readonly', 'value'=>$rows_data[0]->nm_perusahaan));
					?>
				</div>
				<label class='label-control col-sm-2'><b>Province <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					 <?php
						echo form_input(array('id'=>'company_phone','name'=>'company_phone','class'=>'form-control input-md', 'readonly'=>'readonly','value'=>$rows_data[0]->kota));
					?>		
				</div>
				
			</div>
			
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Phone <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					 <?php
						echo form_input(array('id'=>'company_phone','name'=>'company_phone','class'=>'form-control input-md', 'readonly'=>'readonly','value'=>$rows_data[0]->no_telp));
					?>	
				</div>
				<label class='label-control col-sm-2'><b>Fax <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					 <?php
					 echo form_input(array('id'=>'company_fax','name'=>'company_fax','class'=>'form-control input-md', 'readonly'=>'readonly','value'=>$rows_data[0]->fax));
					?>		
				</div>	
			</div>
			
			<div class='form-group row'>			
				
				<label class='label-control col-sm-2'><b>Website</b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'company_web','name'=>'company_web','class'=>'form-control input-md', 'readonly'=>'readonly','value'=>$rows_data[0]->website));
					?>	
				</div>
				<label class='label-control col-sm-2'><b>Address <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					 <?php
						echo form_textarea(array('id'=>'company_address','name'=>'company_address','class'=>'form-control input-md','rows'=>'3','cols'=>'75', 'readonly'=>'readonly','value'=>$rows_data[0]->alamat));
					?>
				</div>
			</div>
			
		</div>
		<div class='box-footer'>
			<?php
			// echo form_button(array('type'=>'submit','class'=>'btn btn-md btn-primary','value'=>'save','content'=>'Save','id'=>'simpan-bro')).' ';
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
