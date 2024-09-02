<style>
body{
	font-family: sans-serif;
}
table.garis {
	border-collapse: collapse;
	font-size: 0.9em;
	font-family: sans-serif;
}
@media print {
    .pagebreak { page-break-before: always; } /* page-break-after works, as well */
}
</style>
<table cellpadding=2 cellspacing=0 border=0 width=650>
<tr>
	<th colspan=5>KASBON<br /><br /><br /></th>
</tr>
<tr>
	<td>No Dokumen</td>
	<td>: <?=$data->no_doc?></td>
	<td rowspan=7></td>
	<td>Tanggal</td>
	<td>: <?=tgl_indo($data->tgl_doc)?></td>
</tr>
<tr>
	<td>Keperluan</td>
	<td colspan=3>: <?=$data->keperluan?></td>
</tr>
<tr>
	<td>Jumlah Kasbon</td>
	<td>: <?=number_format($data->jumlah_kasbon)?></td>
	<td>Department</td>
	<td>: <?php echo ($combodept->nm_dept);?></td>
</tr>
<tr>
	<td>Transfer ke</td>
	<td>Bank <?=$data->bank_id?></td>
	<td>Nomor Rekening</td>
	<td><?php echo ($data->accnumber);?> - <?=$data->accname?></td>
</tr>
<tr>
	<td height=60></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr>
	<td colspan=2 align=center>Mengajukan</td>
	<td colspan=2 align=center>Mengetahui</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<?php
$mengajukan= $this->db->query("SELECT nm_lengkap FROM users a WHERE a.username='".$data->created_by."'")->row();
$mengetahui=$this->db->query("SELECT nm_lengkap FROM users a WHERE a.username='".$data->approved_by."'")->row();
?>
<tr height=120>
	<td colspan=2 align=center nowrap valign="bottom">
	<u>&nbsp; &nbsp; <?=(($mengajukan)?$mengajukan->nm_lengkap:' &nbsp; &nbsp;  &nbsp; &nbsp;  &nbsp; &nbsp; ')?> &nbsp; &nbsp; </u></td>
	<td colspan=2 align=center nowrap valign="bottom">
	<u>&nbsp; &nbsp; <?=(($mengetahui)?$mengetahui->nm_lengkap:' &nbsp; &nbsp;  &nbsp; &nbsp;  &nbsp; &nbsp; ')?> &nbsp; &nbsp; </u></td>
</tr>
</table>

<?php
if(isset($data)){
	echo '<div class="pagebreak"> </div>';
	if($data->doc_file!=''){
		 if(strpos($data->doc_file,'pdf',0)>1){
			 echo '<div class="col-md-12">
			<iframe src="'.base_url('assets/expense/'.$data->doc_file).'#toolbar=0&navpanes=0" title="PDF" style="width:600px; height:500px;" frameborder="0">
					 <a href="'.base_url('assets/expense/'.$data->doc_file).'">Download PDF</a>
			</iframe>
			<br />'.$data->no_doc.'</div>';
		 }else{
			echo '<div class="col-md-12"><a href="'.base_url('assets/expense/'.$data->doc_file).'" target="_blank"><img src="'.base_url('assets/expense/'.$data->doc_file).'" class="img-responsive"></a><br />'.$data->no_doc.'</div>';
		 }
	}
	if($data->doc_file_2!=''){
		 if(strpos($data->doc_file_2,'pdf',0)>1){
			 echo '<div class="col-md-12">
			<iframe src="'.base_url('assets/expense/'.$data->doc_file_2).'#toolbar=0&navpanes=0" title="PDF" style="width:600px; height:500px;" frameborder="0">
					 <a href="'.base_url('assets/expense/'.$data->doc_file_2).'">Download PDF</a>
			</iframe>
			<br />'.$data->no_doc.'</div>';
		 }else{
			echo '<div class="col-md-12"><a href="'.base_url('assets/expense/'.$data->doc_file_2).'" target="_blank"><img src="'.base_url('assets/expense/'.$data->doc_file_2).'" class="img-responsive"></a><br />'.$data->no_doc.'</div>';
		 }
	}
}
?>