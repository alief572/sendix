<?php
$this->load->view('include/side_menu');
//echo"<pre>";print_r($data_menu);

$ArrSatuan = array();
foreach($vehicle_tool AS $val => $valx){
	$ArrSatuan[$valx['code_group']] = strtoupper($valx['category'])." - ".strtoupper($valx['spec']);
}
$ArrSatuan[0] = 'Select Category';

$ArrCurrency = array();
foreach($currency AS $val => $valx){
	$ArrCurrency[$valx['kode']] = strtoupper($valx['mata_uang'])." - ".strtoupper($valx['negara'])." (".strtoupper($valx['kode']).")";
}


?>
<form action="#" method="POST" id="form_man_power" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?php echo $title;?></h3>
		</div>
		<?php
			$code_group      = (!empty($header[0]->code_group))?$header[0]->code_group:'0';
			$tanda 			     = (!empty($header[0]->id))?'edit':'';
      $unit_material 	 = (!empty($header[0]->unit_material))?$header[0]->unit_material:'';
      $kurs 	         = (!empty($header[0]->kurs))?$header[0]->kurs:'IDR';
			$brand 	         = (!empty($header[0]->brand))?strtoupper($header[0]->brand):'';
			$spec 	         = (!empty($header[0]->spec))?strtoupper($header[0]->spec):'';
			$category 	     = (!empty($header[0]->cty ))?strtoupper($header[0]->cty):'';
			// $material_name 	 = (!empty($header[0]->material_name))?strtoupper($header[0]->material_name):'';
			$code_groupx     = $category." - ".$spec;
			$kode						 = $this->uri->segment(3);

      $ArrUnit = array();
      foreach($unit AS $val => $valx){
      	$ArrUnit[$valx['category_list']] = strtoupper($valx['category_list']);
        $ArrUnit[$unit_material] = strtoupper($unit_material);
      }

			echo form_input(array('type'=>'hidden','name'=>'tanda_edit','class'=>'form-control input-md'),$tanda);
			echo form_input(array('type'=>'hidden','name'=>'id','id'=>'id','class'=>'form-control input-md'),$kode);
		?>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Heavy Equipment <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
          <?php
					if(empty($kode)){
            echo form_dropdown('code_group', $ArrSatuan, '0', array('id'=>'code_group','class'=>'form-control input-md clSelect'));
					}
					else{
						echo form_input(array('type'=>'hidden','id'=>'code_group','name'=>'code_group','class'=>'form-control input-md','readonly'=>'readonly'),$code_group);
						echo form_input(array('id'=>'code_groupx','name'=>'code_groupx','class'=>'form-control input-md','readonly'=>'readonly'),$code_groupx);
					}
          ?>
				</div>
        <label class='label-control col-sm-2'><b>Spesification/Sertification</b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_input(array('id'=>'spec','name'=>'spec','class'=>'form-control input-md','readonly'=>'readonly','placeholder'=>'Spesification'),$spec);
					?>
				</div>
			</div>

			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Unit <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					if(empty($kode)){
            echo form_dropdown('unit_material', '', '0', array('id'=>'unit_material','class'=>'form-control input-md clSelect'));
					}
					else{
						echo form_dropdown('unit_material', $ArrUnit, $unit_material, array('id'=>'unit_material','class'=>'form-control input-md clSelect'));
					}
					?>
				</div>
				<label class='label-control col-sm-2'><b>Kurs <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
          <?php
            echo form_dropdown('kurs', $ArrCurrency, $kurs, array('id'=>'kurs','class'=>'form-control input-md clSelect'));
          ?>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Price Reference <span class='text-red'>*</span></b></label>
			</div>
			<?php
			$nomor = 0;
			foreach($region AS $valR => $valxR){
				$rate = (!empty($valxR['rate']))?number_format($valxR['rate']):'';
				$id = (!empty($valxR['id']))?$valxR['id']:'';
				$nomor++;
				?>
				<div class='form-group row'>
					<label class='label-control col-sm-2'></label>
					<label class='label-control col-sm-2'><?= ucwords(strtolower($valxR['region']));?></label>
					<div class='col-sm-2'>
						<?php
							echo form_input(array('type'=>'hidden','id'=>$valxR['region_code'],'name'=>'DetailData['.$nomor.'][region]','class'=>'form-control input-md'),$valxR['region']);
							echo form_input(array('id'=>'rate_'.$valxR['region_code'],'name'=>'DetailData['.$nomor.'][rate]','class'=>'form-control input-md maskM','placeholder'=>'Rate '.ucwords(strtolower($valxR['region'])),'data-decimal'=>'.','data-thousand'=>'','data-precision'=>'0','data-allow-zero'=>''),$rate);
							echo form_input(array('type'=>'hidden','name'=>'DetailData['.$nomor.'][id]','class'=>'form-control input-md'),$id);

						?>
					</div>
				</div>
			<?php } ?>
		</div>
		<div class='box-footer'>
			<?php
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-primary','value'=>'save','content'=>'Save','id'=>'save_price_ref')).' ';
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','value'=>'back','content'=>'Back','id'=>'back'));
			?>
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
	#category_chosen{
		width: 100% !important;
	}
	.add_plus{
		cursor : pointer;
		color: white;
		background-color: #605ca8 !important;
	}
	.maskM{
		text-align:right;
	}
	#save_category {
	  color: white;
	  background-color: #605ca8;
	}
	#order_point_date {
	  cursor: pointer;
	}
</style>
<script>
	$(document).ready(function(){
		$('.maskM').maskMoney();

    $(document).on('change', "#code_group", function(){
			loading_spinner();
			var code_group = $(this).val();
			$.ajax({
				url: base_url+active_controller+'/get_detail/'+code_group,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data){
					$("#spec").val(data.spec);
          $("#brand").val(data.brand);
          $("#unit_material").html(data.option).trigger("chosen:updated");
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
			// AppendBarisBqDet(nomor);
		});

		$('#save_price_ref').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var code_group	= $('#code_group').val();
			var unit_material		= $('#unit_material').val();
      var rate		= $('#rate').val();

			if(code_group=='0'){
				swal({
				  title	: "Error Message!",
				  text	: 'Consumable not selected, please select first ...',
				  type	: "warning"
				});
				$('#save_price_ref').prop('disabled',false);
				return false;
			}
			if(unit_material=='0'){
				swal({
				  title	: "Error Message!",
				  text	: 'Unit not selected, please select first ...',
				  type	: "warning"
				});
				$('#save_price_ref').prop('disabled',false);
				return false;
			}
      if(rate=='0' || rate==''){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Price Reference, please input first ...',
				  type	: "warning"
				});
				$('#save_price_ref').prop('disabled',false);
				return false;
			}
			swal({
				  title: "Are you sure?",
				  text: "You will not be able to process again this data!",
				  type: "warning",
				  showCancelButton: true,
				  confirmButtonClass: "btn-danger",
				  confirmButtonText: "Yes, Process it!",
				  cancelButtonText: "No, cancel process!",
				  closeOnConfirm: false,
				  closeOnCancel: false
				},
				function(isConfirm) {
				  if (isConfirm) {
						loading_spinner();
						var formData 	= new FormData($('#form_man_power')[0]);
						var baseurl		= base_url + active_controller +'/add_he';
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
										  timer	: 7000
										});
									window.location.href = base_url + active_controller+'/index_he';
								}
								else if(data.status == 2 || data.status == 3){
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 7000
									});
								}

								$('#save_price_ref').prop('disabled',false);
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
								$('#save_price_ref').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#save_price_ref').prop('disabled',false);
					return false;
				  }
			});
		});

		$('#back').click(function(e){
			window.location.href = base_url + active_controller+'/index_he';
		});

	});
</script>
