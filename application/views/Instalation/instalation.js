
//change cyctletime detail bq sum_ct
$(document).on('keyup','.dim_ch', function(){
  var no = $(this).data('no');
  var dim = $('#diameter_'+no).val();
  var dim2 = $('#diameter_'+no);
  var satuan = $('#satuan_'+no).val();
  var qty = $('#qty_'+no).val();

  if(satuan == 'joint'){
    // loading_spinner();
    $.ajax({
      url: base_url+'index.php/'+active_controller+'/get_cycletime/'+dim,
      cache: false,
      type: "POST",
      dataType: "json",
      success: function(data){
        var total = (data.cycletime * qty)/60;
        $("#cycletime_"+no).val(total.toFixed(2));
        $("#alert").html("<p style='color:"+data.color+";'><b>"+data.alert+"</b></p>");
        $("#alert").show();
        $("#alert").fadeOut(3000);
        $("#diameter_"+no).focus();
        swal.close();
        sum_bq();
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
  }
});

//change cyctletime detail bq
$(document).on('change','.ch_cycletime', function(){
  var no = $(this).data('no');
  var satuan = $('#satuan_'+no).val();
  var dim = $('#diameter_'+no).val();
  var qty = $('#qty_'+no).val();

  if(satuan == 'joint'){
    // loading_spinner();
    $("#cycletime_"+no).prop('readonly',true);
    $.ajax({
      url: base_url+'index.php/'+active_controller+'/get_cycletime/'+dim,
      cache: false,
      type: "POST",
      dataType: "json",
      success: function(data){
        var total = (data.cycletime * qty)/60;
        $("#cycletime_"+no).val(total.toFixed(2));
        $("#alert").html("<p style='color:"+data.color+";'><b>"+data.alert+"</b></p>");
        $("#alert").show();
        $("#alert").fadeOut(3000);
        swal.close();
        sum_bq();
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

  }
  else{
    $("#cycletime_"+no).val('');
    $("#alert").html("<p></p>");
    $("#cycletime_"+no).prop('readonly',false);
    $("#alert").fadeOut(3000);
    sum_bq();
  }
});

//change ct
$(document).on('keyup','.sum_ct, #bq_mp, #total_time', function(){
    sum_bq();
});

$(document).on('keypress keyup blur','.chTime', function(){
  var no = $(this).data('nomor');
  var SUM = 0;
  $(".chTime" ).each(function() {
    SUM += Number($(this).val());
  });
  $('.dayT_'+no).val(getNum(SUM));
});

$(document).on('keypress keyup blur','.chMPQty', function(){
  var no1 = $(this).data('no1');
  var no2 = $(this).data('no2');
  $('#jml_orangm_'+no1+'_'+no2).val($(this).val());
  $('#jml_orango_'+no1+'_'+no2).val($(this).val());
});

$(document).on('click','.aDel', function(){
  var del_tr = $(this).data('del_tr');
  // alert($(this).parent().parent().html());
  $(this).parent().parent().remove();
  // alert($(this).find('div').html());
});

$(document).on('click','.aDelP', function(){
  $(this).parent().parent().parent().remove();
});

$(document).on('click','.delRows', function(){
  $(this).parent().parent().remove();

  var updatemax	=	$("#numberMaxAk").val() - 1;
  $("#numberMaxAk").val(updatemax);

  var maxLine = $("#numberMaxAk").val();
  if(maxLine == 0){
    $("#detail_body_ak_empty").show();
  }
});

$(document).on('click','.delRowsH', function(){
  $(this).parent().parent().remove();

  var updatemax	=	$("#numberHouse").val() - 1;
  $("#numberHouse").val(updatemax);

  var maxLine = $("#numberHouse").val();
  if(maxLine == 0){
    $("#detail_body_house_empty").show();
  }
});

$(document).on('click','.delRowsT', function(){
  $(this).parent().parent().remove();

  var updatemax	=	$("#numberTrans").val() - 1;
  $("#numberTrans").val(updatemax);

  var maxLine = $("#numberTrans").val();
  if(maxLine == 0){
    $("#detail_body_trans_empty").show();
  }
});

$(document).on('click','.delRowsE', function(){
  $(this).parent().parent().remove();

  var updatemax	=	$("#numberEtc").val() - 1;
  $("#numberEtc").val(updatemax);

  var maxLine = $("#numberEtc").val();
  if(maxLine == 0){
    $("#detail_body_etc_empty").show();
  }
});

//add component
$(document).on('click','.aAdd', function(){
  var num1 		= $(this).data('num1');
  var num2 		= $(this).data('num2');
  var numlast = $(this).data('numlast');
  var tanda 	= $(this).data('tanda');
  var tanda2 	= $(this).data('tanda2');
  loading_spinner();
  addDropdown(num1, num2, numlast, tanda, tanda2);
});

//back
$('#back_work').click(function(e){
  window.location.href = base_url + active_controller;
});

var nomor	= 1;
$('#add_sp').click(function(e){
  e.preventDefault();
  loading_spinner();
  var nilaiAwal	= parseInt($("#numberMax").val());
  var nilaiAkhir	= nilaiAwal + 1;
  $("#numberMax").val(nilaiAkhir);

  AppendBaris(nomor, nilaiAkhir);

  $('#head_table').show();
  $('.chosen_select').chosen({width: '100%'});

  $("#detail_body_empty").hide();
  $("#detail_body_meal_empty").hide();
  $("#detail_body_ot_empty").hide();
  $('#save_work').show();
  swal.close();
});

//add Housing
var nomor_house	= 1;
$('#add_house').click(function(e){
  e.preventDefault();
  loading_spinner();
  var nilaiAwal	= parseInt($("#numberHouse").val());
  var nilaiAkhir	= nilaiAwal + 1;
  $("#numberHouse").val(nilaiAkhir);

  AppendBarisHouse(nomor_house, nilaiAkhir);
  $('.chosen_select').chosen({width: '100%'});

  $("#detail_body_house_empty").hide();
  $('#save_work').show();
  swal.close();
});

//add Trans
var nomor_trans	= 1;
$('#add_trans').click(function(e){
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

//add Etc
var nomor_etc	= 1;
$('#add_etc').click(function(e){
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

//Add BQ
var nomorBq	= 1;
$('#add_bq').click(function(e){
  e.preventDefault();
  var nilaiAwal	= parseInt($("#numberMaxBq").val());
  var nilaiAkhir	= nilaiAwal + 1;
  $("#numberMaxBq").val(nilaiAkhir);
  loading_spinner();
  AppendBarisBq(nomorBq, nilaiAkhir);
  swal.close();
  $('.chosen_select').chosen({width: '100%'});
  $("#detail_bq_empty").hide();
});

function AppendBaris(intd){
  var nomor	= 1;
  var valuex	= $('#detail_body').find('tr').length;
  if(valuex > 0){
    var akhir	= $('#detail_body tr:last').attr('id');
    // console.log(akhir);
    var det_id	= akhir.split('_');
    var nomor	= parseInt(det_id[1])+1;
  }

  var Rows	 = 	"<tr id='tr_"+nomor+"'>";
    Rows	+= 		"<td align='left'  width='10%'>";
    Rows	+=			"<div class='labDet'>Job Name</div>";
    Rows	+= 		"<div class='input-group'>";
    Rows	+=			"<select name='ListDetail["+nomor+"][code_work]' id='code_work_"+nomor+"' class='chosen_select form-control inline-block'></select>";
    Rows 	+= 		"<span class='input-group-addon cldelete' onClick='delRow("+nomor+")'><i class='fa fa-close'></i></span>";
    Rows	+= 		"<div>";
    Rows	+= 		"</td>";
    Rows	+= 		"<td align='left'  width='90%'>";
    Rows	+= 			"<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>";
    Rows 	+= 				"<tbody id='detail_bqDet_"+nomor+"'></tbody>";
    Rows 	+= 			"</table>";
    Rows	+= 		"</td>";
    Rows	+= 	"</tr>";

  var RowsMeal	 = 	"<tr id='trmeal_"+nomor+"'>";
    RowsMeal	+= 		"<td align='left' colspan='6'>";
    RowsMeal	+= 			"<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>";
    RowsMeal 	+= 				"<tbody id='detail_bqMeal_"+nomor+"'></tbody>";
    RowsMeal 	+= 			"</table>";
    RowsMeal	+= 		"</td>";
    RowsMeal	+= 	"</tr>";

  var RowsOT	 = 	"<tr id='trot_"+nomor+"'>";
    RowsOT	+= 		"<td align='left' colspan='6'>";
    RowsOT	+= 			"<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>";
    RowsOT 	+= 				"<tbody id='detail_bqOT_"+nomor+"'></tbody>";
    RowsOT 	+= 			"</table>";
    RowsOT	+= 		"</td>";
    RowsOT	+= 	"</tr>";

  $('#detail_body').append(Rows);
  $('#detail_body_meal').append(RowsMeal);
  $('#detail_body_ot').append(RowsOT);

  var code_work 	= '#code_work_'+nomor;
  loading_spinner();
  //code work
  $.ajax({
    url: base_url+'index.php/'+active_controller+'/list_work',
    cache: false,
    type: "POST",
    dataType: "json",
    success: function(data){
      $(code_work).html(data.option).trigger("chosen:updated");
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

  $(document).on('change', code_work, function(){
    loading_spinner();
    var codeWork = $(this).val();
    $.ajax({
      url: base_url+'index.php/'+active_controller+'/list_work_det/'+codeWork+'/'+nomor,
      cache: false,
      type: "POST",
      dataType: "json",
      success: function(data){
        $("#detail_bqDet_"+data.nomor).html(data.rowx);
        $("#detail_bqMeal_"+data.nomor).html(data.row_meal);
        $("#detail_bqOT_"+data.nomor).html(data.overtime);
        $('.chosen_select').chosen();
        swal.close();
        // AppendBarisBqDet(data.nomor, data.code_work, data.loop);
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
}

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
    Rows	+=			"<select name='ListDetailHouse["+nomor+"][code_group]' id='item_costh_"+nomor+"' class='chosen_select form-control inline-block'></select>";
    Rows	+= 		"</td>";
    Rows	+= 		"<td align='left'>";
    Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][qty]' id='qtyh_"+nomor+"' class='form-control input-md numberFull' placeholder='Qty'>";
    Rows	+= 		"</td>";
    Rows	+= 		"<td align='left'>";
    Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][value]' id='valueh_"+nomor+"' class='form-control input-md numberFull' placeholder='Value'>";
    Rows	+= 		"</td>";
    Rows	+= 		"<td align='left'>";
    Rows	+=			"<select name='ListDetailHouse["+nomor+"][satuan]' id='satuanh_"+nomor+"' class='chosen_select form-control inline-block'></select>";
    Rows	+= 		"</td>";
    Rows	+= 		"<td align='left'>";
    Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][note]' id='noteh_"+nomor+"' class='form-control input-md' placeholder='Note'>";
    Rows	+= 		"</td>";
    Rows	+= 		"<td align='center'>";
    Rows 	+=			"<button type='button' style='min-width:70px;' class='btn btn-danger btn-sm delRowsH' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
    Rows	+= 		"</td>";
    Rows	+= 	"</tr>";

  $('#detail_body_house').append(Rows);

  var item_cost 	= '#item_costh_'+nomor;
  var satuan 	= '#satuanh_'+nomor;
  loading_spinner();
  //tempat tinggal
  $.ajax({
    url: base_url+'index.php/'+active_controller+'/list_tempat_tinggal',
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
    url: base_url+'index.php/'+active_controller+'/list_satuan',
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
    Rows	+=			"<select name='ListDetailTrans["+nomor+"][item_cost]' id='item_costt_"+nomor+"' class='chosen_select form-control inline-block'></select>";
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
    Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][pulang_pergi]' id='pulang_pergit_"+nomor+"' class='form-control input-md' placeholder='Round-Trip'>";
    Rows	+= 		"</td>";
    Rows	+= 		"<td align='left'>";
    Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][note]' id='notet_"+nomor+"' class='form-control input-md' placeholder='Note'>";
    Rows	+= 		"</td>";
    Rows	+= 		"<td align='center'>";
    Rows 	+=			"<button type='button' style='min-width:70px;' class='btn btn-danger btn-sm delRowsT' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
    Rows	+= 		"</td>";
    Rows	+= 	"</tr>";

  $('#detail_body_trans').append(Rows);

  var item_cost 	= '#item_costt_'+nomor;
  var kendaraan 	= '#kendaraant_'+nomor;
  loading_spinner();
  //tempat tinggal
  $.ajax({
    url: base_url+'index.php/'+active_controller+'/list_tiket',
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
      url: base_url+'index.php/'+active_controller+'/list_sewa_kendaraan/'+ad,
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
    Rows	+=			"<select name='ListDetailEtc["+nomor+"][code_group]' id='item_coste_"+nomor+"' class='chosen_select form-control inline-block'></select>";
    Rows	+= 		"</td>";
    Rows	+= 		"<td align='left'>";
    Rows	+=				"<input type='text' name='ListDetailEtc["+nomor+"][qty]' id='qtye_"+nomor+"' class='form-control input-md numberFull' placeholder='Value'>";
    Rows	+= 		"</td>";
    Rows	+= 		"<td align='left'>";
    Rows	+=				"<input type='text' name='ListDetailEtc["+nomor+"][note]' id='notet_"+nomor+"' class='form-control input-md' placeholder='Note'>";
    Rows	+= 		"</td>";
    Rows	+= 		"<td align='center'>";
    Rows 	+=			"<button type='button' style='min-width:70px;' class='btn btn-danger btn-sm delRowsE' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
    Rows	+= 		"</td>";
    Rows	+= 	"</tr>";

  $('#detail_body_etc').append(Rows);

  var item_cost 	= '#item_coste_'+nomor;
  loading_spinner();
  //tempat tinggal
  $.ajax({
    url: base_url+'index.php/'+active_controller+'/list_etc',
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

  number_full();
  ex_petik();
}

function delRow(row){
  $('#tr_'+row).remove();
  $('#trmeal_'+row).remove();
  $('#trot_'+row).remove();

  var updatemax	=	$("#numberMax").val() - 1;
  $("#numberMax").val(updatemax);

  var maxLine = $("#numberMax").val();
  if(maxLine == 0){
    $("#detail_body_empty").show();
    $("#detail_body_meal_empty").show();
    $("#detail_body_ot_empty").show();
  }
}

function AppendBarisBq(intd){
  var nomor	= 1;
  var valuex	= $('#detail_bq').find('tr').length;
  if(valuex > 0){
    var akhir	= $('#detail_bq tr:last').attr('id');
    var det_id	= akhir.split('_');
    var nomor	= parseInt(det_id[1])+1;
  }

  var Rows	 = 	"<tr id='trbq_"+nomor+"'>";
    Rows	+= 		"<td align='left'>";
    Rows	+=				"<input type='text' name='ListDetailBq["+nomor+"][diameter]' id='diameter_"+nomor+"' data-no='"+nomor+"' class='form-control input-md numberFull dim_ch sum_dim' placeholder='Diameter'>";
    Rows	+= 		"</td>";
    Rows	+= 		"<td align='left'>";
    Rows	+=				"<input type='text' name='ListDetailBq["+nomor+"][qty]' id='qty_"+nomor+"'  data-no='"+nomor+"' class='form-control input-md numberFull dim_ch sum_qty' placeholder='Quantity'>";
    Rows	+= 		"</td>";
    Rows	+= 		"<td align='left' style='text-align: left;'>";
    Rows	+=			"<select name='ListDetailBq["+nomor+"][satuan_code]' id='satuan_"+nomor+"' data-no='"+nomor+"' class='chosen_select form-control inline-block ch_cycletime'></select>";
    Rows	+= 		"</td>";
    Rows	+= 		"<td align='left'>";
    Rows	+=				"<input type='text' name='ListDetailBq["+nomor+"][cycletime]' id='cycletime_"+nomor+"' class='form-control input-md numberOnly sum_ct' readonly placeholder='Cycletime'>";
    Rows	+= 		"</td>";
    Rows	+= 		"<td align='center'>";
    Rows 	+=			"<button type='button' style='min-width:70px;' class='btn btn-danger btn-sm' data-toggle='tooltip' data-placement='bottom' onClick='delRowBq("+nomor+")' title='Delete Record'>Del</button>";
    Rows	+= 		"</td>";
    Rows	+= 	"</tr>";

  $('#detail_bq').append(Rows);

  var satuan 	= '#satuan_'+nomor;
  loading_spinner();
  $.ajax({
    url: base_url+'index.php/'+active_controller+'/list_bq_project',
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
  number_full();
}

function delRowBq(row){
  $('#trbq_'+row).remove();

  var updatemax	=	$("#numberMaxBq").val() - 1;
  $("#numberMaxBq").val(updatemax);

  var maxLine = $("#numberMaxBq").val();
  if(maxLine == 0){
    $("#detail_bq_empty").show();
  }
}

function addDropdown(num1, num2, numlast, tanda, tanda2){
  // alert(num1+'/'+num2+'/'+numlast+'/'+tanda);
  $.ajax({
    url: base_url+'index.php/'+active_controller+'/add_dropdown/'+num1+'/'+num2+'/'+numlast+'/'+tanda+'/'+tanda2,
    cache: false,
    type: "POST",
    dataType: "json",
    success: function(data){
      $("#"+data.tanda+data.num1+'_'+data.num2).prepend(data.rowx);
      $('.chosen_select').chosen();
      swal.close();
      // $("#"+data.tanda+data.num1+'_'+data.num2).remove();
      // alert("#"+data.tanda+data.num1+'_'+data.num2);
      // AppendBarisBqDet(data.nomor, data.code_work, data.loop);
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
}

function sum_bq(){
  var SUM_dim = 0;
  var SUM_qty = 0;
  var SUM_ct  = 0;
  var mp          = getNum($("#bq_mp").val());
  var total_time  = getNum($("#total_time").val());

  $(".sum_qty" ).each(function() {
    SUM_qty += Number($(this).val());
  });
  $(".sum_ct" ).each(function() {
    SUM_ct += Number($(this).val());
  });

  var cal_mp = (getNum(SUM_ct) / mp) / total_time;

  $('#bq_qty').val(getNum(SUM_qty));
  $('#bq_ct').val(getNum(SUM_ct));
  $('#bq_total').val(getNum(cal_mp.toFixed(2)));
}
