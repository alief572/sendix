
<div class="box box-primary">
	<div class="box-body">
		<div class='form-group row'>
			<label class='label-control col-sm-3'><b>Approved</b></label>
			<div class='col-sm-9'>
				<?php
                    echo form_input(array('type'=>'hidden','id'=>'project_code','name'=>'project_code','class'=>'form-control input-md','autocomplete'=>'off','readonly'=>'readonly'),strtoupper($id));
                ?>
                <select name="status" id="status" class='form-control'>
                    <option value="0">Select An Option</option>
                    <option value="N">Back To Estimation</option>
                    <option value="X">Back To Costing</option>
                </select>
			</div>
    </div>
		<div class='form-group row'>
			<label class='label-control col-sm-3'><b>Reason</b></label>
			<div class='col-sm-9'>
				<?php
				 echo form_textarea(array('id'=>'reason_approved','name'=>'reason_approved','class'=>'form-control input-md','rows'=>'3'));
				?>
			</div>
		</div>
    <div class='form-group row'>
			<label class='label-control col-sm-3'></label>
			<div class='col-sm-9'>
                <?php
                echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','value'=>'Reject','content'=>'Reject','id'=>'reject'));
                ?>
			</div>
		</div>
	</div>
</div>

<script>
swal.close();

</script>
