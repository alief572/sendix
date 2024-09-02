<?php
$this->load->view('include/side_menu');

//Customer
$ArrCust = array();
foreach($cust AS $val => $valx){
	$ArrCust[$valx['id_customer']] = strtoupper($valx['nm_customer']);
}
$ArrCust[0]	= 'Select An Customer';
//app
$ArrApp = array();
foreach($app AS $val => $valx){
	$ArrApp[$valx['category_list']] = strtoupper($valx['view_']);
}
//req
$ArrReq = array();
foreach($req AS $val => $valx){
	$ArrReq[$valx['category_list']] = strtoupper($valx['view_']);
}
// echo Enkripsi('IPPS20001L');
?>
<form action="#" method="POST" id="form_work" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?php echo $title;?></h3>
			<?php
				$id_customer 	= (!empty($header[0]->id_customer))?$header[0]->id_customer:'0';
				$project 			= (!empty($header[0]->project))?strtoupper($header[0]->project):'';
				$ref_cust 		= (!empty($header[0]->ref_cust))?strtoupper($header[0]->ref_cust):'';
				$validity 		= (!empty($header[0]->validity))?strtoupper($header[0]->validity):'';
				$location 		= (!empty($header[0]->location))?strtoupper($header[0]->location):'';
				$app 					= (!empty($header[0]->app))?$header[0]->app:'above ground';


				echo form_input(array('type'=>'hidden','name'=>'no_ipp','class'=>'form-control input-md'),$this->uri->segment(3));
			?>
		</div>
		<!-- /.box-header -->
		<div class="box-body">

		<div class="box box-success">
			<div class="box-header">
				<h3 class="box-title">Header</h3>
			</div>
			<div class="box-body">
				<div class='form-group row'>
						<label class='label-control col-sm-2'><b>Customer Name <span class='text-red'>*</span></b></label>
						<div class='col-sm-4'>
							<?php
							 echo form_dropdown('id_customer', $ArrCust, $id_customer, array('id'=>'id_customer','name'=>'id_customer','class'=>'form-control input-md clSelect'));
							?>
						</div>
						<label class='label-control col-sm-2'><b>Validity & Guarantee <span class='text-red'>*</span></b></label>
						<div class='col-sm-4'>
							<?php
							 echo form_input(array('id'=>'validity','name'=>'validity','class'=>'form-control input-md','placeholder'=>'Validity & Guarantee'),$validity);
							?>
						</div>
					</div>
					<div class='form-group row'>
						<label class='label-control col-sm-2'><b>Project Name <span class='text-red'>*</span></b></label>
						<div class='col-sm-4'>
							<?php
							 echo form_textarea(array('id'=>'project','name'=>'project','class'=>'form-control input-md','rows'=>'2','cols'=>'75','placeholder'=>'Project'),$project);
							?>
						</div>
						<label class='label-control col-sm-2'><b>Referensi Customer/Project <span class='text-red'>*</span></b></label>
						<div class='col-sm-4'>
							<?php
							 echo form_textarea(array('id'=>'ref_cust','name'=>'ref_cust','class'=>'form-control input-md','rows'=>'2','cols'=>'75','placeholder'=>'Ref Customer/Project'),$ref_cust);
							?>
						</div>
					</div>
					<div class='form-group row'>
						<label class='label-control col-sm-2'><b>Location <span class='text-red'>*</span></b></label>
						<div class='col-sm-4'>
							<?php
							 echo form_textarea(array('id'=>'location','name'=>'location','class'=>'form-control input-md','rows'=>'2','cols'=>'75','placeholder'=>'Location'),$location);
							?>
						</div>
					</div>
			</div>
		</div>

		<div class="box box-info">
			<div class="box-header">
				<h3 class="box-title">Spesification</h3>
			</div>
			<div class="box-body">
					<div class='form-group row'>
						<label class='label-control col-sm-2'><b>Application <span class='text-red'>*</span></b></label>
						<div class='col-sm-4'>
							<?php
							 echo form_dropdown('app', $ArrApp, $app, array('id'=>'app','name'=>'app','class'=>'form-control input-md clSelect'));
							?>
						</div>
					</div>

					<?php
					// product
					$nomor=0;
					foreach($product AS $val => $valx){
						$nomor++;
						?>
						<div class='form-group row'>
							<label class='label-control col-sm-2'><?= ($val == 0)?ucwords($valx['category_'])." <span class='text-red'>*</span>":'';?></b></label>
							<label class='label-control col-sm-2'><b><?= ucwords($valx['category_list']);?></b></label>
							<div class='col-sm-2'>
								<?php
									$selReq = (!empty($valx['req']))?$valx['req']:'customer';
									$selHide = ($selReq == 'customer')?'specHide':'';
									$selSpec = (!empty($valx['spec']))?$valx['spec']:'';
								 	echo form_dropdown('Detail['.$nomor.'][req]', $ArrReq, $selReq, array('class'=>'form-control input-md clSelect','data-num'=>$nomor));
								 	echo form_input(array('type'=>'hidden','name'=>'Detail['.$nomor.'][category]','class'=>'form-control input-md'),$valx['category']);
								 	echo form_input(array('type'=>'hidden','name'=>'Detail['.$nomor.'][category_list]','class'=>'form-control input-md'),$valx['category_list']);
								?>
							</div>
							<div class='col-sm-4'>
								<?php
								 echo form_input(array('type'=>'text','name'=>'Detail['.$nomor.'][spec]','id'=>'spec_'.$nomor,'class'=>'form-control input-md '.$selHide,'placeholder'=>'Capacity/Specificity'),$selSpec);
								?>
							</div>
						</div>
					<?php
					}
					//end product
					?>

					<?php
					// material
					foreach($material AS $val => $valx){
						$nomor++;
						?>
						<div class='form-group row'>
							<label class='label-control col-sm-2'><?= ($val == 0)?ucwords($valx['category_'])." <span class='text-red'>*</span>":'';?></b></label>
							<label class='label-control col-sm-2'><b><?= ucwords($valx['category_list']);?></b></label>
							<div class='col-sm-2'>
								<?php
									$selReq = (!empty($valx['req']))?$valx['req']:'customer';
									$selHide = ($selReq == 'customer')?'specHide':'';
									$selSpec = (!empty($valx['spec']))?$valx['spec']:'';
								 	echo form_dropdown('Detail['.$nomor.'][req]', $ArrReq, $selReq, array('class'=>'form-control input-md clSelect','data-num'=>$nomor));
								 	echo form_input(array('type'=>'hidden','name'=>'Detail['.$nomor.'][category]','class'=>'form-control input-md'),$valx['category']);
								 	echo form_input(array('type'=>'hidden','name'=>'Detail['.$nomor.'][category_list]','class'=>'form-control input-md'),$valx['category_list']);
								?>
							</div>
							<div class='col-sm-4'>
								<?php
								 echo form_input(array('type'=>'text','name'=>'Detail['.$nomor.'][spec]','id'=>'spec_'.$nomor,'class'=>'form-control input-md '.$selHide,'placeholder'=>'Capacity/Specificity'),$selSpec);
								?>
							</div>
						</div>
					<?php
					}
					echo "<div id='material'></div>";
					echo "<div class='form-group row'>";
						echo "<div class='col-sm-2'></div>";
						echo "<div class='col-sm-2'>";
							echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success addRow','data-category'=>'material','data-unik'=>'0','value'=>'Add','content'=>'Add'));
						echo "</div>";
					echo "</div>";
					//end material
					?>

					<?php
					// fasilitas
					foreach($fasilitas AS $val => $valx){
						$nomor++;
						?>
						<div class='form-group row'>
							<label class='label-control col-sm-2'><?= ($val == 0)?ucwords($valx['category_'])." <span class='text-red'>*</span>":'';?></b></label>
							<label class='label-control col-sm-2'><b><?= ucwords($valx['category_list']);?></b></label>
							<div class='col-sm-2'>
								<?php
									$selReq = (!empty($valx['req']))?$valx['req']:'customer';
									$selHide = ($selReq == 'customer')?'specHide':'';
									$selSpec = (!empty($valx['spec']))?$valx['spec']:'';
								 	echo form_dropdown('Detail['.$nomor.'][req]', $ArrReq, $selReq, array('class'=>'form-control input-md clSelect','data-num'=>$nomor));
								 	echo form_input(array('type'=>'hidden','name'=>'Detail['.$nomor.'][category]','class'=>'form-control input-md'),$valx['category']);
								 	echo form_input(array('type'=>'hidden','name'=>'Detail['.$nomor.'][category_list]','class'=>'form-control input-md'),$valx['category_list']);
								?>
							</div>
							<div class='col-sm-4'>
								<?php
								 echo form_input(array('type'=>'text','name'=>'Detail['.$nomor.'][spec]','id'=>'spec_'.$nomor,'class'=>'form-control input-md '.$selHide,'placeholder'=>'Capacity/Specificity'),$selSpec);
								?>
							</div>
						</div>
					<?php
					}
					echo "<div id='fasilitas'></div>";
					echo "<div class='form-group row'>";
						echo "<div class='col-sm-2'></div>";
						echo "<div class='col-sm-2'>";
							echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success addRow','data-category'=>'fasilitas','data-unik'=>'1','value'=>'Add','content'=>'Add'));
						echo "</div>";
					echo "</div>";
					//end fasilitas
					?>

					<?php
					// akomodasi
					foreach($akomodasi AS $val => $valx){
						$nomor++;
						?>
						<div class='form-group row'>
							<label class='label-control col-sm-2'><?= ($val == 0)?ucwords($valx['category_'])." <span class='text-red'>*</span>":'';?></b></label>
							<label class='label-control col-sm-2'><b><?= ucwords($valx['category_list']);?></b></label>
							<div class='col-sm-2'>
								<?php
									$selReq = (!empty($valx['req']))?$valx['req']:'customer';
									$selHide = ($selReq == 'customer')?'specHide':'';
									$selSpec = (!empty($valx['spec']))?$valx['spec']:'';
								 	echo form_dropdown('Detail['.$nomor.'][req]', $ArrReq, $selReq, array('class'=>'form-control input-md clSelect','data-num'=>$nomor));
								 	echo form_input(array('type'=>'hidden','name'=>'Detail['.$nomor.'][category]','class'=>'form-control input-md'),$valx['category']);
								 	echo form_input(array('type'=>'hidden','name'=>'Detail['.$nomor.'][category_list]','class'=>'form-control input-md'),$valx['category_list']);
								?>
							</div>
							<div class='col-sm-4'>
								<?php
								 echo form_input(array('type'=>'text','name'=>'Detail['.$nomor.'][spec]','id'=>'spec_'.$nomor,'class'=>'form-control input-md '.$selHide,'placeholder'=>'Capacity/Specificity'),$selSpec);
								?>
							</div>
						</div>
					<?php
					}
					echo "<div id='akomodasi'></div>";
					echo "<div class='form-group row'>";
						echo "<div class='col-sm-2'></div>";
						echo "<div class='col-sm-2'>";
							echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success addRow','data-category'=>'akomodasi','data-unik'=>'2','value'=>'Add','content'=>'Add'));
						echo "</div>";
					echo "</div>";
					//end akomodasi
					?>

					<?php
					// pekerja
					foreach($pekerja AS $val => $valx){
						$nomor++;
						?>
						<div class='form-group row'>
							<label class='label-control col-sm-2'><?= ($val == 0)?ucwords($valx['category_'])." <span class='text-red'>*</span>":'';?></b></label>
							<label class='label-control col-sm-2'><b><?= ucwords($valx['category_list']);?></b></label>
							<div class='col-sm-2'>
								<?php
									$selReq = (!empty($valx['req']))?$valx['req']:'customer';
									$selHide = ($selReq == 'customer')?'specHide':'';
									$selSpec = (!empty($valx['spec']))?$valx['spec']:'';
								 	echo form_dropdown('Detail['.$nomor.'][req]', $ArrReq, $selReq, array('class'=>'form-control input-md clSelect','data-num'=>$nomor));
								 	echo form_input(array('type'=>'hidden','name'=>'Detail['.$nomor.'][category]','class'=>'form-control input-md'),$valx['category']);
								 	echo form_input(array('type'=>'hidden','name'=>'Detail['.$nomor.'][category_list]','class'=>'form-control input-md'),$valx['category_list']);
								?>
							</div>
							<div class='col-sm-4'>
								<?php
								 echo form_input(array('type'=>'text','name'=>'Detail['.$nomor.'][spec]','id'=>'spec_'.$nomor,'class'=>'form-control input-md '.$selHide,'placeholder'=>'Capacity/Specificity'),$selSpec);
								?>
							</div>
						</div>
					<?php
					}
					echo "<div id='pekerja'></div>";
					echo "<div class='form-group row'>";
						echo "<div class='col-sm-2'></div>";
						echo "<div class='col-sm-2'>";
							echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success addRow','data-category'=>'pekerja','data-unik'=>'3','value'=>'Add','content'=>'Add'));
						echo "</div>";
					echo "</div>";
					//end pekerja
					?>

					<?php
					// add
					foreach($add AS $val => $valx){
						$nomor++;
						?>
						<div class='form-group row'>
							<label class='label-control col-sm-2'><?= ($val == 0)?ucwords($valx['category_'])." <span class='text-red'>*</span>":'';?></b></label>
							<label class='label-control col-sm-2'><b><?= ucwords($valx['category_list']);?></b></label>
							<div class='col-sm-2'>
								<?php
									$selReq = (!empty($valx['req']))?$valx['req']:'customer';
									$selHide = ($selReq == 'customer')?'specHide':'';
									$selSpec = (!empty($valx['spec']))?$valx['spec']:'';
								 	echo form_dropdown('Detail['.$nomor.'][req]', $ArrReq, $selReq, array('class'=>'form-control input-md clSelect','data-num'=>$nomor));
								 	echo form_input(array('type'=>'hidden','name'=>'Detail['.$nomor.'][category]','class'=>'form-control input-md'),$valx['category']);
								 	echo form_input(array('type'=>'hidden','name'=>'Detail['.$nomor.'][category_list]','class'=>'form-control input-md'),$valx['category_list']);
								?>
							</div>
							<div class='col-sm-4'>
								<?php
								 echo form_input(array('type'=>'text','name'=>'Detail['.$nomor.'][spec]','id'=>'spec_'.$nomor,'class'=>'form-control input-md '.$selHide,'placeholder'=>'Capacity/Specificity'),$selSpec);
								?>
							</div>
						</div>
					<?php
					}
					echo "<div id='add'></div>";
					echo "<div class='form-group row'>";
						echo "<div class='col-sm-2'></div>";
						echo "<div class='col-sm-2'>";
							echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success addRow','data-category'=>'add','data-unik'=>'4','value'=>'Add','content'=>'Add'));
						echo "</div>";
					echo "</div>";
					//end add
					?>
			</div>
		</div>

		</div>
		<div class='box-footer'>
			<?php
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-primary','value'=>'save','content'=>'Save','id'=>'save_work')).' ';
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','value'=>'back','content'=>'Back','id'=>'back_work'));
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
	.labDet{
		font-weight: bold;
		margin: 5px 0px 3px 5px;
		color: #0376c7;
	}
	.labAdd{
		font-weight: bold;
		margin: 5px 0px 3px 5px;
		color: #0aa92c;
	}
	.add_plus{
		cursor : pointer;
		color: white;
		background-color: #605ca8 !important;
	}
	.clSelect{
		width: 100%;
	}
	.mbut{
		margin-bottom:7px;
	}
	.spanDel{
		float:right;
	}
	.addRow{
		min-width: 100px;
	}
	.add_del{
		cursor : pointer;
		color: white;
		background-color: #605ca8 !important;
	}

	.delAdd{
		cursor : pointer;
		color: white;
		background-color: #ce1111 !important;
	}
	.widCtr{
		width: 70px !important;
	}
</style>
<script>
	$(document).ready(function(){
		$('#date_delivery').datepicker({
			dateFormat : 'yy-mm-dd',
			startDate: 'now'
		});

		$(".specHide").hide();

		$(document).on('change','.clSelect', function(){
			var data = $(this).val();
			var num = $(this).data('num');

			if(data == 'ori'){
				$("#spec_"+num).show();
			}
			else{
				$("#spec_"+num).hide();
			}
		});

		//save
		$('#save_work').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var id_customer	= $('#id_customer').val();
			var project			= $('#project').val();
			var validity		= $('#validity').val();
			var ref_cust		= $('#ref_cust').val();

			if(id_customer=='0'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Customer Name, please input first ...',
				  type	: "warning"
				});
				$('#save_work').prop('disabled',false);
				return false;
			}

			if(project == ''){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Project, please input first ...',
				  type	: "warning"
				});
				$('#save_work').prop('disabled',false);
				return false;
			}

			if(validity == ''){
				swal({
					title	: "Error Message!",
					text	: 'Empty Validity & Guarantee, please input first ...',
					type	: "warning"
				});
				$('#save_work').prop('disabled',false);
				return false;
			}

			if(ref_cust == ''){
				swal({
					title	: "Error Message!",
					text	: 'Empty Referensi Customer/Project, please input first ...',
					type	: "warning"
				});
				$('#save_work').prop('disabled',false);
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
						var formData 	= new FormData($('#form_work')[0]);
						var baseurl		= base_url + active_controller +'/add';
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
									window.location.href = base_url + active_controller;
								}
								else if(data.status == 2 || data.status == 3){
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 7000
									});
								}

								$('#save_work').prop('disabled',false);
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
								$('#save_work').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#save_work').prop('disabled',false);
					return false;
				  }
			});
		});

		$('#back_work').click(function(e){
			window.location.href = base_url + active_controller;
		});

		//add
		var nomor	= 1;
		$('.addRow').click(function(e){
			e.preventDefault();
			var category	= $(this).data('category');
			var unik			= $(this).data('unik');
			AppendBaris(nomor, category, unik);
		});

		$(document).on('click','.delAdd', function(){
			var del = $(this).parent().parent().parent().html();
			// alert(del);
			$(this).parent().parent().parent().remove();
		});

	});

	function AppendBaris(intd, category, unik){
		var nomor	= 1;
		var valuex	= $('#'+category).find('tr').length;
		if(valuex > 0){
			var akhir	= $("#"+category+" tr:last").attr('id');
			var det_id	= akhir.split('_');
			var nomor	= parseInt(det_id[1])+1;
		}

		var Rows = "<div class='form-group row'>";
					Rows += "<label class='label-control col-sm-2'></label>";
					Rows += "<label class='label-control col-sm-2'>";
					Rows += "<div class='input-group'>";
					Rows += "<input type='text' name='Detail["+unik+nomor+"][category_list]' class='form-control input-md'>";
					Rows += "<span class='input-group-addon delAdd'><i class='fa fa-close'></i></span>";
					Rows += "<input type='hidden' name='Detail["+unik+nomor+"][category]' class='form-control input-md' value='"+category+"'>";
					Rows+= 	"</div>";
					Rows += "</label>";
					Rows += "<div class='col-sm-2'>";
					Rows += "<select name='Detail["+unik+nomor+"][req]' id='req_"+unik+nomor+"' class='chosen_select form-control inline-block clSelect' data-num='"+unik+nomor+"'><option value='customer'>CUSTOMER</option><option value='ori'>ORI</option></select>";
					Rows += "</div>";
					Rows += "<div class='col-sm-4'>";
					Rows += "<input type='text' name='Detail["+unik+nomor+"][spec]' id='spec_"+unik+nomor+"' class='form-control input-md specHide' placeholder='Capacity/Specificity'>";
					Rows += "</div>";
				Rows += "</div>";

		$('#'+category).append(Rows);
		$('.chosen_select').chosen();
		$(".specHide").hide();
	}

</script>
