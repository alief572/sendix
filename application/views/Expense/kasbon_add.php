<link rel="stylesheet" href="<?=base_url()?>assets/plugins/select2/select2.css">
<script src="<?=base_url()?>assets/plugins/select2/select2.full.min.js"></script>
<?php
$dept='';$bank_id='';$accnumber='';$accname='';
$data_session = $this->session->userdata;
$dateTime = date('Y-m-d H:i:s');
$UserName = $data_session['ORI_User']['id_user'];
$dept = $data_session['ORI_User']['department_id'];

?>
<?=form_open($this->uri->uri_string(),array('id'=>'frm_data','name'=>'frm_data','role'=>'form','class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
<input type="hidden" id="id" name="id" value="<?php echo set_value('id', isset($data->id) ? $data->id : ''); ?>">
<input type="hidden" id="nama" name="nama" value="<?php echo set_value('nama', isset($data->nama) ? $data->nama : $UserName); ?>">
<div class="tab-content">
	<div class="tab-pane active">
		<div class="box box-primary">
			<div class="box-body">
				<div class="form-group ">
					<label class="col-sm-2 control-label">No Dokumen</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="no_doc" name="no_doc" value="<?php echo (isset($data->no_doc) ? $data->no_doc: ""); ?>" placeholder="Automatic" readonly>
					</div>
					<label class="col-sm-2 control-label">Tanggal <b class="text-red">*</b></label>
					<div class="col-sm-4">
						<input type="text" class="form-control tanggal" id="tgl_doc" name="tgl_doc" value="<?php echo (isset($data->tgl_doc) ? $data->tgl_doc: date("Y-m-d")); ?>" placeholder="Tanggal Dokumen" required>
					</div>
				</div>
				<div class="form-group ">
					<label class="col-sm-2 control-label">Keperluan</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="keperluan" name="keperluan" value="<?php echo (isset($data->keperluan) ? $data->keperluan: ''); ?>" placeholder="Keperluan" required>
					</div>
					<label class="col-sm-2 control-label">Jumlah Kasbon</label>
					<div class="col-sm-4">
						<input type="text" class="form-control divide" id="jumlah_kasbon" name="jumlah_kasbon" value="<?php echo (isset($data->jumlah_kasbon) ? $data->jumlah_kasbon: '0'); ?>" placeholder="0">
					</div>
				</div>
				<div class="form-group ">
					<label class='col-sm-2 col-md-2 control-label'><b>Department</b></label>
					<div class='col-sm-4 col-md-4'>
					 <?php
						$deptid=(isset($data->departement)?$data->departement:$dept);
						echo form_dropdown('departement',$combodept,$deptid,array('id'=>'departement','class'=>'form-control','required'=>'required'));
					 ?>
					</div>
					<div class="col-sm-1 col-md-1"></div>
					<div class="col-sm-5 col-md-5"><?php
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
					?></div>
				</div>
				<div class="form-group ">
					<label class='col-sm-2 col-md-2 control-label'><b>COA Kasbon</b></label>
					<div class='col-sm-4 col-md-4'>
					 <?php
						$coa=(isset($data->coa)?$data->coa:'');
						echo form_dropdown('coa',$data_coa_kasbon,$coa,array('id'=>'coa','class'=>'form-control select2','required'=>'required'));
					 ?>
					</div>

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
				<div class="form-group ">
					<label class="col-sm-2 control-label">Dokumen 1</label>
					<div class="col-sm-4">
						<input type="hidden" name="filename" id="filename" value="<?=(isset($data->doc_file)?$data->doc_file:'');?>">
						<input type="file" name="doc_file" id="doc_file">
						<span class="pull-right"><?php
						if(isset($data->doc_file)){
							echo ($data->doc_file!=''?'<a href="'.base_url('assets/expense/'.$data->doc_file).'" download target="_blank"><i class="fa fa-download"></i></a>':'');
						}
						?>
						</span>
					</div>
					<label class="col-sm-2 control-label">Dokumen 2</label>
					<div class="col-sm-4">
						<input type="hidden" name="filename2" id="filename2" value="<?=(isset($data->doc_file_2)?$data->doc_file_2:'');?>">
						<input type="file" name="doc_file_2" id="doc_file_2">
						<span class="pull-right"><?php
						if(isset($data->doc_file_2)){
							echo ($data->doc_file_2!=''?'<a href="'.base_url('assets/expense/'.$data->doc_file_2).'" download target="_blank"><i class="fa fa-download"></i></a>':'');
						}
						?>
						</span>
					</div>
				</div>
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
			<div class="box-footer">
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<?php
						if (isset($data)) {
							if ($data->status==0 && $stsview=='approve'){
								echo '<a href="#" name="Approve" class="btn btn-primary btn-sm" id="approve" onclick="data_approve()"><i class="fa fa-check-square-o">&nbsp;</i> Approve</a>';
								echo ' <a class="btn btn-danger btn-sm" onclick="data_reject()"><i class="fa fa-ban">&nbsp;</i> Reject</a>';
							}
						}
						if( $stsview=='') { ?>
						<button type="submit" name="save" class="btn btn-success btn-sm stsview" id="submit"><i class="fa fa-save">&nbsp;</i>Simpan</button>
						<?php } ?>
						<a class="btn btn-warning btn-sm" onclick="location.reload();return false;"><i class="fa fa-reply">&nbsp;</i>Batal</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= form_close() ?>
<script src="<?= base_url('assets/js/number-divider.min.js')?>"></script>
<script type="text/javascript">
	<?php 
	if(isset($stsview)){
		if($stsview=='view' || $stsview=='approve'){
			?>
			$(".stsview").addClass("hidden");
			$("#frm_data :input").prop("disabled", true);
			<?php
		}
	}
	?>

	var url_save = base_url+'expense/kasbon_save/';
	var url_approve = base_url+'expense/kasbon_approve/';
	var url_reject = base_url+'expense/kasbon_reject/';
	$('.divide').divide();
	$('.select2').select2();
	$('.tanggal').datepicker({dateFormat: 'yy-mm-dd',});
    $('#frm_data').on('submit', function(e){
        e.preventDefault();
		var errors="";
		if($("#tgl_doc").val()=="") errors="Tanggal Transaksi tidak boleh kosong";
		if($("#coa").val()=="" || $("#coa").val()=="0") errors="COA Kasbon tidak boleh kosong";
		if($("#jumlah_kasbon").val()=="" || $("#jumlah_kasbon").val()=="0") errors="Jumlah Kasbon tidak boleh kosong";
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
						cancel();
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

	function data_approve(){
		swal({
		  title: "Anda Yakin?",
		  text: "Setujui Data Akan!",
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
							text: "Data Gagal Di Update",
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
