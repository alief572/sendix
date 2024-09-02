<?php
$this->load->view('include/side_menu');
?>
<form action="#" method="POST" id="form_ct" enctype="multipart/form-data" autocomplete='off'> 
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title"><?php echo $title;?></h3>
		<div class="box-tool pull-right">
            
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
    <?php
    echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success','style'=>'float:right; margin-bottom:5px;','value'=>'back','content'=>'Add Costcenter','id'=>'add-payment'));
    ?>
    <table class='table table-bordered table-striped'>
        <thead>
        <tr class='bg-blue'>
			<td align='center' width='20%'><b>Department</b></td>
            <td align='center'><b>Costcenter Name</b></td>
            <td align='center' width='10%'><b>Shift 1</b></td>
            <td align='center' width='10%'><b>Shift 2</b></td>
            <td align='center' width='10%'><b>Shift 3</b></td>
            <td align='center' width='5%'><b>#</b></td>
        </tr>
        </thead>
        <tbody id='list_payment'></tbody>
            <tbody id='list_empty'>
            <tr>
            <td colspan='5'>Empty List</td>
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
		window.location.href = base_url + active_controller+'/costcenter';
	});

	$(document).on('click','.del', function(){
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
		Template	+= '<td align="left">';
		Template	+= '<select name="detail['+loop+'][id_dept]" class="chosen-select form-control input-sm inline-blockd dept">';
		Template	+= '<option value="0">Select Department</option>';
		<?php
		foreach($department AS $val => $valx){
			?>
			Template	+= '<option value="<?=$valx['id'];?>"><?=$valx['nm_dept'];?></option>';
			<?php
		}
		?>
		Template	+= '</select>';
		Template	+= '</td>';
		Template	+='<td align="left">';
		Template	+='<input type="text" class="form-control input-md costcenter" name="detail['+loop+'][nm_costcenter]" id="data1_'+loop+'_costcenter" placeholder="Costcenter Name">';
		Template	+='</td>';
		Template	+='<td align="center">';
		Template	+='<input type="text" class="form-control input-md text-center maskM" name="detail['+loop+'][mp_1]" id="data1_'+loop+'_mp_1" placeholder="Qty MP Shift 1" data-decimal="." data-thousand="" data-precision="0" data-allow-zero="">';
		Template	+='</td>';
		Template	+='<td align="center">';
		Template	+='<input type="text" class="form-control input-md text-center maskM" name="detail['+loop+'][mp_2]" id="data1_'+loop+'_mp_2" placeholder="Qty MPr Shift 2" data-decimal="." data-thousand="" data-precision="0" data-allow-zero="">';
		Template	+='</td>';
		Template	+='<td align="center">';
		Template	+='<input type="text" class="form-control input-md text-center maskM" name="detail['+loop+'][mp_3]" id="data1_'+loop+'_mp_3" placeholder="Qty MP Shift 3" data-decimal="." data-thousand="" data-precision="0" data-allow-zero="">';
		Template	+='</td>';
		Template	+='<td align="center"><button type="button" class="btn btn-sm btn-danger del" title="Hapus Data" data-role="qtip"><i class="fa fa-trash-o"></i></button></td>';
		Template	+='</tr>';

      $('#list_payment').append(Template);
      $('.chosen-select').chosen();
      $('.maskM').maskMoney();
    });

	$(document).on('click', '#save', function(){
		var dept		= $('.dept').val();
		var costcenter	= $('.costcenter').val();
		
		if(dept == '0' ){
			swal({
				title	: "Error Message!",
				text	: 'Department name empty, select first ...',
				type	: "warning"
			});

			$('#save').prop('disabled',false);
			return false;
		}
		if(costcenter == '' ){
			swal({
				title	: "Error Message!",
				text	: 'Costcenter empty, select first ...',
				type	: "warning"
			});

			$('#save').prop('disabled',false);
			return false;
		}
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
				var baseurl		= base_url + active_controller +'/add_costcenter';
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
							window.location.href = base_url + active_controller+'/costcenter';
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
