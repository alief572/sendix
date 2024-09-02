<?= form_open($this->uri->uri_string(),array('id'=>'frm_data','name'=>'frm_data','role'=>'form','class'=>'form-horizontal')) ?>
<input type="hidden" id="tipe" name="tipe" value="<?php echo $tipe; ?>">
<link rel="stylesheet" href="<?=base_url()?>assets/plugins/select2/select2.css">
<script src="<?=base_url()?>assets/plugins/select2/select2.full.min.js"></script>
<?php
$readonly=""; 
?>
<div class="tab-content">
	<div class="tab-pane active">
		<div class="box box-primary">
			<div class="box-body">
				<div class="form-group ">
					<label for="coa" class="col-sm-2 control-label">COA<b class="text-red">*</b></label>
					<div class="col-sm-10"><?php ?>
						<select name="coa[]" id="coa" class="form-control select2" multiple placeholder="COA">
						<?php
						$arraycoa=array();
						if(isset($data)) $arraycoa=explode(';',$data->kode_text);
						foreach ($datacoa as $key=>$val){
							$selected='';
							if(isset($data->kode_text)){
								if (in_array($key, $arraycoa)) {
								  $selected=' selected';
								}
							}
							echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
						}
						?>
						</select>
					</div>					
				</div>
				<div class="box-footer">
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" name="save" class="btn btn-success btn-sm" id="submit"><i class="fa fa-save">&nbsp;</i>Simpan</button>
							<a class="btn btn-warning btn-sm" onclick="cancel()"><i class="fa fa-reply">&nbsp;</i>Batal</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= form_close() ?>
<script type="text/javascript">
	var url_save = base_url+'pettycash/coa_save/';	
	$("#coa").focus();
	$('.select2').select2();
    $('#frm_data').on('submit', function(e){
        e.preventDefault();
		var errors="";
		if(errors==""){
			data_save();
		}else{
			swal(errors);
			return false;
		}
    });
</script>
