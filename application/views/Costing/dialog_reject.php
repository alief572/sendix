<?php
$ArrIPP['C'] = 'Reject to Costing';
$ArrIPP['I'] = 'Reject to Project Instalation';
 ?>
<div class="box box-primary">
	<div class="box-body">
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>Project Name</b></label>
			<div class='col-sm-10'>
				<?php
        echo form_input(array('type'=>'hidden','id'=>'project_code','name'=>'project_code','class'=>'form-control input-md','autocomplete'=>'off','readonly'=>'readonly'),strtoupper($id));
        echo form_input(array('id'=>'project_name','name'=>'project_name','class'=>'form-control input-md','autocomplete'=>'off','readonly'=>'readonly'),strtoupper(get_name('project_header', 'project_name', 'project_code', $id)));
				?>
			</div>
    </div>
    <div class='form-group row'>
      <label class='label-control col-sm-2'><b>Reject To</b></label>
      <div class='col-sm-10'>
				<?php
				 echo form_dropdown('tanda', $ArrIPP, 'C', array('id'=>'tanda','name'=>'tanda','class'=>'form-control input-md clSelect'));
				?>
      </div>
    </div>
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>Reason</b></label>
			<div class='col-sm-10'>
				<?php
				 echo form_textarea(array('id'=>'reason_approved','name'=>'reason_approved','class'=>'form-control input-md','rows'=>'3'));
				?>
			</div>
		</div>
    <div class='form-group row'>
			<label class='label-control col-sm-2'></label>
			<div class='col-sm-10'>
        <?php
        // echo form_button(array('type'=>'button','class'=>'btn btn-md btn-success','value'=>'Approve','content'=>'Approve','id'=>'approve'));
        echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','value'=>'Reject','content'=>'Reject','id'=>'reject'));
        ?>
			</div>
		</div>
	</div>
</div>

<script>
swal.close();

</script>
