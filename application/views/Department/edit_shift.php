<?php
$ArrDay['sunday'] = 'Sunday';
$ArrDay['monday'] = 'Monday';
$ArrDay['tuesday'] = 'Tuesday';
$ArrDay['wednesday'] = 'Wednesday';
$ArrDay['thursday'] = 'Thursday';
$ArrDay['friday'] = 'Friday';
$ArrDay['saturday'] = 'Saturday';

$ArrShift = array();
foreach($shift AS $val => $valx){
	$ArrShift[$valx['id']] = strtoupper($valx['name']);
}
?>
<div class="box box-primary">
	<!-- /.box-header -->
	<div class="box-body">
    <br>
		<input type="hidden" name="id_shift" id="id_shift" value='<?= $data[0]->id_shift; ?>'>
        <div class="form-group row">
            <div class="col-md-3">
                <label for="">Day Name</label>
            </div>
                <div class="col-md-9">
                <?php
                    echo form_dropdown('day', $ArrDay, $data[0]->day, array('id'=>'day','class'=>'form-control input-md chosen-select'));
                ?>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <label for="">Shift Name</label>
            </div>
                <div class="col-md-9">
                <?php
                    echo form_dropdown('id_type', $ArrShift, $data[0]->id_type, array('id'=>'id_type','class'=>'form-control input-md chosen-select'));
                ?>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <label for="">Work Time</label>
            </div>
            <div class="col-md-2">
                <input type="time" class="form-control" id="start_work" name="start_work"  value="<?= strtolower($data[0]->start_work) ?>">
            </div>
            <div class="col-md-2">
                <input type="time" class="form-control" id="done_work" name="done_work"  value="<?= strtolower($data[0]->done_work) ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <label for="">Break Time 1</label>
            </div>
            <div class="col-md-2">
                <input type="time" class="form-control" id="start_break_1" name="start_break_1"  value="<?= strtolower($data[0]->start_break_1) ?>">
            </div>
            <div class="col-md-2">
                <input type="time" class="form-control" id="done_break_1" name="done_break_1"  value="<?= strtolower($data[0]->done_break_1) ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <label for="">Break Time 2</label>
            </div>
            <div class="col-md-2">
                <input type="time" class="form-control" id="start_break_2" name="start_break_2"  value="<?= strtolower($data[0]->start_break_2) ?>">
            </div>
            <div class="col-md-2">
                <input type="time" class="form-control" id="done_break_2" name="done_break_2"  value="<?= strtolower($data[0]->done_break_2) ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <label for="">Break Time 3</label>
            </div>
            <div class="col-md-2">
                <input type="time" class="form-control" id="start_break_3" name="start_break_3"  value="<?= strtolower($data[0]->start_break_3) ?>">
            </div>
            <div class="col-md-2">
                <input type="time" class="form-control" id="done_break_3" name="done_break_3"  value="<?= strtolower($data[0]->done_break_3) ?>">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3"></div>
                <div class="col-md-9">
                <button type="button" class="btn btn-primary" name="save" id="update"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
	</div>
	<!-- /.box-body -->
</div>
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
</style>
<script>
    swal.close();
    $(document).ready(function(){
        $('.chosen-select').chosen();
	});
</script>