<?php
$this->load->view('include/side_menu');
$ArrList = array();
foreach($project AS $val => $valx){
	$ArrList[$valx['project_code']] = strtoupper($valx['no_ipp'].' - '.$valx['project_code'].' - '.$valx['project_name']);
}
$ArrList[0]	= 'Select An Project';
// echo"<pre>";
// print_r($ArrRegion);
// echo"</pre>";
?>
<form action="#" method="POST" id="form_work" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?php echo $title;?></h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Project Name <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
					 echo form_dropdown('project_code', $ArrList, '0', array('id'=>'project_code','name'=>'project_code','class'=>'form-control input-md clSelect'));
					?>
				</div>
			</div>
			<br>
			<div class="box box-info" id="cost_id">
				<div class="box-header">
					<h3 class="box-title">Costing</h3>
				</div>
				<div class="box-body">
            <div id="header"></div>
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
	#save_category {
	  color: white;
	  background-color: #605ca8;
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
	.clSelect{
		width: 100% !important;
	}
  .cldelete{
		cursor : pointer;
		color: white;
		background-color: #ce1111 !important;
	}
  textarea {
    resize: none;
  }
  .aAdd{
		margin-top: 10px;
		min-width: 150px;
	}
  .widCtr{
		width: 50px !important;
	}
	.widC{
		width: 300px !important;
	}
</style>
<script>
	$(document).ready(function(){
    $("#cost_id").hide();
		$("#save_work").hide();
    $(".rate").maskMoney();

		$(document).on('keypress keyup blur', '.rate_trans, .pp_trans', function(){
			var rate 				= getNum($(this).parent().parent().find("td:nth-child(8) input").val().split(",").join(""));
			var people 			= getNum($(this).parent().parent().find("td:nth-child(5) input").val());
			var pp 					= getNum($(this).parent().parent().find("td:nth-child(6) input").val());
			var rate_total 	= $(this).parent().parent().find("td:nth-child(9) input");
			var rate_t 			= rate * people * pp;
			$(rate_total).val(rate_t);
			get_sum();
		});

		$(document).on('keypress keyup blur', '.day_meal', function(){
			var rate 				= getNum($(this).parent().parent().find("td:nth-child(6) input").val().split(",").join(""));
			var day 				= getNum($(this).parent().parent().find("td:nth-child(4) input").val());
			var people 			= getNum($(this).parent().parent().find("td:nth-child(3) input").val());
			var rate_total 	= $(this).parent().parent().find("td:nth-child(7) input");
			var rate_t 			= rate * day * people;
			$(rate_total).val(rate_t);
			get_sum();
		});

		$(document).on('keypress keyup blur', '.day_ot, .jam_ot', function(){
			var rate 				= getNum($(this).parent().parent().find("td:nth-child(6) input").val().split(",").join(""));
			var day 				= getNum($(this).parent().parent().find("td:nth-child(3) input").val());
			var people 			= getNum($(this).parent().parent().find("td:nth-child(2) input").val());
			var jam 				= getNum($(this).parent().parent().find("td:nth-child(4) input").val());
			var rate_total 	= $(this).parent().parent().find("td:nth-child(7) input");
			var rate_t 			= rate * day * people * jam;
			$(rate_total).val(rate_t);
			get_sum();
		});

		$(document).on('keypress keyup blur', '.qty_house, .day_house', function(){
			var rate 				= getNum($(this).parent().parent().find("td:nth-child(6) input").val().split(",").join(""));
			var qty 				= getNum($(this).parent().parent().find("td:nth-child(2) input").val());
			var day 				= getNum($(this).parent().parent().find("td:nth-child(3) input").val());
			var rate_total 	= $(this).parent().parent().find("td:nth-child(7) input");
			var rate_t 			= rate * day * qty;
			$(rate_total).val(rate_t);
			get_sum();
		});

		$(document).on('keypress keyup blur', '.qty_etc', function(){
			var rate 				= getNum($(this).parent().parent().find("td:nth-child(4) input").val().split(",").join(""));
			var qty 				= getNum($(this).parent().parent().find("td:nth-child(2) input").val());
			var rate_total 	= $(this).parent().parent().find("td:nth-child(5) input");
			var rate_t 			= rate * qty;
			$(rate_total).val(rate_t);
			get_sum();
		});

		$(document).on('keypress keyup blur', '#total_time', function(){
			var time 				= getNum($(this).val());
			if(time < 0){
				var time 				= 0;
			}
			$('.jam_ot').val(time);
			$(".jam_ot" ).each(function() {
				var rate 				= getNum($(this).parent().parent().find("td:nth-child(6) input").val().split(",").join(""));
				var day 				= getNum($(this).parent().parent().find("td:nth-child(3) input").val());
				var people 			= getNum($(this).parent().parent().find("td:nth-child(2) input").val());
				var jam 				= getNum($(this).parent().parent().find("td:nth-child(4) input").val());
				var rate_total 	= $(this).parent().parent().find("td:nth-child(7) input");
				var rate_t 			= rate * day * people * jam;
				$(rate_total).val(rate_t);
				get_sum();
			});
		});

		$(document).on('change', '.ch_rate', function(){
			var code				= $(this).data('code');
			var category		= $(this).data('category');
			var unit				= $(this).val();
			var region			= $("#region_code").val();

			var time 				= getNum($(this).parent().parent().find("td:nth-child(4) input").val());
			var qty 				= getNum($(this).parent().parent().find("td:nth-child(3) input").val());

			var cost 				= $(this).parent().parent().find("td:nth-child(6) input"); //cost
			var cost_unit 	= $(this).parent().parent().find("td:nth-child(7) input"); //cost_unit
			var total_cost 	= $(this).parent().parent().find("td:nth-child(8) input"); //total_cost

			loading_spinner();
  		//tempat tinggal
  		$.ajax({
  			url: base_url+ active_controller+'/get_rate'+'/'+code+'/'+category+'/'+unit+'/'+time+'/'+qty+'/'+region,
  			cache: false,
  			type: "POST",
  			dataType: "json",
  			success: function(data){
  			  $(cost).val(data.cost);
					$(cost_unit).val(data.cost_unit);
					$(total_cost).val(data.total_cost);
  				swal.close();
					get_sum();
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

		$(document).on('change', '.etcSelect', function(){
      var nomor 	    = $(this).data('nomor');
      var code_group 	= $(this).val();
  		var unit 				= 'day';
  		var region 		  = $('#region_code').val();
      var category 		= 'akomodasi';
      var qty 		    = getNum($('#qtye_'+nomor).val());
  		loading_spinner();
  		//tempat tinggal
  		$.ajax({
  			url: base_url+ active_controller+'/getPrice'+'/'+code_group+'/'+unit+'/'+region+'/'+category,
  			cache: false,
  			type: "POST",
  			dataType: "json",
  			success: function(data){
  			  $("#ratee_"+nomor).val(data.rate);
          var ratex = data.rate.split(",").join("");
          $("#total_ratee_"+nomor).val(ratex * qty);
  				swal.close();
					get_sum();
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

		$(document).on('change', '.houseSelect, .unit_house', function(){
      var nomor 	    = $(this).data('nomor');
      var code_group 	= $('#codeh_'+nomor).val();
  		var unit 				= $('#satuanh_'+nomor).val();
  		var region 		  = $('#region_code').val();
      var category 		= 'akomodasi';
      var qty 		    = getNum($('#qtyh_'+nomor).val());
      var day 		    = getNum($('#valueh_'+nomor).val());
  		loading_spinner();
  		//tempat tinggal
  		$.ajax({
  			url: base_url+ active_controller+'/getPrice'+'/'+code_group+'/'+unit+'/'+region+'/'+category,
  			cache: false,
  			type: "POST",
  			dataType: "json",
  			success: function(data){
  			  $("#rateh_"+nomor).val(data.rate);
          var ratex = data.rate.split(",").join("");
          $("#total_rateh_"+nomor).val(ratex * qty * day);
  				swal.close();
					get_sum();
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

    $(document).on('change', '#project_code', function(){
			loading_spinner();
      $("#cost_id").slideUp("slow");
			var project_code = $(this).val();
			$.ajax({
				url: base_url+'index.php/'+active_controller+'/get_project/'+project_code,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data){
					$("#header").html(data.header);
          // $('select').addClass('chosen_select');
						$('.chosen_select').chosen({width: '100%'});
          $("#cost_id").slideDown("slow");
          $(".rate").maskMoney();
					$(".hideSP").hide();
          number_full();
      		ex_petik();

					$(".rate_meal" ).each(function() {
						var rate 				= getNum($(this).val().split(",").join(""));
						var day 				= getNum($(this).parent().parent().find("td:nth-child(4) input").val());
						var people 			= getNum($(this).parent().parent().find("td:nth-child(3) input").val());
						var rate_total 	= $(this).parent().parent().find("td:nth-child(7) input");
						var rate_t 			= rate * day * people;
						$(rate_total).val(rate_t);
					});

					$(".rate_ot" ).each(function() {
						var rate 				= getNum($(this).val().split(",").join(""));
						var day 				= getNum($(this).parent().parent().find("td:nth-child(3) input").val());
						var people 			= getNum($(this).parent().parent().find("td:nth-child(2) input").val());
						var jam 				= getNum($(this).parent().parent().find("td:nth-child(4) input").val());
						var rate_total 	= $(this).parent().parent().find("td:nth-child(7) input");
						var rate_t 			= rate * day * people * jam;
						$(rate_total).val(rate_t);
					});

					$(".rate_house" ).each(function() {
						var rate 				= getNum($(this).val().split(",").join(""));
						var qty 				= getNum($(this).parent().parent().find("td:nth-child(2) input").val());
						var day 				= getNum($(this).parent().parent().find("td:nth-child(3) input").val());
						var rate_total 	= $(this).parent().parent().find("td:nth-child(7) input");
						var rate_t 			= rate * day * qty;
						$(rate_total).val(rate_t);
					});

					$(".rate_etc" ).each(function() {
						var rate 				= getNum($(this).val().split(",").join(""));
						var qty 				= getNum($(this).parent().parent().find("td:nth-child(2) input").val());
						var rate_total 	= $(this).parent().parent().find("td:nth-child(5) input");
						var rate_t 			= rate * qty;
						$(rate_total).val(rate_t);
					});

					$("#save_work").show();
					swal.close();
					get_sum();
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
		//save
		$('#save_work').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var project_code	= $('#project_code').val();

			if(project_code=='0'){
				swal({
				  title	: "Error Message!",
				  text	: 'Project not selected, please select first ...',
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

    //add Housing
		var nomor_house	= 1;
    $(document).on('click', '#add_house', function(e){
			e.preventDefault();
			loading_spinner();
			var nilaiAwal	= parseInt($("#numberHouse").val());
			var nilaiAkhir	= nilaiAwal + 1;
			$("#numberHouse").val(nilaiAkhir);

			AppendBarisHouse(nomor_house, nilaiAkhir);
			$('.chosen_select').chosen({width: '100%'});

			$("#detail_body_house_empty").hide();
			swal.close();
		});

    $(document).on('click','.delRowsH', function(){
			$(this).parent().parent().parent().remove();

			var updatemax	=	$("#numberHouse").val() - 1;
			$("#numberHouse").val(updatemax);

			var maxLine = $("#numberHouse").val();
			if(maxLine == 0){
				$("#detail_body_house_empty").show();
			}
		});

    //add Trans
		var nomor_trans	= 1;
    $(document).on('click', '#add_trans', function(e){
			e.preventDefault();
			loading_spinner();
			var nilaiAwal	= parseInt($("#numberTrans").val());
			var nilaiAkhir	= nilaiAwal + 1;
			$("#numberTrans").val(nilaiAkhir);

			AppendBarisTrans(nomor_trans, nilaiAkhir);
			$('.chosen_select').chosen({width: '100%'});

			$("#detail_body_trans_empty").hide();
			$('#save_work').show();
			swal.close();
		});

    $(document).on('click','.delRowsT', function(){
			$(this).parent().parent().parent().remove();

			var updatemax	=	$("#numberTrans").val() - 1;
			$("#numberTrans").val(updatemax);

			var maxLine = $("#numberTrans").val();
			if(maxLine == 0){
				$("#detail_body_trans_empty").show();
			}
		});

    //add Etc
		var nomor_etc	= 1;
    $(document).on('click','#add_etc', function(e){
			e.preventDefault();
			loading_spinner();
			var nilaiAwal	= parseInt($("#numberEtc").val());
			var nilaiAkhir	= nilaiAwal + 1;
			$("#numberEtc").val(nilaiAkhir);

			AppendBarisEtc(nomor_etc, nilaiAkhir);
			$('.chosen_select').chosen({width: '100%'});

			$("#detail_body_etc_empty").hide();
			$('#save_work').show();
			swal.close();
		});

    $(document).on('click','.delRowsE', function(){
			$(this).parent().parent().parent().remove();

			var updatemax	=	$("#numberEtc").val() - 1;
			$("#numberEtc").val(updatemax);

			var maxLine = $("#numberEtc").val();
			if(maxLine == 0){
				$("#detail_body_etc_empty").show();
			}
		});

	});

  function AppendBarisHouse(intd){
		var nomor	= 1;
		var valuex	= $('#detail_body_house').find('tr').length;
		if(valuex > 0){
			var akhir	= $('#detail_body_house tr:last').attr('id');
			// console.log(akhir);
			var det_id	= akhir.split('_');
			var nomor	= parseInt(det_id[1])+1;
		}

		var Rows	 = 	"<tr id='trak_"+nomor+"'>";
			Rows	+= 		"<td align='left'  width='10%'>";
      Rows  +=    "<div class='input-group'>";
			Rows	+=			"<select name='ListDetailHouse["+nomor+"][code_group]' id='codeh_"+nomor+"' data-nomor='"+nomor+"' class='chosen_select form-control inline-block houseSelect'></select>";
      Rows	+= 		"<span class='input-group-addon cldelete delRowsH'><i class='fa fa-close'></i></span>";
      Rows	+= 		"</div>";
      Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][qty]' id='qtyh_"+nomor+"' class='form-control input-md numberFull qty_house' placeholder='Qty'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][value]' id='valueh_"+nomor+"' class='form-control input-md numberFull day_house' placeholder='Value'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=			"<select name='ListDetailHouse["+nomor+"][satuan]' id='satuanh_"+nomor+"' id='satuanh_"+nomor+"' class='chosen_select form-control inline-block unit_house'></select>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][note]' id='noteh_"+nomor+"' class='form-control input-md' placeholder='Note'>";
			Rows	+= 		"</td>";
      Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][rate]' id='rateh_"+nomor+"' class='form-control input-md text-right rate rate_house' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
			Rows	+= 		"</td>";
      Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][total_rate]' id='total_rateh_"+nomor+"' class='form-control input-md text-right rate sum_house' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
			Rows	+= 		"</td>";
			Rows	+= 	"</tr>";

		$('#detail_body_house').append(Rows);

		var item_cost 	= '#codeh_'+nomor;
		var satuan 	= '#satuanh_'+nomor;
		loading_spinner();
		//tempat tinggal
		$.ajax({
			url: base_url+'index.php/instalation/list_tempat_tinggal',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$(item_cost).html(data.option).trigger("chosen:updated");
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
		//satuan
		$.ajax({
			url: base_url+'index.php/instalation/list_satuan',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$(satuan).html(data.option).trigger("chosen:updated");
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
    $(".rate").maskMoney();
		number_full();
		ex_petik()
	}

  function AppendBarisTrans(intd){
		var nomor	= 1;
		var valuex	= $('#detail_body_trans').find('tr').length;
		if(valuex > 0){
			var akhir	= $('#detail_body_trans tr:last').attr('id');
			// console.log(akhir);
			var det_id	= akhir.split('_');
			var nomor	= parseInt(det_id[1])+1;
		}

		var Rows	 = 	"<tr id='trak_"+nomor+"'>";
			Rows	+= 		"<td align='left'  width='10%'>";
      Rows  +=    "<div class='input-group'>";
			Rows	+=			"<select name='ListDetailTrans["+nomor+"][item_cost]' id='item_costt_"+nomor+"' class='chosen_select form-control inline-block'></select>";
      Rows	+= 		"<span class='input-group-addon cldelete delRowsT'><i class='fa fa-close'></i></span>";
      Rows	+= 		"</div>";
      Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=			"<select name='ListDetailTrans["+nomor+"][kendaraan]' id='kendaraant_"+nomor+"' class='chosen_select form-control inline-block'></select>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][asal]' id='asalt_"+nomor+"' class='form-control input-md' placeholder='Origin'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][tujuan]' id='tujuant_"+nomor+"' class='form-control input-md' placeholder='Destination'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][value]' id='valuet_"+nomor+"' class='form-control input-md numberFull' placeholder='Value'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][pulang_pergi]' id='pulang_pergit_"+nomor+"' class='form-control input-md numberFull pp_trans' placeholder='Round-Trip'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][note]' id='notet_"+nomor+"' class='form-control input-md' placeholder='Note'>";
			Rows	+= 		"</td>";
      Rows	+= 		"<td align='left'>";
      Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][rate]' id='ratet_"+nomor+"' class='form-control input-md text-right rate rate_trans' placeholder='Rate' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
      Rows	+= 		"</td>";
      Rows	+= 		"<td align='left'>";
      Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][total_rate]' id='total_ratet_"+nomor+"' class='form-control input-md text-right rate sum_trans' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
      Rows	+= 		"</td>";
			Rows	+= 	"</tr>";

		$('#detail_body_trans').append(Rows);

		var item_cost 	= '#item_costt_'+nomor;
		var kendaraan 	= '#kendaraant_'+nomor;
		loading_spinner();
		//tempat tinggal
		$.ajax({
			url: base_url+'index.php/instalation/list_tiket',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$(item_cost).html(data.option).trigger("chosen:updated");
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

		$(document).on('change',item_cost,function(){
			var ad = $(this).val();

			$.ajax({
				url: base_url+'index.php/instalation/list_sewa_kendaraan/'+ad,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data){
					$(kendaraan).html(data.option).trigger("chosen:updated");
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
    $(".rate").maskMoney();
		number_full();
		ex_petik()
	}

  function AppendBarisEtc(intd){
		var nomor	= 1;
		var valuex	= $('#detail_body_etc').find('tr').length;
		if(valuex > 0){
			var akhir	= $('#detail_body_etc tr:last').attr('id');
			// console.log(akhir);
			var det_id	= akhir.split('_');
			var nomor	= parseInt(det_id[1])+1;
		}

		var Rows	 = 	"<tr id='trak_"+nomor+"'>";
			Rows	+= 		"<td align='left'  width='10%'>";
      Rows  +=    "<div class='input-group'>";
			Rows	+=			"<select name='ListDetailEtc["+nomor+"][code_group]' id='item_coste_"+nomor+"' data-nomor='"+nomor+"' class='chosen_select form-control inline-block etcSelect'></select>";
      Rows	+= 		"<span class='input-group-addon cldelete delRowsE'><i class='fa fa-close'></i></span>";
      Rows	+= 		"</div>";
      Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailEtc["+nomor+"][qty]' id='qtye_"+nomor+"' class='form-control input-md numberFull qty_etc' placeholder='Value'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailEtc["+nomor+"][note]' id='notee_"+nomor+"' class='form-control input-md' placeholder='Note'>";
			Rows	+= 		"</td>";
      Rows	+= 		"<td align='left'>";
      Rows	+=				"<input type='text' name='ListDetailEtc["+nomor+"][rate]' id='ratee_"+nomor+"' class='form-control input-md text-right rate' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
      Rows	+= 		"</td>";
      Rows	+= 		"<td align='left'>";
      Rows	+=				"<input type='text' name='ListDetailEtc["+nomor+"][total_rate]' id='total_ratee_"+nomor+"' class='form-control input-md text-right rate sum_etc' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
      Rows	+= 		"</td>";
			Rows	+= 	"</tr>";

		$('#detail_body_etc').append(Rows);

		var item_cost 	= '#item_coste_'+nomor;
		loading_spinner();
		//tempat tinggal
		$.ajax({
			url: base_url+'index.php/instalation/list_etc',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$(item_cost).html(data.option).trigger("chosen:updated");
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
    $(".rate").maskMoney();
		number_full();
		ex_petik();
	}

	function get_sum(){
		var SUM_HE = 0;
		var SUM_VT = 0;
		var SUM_CN = 0;
		var SUM_MP = 0;
		var SUM_MEAL = 0;
		var SUM_OT = 0;
		var SUM_HOUSE = 0;
		var SUM_TRANS = 0;
		var SUM_ETC = 0;

		$(".sum_vt" ).each(function() {
			SUM_VT += Number(getNum($(this).val().split(",").join("")));
    	});
		$(".sum_he" ).each(function() {
			SUM_HE += Number(getNum($(this).val().split(",").join("")));
    	});
		$(".sum_cn" ).each(function() {
			SUM_CN += Number(getNum($(this).val().split(",").join("")));
    });
		$(".sum_mp" ).each(function() {
			SUM_MP += Number(getNum($(this).val().split(",").join("")));
    });
		$(".sum_meal" ).each(function() {
			SUM_MEAL += Number(getNum($(this).val().split(",").join("")));
    });
		$(".sum_ot" ).each(function() {
			SUM_OT += Number(getNum($(this).val().split(",").join("")));
    });
		$(".sum_house" ).each(function() {
			SUM_HOUSE += Number(getNum($(this).val().split(",").join("")));
    });
		$(".sum_trans" ).each(function() {
			SUM_TRANS += Number(getNum($(this).val().split(",").join("")));
    });
		$(".sum_etc" ).each(function() {
			SUM_ETC += Number(getNum($(this).val().split(",").join("")));
    });
		var sum_total = SUM_VT + SUM_HE + SUM_CN + SUM_MP + SUM_MEAL + SUM_OT + SUM_HOUSE + SUM_TRANS + SUM_ETC;
		$("#sum_he").val(('' + SUM_HE).replace(/\B(?=(?:\d{3})+(?!\d))/g, ','));
		$("#sum_vt").val(('' + SUM_VT).replace(/\B(?=(?:\d{3})+(?!\d))/g, ','));
		$("#sum_cn").val(('' + SUM_CN).replace(/\B(?=(?:\d{3})+(?!\d))/g, ','));
		$("#sum_mp").val(('' + SUM_MP).replace(/\B(?=(?:\d{3})+(?!\d))/g, ','));
		$("#sum_meal").val(('' + SUM_MEAL).replace(/\B(?=(?:\d{3})+(?!\d))/g, ','));
		$("#sum_ot").val(('' + SUM_OT).replace(/\B(?=(?:\d{3})+(?!\d))/g, ','));
		$("#sum_house").val(('' + SUM_HOUSE).replace(/\B(?=(?:\d{3})+(?!\d))/g, ','));
		$("#sum_trans").val(('' + SUM_TRANS).replace(/\B(?=(?:\d{3})+(?!\d))/g, ','));
		$("#sum_etc").val(('' + SUM_ETC).replace(/\B(?=(?:\d{3})+(?!\d))/g, ','));
		$("#sum_total").val(('' + sum_total).replace(/\B(?=(?:\d{3})+(?!\d))/g, ','));

	}
</script>
