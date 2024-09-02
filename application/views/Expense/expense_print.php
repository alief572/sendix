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
$judul='Form Permintaan Expense';
if($data->pettycash!='') $judul='Form Expense Report Petty Cash';
$dtdepartment= $this->db->query("SELECT * FROM department a WHERE a.id='".$data->departement."'")->row();
$nm_dept='';
if(!empty($dtdepartment)) $nm_dept=$dtdepartment->nm_dept;
?>
<table cellpadding=2 cellspacing=0 border=0 width=650>
<tr>
	<th colspan=9 height=50><?=$judul?></th>
</tr>
<tr>
	<th colspan=9>
	<table>
		<tr>
			<td width="50%">No Dokumen : <?=$data->no_doc?></td>
			<td width="50%" align=right>Departmen : <?=$nm_dept?></td>
		</tr>
		<tr>
			<td colspan=2>Transfer ke Bank <?=$data->bank_id?>. Nomor Rekening <?php echo ($data->accnumber);?> - <?=$data->accname?></td>
		</tr>
	</table>
	</th>
</tr>
<tr>
	<td colspan=9>
	<table cellpadding=2 cellspacing=0 border=1 width=650 class="garis">
	<tr>
		<th nowrap>No</th>
		<th nowrap>Tgl Pengajuan</th>
		<th nowrap>Nama Barang</th>
		<th nowrap>Spesifikasi</th>
		<th nowrap>Jml</th>
		<th nowrap>Tgl Dibutuhkan</th>
		<th nowrap>Biaya Satuan</th>
		<th nowrap>Total Biaya</th>
		<th nowrap>Kasbon</th>
	</tr>
	<?php $total_expense=0; $total_tol=0;$total_parkir=0;$total_kasbon=0; $idd=1; $total_km=0; $grand_total=0;$i=0;$gambar="";
	if(!empty($data_detail)){
		foreach($data_detail AS $record){ $i++;?>
		<tr>
			<td><?=$i;?></td>
			<td><?=tgl_indo($data->tgl_doc);?></td>
			<td><?=$record->deskripsi;?></td>
			<td><?=$record->keterangan;?></td>
			<td align="right"><?=number_format($record->qty);?></td>
			<td><?=tgl_indo($record->tanggal);?></td>
			<td align="right"><?=number_format($record->harga);?></td>
			<td align="right"><?=number_format($record->expense);?></td>
			<td align="right"><?=number_format($record->kasbon);?></td>
		</tr>
		<?php
			if($record->doc_file!=''){
				 if(strpos($record->doc_file,'pdf',0)>1){
				 }else{
					$gambar.='<img src="'.base_url("assets/expense/".$record->doc_file).'" width="500"><br />';
				 }
			}
			$total_expense=($total_expense+($record->expense));
			$total_kasbon=($total_kasbon+($record->kasbon));
			$idd++;
		}
	}
	$grand_total=($total_expense);
	for($x=0;$x<(5-$i);$x++){
	echo '
		<tr>
			<td>&nbsp;</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	';
	}
	$text="Saldo";$sisakurang="";
	if ($total_expense>$total_kasbon){
//		$text="(Kurang)";
		$sisakurang=number_format($total_expense-$total_kasbon);
	}else{
//		$text="Sisa Lebih";
		$sisakurang="( ".number_format($total_expense-$total_kasbon)." )";
	}
	?>
	<tr>
		<td colspan=7 align=center><strong>Total</strong></td>
		<td align="right"><?=number_format($grand_total);?></td>
		<td align="right"><?=number_format($total_kasbon);?></td>
	</tr>
	<?php if($total_kasbon>0) {?>
	<tr>
		<td colspan=7 align=center><strong><?=$text?></strong></td>
		<td colspan=2 align="right"><?=$sisakurang;?></td>
	</tr>
	<?php } ?>
	</table><br />
	</td>
</tr>

<?php
$mengajukan= $this->db->query("SELECT nm_lengkap FROM users a WHERE a.username='".$data->created_by."'")->row();
$mengetahui=$this->db->query("SELECT nm_lengkap FROM users a WHERE a.username='".$data->approved_by."'")->row();
?>

<tr>
	<td colspan=2 align=center>Mengajukan</td>
	<td></td>
	<td align=center colspan=2>Mengetahui</td>
	<td></td>
	<td></td>
	<td align=center colspan=2>Menyetujui</td>
</tr>
<tr>
	<td colspan=9>&nbsp;</td>
</tr>
<tr height=120>
	<td colspan=2 align=center nowrap valign="bottom"><u>&nbsp; &nbsp; <?=(($mengajukan)?$mengajukan->nm_lengkap:' &nbsp; &nbsp;  &nbsp; &nbsp;  &nbsp; &nbsp; ')?> &nbsp; &nbsp; </u><br /> &nbsp;</td>
	<td width=20>&nbsp;</td>
	<td colspan=2 align=center nowrap valign="bottom"><u>&nbsp; &nbsp; <?=(($mengetahui)?$mengetahui->nm_lengkap:' &nbsp; &nbsp;  &nbsp; &nbsp;  &nbsp; &nbsp; ')?> &nbsp; &nbsp; </u><br /> &nbsp;</td>
	<td width=20>&nbsp;</td>
	<td width=20>&nbsp;</td>
	<td colspan=2 align=center nowrap valign="bottom"><u>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </u><br /><br /></td>
</tr>
</table>
<!--<em>STM/FR02/09/01/00</em>-->

<br />
<?=$gambar?>