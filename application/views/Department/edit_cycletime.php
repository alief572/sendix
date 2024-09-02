<?php
$this->load->view('include/side_menu');
//Product
$ArrProduct = array();
foreach($product AS $val => $valx){
	$d1 = ' '.$valx['value_d'].' mm';
	$d2 = '';
	if(!empty($valx['value_d2'])){
		$d2 = ' x '.$valx['value_d2'].' mm';
	}
	$ArrProduct[$valx['id']] = ucwords(strtolower($valx['parent_product'])).', '.$d1.$d2;
}

//Pressure
$ArrPressure = array();
foreach($pressure AS $val => $valx){
	$ArrPressure[$valx['name']] = "PN ".ucwords(strtolower($valx['name']));
}

//Liner
$ArrLiner = array();
foreach($liner AS $val => $valx){
	$ArrLiner[$valx['name']] = ucwords(strtolower($valx['name']))." mm";
}

?>
<form action="#" method="POST" id="form_ct" enctype="multipart/form-data"> 
<input type="hidden" name="id_time" value="<?=$data[0]->id_time;?>">
<input type="hidden" name="id_product" value="<?=$data[0]->id_product;?>">
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title"><?php echo $title;?></h3>
		<div class="box-tool pull-right">
            
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
        <div class="form-group row">
            <div class="col-md-2">
                <label for="customer">Product Name</label>
            </div>
            <div class="col-md-4">
                <?php
                    echo form_dropdown('id_productx', $ArrProduct, $data[0]->id_product, array('id'=>'id_productx','class'=>'form-control input-md chosen-select','disabled'=>'disabled'));
                ?>
            </div>
        </div>
		<div class="form-group row">
            <div class="col-md-2">
                <label for="customer">Pressure  <span class='text-red'>*</span> | Liner <span class='text-red'>*</span></label>
            </div>
            <div class="col-md-2">
                <?php
                    echo form_dropdown('pressure', $ArrPressure, $data[0]->pressure, array('id'=>'pressure','class'=>'form-control input-md chosen-select'));
                ?>
            </div>
			<div class="col-md-2">
                <?php
                    echo form_dropdown('liner', $ArrLiner, $data[0]->liner, array('id'=>'liner','class'=>'form-control input-md chosen-select'));
                ?>
            </div>
        </div>
        
        <table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
            <thead>
                <tr class='bg-blue'>
                    <th class='text-center' style='width: 4%;'>#</th>
                    <th class='text-center' style='width: 30%;'>Cost Center</th>
                    <th class='text-center'>Machine</th>
                    <th class='text-center'>Mould/Tools</th>
                    <th class='text-center'>Information</th>
                    <th class='text-center' style='width: 4%;'>#</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $id = 0;
            foreach($detail AS $val2 => $val2x){
                $id++;
                echo "<tr class='header_".$id."'>";
                    echo "<td align='center'>".$id."</td>";
                    echo "<td align='left'>";
                    echo "<select name='Detail[".$id."][costcenter]' class='chosen_select form-control input-sm inline-blockd costcenter'>";
                    foreach($costcenter AS $val => $valx){
                        $sel = ($valx['id_costcenter'] == $val2x['costcenter'])?'selected':'';
                        echo "<option value='".$valx['id_costcenter']."' ".$sel.">".strtoupper($valx['nm_costcenter'])."</option>";
                    }
                    echo 		"</select>";
                    echo "</td>";
                    echo "<td align='left'>";
                    echo "<select name='Detail[".$id."][machine]' class='chosen_select form-control input-sm inline-blockd'>";
                    echo "<option value='0'>NONE MACHINE</option>";
                    foreach($machine AS $val => $valx){
                        $sel = ($valx['id_mesin'] == $val2x['machine'])?'selected':'';
                        echo "<option value='".$valx['id_mesin']."' ".$sel.">".strtoupper($valx['nm_mesin'])."</option>";
                    }
                    echo "</select>";
                    echo "</td>";
                    echo "<td align='left'>";
                    echo "<select name='Detail[".$id."][mould]' class='chosen_select form-control input-sm inline-blockd'>";
                    echo "<option value='0'>NONE MOULD/TOOLS</option>";
                    // foreach($mould AS $val => $valx){
                    //   $sel = ($valx['kd_asset'] == $val2x['mould'])?'selected':'';
                    // echo "<option value='".$valx['kd_asset']."' ".$sel.">".strtoupper($valx['nm_asset'])."</option>";
                    // }

                    echo "</select>";
                    echo "</td>";
                    echo "<td align='left'></td>";
                    echo "<td align='center'>";
                    echo "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
                    echo "</td>";
                echo "</tr>";
                   
                $q_dheader_test = $this->db->query("SELECT * FROM cycletime_detail_detail WHERE id_costcenter='".$val2x['id_costcenter']."'")->result_array();
                $no = 0;
                foreach($q_dheader_test AS $val2D => $val2Dx){ $no++;
                    echo "<tr class='header_".$id."'>";
                        echo "<td align='center'></td>";
                        echo "<td align='left' style='vertical-align:middle; padding-left: 30px;'>";
                        echo "<select name='Detail[".$id."][detail][".$no."][id_process]' class='chosen_select form-control input-sm inline-blockd process'>";
                        foreach($process AS $val => $valx){
                            $sel = ($valx['code_process'] == $val2Dx['id_process'])?'selected':'';
                            echo "<option value='".$valx['code_process']."' ".$sel.">".strtoupper($valx['nm_process'])."</option>";
                        }
                        echo "</select>";
                        echo "</td>";
                        echo "<td align='left'>";
                        echo "<input type='text' name='Detail[".$id."][detail][".$no."][cycletime]' value='".$val2Dx['cycletime']."' class='form-control input-md maskM' placeholder='Cycletime (Minutes)'  data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
                        echo "</td>";
                        echo "<td align='left'>";
                        echo "<input type='text' name='Detail[".$id."][detail][".$no."][qty_mp]' value='".$val2Dx['qty_mp']."' class='form-control input-md maskM' placeholder='Qty Man Power'  data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
                         echo "</td>";
                        echo "<td align='left'>";
                        echo "<input type='text' name='Detail[".$id."][detail][".$no."][note]' value='".$val2Dx['note']."' class='form-control input-md' placeholder='Information'  data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
                        echo "</td>";
                        echo "<td align='center'>";
                        echo "&nbsp;<button type='button' class='btn btn-sm btn-danger delSubPart' title='Delete Part'><i class='fa fa-close'></i></button>";
                        echo "</td>";
                    echo "</tr>";
                }
                echo "<tr id='add_".$id."_".$no."' class='header_".$id."'>";
                  echo "<td align='center'></td>";
                  echo "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-primary addSubPart' title='Add Process'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Process</button></td>";
                  echo "<td align='center'></td>";
                  echo "<td align='center'></td>";
                  echo "<td align='center'></td>";
                  echo "<td align='center'></td>";
                echo "</tr>";
            }
            ?>
             <tr id='add_<?=$id;?>'>
               <td align='center'></td>
               <td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPart' title='Add Costcenter'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Costcenter</button></td>
               <td align='center'></td>
               <td align='center'></td>
               <td align='center'></td>
               <td align='center'></td>
             </tr>
           </tbody>
        </table>
        <div class='box-footer'>
        <?php
            echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','style'=>'float:right; margin-left:5px;','value'=>'back','content'=>'Back','onClick'=>'javascript:back()'));
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
	$(document).on('click', '.addPart', function(){
		loading_spinner();
		var get_id 		= $(this).parent().parent().attr('id');
		// console.log(get_id);
		var split_id	= get_id.split('_');
		var id 		= parseInt(split_id[1])+1;
		var id_bef 	= split_id[1];

		$.ajax({
			url: base_url+'index.php/'+active_controller+'/get_add/'+id,
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$("#add_"+id_bef).before(data.header);
				$("#add_"+id_bef).remove();
				$('.chosen_select').chosen({width: '100%'});
				$('.maskM').maskMoney();
				swal.close();
			},
			error: function() {
				swal({
					title				: "Error Message !",
					text				: 'Connection Time Out. Please try again..',
					type				: "warning",
					timer				: 3000,
					showCancelButton	: false,
					showConfirmButton	: false,
					allowOutsideClick	: false
				});
			}
		});
	});
	
	//add part
	$(document).on('click', '.addSubPart', function(){
		loading_spinner();
		var get_id 		= $(this).parent().parent().attr('id');
		// console.log(get_id);
		var split_id	= get_id.split('_');
		var id 			= split_id[1];
		var id2 		= parseInt(split_id[2])+1;
		var id_bef 		= split_id[2];

		$.ajax({
			url: base_url+'index.php/'+active_controller+'/get_add_sub/'+id+'/'+id2,
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$("#add_"+id+"_"+id_bef).before(data.header);
				$("#add_"+id+"_"+id_bef).remove();
				$('.chosen_select').chosen({width: '100%'});
				$('.maskM').maskMoney();
				swal.close();
			},
			error: function() {
				swal({
					title				: "Error Message !",
					text				: 'Connection Time Out. Please try again..',
					type				: "warning",
					timer				: 3000,
					showCancelButton	: false,
					showConfirmButton	: false,
					allowOutsideClick	: false
				});
			}
		});
	});
	
	//delete part
	$(document).on('click', '.delPart', function(){
		var get_id 		= $(this).parent().parent().attr('class');
		$("."+get_id).remove();
	});

	$(document).on('click', '.delSubPart', function(){
		var get_id 		= $(this).parent().parent('tr').html();
		$(this).parent().parent('tr').remove();
	});

	//SAVE
	$(document).on('click', '#save', function(){
		var produk		= $('#id_product').val();
		var costcenter	= $('.costcenter').val();
		var process		= $('.process').val();
		
		if(produk == '0' ){
			swal({
				title	: "Error Message!",
				text	: 'Product name empty, select first ...',
				type	: "warning"
			});

			$('#save').prop('disabled',false);
			return false;
		}
		if(costcenter == '0' ){
			swal({
				title	: "Error Message!",
				text	: 'Costcenter empty, select first ...',
				type	: "warning"
			});

			$('#save').prop('disabled',false);
			return false;
		}
		if(process == '0' ){
			swal({
				title	: "Error Message!",
				text	: 'Process name empty, select first ...',
				type	: "warning"
			});

			$('#save').prop('disabled',false);
			return false;
		}

		swal({
		  title: "Are you sure?",
		  text: "Delete this data ?",
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
				var baseurl		= base_url + active_controller +'/edit_cycletime';
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
							window.location.href = base_url + active_controller;
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
