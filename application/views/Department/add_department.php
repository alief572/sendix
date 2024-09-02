<?php
$ArrSelect['Y']	= 'Active';
$ArrSelect['N']	= 'Not Active';

$id             = (!empty($data[0]->id))?$data[0]->id:'';
$nm_dept        = (!empty($data[0]->nm_dept))?$data[0]->nm_dept:'';
$status         = (!empty($data[0]->status))?$data[0]->status:'Y';
?>
<div class="box box-primary"><br>
    <div class="box-body">
        <div class="form-group row">
            <div class="col-md-3">
                <label>Department Name</label> 
            </div>
            <div class="col-md-9">
                <input type="text" class="form-control" id="nm_dept" name="nm_dept" placeholder="Department Name" value='<?=$nm_dept;?>'>
                <input type="hidden" class="form-control" id="id" name="id" value='<?=$id;?>'>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <label>Status</label>
            </div>
            <div class="col-md-9">
                <?php
                    echo form_dropdown('status', $ArrSelect, $status, array('id'=>'status','class'=>'form-control input-md chosen-select'));
                ?>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3"></div>
            <div class="col-md-9">
                <button type="button" class="btn btn-primary" name="save" id="save"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    swal.close();
</script>