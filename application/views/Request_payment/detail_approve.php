<?php
$this->load->view('include/side_menu');
?>
<?php
if ($type == 'expense') {
	$keterangan = $header->informasi;
} elseif ($type == 'kasbon') {
	$keterangan = $header->keperluan;
} elseif ($type == 'transportasi') {
	$keterangan = 'Transportasi';
}
?>
<div id="alert_edit" class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<?= form_open($this->uri->uri_string(), array('id' => 'frm_data', 'name' => 'frm_data', 'role' => 'form', 'class' => 'form-horizontal')); ?>
<div class="box">
	<div class="box-body">
		<div class="row">
			<input type="hidden" id="id" name="id" value="<?= $header->id; ?>">
			<input type="hidden" id="tipe" name="tipe" value="<?= $type; ?>">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-4 text-right" style="margin-bottom: 1rem;"><label for="" class="control-label">Nomor Dokumen </label></div>
					<div class="col-md-6" style="margin-bottom: 1rem;">
						<input type="text" id="no_doc" name="no_doc" class="form-control" readonly value="<?= $header->no_doc; ?>">
					</div>

					<div class="col-md-4 text-right" style="margin-bottom: 1rem;"><label for="" class="control-label">Keterangan</label></div>
					<div class="col-md-6" style="margin-bottom: 1rem;">
						<input type="text" name="informasi" class="form-control" readonly value="<?= ($keterangan) ?: ''; ?>">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-4 text-right"><label for="" class="control-label">Tgl.</label></div>
					<div class="col-md-6">
						<input type="text" name="date" class="form-control" readonly value="<?= $header->tgl_doc; ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table id="mytabledata" class="table table-bordered">
				<thead>
					<tr>
						<th width="5">#</th>
						<th class="exclass">Perkiraan</th>
						<th class="exclass">Barang/Jasa</th>
						<th>Tgl.</th>
						<th class="exclass">Jumlah</th>
						<th class="exclass">Harga</th>
						<th class="exclass">Total Harga</th>
						<th class="exclass">Bon Bukti</th>
						<th class="exclass">
							<div class="checkbox">
								<label><input class="master_check" type="checkbox">Semua</label>
							</div>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$approved=0;
					if (!empty($details)) {
						$coadata=array();
						$records=$this->db->query("select * from ".DBACC.".coa_master where no_perkiraan")->result();
						foreach($records AS $rows){
							$coadata[$rows->no_perkiraan]=$rows->nama;
						}
						$n = $gTotal = 0;
						foreach ($details as $dtl) : $n++;
							if ($type == 'expense') :
								$gTotal += $dtl->total_harga; 
								if($dtl->status != '0') $approved++;
								?>
								<tr>
									<td><?= $n; ?></td>
									<td><?= $dtl->coa; ?>-<?=$coadata[$dtl->coa]?></td>
									<td><?= $dtl->deskripsi; ?></td>
									<td><?= $dtl->tanggal; ?></td>
									<td><?= $dtl->qty; ?></td>
									<td class="text-right"><?= number_format($dtl->harga); ?></td>
									<td class="text-right"><?= number_format($dtl->total_harga); ?></td>
									<td class="text-center"><a href="<?= base_url('assets/expense/') . $dtl->doc_file; ?>" target="_blank"><i class="fa fa-download"></i></a></td>
									<td>
										<?php if ($dtl->status == '0') : ?>
											<input type="checkbox" value="<?= $dtl->id; ?>" name="item[<?= $n; ?>][id]" class="check_item" id="check_<?= $dtl->id; ?>">
										<?php elseif ($dtl->status == '1') : ?>
											<label for="" class="label bg-maroon">Process</label>
										<?php elseif ($dtl->status == '2') : ?>
											<label for="" class="label bg-green">PAID</label>
										<?php else : ?>
											<label for="" class="label bg-gray"><span class="text-muted">Undefine</span></label>
										<?php endif; ?>
									</td>
								</tr>
							<?php elseif ($type == 'kasbon') :
								$gTotal += $dtl->jumlah_kasbon;
								if($dtl->status != '2') $approved++;
							?>
								<tr>
									<td><?= $n; ?></td>
									<td><?= $dtl->coa; ?>-<?=$coadata[$dtl->coa]?></td>
									<td><?= $dtl->keperluan; ?></td>
									<td><?= $dtl->tgl_doc; ?></td>
									<td>1</td>
									<td class="text-right"><?= number_format($dtl->jumlah_kasbon); ?></td>
									<td class="text-right"><?= number_format($dtl->jumlah_kasbon); ?></td>
									<td class="text-center"><a href="<?= base_url('assets/expense/') . $dtl->doc_file; ?>" target="_blank"><i class="fa fa-download"></i></a></td>
									<td>
										<?php if ($dtl->status == '2') : ?>
											<input type="checkbox" value="<?= $dtl->id; ?>" name="item[<?= $n; ?>][id]" class="check_item" id="check_<?= $dtl->id; ?>">
										<?php elseif ($dtl->status == '3') : ?>
											<label for="" class="label bg-maroon">Process</label>
										<?php elseif ($dtl->status == '4') : ?>
											<label for="" class="label bg-green">PAID</label>
										<?php else : ?>
											<label for="" class="label bg-gray"><span class="text-muted">Undefine</span></label>
										<?php endif; ?>
									</td>
								</tr>
							<?php elseif ($type == 'transportasi') :
								$gTotal += $dtl->jumlah_kasbon;
								if($dtl->status != '1') $approved++;
								?>
								<tr>
									<td><?= $n; ?></td>
									<td></td>
									<td><?= $dtl->keperluan; ?></td>
									<td><?= $dtl->tgl_doc; ?></td>
									<td>1</td>
									<td class="text-right"><?= number_format($dtl->jumlah_kasbon); ?></td>
									<td class="text-right"><?= number_format($dtl->jumlah_kasbon); ?></td>
									<td class="text-center"><a href="<?= base_url('assets/expense/') . $dtl->doc_file; ?>" target="_blank"><i class="fa fa-download"></i></a></td>
									<td>
										<?php if ($dtl->status == '1') : ?>
											<input type="checkbox" value="<?= $dtl->id; ?>" name="item[<?= $n; ?>][id]" class="check_item" id="check_<?= $dtl->id; ?>">
										<?php elseif ($dtl->status == '2') : ?>
											<label for="" class="label bg-maroon">Process</label>
										<?php elseif ($dtl->status == '3') : ?>
											<label for="" class="label bg-green">PAID</label>
										<?php else : ?>
											<label for="" class="label bg-gray"><span class="text-muted">Undefine</span></label>
										<?php endif; ?>
									</td>
								</tr>
					<?php endif;
						endforeach;
					}  ?>
				</tbody>
				<tfoot>
					<tr class="bg-blue">
						<th colspan="6" class="text-right">Total</th>
						<th class="text-right"><?= number_format($gTotal); ?></th>
						<th colspan="2" class="text-center"></th>
					</tr>
					<tr>
						<th colspan="7"><a href="<?= base_url($this->uri->segment(1) . '/list_approve'); ?>" class="btn btn-default btn-sm"><i class="fa fa-reply">&nbsp;</i>Back</a></th>
						<th class="text-center">
						<?php if($approved==0) { ?>
						<a class="btn btn-danger btn-sm" onclick="data_reject()"><i class="fa fa-ban">&nbsp;</i> Reject</a>
						<?php } ?>
						</th>
						<th class="text-center"><button type="button" class="btn btn-success btn-sm text-right" id="process"><i class="fa fa-save">&nbsp;</i>Approve</button></th>
					</tr>					
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?= form_close() ?>
<?php $this->load->view('include/footer'); ?>
<script src="<?= base_url('assets/js/number-divider.min.js') ?>"></script>
<script type="text/javascript">
	var url_save = base_url + 'request_payment/save_approval';
	var url_reject = base_url + 'request_payment/save_reject';
	$('.divide').divide();

	$(document).on('click', '.master_check', function() {
		const checked = $(this).is(':checked');
		$('.check_item').prop('checked', false)
		if (checked) {
			$('.check_item').prop('checked', true)
		}
	})

	//Save
	$(document).on('click', '#process', function(e) {
		var errors = "";
		if ($("#bank_coa").val() == "0") errors = "Bank tidak boleh kosong";
		const check = $('.check_item').is(':checked')
		if (check) {
			swal({
					title: "Anda Yakin?",
					text: "Item Akan Di Approve!",
					type: "info",
					showCancelButton: true,
					confirmButtonText: "Ya, Approve!",
					cancelButtonText: "Tidak!",
					closeOnConfirm: false,
					closeOnCancel: true
				},
				function(isConfirm) {
					if (isConfirm) {
						var formdata = new FormData($('#frm_data')[0]);
						$.ajax({
							url: url_save,
							dataType: "json",
							type: 'POST',
							data: formdata,
							processData: false,
							contentType: false,
							success: function(msg) {
								if (msg['save'] == '1') {
									swal({
										title: "Sukses!",
										text: "Data Berhasil Di Approve",
										type: "success",
										timer: 1500,
										showConfirmButton: false
									});
									location.href = base_url + active_controller + '/list_approve';
								} else {
									swal({
										title: "Gagal!",
										text: "Data Gagal Di Approve",
										type: "error",
										timer: 1500,
										showConfirmButton: false
									});
								};
								console.log(msg);
							},
							error: function(msg) {
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
		} else {
			swal("Warning!", "Pilih item yang akan di Approve!", "warning", 3000);
			return false;
		}
	});
	$("#btnxls").click(function() {
		$("#mytabledata").table2excel({
			exclude: ".exclass",
			name: "Weekly Budget",
			filename: "WeeklyBudget.xls", // do include extension
			preserveColors: false // set to true if you want background colors and font colors preserved
		});
	});
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
				  text: "Dokumen ini Akan Tolak!",
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
					tipe=$("#tipe").val();
					no_doc=$("#no_doc").val();
					$.ajax({
						url: url_reject,
						data: {'id':id,'tipe':tipe,'no_doc':no_doc,'reason':inputValue},
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
								location.href = base_url + active_controller + '/list_approve';
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