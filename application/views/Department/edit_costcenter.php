<?php
$ArrProduct = array();
foreach($department AS $val => $valx){
	$ArrProduct[$valx['id']] = ucwords(strtolower($valx['nm_dept']));
}
?>
<div class="box box-primary">
	<!-- /.box-header -->
	<div class="box-body">
    <br>
		<input type="hidden" name="id_costcenter" id="id_costcenter" value='<?= $data[0]->id_costcenter; ?>'>
        <div class="form-group row">
            <div class="col-md-3">
                <label for="">Department</label>
            </div>
                <div class="col-md-9">
                <?php
                    echo form_dropdown('id_dept', $ArrProduct, $data[0]->id_dept, array('id'=>'id_dept','class'=>'form-control input-md chosen-select'));
                ?>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <label for="">Costcenter Name</label>
            </div>
                <div class="col-md-9">
                <input type="text" class="form-control" id="nm_costcenter" name="nm_costcenter"  placeholder="Costcenter Name" value="<?= strtolower($data[0]->nm_costcenter) ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <label for="">Qty MP Shift 1</label>
            </div>
                <div class="col-md-9">
                <input type="text" class="form-control qty" id="mp_1" name="mp_1"  placeholder="Qty MP Shift 1" value="<?= strtolower($data[0]->mp_1) ?>" data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='false'>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <label for="">Qty MP Shift 1</label>
            </div>
                <div class="col-md-9">
                <input type="text" class="form-control qty" id="mp_2" name="mp_2"  placeholder="Qty MP Shift 2" value="<?= strtolower($data[0]->mp_2) ?>" data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='false'>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <label for="">Qty MP Shift 1</label>
            </div>
                <div class="col-md-9">
                <input type="text" class="form-control qty" id="mp_3" name="mp_3"  placeholder="Qty MP Shift 3" value="<?= strtolower($data[0]->mp_3) ?>" data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='false'>
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
        $('.qty').maskMoney();
	});
</script>