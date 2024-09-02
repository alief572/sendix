<?php
$this->load->view('include/side_menu');
?>
<div id="alert_edit" class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<?=form_open($this->uri->uri_string(),array('id'=>'frm_data','name'=>'frm_data','role'=>'form','class'=>'form-horizontal'));?>
<div class="box">
	<div class="box-header">
		<h3 class="box-title"><?php echo $title;?></h3>
	</div>
	<div class="box-body">
		<div class="table-responsive">
		<table id="mytabledata" class="table table-bordered">
		<thead>
		<tr>
			<th width="5">#</th>
			<th class="exclass">No Dokumen</th>
			<th>Request By</th>
			<th class="exclass">Tanggal</th>
			<th>Keperluan</th>
			<th class="exclass">Tipe</th>
			<th>Nilai Pengajuan</th>
			<th class="exclass">Tgl Pembayaran</th>
			<th class="exclass">Bank</th>
			<th class="exclass">Keterangan</th>
			<th class="exclass">Payment</th>
			<th class="exclass">Administrasi</th>
		</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($results)){
			$numb=0; foreach($results AS $record){ $numb++; ?>
		<tr>
		    <td><?= $numb; ?></td>
			<td class="exclass"><?= $record->no_doc ?></td>
			<td><?= $record->nama ?></td>
			<td class="exclass"><?= $record->tgl_doc ?></td>
			<td><?= $record->keperluan ?></td>
			<td class="exclass"><?= $record->tipe ?></td>			
			<td><?= number_format($record->jumlah) ?></td>
			<td class="exclass"><?=$record->pay_on?></td>
			<td class="exclass"><?=$record->namabank?></td>
			<td class="exclass"><?=$record->keterangan?></td>
			<td class="exclass"><?=number_format($record->bank_nilai)?></td>
			<td class="exclass"><?=number_format($record->bank_admin)?></td>
		</tr>
		<?php
			}
		}  ?>
		</tbody>
		</table>
		</div>
	</div>
	<!-- /.box-body -->
</div>
<?= form_close() ?>
<?php $this->load->view('include/footer'); ?>
<script src="<?= base_url('assets/js/number-divider.min.js')?>"></script>
<script type="text/javascript">
	$("#mytabledata").DataTable({
    });
</script>
