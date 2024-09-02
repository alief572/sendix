<?php
$this->load->view('include/side_menu');
?>
<form action="#" method="POST" id="form_ct" enctype="multipart/form-data"> 
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title"><?php echo $title;?></h3>
		<div class="box-tool pull-right">
            
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
    <?php
    echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success','style'=>'float:right; margin-bottom:5px;','value'=>'back','content'=>'Add Shift','id'=>'add-payment'));
    ?>
    <table class='table table-bordered table-striped'>
        <thead>
        <tr class='bg-blue'>
            <td align='center' width='17%'><b>Shift Name</b></td>	
            <td align='center' width='17%'><b>Day</b></td>							
            <td align='center' width='12%'><b>Start Work</b></td>
            <td align='center' width='12%'><b>Break I</b></td>
            <td align='center' width='12%'><b>Break II</b></td>
            <td align='center' width='12%'><b>Break II</b></td>
            <td align='center' width='12%'><b>Finish Work</b></td>
            <td align='center' width='6%'><b>#</b></td>
        </tr>
        </thead>
        <tbody id='list_payment'></tbody>
            <tbody id='list_empty'>
            <tr>
            <td colspan='8'>Empty List</td>
            </tr>
        </tbody>
    </table>
    <div class='box-footer'>
    <?php
        echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','style'=>'float:right; margin-left:5px;','id'=>'back','content'=>'Back'));
        echo form_button(array('type'=>'button','class'=>'btn btn-md btn-primary','style'=>'float:right;','value'=>'save','content'=>'Save','id'=>'save')).' ';
        
     ?>
    </div>
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
</style>
<script>
	
	$('#back').click(function(){
		window.location.href = base_url + active_controller+'/shift';
	});

	$(document).on('click','.delete', function(){
		$(this).parent().parent().remove(); 
	});

	$('#add-payment').click(function(){
        var jumlah	=$('#list_payment').find('tr').length;
        if(jumlah==0 || jumlah==null){
            var ada		= 0;
            var loop	= 1;
        }
        else{
            var nilai		= $('#list_payment tr:last').attr('id');
            var jum1		= nilai.split('_');
            var loop		= parseInt(jum1[1])+1;
        }

        var emp = $("#emp").val();
        $("#emp").val(parseInt(emp)+1);
        $("#list_empty").hide();

        Template	='<tr id="tr_'+loop+'">';
        Template	+='<td align="left">';
        Template	+='<select name="detail['+loop+'][day]" class="form-control chosen-select">';
        Template	+='<option value="0">Select Shift</option>';
        Template	+='<option value="sunday">Sunday</option>';
        Template	+='<option value="monday">Monday</option>';
        Template	+='<option value="tuesday">Tuesday</option>';
        Template	+='<option value="wednesday">Wednesday</option>';
        Template	+='<option value="thursday">Thursday</option>';
        Template	+='<option value="friday">Friday</option>';
        Template	+='<option value="saturday">Saturday</option>';
        Template	+='</select>';
        Template	+='</td>';
        Template	+='<td align="left">';
        Template	+='<select name="detail['+loop+'][id_type]" class="form-control chosen-select">';
        Template	+='<option value="0">Select Day</option>';
        Template	+='<?php foreach ($shift as $val => $valx){ ?>';
        Template	+='<option value="<?= $valx['id'];?>"><?= strtoupper(strtolower($valx['name']))?></option>';
        Template	+='<?php } ?>';
        Template	+='</select>';
        Template	+='</td>';
        Template	+='<td align="left">';
        Template	+='<input type="time" class="form-control input-sm" name="detail['+loop+'][start_work]">';
        Template	+='</td>';
        Template	+='<td align="left">';
        Template	+='<input type="time" class="form-control input-sm" name="detail['+loop+'][start_break_1]">';
        Template	+='<input type="time" class="form-control input-sm" name="detail['+loop+'][done_break_1]">';
        Template	+='</td>';	
        Template	+='<td align="left">';
        Template	+='<input type="time" class="form-control input-sm" name="detail['+loop+'][start_break_2]">';
        Template	+='<input type="time" class="form-control input-sm" name="detail['+loop+'][done_break_2]">';
        Template	+='</td>';	
        Template	+='<td align="left">';
        Template	+='<input type="time" class="form-control input-sm" name="detail['+loop+'][start_break_3]">';
        Template	+='<input type="time" class="form-control input-sm" name="detail['+loop+'][done_break_3]">';
        Template	+='</td>';	
        Template	+='<td align="left">';
        Template	+='<input type="time" class="form-control input-sm" name="detail['+loop+'][done_work]">';
        Template	+='</td>';	
        Template	+='<td align="center"><button type="button" class="btn btn-sm btn-danger delete" title="Hapus Data" data-role="qtip"><i class="fa fa-trash-o"></i></button></td>';
        Template	+='</tr>';

        $('#list_payment').append(Template);
        $('.chosen-select').chosen();
        $('.maskM').maskMoney();
    });

	$(document).on('click', '#save', function(){
		
		swal({
		  title: "Are you sure?",
		  text: "Save this data ?",
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
				var formData 	= new FormData($('#form_ct')[0]);
				var baseurl		= base_url + active_controller +'/add_shift';
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
								  timer	: 7000,
								  showCancelButton	: false,
								  showConfirmButton	: false,
								  allowOutsideClick	: false
								});
							window.location.href = base_url + active_controller+'/shift';
						}
						else if(data.status == 0){
							swal({
							  title	: "Save Failed!",
							  text	: data.pesan,
							  type	: "warning",
							  timer	: 7000,
							  showCancelButton	: false,
							  showConfirmButton	: false,
							  allowOutsideClick	: false
							});
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
					}
				});
			} else {
			swal("Cancelled", "Data can be process again :)", "error");
			return false;
			}
		});
	});

</script>
