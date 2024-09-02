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
				<div class="hidden">
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
			<table class="table table-bordered table-striped" width="100%">
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
					<th scope="col" class="column-primary"></th>
					</tr>
				</thead>
				<tbody id="detail_body">
				<?php $total=0; $idd=1;$grand_total=0;$total_expense=0;$total_kasbon=0;
				if(!empty($data_detail)){
					foreach($data_detail AS $record){
					$tekskasbon="";
					if($record->id_kasbon!='') $tekskasbon=' readonly';?>
					<tr id='tr1_<?=$idd?>' class='delAll <?=($record->id_kasbon!=''?'kasbonrow':'')?>'>
						<td data-header="#">
						<input type='hidden' name='id_kasbon[]' id='id_kasbon_<?=$idd?>' value='<?=$record->id_kasbon;?>'>
						<input type="hidden" name="filename[]" id="filename_<?=$idd?>" value="<?=$record->doc_file;?>">
						<input type="hidden" name="detail_id[]" id="raw_id_<?=$idd?>" value="<?=$idd;?>" class="dtlloop"><?=$idd;?></td>
						<td data-header="Jenis & Tanggal">
						<?php
						if($tekskasbon=='') {
							echo form_dropdown('coa[]',$data_budget, (isset($record->coa)?$record->coa:''),array('id'=>'coa'.$idd,'required'=>'required','class'=>'form-control select2','style'=>'width:300px'));
						} else {
							echo '<input type="hidden" name="coa[]" id="coa'.$idd.'" value="'.$record->coa.'">';
						}
						?>
						<input type="text" class="form-control tanggal input-sm" name="tanggal[]" id="tanggal<?=$idd;?>" value="<?=$record->tanggal;?>" <?=$tekskasbon?> ></td>
						<td data-header="Barang / Jasa & Keterangan"><input type="text" class="form-control input-sm" name="deskripsi[]" id="deskripsi_<?=$idd;?>" value="<?=$record->deskripsi;?>" <?=$tekskasbon?> style="width:100px;">
						<input type="text" class="form-control input-sm" name="keterangan[]" id="keterangan_<?=$idd;?>" value="<?=$record->keterangan;?>"></td>
						<td data-header="Qty"><input type="text" class="form-control divide input-sm" name="qty[]" id="qty_<?=$idd;?>" value="<?=$record->qty;?>" onblur="cektotal(<?=$idd;?>)" <?=$tekskasbon?> size="15" style="width:60px;"></td>
						<td data-header="Harga Satuan"><input type="text" class="form-control divide input-sm" name="harga[]" id="harga_<?=$idd;?>" value="<?=$record->harga;?>" onblur="cektotal(<?=$idd;?>)" <?=$tekskasbon?> style="width:100px;"></td>
						<td data-header="Expense"><input type="text" class="form-control divide subtotal input-sm" name="expense[]" id="expense_<?=$idd;?>" value="<?=($record->expense);?>" tabindex="-1" readonly style="width:100px;"></td>
						<td data-header="Kasbon"><input type="text" class="form-control divide subkasbon input-sm" name="kasbon[]" id="kasbon_<?=$idd;?>" value="<?=($record->kasbon);?>" tabindex="-1" readonly style="width:100px;"></td>
						<td data-header="Bon Bukti" width="50">
							<div class="upload-btn-wrapper">
							<?php if($tekskasbon=='') { ?>
							  
							  <?php } ?>
							</div>
						<span class="pull-right"><?=($record->doc_file!=''?'<a href="'.base_url('assets/expense/'.$record->doc_file).'" download target="_blank"><i class="fa fa-download"></i></a>':'')?></span>
						</td>
						<th scope="row" align='center'></th>
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
					$grand_total=($grand_total+($total_expense-$total_kasbon));
				}
				$hidetransfer='hidden';
				if($grand_total<0) $hidetransfer='';
				?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5" align=right>TOTAL</td>
						<td><input type="text" class="form-control divide input-sm" id="total_expense" name="total_expense" value="<?=$total_expense?>" placeholder="Total Expense" tabindex="-1" readonly style='width:100px;'></td>
						<td><input type="text" class="form-control divide input-sm" id="total_kasbon" name="total_kasbon" value="<?=$total_kasbon?>" placeholder="Total Kasbon" tabindex="-1" readonly style='width:100px;'></td>
						<td align=right colspan=2><div class="row"><div class="col-md-2">Saldo</div><div class="col-md-10"><input type="text" class="form-control divide input-sm" id="grand_total" name="grand_total" value="<?=$grand_total?>" placeholder="Grand Total" tabindex="-1" readonly></div></div></td>
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
							if($data->status==1){
								if($stsview=='review'){
									$urlback='list_expense_approval';
									echo '<a class="btn btn-warning btn-sm" onclick="data_approve()"><i class="fa fa-save">&nbsp;</i> Approve</a> ';
									echo ' <a class="btn btn-danger btn-sm" onclick="data_reject()"><i class="fa fa-ban">&nbsp;</i> Reject</a>';
								}
							}
						}
						?>
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
	var url_approve = base_url+'expense/return_confirm/';
	var url_reject = base_url+'expense/reject/';
	$('.divide').divide();
	$('.select2').select2();
	<?php if(isset($stsview)){
		if($stsview=='review'){
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
