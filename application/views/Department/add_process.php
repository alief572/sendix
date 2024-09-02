<?php

$id             = (!empty($data[0]->id))?$data[0]->id:'';
$code_process   = (!empty($data[0]->code_process))?$data[0]->code_process:'';
$nm_process     = (!empty($data[0]->nm_process))?$data[0]->nm_process:'';
$keterangan     = (!empty($data[0]->keterangan))?$data[0]->keterangan:'';
?>
<div class="box box-primary"><br>
    <div class="box-body">
        <div class="form-group row">
            <div class="col-md-3">
                <label>Process Name</label>
            </div>
            <div class="col-md-9">
                <input type="text" class="form-control" id="nm_process" name="nm_process" placeholder="Process Name" value='<?=$nm_process;?>'>
                <input type="hidden" class="form-control" id="id" name="id" value='<?=$id;?>'>
                <input type="hidden" class="form-control" id="code_process" name="code_process" value='<?=$code_process;?>'>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <label>Information</label>
            </div>
            <div class="col-md-9">
                <textarea type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Information"><?=$keterangan;?></textarea>
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