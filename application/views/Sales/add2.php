<?php
$this->load->view('include/side_menu');

//Customer
$ArrCust = array();
foreach($cust AS $val => $valx){
	$ArrCust[$valx['id_customer']] = strtoupper($valx['nm_customer']);
}
$ArrCust[0]	= 'Select An Customer';

$no_proposal 	= (!empty($header[0]->no_proposal))?$header[0]->no_proposal:'';
$id_customer 	= (!empty($header[0]->id_customer))?$header[0]->id_customer:'0';
$project 		= (!empty($header[0]->project))?$header[0]->project:'';
$note 			= (!empty($header[0]->note))?$header[0]->note:'';
$ref_cust 		= (!empty($header[0]->ref_cust))?$header[0]->ref_cust:'';
$ruang_lingkup 	= (!empty($header[0]->ruang_lingkup))?$header[0]->ruang_lingkup:'';
$validity 		= (!empty($header[0]->validity))?$header[0]->validity:'';
$payment 		= (!empty($header[0]->payment))?$header[0]->payment:'';
$harga_per_pcs 	= (!empty($header[0]->harga_per_pcs))?$header[0]->harga_per_pcs:'';
$jumlah 		= (!empty($header[0]->jumlah))?$header[0]->jumlah:'';
$kapan 			= (!empty($header[0]->kapan))?$header[0]->kapan:'';
$alamat 		= (!empty($header[0]->alamat))?$header[0]->alamat:'';
$syarat_cust 	= (!empty($header[0]->syarat_cust))?$header[0]->syarat_cust:'';


$unit 			= (!empty($header[0]->unit))?$header[0]->unit:'';
$inspeksi 		= (!empty($header[0]->inspeksi))?$header[0]->inspeksi:'';
$keb_joint 		= (!empty($header[0]->keb_joint))?$header[0]->keb_joint:'';
$test 			= (!empty($header[0]->test))?$header[0]->test:'';
$sertifikat 	= (!empty($header[0]->sertifikat))?$header[0]->sertifikat:'';
$syarat 		= (!empty($header[0]->syarat))?$header[0]->syarat:'';
$alat_berat 	= (!empty($header[0]->alat_berat))?$header[0]->alat_berat:'';
$scaffolding 	= (!empty($header[0]->scaffolding))?$header[0]->scaffolding:'';
$electricity 	= (!empty($header[0]->electricity))?$header[0]->electricity:'';

$informasi 			= (!empty($header[0]->informasi))?json_decode($header[0]->informasi):array();
$jenis_test 		= (!empty($header[0]->jenis_test))?json_decode($header[0]->jenis_test):array();
$app 				= (!empty($header[0]->app))?json_decode($header[0]->app):array();
$nm_product 		= (!empty($header[0]->nm_product))?json_decode($header[0]->nm_product):array();
$jenis_sertifikat 	= (!empty($header[0]->jenis_sertifikat))?json_decode($header[0]->jenis_sertifikat):array();

?>
<form action="#" method="POST" id="form_work" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?php echo $title;?></h3>
			<?php
				echo form_input(array('type'=>'hidden','name'=>'no_ipp','class'=>'form-control input-md'),$this->uri->segment(3));
			?>
		</div>
		<!-- /.box-header -->
		<div class="box-body table-responsive">
			<table class='table table-sm' cellpadding='0' cellspacing='0'>
				<tr hidden>
					<td width='1%' class='text-bold'>1.</td>
					<td width='35%' class='text-bold' colspan='2'>Nomor Proposal</td>
					<td width='1%' class='text-bold'>:</td>
					<td width='32%' colspan='2'><input type="text" name='no_proposal' class='form-control input-sm' placeholder='Nomor Proposal' value='<?=$no_proposal;?>'></td>
					<td width='32%' colspan='2'></td>
				</tr>
				<tr>
					<td class='text-bold'>1.</td>
					<td class='text-bold' colspan='2'>Nama Customer</td>
					<td class='text-bold'>:</td>
					<td colspan='2'>
						<?php
							echo form_dropdown('id_customer', $ArrCust, $id_customer, array('id'=>'id_customer','name'=>'id_customer','class'=>'form-control input-md clSelect'));
						?>
					</td>
					<td width='32%' colspan='2'><textarea name='note' class='form-control input-sm' rows='2' placeholder='Note'><?=$note;?></textarea></td>
				</tr>
				<tr>
					<td class='text-bold'>2.</td>
					<td class='text-bold' colspan='2'>Nama Project</td>
					<td class='text-bold'>:</td>
					<td colspan='4'><input type="text" name='project' class='form-control input-sm' placeholder='Nama Project' value='<?=$project;?>'></td>
				</tr>
				<tr>
					<td class='text-bold'>3.</td>
					<td class='text-bold' colspan='2'>Nama Produk</td>
					<td class='text-bold'>:</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='nm_product[]' type="checkbox" id="product1" value="instalasi pipe" <?=(in_array("instalasi pipe", $nm_product))?'checked':'';?>>
							<label class="form-check-label" for="product1">INSTALASI PIPE</label>
						</div>
					</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='nm_product[]' type="checkbox" id="product2" value="instalasi tank" <?=(in_array("instalasi tank", $nm_product))?'checked':'';?>>
							<label class="form-check-label" for="product2">INSTALASI TANK</label>
						</div>
					</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='nm_product[]' type="checkbox" id="product3" value="instalasi custom" <?=(in_array("instalasi custom", $nm_product))?'checked':'';?>>
							<label class="form-check-label" for="product3">INSTALASI CUSTOM</label>
						</div>
					</td>
					<td></td>
				</tr>
				<tr>
					<td class='text-bold'>4.</td>
					<td class='text-bold' colspan='2'>Referensi Customer / Project</td>
					<td class='text-bold'>:</td>
					<td colspan='4'><input type="text" name='ref_cust' class='form-control input-sm' placeholder='Referensi Customer / Project' value='<?=$ref_cust;?>'></td>
				</tr>
				<tr>
					<td class='text-bold'>5.</td>
					<td class='text-bold' colspan='2'>Ruang Lingkup Project</td>
					<td class='text-bold'></td>
					<td colspan='4'><input type="hidden" name='ruang_lingkup' class='form-control input-sm' placeholder='Ruang Lingkup Project' value='<?=$ruang_lingkup;?>'></td>
				</tr>
				<tr>
					<td class='text-bold'></td>
					<td class='text-bold'>a.</td>
					<td class='text-bold' colspan='6'>Perkiraan Proyek</td>
				</tr>
				<tr>
					<td class='text-bold'></td>
					<td class='text-bold'></td>
					<td colspan='6'>Budget Customer, jika ada  (informasi dari customer/ perkiraan harga jual)</td>
				</tr>
				<tr>
					<td class='text-bold'></td>
					<td class='text-bold'></td>
					<td>Harga Per pcs</td>
					<td class='text-bold'>:</td>
					<td colspan='2'><input type="text" name='harga_per_pcs' class='form-control input-sm' value='<?=$harga_per_pcs;?>'></td>
				</tr>
				<tr>
					<td class='text-bold'></td>
					<td class='text-bold'></td>
					<td>Jumlah</td>
					<td class='text-bold'>:</td>
					<td colspan='2'><input type="text" name='jumlah' class='form-control input-sm' value='<?=$jumlah;?>'></td>
					<td colspan='2'>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="unit" id="inlineRadio1" value="unit" <?=($unit == 'unit')?'checked':'';?>>&nbsp;Unit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input class="form-check-input" type="radio" name="unit" id="inlineRadio2" value="set" <?=($unit == 'set')?'checked':'';?>>&nbsp;Set
						</div>
					</td>
				</tr>
				<tr>
					<td class='text-bold'></td>
					<td class='text-bold'></td>
					<td>Inspeksi Pekerjaan</td>
					<td class='text-bold'>:</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='inspeksi' type="radio" id="inspeksi2" value="ya" <?=($inspeksi == 'ya')?'checked':'';?>>&nbsp;Ya
						</div>
					</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='inspeksi' type="radio" id="inspeksi1" value="tidak" <?=($inspeksi == 'tidak')?'checked':'';?>>&nbsp;Tidak/Belum
							<span style='float:right;'>Kapan :</span>
						</div>
					</td>
					<td colspan='2'><input type="text" name='kapan' class='form-control input-sm' value='<?=$kapan;?>'></td>
				</tr>
				<tr>
					<td class='text-bold'></td>
					<td class='text-bold'></td>
					<td>Alamat Pengiriman</td>
					<td class='text-bold'>:</td>
					<td colspan='4'><input type="text" name='alamat' class='form-control input-sm' placeholder='Alamat Pengiriman' value='<?=$alamat;?>'></td>
				</tr>
				<tr>
					<td class='text-bold'></td>
					<td class='text-bold'>b.</td>
					<td class='text-bold'>Informasi / dokumen dari customer</td>
					<td class='text-bold'>:</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='informasi[]' type="checkbox" id="info1" value="ada" <?=(in_array("ada", $informasi))?'checked':'';?>>&nbsp;Ada
						</div>
					</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='informasi[]' type="checkbox" id="info2" value="tidak ada"  <?=(in_array("tidak ada", $informasi))?'checked':'';?>>&nbsp;Tidak Ada
						</div>
					</td>
					<td width='16%'>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='informasi[]' type="checkbox" id="info3" value="sample" <?=(in_array("sample", $informasi))?'checked':'';?>>&nbsp;Sample
						</div>
					</td>
					<td width='16%'>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='informasi[]' type="checkbox" id="info4" value="drawing" <?=(in_array("drawing", $informasi))?'checked':'';?>>&nbsp;Drawing
						</div>
					</td>
				</tr>
				<tr>
					<td class='text-bold'></td>
					<td class='text-bold'></td>
					<td class='text-bold'></td>
					<td class='text-bold'></td>
					<td colspan='4'>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='informasi[]' type="checkbox" id="info5" value="lainnya" <?=(in_array("lainnya", $informasi))?'checked':'';?>>&nbsp;Spesifikasi teknis lainnya (lihat attachment / email)
						</div>
					</td>
				</tr>
				<tr>
					<td class='text-bold'></td>
					<td class='text-bold'>c.</td>
					<td class='text-bold'>Kebutuhan Field Joint/material</td>
					<td class='text-bold'>:</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='keb_joint' type="radio" id="info1" value="ada" <?=($keb_joint == 'ada')?'checked':'';?>>&nbsp;Ya
						</div>
					</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='keb_joint' type="radio" id="info2" value="tidak ada" <?=($keb_joint == 'tidak ada')?'checked':'';?>>&nbsp;Tidak
						</div>
					</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td class='text-bold'></td>
					<td class='text-bold'>d.</td>
					<td class='text-bold'>Jenis Instalasi</td>
					<td class='text-bold'>:</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='app[]' type="checkbox" id="jnsinst1" value="under ground" <?=(in_array("under ground", $app))?'checked':'';?> >&nbsp;Under Ground
						</div>
					</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='app[]' type="checkbox" id="jnsinst2" value="above ground" <?=(in_array("above ground", $app))?'checked':'';?> >&nbsp;Above Ground
						</div>
					</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td class='text-bold'></td>
					<td class='text-bold'>e.</td>
					<td class='text-bold'>Test</td>
					<td class='text-bold'>:</td>
					<td>
						<div class="form-check-inline">
							<input  name='test' type="radio" id="jnstest1" value="ya" <?=($test == 'ya')?'checked':'';?>>&nbsp;Ya
							<span style='float:right;'><b><i>Jenis Test :</i></b></span>
						</div>
					</td>
					<td>
						<div class="form-check-inline">
							<input  name='jenis_test[]' type="checkbox" value="Hydrotest" <?=(in_array("Hydrotest", $jenis_test))?'checked':'';?>>&nbsp;<i>Hydrotest</i>
						</div>
					</td>
					<td>
						<div class="form-check-inline">
							<input  name='jenis_test[]' type="checkbox" value="Leakage Test" <?=(in_array("Leakage Test", $jenis_test))?'checked':'';?>>&nbsp;<i>Leakage Test</i>
						</div>
					</td>
					<td></td>
				</tr>
				<tr>
					<td class='text-bold'></td>
					<td class='text-bold'></td>
					<td class='text-bold'></td>
					<td class='text-bold'></td>
					<td>
						<div class="form-check-inline">
							<input class="form-check-input" name='test' type="radio" value="tidak" <?=($test == 'tidak')?'checked':'';?>>&nbsp;Tidak tau
						</div>
					</td>
					<td>
						<div class="form-check-inline">
							<input  name='jenis_test[]' type="checkbox" value="Pressure Test" <?=(in_array("Pressure Test", $jenis_test))?'checked':'';?>>&nbsp;<i>Pressure Test</i>
						</div>
					</td>
					<td>
						<div class="form-check-inline">
							<input  name='jenis_test[]' type="checkbox" value="Mechanical Test" <?=(in_array("Mechanical Test", $jenis_test))?'checked':'';?>>&nbsp;<i>Mechanical Test</i>
						</div>
					</td>
					<td></td>
				</tr>
				<tr>
					<td class='text-bold'></td>
					<td class='text-bold'>f.</td>
					<td class='text-bold'>Sertifikat</td>
					<td class='text-bold'>:</td>
					<td>
						<div class="form-check-inline">
							<input  name='sertifikat' type="radio" value="ya" <?=($sertifikat == 'ya')?'checked':'';?>>&nbsp;Ya
							<span style='float:right;'><b><i>Jenis Sertifikat :</i></b></span>
						</div>
					</td>
					<td>
						<div class="form-check-inline">
							<input  name='jenis_sertifikat[]' type="checkbox" value="COC" <?=(in_array("COC", $jenis_sertifikat))?'checked':'';?>>&nbsp;<i>COC</i>
						</div>
					</td>
					<td>
						<div class="form-check-inline">
							<input  name='jenis_sertifikat[]' type="checkbox" value="COM" <?=(in_array("COM", $jenis_sertifikat))?'checked':'';?>>&nbsp;<i>COM</i>
						</div>
					</td>
					<td>
						<div class="form-check-inline">
							<input  name='jenis_sertifikat[]' type="checkbox" value="MDR" <?=(in_array("MDR", $jenis_sertifikat))?'checked':'';?>>&nbsp;<i>MDR</i>
						</div>
					</td>
				</tr>
				<tr>
					<td class='text-bold'></td>
					<td class='text-bold'></td>
					<td class='text-bold'></td>
					<td class='text-bold'></td>
					<td>
						<div class="form-check-inline">
							<input class="form-check-input" name='sertifikat' type="radio" value="tidak" <?=($sertifikat == 'tidak')?'checked':'';?>>&nbsp;Tidak tau
						</div>
					</td>
					<td>
						<div class="form-check-inline">
							<input  name='jenis_sertifikat[]' type="checkbox" value="COA" <?=(in_array("COA", $jenis_sertifikat))?'checked':'';?>>&nbsp;<i>COA</i>
						</div>
					</td>
					<td>
						<div class="form-check-inline">
							<input  name='jenis_sertifikat[]' type="checkbox" value="COO" <?=(in_array("COO", $jenis_sertifikat))?'checked':'';?>>&nbsp;<i>COO</i>
						</div>
					</td>
					<td></td>
				</tr>
				<tr>
					<td class='text-bold'></td>
					<td class='text-bold'>g.</td>
					<td class='text-bold'>Persyaratan khusus dari Customer</td>
					<td class='text-bold'>:</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='syarat' type="radio" value="ada" <?=($syarat == 'ada')?'checked':'';?>>&nbsp;Ya
						</div>
					</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='syarat' type="radio" value="tidak ada" <?=($syarat == 'tidak')?'checked':'';?>>&nbsp;Tidak Ada
							<span style='float:right;'>Yaitu :</span>
						</div>
					</td>
					<td colspan='2'><input type="text" name='syarat_cust' class='form-control input-sm' value='<?=$syarat_cust;?>'></td>
				</tr>
				<tr>
					<td class='text-bold'>6.</td>
					<td class='text-bold' colspan='2'>Alat Berat</td>
					<td class='text-bold'>:</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='alat_berat' type="radio" value="customer"  <?=($alat_berat == 'customer')?'checked':'';?>>&nbsp;Oleh Customer
						</div>
					</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='alat_berat' type="radio" value="ori" <?=($alat_berat == 'ori')?'checked':'';?>>&nbsp;Oleh ORI
						</div>
					</td>
					<td colspan='2'></td>
				</tr>
				<tr>
					<td class='text-bold'>7.</td>
					<td class='text-bold' colspan='2'>Scaffolding</td>
					<td class='text-bold'>:</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='scaffolding' type="radio" value="customer"  <?=($scaffolding == 'customer')?'checked':'';?>>&nbsp;Oleh Customer
						</div>
					</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='scaffolding' type="radio" value="ori" <?=($scaffolding == 'ori')?'checked':'';?>>&nbsp;Oleh ORI
						</div>
					</td>
					<td colspan='2'></td>
				</tr>
				<tr>
					<td class='text-bold'>8.</td>
					<td class='text-bold' colspan='2'>Electricity</td>
					<td class='text-bold'>:</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='electricity' type="radio" value="customer"  <?=($electricity == 'customer')?'checked':'';?>>&nbsp;Oleh Customer
						</div>
					</td>
					<td>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name='electricity' type="radio" value="ori" <?=($electricity == 'ori')?'checked':'';?>>&nbsp;Oleh ORI
						</div>
					</td>
					<td colspan='2'></td>
				</tr>
				<tr>
					<td class='text-bold'>9.</td>
					<td class='text-bold' colspan='2'>Validity & Guarantee</td>
					<td class='text-bold'>:</td>
					<td colspan='2'><input type="text" name='validity' class='form-control input-sm' placeholder='Validity & Guarantee' value='<?=$validity;?>'></td>
					<td colspan='2'></td>
				</tr>
				<tr>
					<td class='text-bold'>10.</td>
					<td class='text-bold' colspan='2'>Payment Term</td>
					<td class='text-bold'>:</td>
					<td colspan='2'><input type="text" name='payment' class='form-control input-sm' placeholder='Payment Term' value='<?=$payment;?>'></td>
					<td colspan='2'></td>
				</tr>
			</table>
		</div>
		<div class='box-footer'>
			<?php
			if($tanda <> 'detail'){
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-primary','value'=>'save','content'=>'Save','id'=>'saved'));
			}
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','value'=>'back','content'=>'Back','id'=>'back'));
			?>
		</div>
		<!-- /.box-body -->
	 </div>
  <!-- /.box -->
</form>

<?php $this->load->view('include/footer'); ?>
<script>
	$(document).ready(function(){
		$('#saved').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
			var id_customer		= $('#id_customer').val();
			var project			= $('#project').val();

			if(id_customer=='0'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Customer Name, please input first ...',
				  type	: "warning"
				});
				$('#saved').prop('disabled',false);
				return false;
			}

			if(project == ''){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Project, please input first ...',
				  type	: "warning"
				});
				$('#saved').prop('disabled',false);
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
						var baseurl		= base_url + active_controller +'/add2';
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
										  timer	: 3000
										});
									window.location.href = base_url + active_controller;
								}
								else if(data.status == 2 || data.status == 3){
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 3000
									});
								}

								$('#saved').prop('disabled',false);
							},
							error: function() {

								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',
								  type				: "warning",
								  timer				: 3000
								});
								$('#saved').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#saved').prop('disabled',false);
					return false;
				  }
			});
		});

		$('#back').click(function(e){
			// window.location.href = base_url + active_controller;
			window.history.back();
		});
	});
</script>