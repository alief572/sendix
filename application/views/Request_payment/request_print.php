<style>
body{
	font-family: sans-serif;
}
table.garis {
	border-collapse: collapse;
	font-size: 0.9em;
	font-family: sans-serif;
}
</style>
<?php
$judul='Form Request Payment';
$nomorreq="";$tglreq="";
if(!empty($data_request)){
	$nomorreq=$data_request[0]->no_request;
	$tglreq=tgl_indo($data_request[0]->created_on);
}
?>
<table cellpadding=2 cellspacing=0 border=0 width=650>
<tr>
	<th colspan=9 height=50><?=$judul?></th>
</tr>
<tr>
	<td colspan=9 align=center>Nomor Request : <?=$nomorreq?><br />
	Tanggal Request : <?=$tglreq?><br /><br /></td>
</tr>
<tr>
	<td colspan=9>
	<table cellpadding=2 cellspacing=0 border=1 width=650 class="garis">
		<tr>
			<th width="5">#</th>
			<th>No Dokumen</th>
			<th>Request By</th>
			<th>Tanggal</th>
			<th>Keperluan</th>
			<th>Tipe</th>
			<th>Nilai Pengajuan</th>
			<th>Tanggal Pembayaran</th>
		</tr>
	<?php
	$i=0;
	if(!empty($data_request)){
		foreach($data_request AS $record){ $i++;?>
		<tr>
			<td><?=$i;?></td>
			<td><?= $record->no_doc ?></td>
			<td><?= $record->nama ?></td>
			<td><?= tgl_indo($record->tgl_doc) ?></td>
			<td><?= $record->keperluan ?></td>
			<td><?= strtoupper($record->tipe) ?></td>
			<td align=right><?= number_format($record->jumlah) ?></td>
			<td><?= tgl_indo($record->tanggal) ?></td>
		</tr>
		<?php
		}
	}
?>
	</table>
	</td>
</tr>
</table>
