<?php
$gambar='';
$dept='';$app='';$bank_id='';$accnumber='';$accname='';
$data_session	= $this->session->userdata;
$dateTime = date('Y-m-d H:i:s');
$UserName = $data_session['ORI_User']['id_user'];
$dept = $data_session['ORI_User']['department_id'];
?>
<link rel="stylesheet" href="<?=base_url()?>assets/plugins/select2/select2.css">
<script src="<?=base_url()?>assets/plugins/select2/select2.full.min.js"></script>
<?=form_open($this->uri->uri_string(),array('id'=>'frm_data','name'=>'frm_data','role'=>'form','class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
<input type="hidden" id="id" name="id" value="<?php echo set_value('id', isset($data->id) ? $data->id : ''); ?>">
<input type="hidden" id="nama" name="nama" value="<?php echo (isset($data->nama) ? $data->nama: $UserName ); ?>">
<input type="hidden" id="approval" name="approval" value="<?php echo (isset($data->approval) ? $data->approval: $app ); ?>">
<style>
@media screen and (max-width: 520px) {
	table {
		width: 100%;
	}
	thead th.column-primary {
		width: 100%;
	}

	thead th:not(.column-primary) {
		display:none;
	}

	th[scope="row"] {
		vertical-align: top;
	}

	td {
		display: block;
		width: auto;
		text-align: right;
	}
	thead th::before {
		text-transform: uppercase;
		font-weight: bold;
		content: attr(data-header);
	}
	thead th:first-child span {
		display: none;
	}
	td::before {
		float: left;
		text-transform: uppercase;
		font-weight: bold;
		content: attr(data-header);
	}
}

</style>
<div class="tab-content">
	<div class="tab-pane active">
		<div class="box box-primary">
			<div class="box-body">
				<div class="form-group ">
					<label class="col-sm-2 col-md-2 control-label">No Dokumen</label>
					<div class="col-sm-4 col-md-4">
						<input type="text" class="form-control" id="no_doc" name="no_doc" value="<?php echo (isset($data->no_doc) ? $data->no_doc: ""); ?>" placeholder="Automatic" readonly>
					</div>
					<label class="col-sm-2 col-md-2 control-label">Tanggal <b class="text-red">*</b></label>
					<div class="col-sm-4 col-md-4">
						<input type="text" class="form-control tanggal" id="tgl_doc" name="tgl_doc" value="<?php echo (isset($data->tgl_doc) ? $data->tgl_doc: date("Y-m-d")); ?>" placeholder="Tanggal Dokumen" required>
					</div>
				</div>
				<div class="form-group ">
					<label class="col-sm-2 col-md-2 control-label">Keterangan <b class="text-red">*</b></label>
					<div class="col-sm-4 col-md-4">
						<input type="text" class="form-control" id="informasi" name="informasi" value="<?php echo (isset($data->informasi) ? $data->informasi: ""); ?>" placeholder="Keterangan" required>					
					</div>
					<label class='col-sm-2 col-md-2 control-label'><b>Department</b></label>
					<div class='col-sm-4 col-md-4'>
					 <?php
						$deptid=(isset($data->departement)?$data->departement:$dept);
						echo form_dropdown('departement',$combodept,$deptid,array('id'=>'departement','class'=>'form-control','required'=>'required'));
					 ?>
					<?php
					if(isset($data->st_reject)){
						if($data->st_reject!=''){
							echo '
							  <div class="alert alert-danger alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<h4><i class="icon fa fa-ban"></i> Alasan Penolakan!</h4>
								'.$data->st_reject.'
							  </div>';
						}
					}
					?>	
					</div>
				</div>
				<div class="form-group ">
					<label class='col-sm-2 col-md-2 control-label'><b>No SO</b></label>
					<div class='col-sm-4 col-md-4'>
						<select id='no_so' name='no_so' class='form-control input-sm select2' style='min-width:200px;'>
							<option value=''>Select Sales Order</option>
							<?php
								$no_so=(isset($data->no_so)?$data->no_so:'');
								foreach($combo_so AS $val => $valx){
									$selected='';
									if($valx['so_number']==$no_so) $selected=' selected';
									echo "<option value='".$valx['so_number']."'".$selected.">".strtoupper($valx['so_number'].' - '.$valx['project'])."</option>";
								}
							?> 
						</select>
					</div>
				</div>

				<div class="">
				<h4>Transfer ke</h4>
				<div class="form-group ">
					<label class="col-md-1 control-label">Bank</label>
					<div class="col-md-2">
						<input type="text" class="form-control" id="bank_id" name="bank_id" value="<?php echo (isset($data->bank_id) ? $data->bank_id: $bank_id); ?>" placeholder="Bank">
					</div>
					<label class="col-md-2 control-label">Nomor Rekening</label>
					<div class="col-md-2">
						<input type="text" class="form-control" id="accnumber" name="accnumber" value="<?php echo (isset($data->accnumber) ? $data->accnumber: $accnumber); ?>" placeholder="Nomor Rekening">
					</div>
					<label class="col-md-2 control-label">Nama Rekening</label>
					<div class="col-md-3">
						<input type="text" class="form-control" id="accname" name="accname" value="<?php echo (isset($data->accname) ? $data->accname: $accname); ?>" placeholder="Nama Pemilik Rekening">
					</div>
				</div>
			</div>
			<div class="table-responsive">
			<table class="table table-bordered table-striped table-condensed" width="100%">
				<thead>
					<tr>
					<th width="5" scope="col" class="column-primary">#</th>
					<th scope="col" width="250">Jenis dan<br /> Tanggal</th>
					<th scope="col" width="250">Barang/Jasa <br />&Keterangan</th>
					<th scope="col" width=150 nowrap>Jumlah</th>
					<th scope="col" width=200 nowrap>Harga Satuan</th>
					<th scope="col" width="200">Expense</th>
					<th scope="col" width="200">Kasbon</th>
					<th scope="col" width="50">Bon Bukti</th>
					<th scope="col" class="column-primary"><div class="pull-right">
						<a class="btn btn-info btn-xs stsview" href="javascript:void(0)" title="Kasbon" onclick="add_kasbon()" id="add-kasbon"><i class="fa fa-user"></i> Kasbon</a><br />
						<a class="btn btn-success btn-xs stsview" href="javascript:void(0)" title="Tambah" onclick="add_detail()" id="add-material"><i class="fa fa-plus"></i> Tambah</a></div></th>
					</tr>
				</thead>
				<tbody id="detail_body">
				<?php $total=0; $idd=1;$grand_total=0;$total_expense=0;$total_kasbon=0;$sub_grand_total=0;
				if(!empty($data_detail)){
					foreach($data_detail AS $record){
					$tekskasbon="";
					if($record->id_kasbon!='') $tekskasbon=' readonly';?>
					<tr id='tr1_<?=$idd?>' class='delAll <?=($record->id_kasbon!=''?'kasbonrow':'')?>'>
						<td data-header="#">
						<input type='hidden' name='id_kasbon[]' id='id_kasbon_<?=$idd?>' value='<?=$record->id_kasbon;?>'>
						<input type="hidden" name="filename[]" id="filename_<?=$idd?>" value="<?=$record->doc_file;?>">
						<input type='hidden' name='kasbon_max[]' id='kasbon_max_<?=$idd?>' value='<?=$record->kasbon_max;?>'>
						<input type="hidden" name="detail_id[]" id="raw_id_<?=$idd?>" value="<?=$idd;?>" class="dtlloop"><?=$idd;?></td>
						<td data-header="Jenis & Tanggal">
						<?php
						$required='required';
						if($tekskasbon=='') {
							echo form_dropdown('coa[]',$data_budget, (isset($record->coa)?$record->coa:''),array('id'=>'coa'.$idd,'required'=>'required','class'=>'form-control select2','style'=>'width:300px'));
						} else {
							echo '<input type="hidden" name="coa[]" id="coa'.$idd.'" value="'.$record->coa.'">';
							$required='readonly';
						}
						?>
						<input type="text" class="form-control <?=($tekskasbon==""?"tanggal":"")?> input-sm" name="tanggal[]" id="tanggal<?=$idd;?>" value="<?=$record->tanggal;?>" <?=$tekskasbon?> ></td>
						<td data-header="Barang / Jasa & Keterangan"><input type="text" class="form-control input-sm" name="deskripsi[]" id="deskripsi_<?=$idd;?>" value="<?=$record->deskripsi;?>" <?=$tekskasbon?> style="width:100px;" <?=$required?>>
						<input type="text" class="form-control input-sm" name="keterangan[]" id="keterangan_<?=$idd;?>" value="<?=$record->keterangan;?>" <?=$required?>></td>
						<td data-header="Qty"><input type="text" class="form-control divide input-sm" name="qty[]" id="qty_<?=$idd;?>" value="<?=$record->qty;?>" onblur="cektotal(<?=$idd;?>)" <?=$tekskasbon?> size="15" style="width:60px;"></td>
						<td data-header="Harga Satuan"><input type="text" class="form-control divide input-sm" name="harga[]" id="harga_<?=$idd;?>" value="<?=$record->harga;?>" onblur="cektotal(<?=$idd;?>)" <?=$tekskasbon?> style="width:100px;"></td>
						<td data-header="Expense"><input type="text" class="form-control divide subtotal input-sm" name="expense[]" id="expense_<?=$idd;?>" value="<?=($record->expense);?>" tabindex="-1" readonly style="width:100px;"></td>
						<td data-header="Kasbon"><input type="text" class="form-control divide subkasbon input-sm" name="kasbon[]" id="kasbon_<?=$idd;?>" value="<?=($record->kasbon);?>" style="width:100px;" <?=(($record->id_kasbon!='')?'onblur="cek_kasbon_max('.$idd.')"':'tabindex="-1" readonly')?>></td>
						<td data-header="Bon Bukti" width="50">
							<div class="upload-btn-wrapper">
							<?php if($tekskasbon=='') { ?>
							  <input type="file" name="doc_file_<?=$idd?>" id="doc_file_<?=$idd?>" />
							  <?php } ?>
							</div>
						<span class="pull-right"><?=($record->doc_file!=''?'<a href="'.base_url('assets/expense/'.$record->doc_file).'" download target="_blank"><i class="fa fa-download"></i></a>':'')?></span>
						</td>
						<th scope="row" align='center'><button type='button' class='btn btn-danger btn-xs stsview' data-toggle='tooltip' onClick='delDetail(<?=$idd?>)' title='Hapus data'><i class='fa fa-close'></i> Hapus</button></th>
					</tr>
					<?php
						if($record->doc_file!=''){
							 if(strpos($record->doc_file,'pdf',0)>1){
								$gambar.='<div class="col-md-12">
								<iframe src="'.base_url('assets/expense/'.$record->doc_file).'#toolbar=0&navpanes=0" title="PDF" style="width:600px; height:500px;" frameborder="0">
										 <a href="'.base_url('assets/expense/'.$record->doc_file).'">Download PDF</a>
								</iframe>
								<br />'.$record->no_doc.'</div>';
							 }else{
								$gambar.='<div class="col-md-4"><a href="'.base_url('assets/expense/'.$record->doc_file).'" target="_blank"><img src="'.base_url('assets/expense/'.$record->doc_file).'" class="img-responsive"></a><br />'.$record->no_doc.'</div>';
							 }
						}
						$total_expense=($total_expense+($record->expense));
						$total_kasbon=($total_kasbon+($record->kasbon));
						$idd++;
					}
				}
				$hidetransfer='hidden';
				$add_ppn_nilai=(isset($data->add_ppn_nilai) ? $data->add_ppn_nilai: 0);
				$add_pph_nilai=(isset($data->add_pph_nilai) ? $data->add_pph_nilai: 0);
				$add_ppn_coa='';
				if(isset($data->add_ppn_coa)){
					$add_ppn_coa='';
				}else{
					$data_ppn= $this->db->query("select kode_3 from ms_generate where tipe='ppn' and kode_3<>''")->row();
					$add_ppn_coa=$data_ppn->kode_3;
				}
				$grand_total=($grand_total+($total_expense+$add_ppn_nilai-$add_pph_nilai-$total_kasbon));
				$sub_grand_total=($sub_grand_total+($total_expense+$add_ppn_nilai-$add_pph_nilai));
				if($grand_total<0) $hidetransfer='';
				?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5" align=right>SUB TOTAL</td>
						<td><input type="text" class="form-control divide input-sm" id="total_expense" name="total_expense" value="<?=$total_expense?>" placeholder="Total Expense" tabindex="-1" readonly style='width:100px;'></td>
						<td><input type="text" class="form-control divide input-sm" id="total_kasbon" name="total_kasbon" value="<?=$total_kasbon?>" placeholder="Total Kasbon" tabindex="-1" readonly style='width:100px;'></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5" align=right>PPN</td>
						<td><input type="text" class="form-control divide input-sm" id="add_ppn_nilai" name="add_ppn_nilai" value="<?=$add_ppn_nilai?>" placeholder="PPN" onblur="cektotal(0)"></td>
						<td colspan=3><input type='hidden' name='add_ppn_coa' id='add_ppn_coa' value='<?=$add_ppn_coa?>'></td>
					</tr>
					<tr>
						<td colspan="5" align=right>PPH</td>
						<td><input type="text" class="form-control divide input-sm" id="add_pph_nilai" name="add_pph_nilai" value="<?=$add_pph_nilai?>" placeholder="PPH" onblur="cektotal(0)"></td>
						<td colspan=3>
							<?php
							$coa_pph='';
							echo form_dropdown('add_pph_coa',$combo_coa_pph,(isset($data->add_pph_coa) ? $data->add_pph_coa: ''),array('id'=>'add_pph_coa','class'=>'form-control'));
							?>
						</td>
					</tr>
					<tr>
						<td colspan="5" align=right>SALDO</td>
						<td><input type="text" class="form-control divide input-sm" id="sub_grand_total" name="sub_grand_total" value="<?=$sub_grand_total?>" placeholder="Sub Total Expense" tabindex="-1" readonly></td>
						<td></td>
						<td><input type="text" class="form-control divide input-sm" id="grand_total" name="grand_total" value="<?=$grand_total?>" placeholder="Grand Total" tabindex="-1" readonly></td>
						<td></td>
					</tr>
					<tr>
					<td colspan="9" id="transfer-area" class="<?=$hidetransfer?>">
						<div class="col-md-3">
							<input type="hidden" name="transferfile" id="transferfile" value="<?=(isset($data->transfer_file)?$data->transfer_file:'');?>">
							Bukti transfer : <input type='file'  name='transfer_file'> <?=(isset($data->transfer_file)?'<a href="'.base_url('assets/expense/'.$data->transfer_file).'">'.$data->transfer_file.'</a>':'')?>
						</div>
						<div class="col-md-4">
							Pilih Bank : <br />
							<?php
							echo form_dropdown('transfer_coa_bank',$data_coa, (isset($data->transfer_coa_bank)?$data->transfer_coa_bank:''),array('id'=>'transfer_coa_bank','class'=>'form-control select2','style'=>'width:90%'));?>
						</div>
						<div class="col-md-2">
							Tanggal Transfer : <input type="text" class="form-control tanggal input-sm" name="transfer_tanggal" id="transfer_tanggal" value="<?=(isset($data->transfer_tanggal)?$data->transfer_tanggal:'');?>"></div>
						<div class="col-md-3">
							Nilai Transfer : <input type="text" class="form-control divide input-sm" name="transfer_jumlah" id="transfer_jumlah" value="<?=(isset($data->transfer_jumlah)?$data->transfer_jumlah:'');?>">
						</div>
					</td>
					</tr>
				</tfoot>
			</table>
			</div>
			<div class="box-footer">
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<?php
						$urlback='';
						if(isset($data)){
							if($data->status==0){
								if($stsview=='approval'){
									$urlback='list_expense_approval';
									echo '<a class="btn btn-warning btn-sm" onclick="data_approve()"><i class="fa fa-save">&nbsp;</i> Approve</a> ';
									echo ' <a class="btn btn-danger btn-sm" onclick="data_reject()"><i class="fa fa-ban">&nbsp;</i> Reject</a>';
								}
							}
						}
						?>
						<button type="submit" name="save" class="btn btn-success btn-sm stsview" id="submit"><i class="fa fa-save">&nbsp;</i>Simpan</button>
						<a class="btn btn-default btn-sm" onclick="window.location.reload();return false;"><i class="fa fa-reply">&nbsp;</i>Batal</a>
					</div>
				</div>
				<div class="row">
				<?=$gambar?>
				</div>
			</div>
		</div>
	</div>
</div>
<?= form_close() ?>
<?php
$datacombocoa="";
foreach($data_budget as $keys=>$val){
	$datacombocoa.="<option value='".$keys."'>".$val."</option>";
}
?>
<script src="<?= base_url('assets/js/number-divider.min.js')?>"></script>
<script type="text/javascript">
	var url_save = base_url+'expense/save/';
	var url_approve = base_url+'expense/approve/';
	var url_reject = base_url+'expense/reject/';
	var nomor=<?=$idd?>;
	$('.divide').divide();
	$('.select2').select2();
    $('#frm_data').on('submit', function(e){
        e.preventDefault();
		var errors="";
		var lops=0;
		if(parseFloat($("#grand_total").val())<0){
			if($("#transfer_coa_bank").val()=="" || $("#transfer_coa_bank").val()=="0") errors="Bank tidak boleh kosong";
			if($("#transfer_jumlah").val()=="" || $("#transfer_jumlah").val()=="0") errors="Nilai Transfer tidak boleh kosong";
			if(parseFloat($("#add_pph_nilai").val())>0) {
				if($("#add_pph_coa").val()=="" || $("#add_pph_coa").val()=="0") errors="COA PPH tidak boleh kosong";
			}
			if(parseFloat($("#grand_total").val())*(-1) != parseFloat($("#transfer_jumlah").val()))  errors="Nilai Transfer harus sama dengan total";
		}
		$('.dtlloop').each(function() {
			lops++;
			var iddtl= $(this).val();
			if($("#id_kasbon_"+iddtl).val()==""){
				if($("#filename_"+iddtl).val()=="") {
					if ($('#doc_file_'+iddtl).get(0).files.length === 0) {
						errors="Bon Bukti harus diupload";
					}
				}
				var nilaiexp=$("#expense_"+iddtl).val();
				if(parseFloat(nilaiexp)<0) errors="Expense harus lebih dari 0";
				if($("#harga_"+iddtl).val()=="" || $("#harga_"+iddtl).val()=="0") errors="Harga Satuan tidak boleh kosong";
				if($("#qty_"+iddtl).val()=="" || $("#qty_"+iddtl).val()=="0") errors="Jumlah tidak boleh kosong";
				if($("#coa_"+iddtl).val()=="" || $("#coa_"+iddtl).val()=="0") errors="Jenis tidak boleh kosong";
				
			}else{
				var nilaikasbon=$("#kasbon_"+iddtl).val();
				if(parseFloat(nilaikasbon)<0) errors="Kasbon harus lebih dari 0";
				if($("#kasbon_"+iddtl).val()=="" || $("#kasbon_"+iddtl).val()=="0") errors="Kasbon tidak boleh kosong";
			}
		});
		if(lops==0) errors="Detail harus diisi";		
		if($("#informasi").val()=="") errors="Keterangan tidak boleh kosong";
		if($("#tgl_doc").val()=="") errors="Tanggal Transaksi tidak boleh kosong";
		if(errors==""){

		swal({
		  title: "Anda Yakin?",
		  text: "Data Akan Disimpan!",
		  type: "info",
		  showCancelButton: true,
		  confirmButtonText: "Ya, simpan!",
		  cancelButtonText: "Tidak!",
		  closeOnConfirm: false,
		  closeOnCancel: true
		},
		function(isConfirm){
		  if (isConfirm) {
			if(Number($("#grand_total").val())>=0) {
				$("#transfer_jumlah").val('0');
				$("transfer_tanggal").val('');
			}

			var formdata = new FormData($('#frm_data')[0]);
			$.ajax({
				url: url_save,
				dataType : "json",
				type: 'POST',
				data: formdata,
				processData	: false,
				contentType	: false,
				success: function(msg){
					if(msg['save']=='1'){
						swal({
							title: "Sukses!",
							text: "Data Berhasil Di Simpan",
							type: "success",
							timer: 1500,
							showConfirmButton: false
						});
						window.location.reload();
					} else {
						swal({
							title: "Gagal!",
							text: "Data Gagal Di Simpan",
							type: "error",
							timer: 1500,
							showConfirmButton: false
						});
					};
					console.log(msg);
				},
				error: function(msg){
					swal({
						title: "Gagal!",
						text: "Ajax Data Gagal Di Proses",
						type: "error",
						timer: 1500,
						showConfirmButton: false
					});
					console.log(msg);
				}
			});
		  }
		});

//			data_save();
		}else{
			swal(errors);
			return false;
		}
    });
	<?php if(isset($stsview)){
		if($stsview=='view'){
			?>
			$(".stsview").addClass("hidden");
			$("#frm_data :input").prop("disabled", true);
			<?php
		}
		if($stsview=='approval'){
			?>
			$(".stsview").addClass("hidden");
			$("#frm_data :input").prop("disabled", true);
			<?php
		}
	}?>
	$(function () {
		$(".tanggal").datepicker({
			dateFormat: 'yy-mm-dd'
		});
	});

	function cektotal(id){
		if(id>0){
			var sqty = $("#qty_"+id).val();
			var pref = $("#harga_"+id).val();
			var subtotal = (parseFloat(sqty)*parseFloat(pref));
			$("#expense_"+id).val(subtotal);
		}
		var sum = 0;
		$('.subtotal').each(function() {
			sum += Number($(this).val());
		});
		$("#total_expense").val(sum);
		var sumkasbon = 0;
		$('.subkasbon').each(function() {
			sumkasbon += Number($(this).val());
		});
		$("#total_kasbon").val(sumkasbon);

		var ppn=$("#add_ppn_nilai").val();
		var pph=$("#add_pph_nilai").val();
		$("#sub_grand_total").val(Number(sum)+parseFloat(ppn)-parseFloat(pph));		
		$("#grand_total").val(Number(sum)+parseFloat(ppn)-parseFloat(pph)-Number(sumkasbon));
		if(Number($("#grand_total").val())<0) {
			$("#transfer-area").removeClass("hidden");
		}else{
			$("#transfer-area").addClass("hidden");
		}
	}
	function add_kasbon(){
		$('.kasbonrow').remove();
		var nama = $("#nama").val();
		var departement = $("#departement").val();
		$.ajax({
			url: base_url +'expense/get_kasbon/'+nama+'/'+departement+'/<?= (isset($data->no_doc) ? $data->no_doc: ""); ?>',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				var i;
				for(i=0; i<data.length; i++){
					var nilai_kasbon=(parseFloat(data[i].jumlah_kasbon)-parseFloat(data[i].jumlah_expense));
					var Rows	 = 	"<tr id='tr1_"+nomor+"' class='delAll kasbonrow'>";
						Rows	+= 		"<td data-header='#'><input type='hidden' name='id_kasbon[]' id='id_kasbon_"+nomor+"' value='"+data[i].no_doc+"'>";
						Rows	+=			"<input type='hidden' name='detail_id[]' id='raw_id_"+nomor+"' value='"+nomor+"' class='dtlloop'>";
						Rows	+= 		"<input type='hidden' name='filename[]' id='filename_"+nomor+"' value='"+data[i].doc_file+"'></td>";
						Rows	+= 		"<td data-header='Tanggal'>";
						Rows	+=			"<input type='text' class='form-control tanggal input-sm' name='tanggal[]' id='tanggal_"+nomor+"' tabindex='-1' readonly value='"+data[i].tgl_doc+"' />";
						Rows	+= 		"<input type='hidden' name='coa[]' id='coa_"+nomor+"' value='"+data[i].coa+"' />";
						Rows	+= 		"</td>";
						Rows	+= 		"<td data-header='Barang / Jasa & Keteranga'>";
						Rows	+=			"<input type='text' class='form-control input-sm' name='deskripsi[]' id='deskripsi_"+nomor+"' value='"+data[i].keperluan+"' tabindex='-1' readonly />";
						Rows	+=			"<input type='text' class='form-control input-sm' name='keterangan[]' id='keterangan_"+nomor+"' />";
						Rows	+= 		"</td>";
						Rows	+= 		"<td data-header='Qty'>";
						Rows	+=			"<input type='text' class='form-control divide input-sm' name='qty[]' value='1' id='qty_"+nomor+"' tabindex='-1' readonly />";
						Rows	+= 		"</td>";
						Rows	+= 		"<td data-header='Harga Satuan'>";
						Rows	+=			"<input type='text' class='form-control divide input-sm' name='harga[]' value='0' id='harga_"+nomor+"' tabindex='-1' readonly style='width:100px;' />";
						Rows	+= 		"</td>";
						Rows	+= 		"<td data-header='Expense'>";
						Rows	+=			"<input type='text' class='form-control divide input-sm subtotal hidden' name='expense[]' value='0' id='expense_"+nomor+"' tabindex='-1' readonly />";
						Rows	+= 		"</td>";
						Rows	+= 		"<td data-header='Kasbon'>";
						Rows	+=			"<input type='text' class='form-control divide input-sm subkasbon' name='kasbon[]' value='"+nilai_kasbon+"' id='kasbon_"+nomor+"' onblur='cek_kasbon_max("+nomor+")' style='width:100px;' /><input type='hidden' name='kasbon_max[]' id='kasbon_max_"+nomor+"' value='"+nilai_kasbon+"'>";
						Rows	+= 		"</td>";
						Rows	+= 		"<td data-header='Bon Bukti'>";
						Rows	+=			"<input type='file'  name='doc_file_"+nomor+"' id='doc_file_"+nomor+"' class='hidden' />";
						Rows	+=		"<span class='pull-right'>";
						if(data[i].doc_file!=''){
							Rows	+=		"<a href='<?=base_url('assets/expense/')?>"+data[i].doc_file+"' download target='_blank'><i class='fa fa-download'></i></a></span>";
						}
						Rows	+= 		"</td>";
						Rows	+= 		"<th scope='row' align='center'>";
						Rows 	+=			"<button type='button' class='btn btn-danger btn-xs' data-toggle='tooltip' onClick='delDetail("+nomor+")' title='Hapus data'><i class='fa fa-close'></i> Hapus</button>";
						Rows	+= 		"</th>";
						Rows	+= 	"</tr>";
						nomor++;
					$('#detail_body').append(Rows);
					cektotal(nomor-1);
				}
				$(".divide").divide();
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
	function cek_kasbon_max(id){
		var kasbon=$("#kasbon_"+id).val();
		var kasbon_max=$("#kasbon_max_"+id).val();
		if(parseFloat(kasbon)>parseFloat(kasbon_max)){
			alert("Kasbon Maksimal "+kasbon_max);
			$("#kasbon_"+id).val(kasbon_max);
		}
		cektotal(id);
	}
	function add_detail(){
		var Rows	 = 	"<tr id='tr1_"+nomor+"' class='delAll'>";
			Rows	+= 		"<td data-header='#'><input type='hidden' name='id_kasbon[]' id='id_kasbon_"+nomor+"' value=''>";
			Rows	+=		"<input type='hidden' name='detail_id[]' id='raw_id_"+nomor+"' value='"+nomor+"' class='dtlloop'>";
			Rows	+= 		"<input type='hidden' name='filename[]' id='filename_"+nomor+"' value=''><input type='hidden' name='kasbon_max[]' id='kasbon_max_"+nomor+"' value='0'></td>";
			Rows	+= 		"<td data-header='Jenis & Tanggal'>";
			Rows	+= 		"<select name='coa[]' id='coa_"+nomor+"' required='required' class='form-control select2' style='width:300px'><?=$datacombocoa?></select>";
			Rows	+=			"<input type='text' class='form-control tanggal input-sm' placeholder='Tanggal' name='tanggal[]' id='tanggal_"+nomor+"' />";
			Rows	+= 		"</td>";
			Rows	+= 		"<td data-header='Barang / Jasa & Keterangan'>";
			Rows	+=			"<input type='text' class='form-control input-sm' placeholder='Barang/Jasa' name='deskripsi[]' id='deskripsi_"+nomor+"' style='width:100px;' required />";
			Rows	+=			"<input type='text' class='form-control input-sm' placeholder='Keterangan' name='keterangan[]' id='keterangan_"+nomor+"' required />";
			Rows	+= 		"</td>";
			Rows	+= 		"<td data-header='Qty'>";
			Rows	+=			"<input type='text' class='form-control divide input-sm' name='qty[]' value='0' id='qty_"+nomor+"' onblur='cektotal("+nomor+")' style='width:60px;' required />";
			Rows	+= 		"</td>";
			Rows	+= 		"<td data-header='Harga Satuan'>";
			Rows	+=			"<input type='text' class='form-control divide input-sm' name='harga[]' value='0' id='harga_"+nomor+"' onblur='cektotal("+nomor+")' style='width:100px;' required />";
			Rows	+= 		"</td>";
			Rows	+= 		"<td data-header='Expense'>";
			Rows	+=			"<input type='text' class='form-control divide input-sm subtotal' name='expense[]' value='0' id='expense_"+nomor+"' tabindex='-1' readonly style='width:100px;' />";
			Rows	+= 		"</td>";
			Rows	+= 		"<td data-header='Kasbon'>";
			Rows	+=			"<input type='text' class='form-control divide input-sm subkasbon hidden' name='kasbon[]' value='0' id='kasbon_"+nomor+"' tabindex='-1' readonly />";
			Rows	+= 		"</td>";
			Rows	+= 		"<td data-header='Bon Bukti'>";
			Rows	+=			"<input type='file'  name='doc_file_"+nomor+"' id='doc_file_"+nomor+"' required />";
			Rows	+= 		"</td>";
			Rows	+= 		"<th align='center' th scope='row'>";
			Rows 	+=			"<button type='button' class='btn btn-danger btn-xs' data-toggle='tooltip' onClick='delDetail("+nomor+")' title='Hapus data'><i class='fa fa-close'></i> Hapus</button>";
			Rows	+= 		"</th>";
			Rows	+= 	"</tr>";
			$("#tanggal_"+nomor).focus();
			nomor++;
		$('#detail_body').append(Rows);
		$(".tanggal").datepicker({
			dateFormat: 'yy-mm-dd'
		});
		$('.select2').select2();
		$(".divide").divide();
	}

	function delDetail(row){
		$('#tr1_'+row).remove();
		cektotal(row);
	}

	function data_approve(){
		swal({
		  title: "Anda Yakin?",
		  text: "Data Akan Disetujui!",
		  type: "info",
		  showCancelButton: true,
		  confirmButtonText: "Ya, setuju!",
		  cancelButtonText: "Tidak!",
		  closeOnConfirm: false,
		  closeOnCancel: true
		},
		function(isConfirm){
		  if (isConfirm) {
			id=$("#id").val();
			$.ajax({
				url: url_approve+id,
				dataType : "json",
				type: 'POST',
				success: function(msg){
					if(msg['save']=='1'){
						swal({
							title: "Sukses!",
							text: "Data Berhasil Di Setujui",
							type: "success",
							timer: 1500,
							showConfirmButton: false
						});
						window.location.reload();
					} else {
						swal({
							title: "Gagal!",
							text: "Data Gagal Di Setujui",
							type: "error",
							timer: 1500,
							showConfirmButton: false
						});
					};
					console.log(msg);
				},
				error: function(msg){
					swal({
						title: "Gagal!",
						text: "Ajax Data Gagal Di Proses",
						type: "error",
						timer: 1500,
						showConfirmButton: false
					});
					console.log(msg);
				}
			});
		  }
		});
	}

	function data_reject(){
		swal({
			title: "Perhatian",
			text: "Berikan alasan penolakan",
			type: "input",
			showCancelButton: true,
			closeOnConfirm: false,
			closeOnCancel: true },
			function(inputValue){
				 if (inputValue === false) return false;
				 if (inputValue === "") {
					swal.showInputError("Tuliskan alasan anda");
					return false
				}

				swal({
				  title: "Anda Yakin?",
				  text: "Data Akan Tolak!",
				  type: "warning",
				  showCancelButton: true,
				  confirmButtonText: "Ya, tolak!",
				  cancelButtonText: "Tidak!",
				  closeOnConfirm: false,
				  closeOnCancel: true
				},
				function(isConfirm){
				  if (isConfirm) {
					id=$("#id").val();
					$.ajax({
						url: url_reject,
						data: {'id':id,'reason':inputValue},
						dataType : "json",
						type: 'POST',
						success: function(msg){
							if(msg['save']=='1'){
								swal({
									title: "Sukses!",
									text: "Data Berhasil Di Tolak",
									type: "success",
									timer: 1500,
									showConfirmButton: false
								});
								window.location.reload();
							} else {
								swal({
									title: "Gagal!",
									text: "Data Gagal Di Tolak",
									type: "error",
									timer: 1500,
									showConfirmButton: false
								});
							};
							console.log(msg);
						},
						error: function(msg){
							swal({
								title: "Gagal!",
								text: "Ajax Data Gagal Di Proses",
								type: "error",
								timer: 1500,
								showConfirmButton: false
							});
							console.log(msg);
						}
					});
				  }
				});

			 });
	}

</script>
