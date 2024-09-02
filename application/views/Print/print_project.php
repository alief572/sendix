<?php
	$sroot 		= $_SERVER['DOCUMENT_ROOT'];
	include $sroot."/ori_instalasi/application/libraries/MPDF57/mpdf.php";
	$mpdf=new mPDF('utf-8','A4');
	// $mpdf=new mPDF('utf-8','A4-L');

	set_time_limit(0);
	ini_set('memory_limit','1024M');

	//Beginning Buffer to save PHP variables and HTML tags
	ob_start();
	date_default_timezone_set('Asia/Jakarta');
	$today = date('l, d F Y [H:i:s]');

	$arrInclude 	= (!empty($header[0]->include_check))?json_decode($header[0]->include_check):array();
	$arrExclude 	= (!empty($header[0]->exclude_check))?json_decode($header[0]->exclude_check):array();
	$arrIncludeTxt 	= (!empty($header[0]->include_text))?json_decode($header[0]->include_text):array();
	$arrExcludeTxt 	= (!empty($header[0]->exclude_text))?json_decode($header[0]->exclude_text):array();
	?>

	<table class="gridtable2" border='1' width='100%' cellpadding='2'>
		<tr>
			<td align='center'><b>PT  ORI POLYTEC COMPOSITE</b></td>
		</tr>
		<tr>
			<td align='center'><b><h2>KEBUTUHAN PROJECT <?=$header[0]->no_ipp;?></h2></b></td>
		</tr>
	</table>
    <br>
    <table class="gridtable2" border='0' width='100%' >
		<tr>
			<td width='20%'>Project Name</td>
			<td width='1%'>:</td>
			<td><?= strtoupper($header[0]->project_name); ?></td>
		</tr>
		<tr>
			<td>Project Location</td>
			<td>:</td>
			<td><?= strtoupper($header[0]->location); ?></td>
		</tr>
        <tr>
			<td>Region</td>
			<td>:</td>
			<td><?= strtoupper($header[0]->region); ?></td>
		</tr>
        <tr>
			<td>Tipe Instalasi</td>
			<td>:</td>
			<td><?= strtoupper($header[0]->tipe); ?></td>
		</tr>
		<tr>
			<td>Time Total /day</td>
			<td>:</td>
			<td><?= $header[0]->total_time; ?></td>
		</tr>
	</table>
    <br>
	<p>BQ Project</p>
    <table class='gridtable' border='1' width='100%' cellpadding='2'>
        <thead id='head_table_bq'>
            <tr class='bg-purple'>
                <th class="text-center" width='16%'>DN (mm)</th>
                <th class="text-center" width='16%'>DN (inch)</th>
                <th class="text-center" width='16%'>Qty</th>
                <th class="text-center" width='16%'>Satuan</th>
                <th class="text-center">Total Time (Hours)</th>
                <th class="text-center" width='16%'>Dia/Inch</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($detail_bq as $key => $value) {
                    echo "<tr>";
                        echo "<td align='center'>".$value['diameter']."</td>";
                        echo "<td align='center'>".$value['diameter2']."</td>";
                        echo "<td align='center'>".$value['qty']."</td>";
                        echo "<td align='center'>".strtoupper($value['satuan_code'])."</td>";
                        echo "<td align='center'>".$value['cycletime']."</td>";
                        echo "<td align='center'>".$value['day_in']."</td>";
                    echo "</tr>";
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan='2' style='vertical-align:middle; text-align:center;'><b> TOTAL</b></td>
                <td align='center'>
                    <span align='center'><b><?=$header[0]->bq_qty;?></b></b>
                </td>
                <td align='center'>-</td>
                <td align='center'>
                    <span align='center'><b><?=$header[0]->bq_ct;?></b></b>
                </td>
                <td align='center'>
                    <span align='center'><b><?=$header[0]->day_in_total;?></b></b>
                </td>
            </tr>
            <tr>
                <td colspan='5' class='text-right'></td>
                <td align='center'>
                    <b>Total Man Power</b><br>
                    <b><span align='center'><?=$header[0]->bq_mp;?></b>
                </td>
            </tr>
            <tr>
                <td colspan='5'></td>
                <td align='center'>
                    <b>Time Est. (days)</b><br>
                    <b><span align='center'><?=$header[0]->bq_total;?></b>
                </td>
            </tr>
        </tfoot>
    </table>
	<br>
	<p>BQ Custom</p>
	<table class='gridtable' border='1' width='100%' cellpadding='2'>
		<thead>
			<tr class='bg-purple'>
				<th class='text-center' width='7%'>#</th>
				<th class='text-center'>Pekerjaan</th>
				<th class='text-center' width='15%'>Qty</th>
				<th class='text-center' width='15%'>Satuan</th>
				<th class='text-center' width='15%'>MP</th>
				<th class='text-center' width='15%'>Jumlah Hari</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$id=0;
			foreach ($detail_cus as $key => $value) { $id++;
				$restCustomDet = $this->db->get_where('project_detail_bq', array('project_code'=>$header[0]->project_code,'pekerjaan'=>$value['pekerjaan'],'category'=>'custom'))->result_array();
				echo "<tr class='header_".$id."'>";
					echo "<td align='center'>".$id."</td>";
					echo "<td align='left'>".$value['pekerjaan']."</td>";
					echo "<td colspan='4'></td>";
				echo "</tr>";
				$no=0;
				foreach ($restCustomDet as $key2 => $value2) { $no++;
					echo "<tr class='header_".$id."'>";
						echo "<td align='center'></td>";
						echo "<td align='left' style='vertical-align:middle; padding-left: 30px;'>".$value2['pekerjaan_detail']."</td>";
						echo "<td align='center'>".$value2['qty']."</td>";
						echo "<td align='center'>".$value2['satuan_code']."</td>";
						echo "<td align='center'>".$value2['mp']."</td>";
						echo "<td align='center'>".$value2['day_in']."</td>";
					echo "</tr>";
				}
			}
			?>
		</tbody>
	</table>
    <br>
	<table class='gridtable' border='1' width='100%' cellpadding='2'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='5'  align='left'>Man Power & Uang Makan</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 5%;'>No</th>
					<th class="text-center" style='width: 25%;'>Category</th>
					<th class="text-center">Specification</th>
					<th class="text-center" style='width: 10%;'>Qty</th>
					<th class="text-center" style='width: 10%;'>Time (Day)</th>
				</tr>
 			</thead>
 			<tbody>
 				<?php
 				$no=0;
 				foreach($mp AS $val => $valx){
 					$no++;
 					// $get_day = $this->db->select('std_time')->get_where('project_detail_header', array('project_code_det'=>$valx['project_code_det']))->result();
 					?>
 					<tr>
 						<td align='center'><?=$no;?></td>
						<td><?=strtoupper(strtolower($valx['category']));?></td>
            			<td><?=strtoupper(strtolower($valx['spec']));?></td>
						<td align='center'><?=strtoupper(strtolower($valx['qty']));?></td>
						<td align='center'><?=number_format($valx['jml_hari'],2);?></td>
 					</tr>
 					<?php
 				}
				if(empty($mp)){
					echo "<tr>";
					echo "<td colspan='5'>Data not found ...</td>";
					echo "</tr>";
				}
 				?>
 			</tbody>
 		</table>

		 <table class='gridtable' border='1' width='100%' cellpadding='2'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='5' align='left'>Consumable & Material</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 5%;'>No</th>
					<th class="text-center" style='width: 25%;'>Category</th>
					<th class="text-center">Specification</th>
					<th class="text-center" style='width: 10%;'>Qty</th>
					<th class="text-center" style='width: 10%;'>Unit</th>
				</tr>
 			</thead>
 			<tbody>
 				<?php
 				$no=0;
 				foreach($cn AS $val => $valx){
 					$no++;
 					?>
 					<tr>
 						<td align='center'><?=$no;?></td>
						<td><?=strtoupper(strtolower($valx['category']));?></td>
            			<td><?=strtoupper(strtolower($valx['spec']));?></td>
						<td align='center'><?=strtoupper(strtolower($valx['qty']));?></td>
						<td align='center'><?=strtoupper(strtolower($valx['unit']));?></td>
 					</tr>
 					<?php
 				}
				if(empty($cn)){
					echo "<tr>";
					echo "<td colspan='5'>Data not found ...</td>";
					echo "</tr>";
				}
 				?>
 			</tbody>
 		</table>
	
		<!-- Tools Equipment -->
		<table class='gridtable' border='1' width='100%' cellpadding='2'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='5' align='left'>Tools Equipment</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 5%;'>No</th>
					<th class="text-center" style='width: 25%;'>Category</th>
					<th class="text-center">Specification</th>
					<th class="text-center" style='width: 10%;'>Qty</th>
					<th class="text-center" style='width: 10%;'>Unit</th>
				</tr>
 			</thead>
 			<tbody>
 				<?php
 				$no=0;
 				foreach($vt AS $val => $valx){
 					$no++;
 					?>
 					<tr>
 						<td align='center'><?=$no;?></td>
						<td><?=strtoupper(strtolower($valx['category']));?></td>
            			<td><?=strtoupper(strtolower($valx['spec']));?></td>
						<td align='center'><?=strtoupper(strtolower($valx['qty']));?></td>
						<td align='center'><?=strtoupper(strtolower($valx['unit']));?></td>
 					</tr>
 					<?php
 				}
				if(empty($vt)){
					echo "<tr>";
					echo "<td colspan='5'>Data not found ...</td>";
					echo "</tr>";
				}
 				?>
 			</tbody>
 		</table>
		<!-- Consumable -->
		 <!-- Heavy Equipment -->
		 <table class='gridtable' border='1' width='100%' cellpadding='2'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='5' align='left'>Heavy & Rental Equipment</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 5%;'>No</th>
					<th class="text-center" style='width: 25%;'>Category</th>
					<th class="text-center">Specification</th>
					<th class="text-center" style='width: 10%;'>Qty</th>
					<th class="text-center" style='width: 10%;'>Time (Day)</th>
				</tr>
 			</thead>
 			<tbody>
 				<?php
 				$no=0;
 				foreach($he AS $val => $valx){
 					$no++;
					?>
 					<tr>
 						<td align='center'><?=$no;?></td>
						<td><?=strtoupper(strtolower($valx['category']));?></td>
            			<td><?=strtoupper(strtolower($valx['spec']));?></td>
						<td align='center'><?=strtoupper(strtolower($valx['qty_']));?></td>
						<td align='center'><?=number_format($valx['std_time'],2);?></td>
 					</tr>
 					<?php
 				}
				if(empty($he)){
					echo "<tr>";
					echo "<td colspan='5'>Data not found ...</td>";
					echo "</tr>";
				}
 				?>
 			</tbody>
 		</table>
		

		 <table class='gridtable' border='1' width='100%' cellpadding='2'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='6' align='left'>Acomodation & Transportation on Site</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 5%;'>No</th>
					<th class="text-center">Item Cost</th>
					<th class="text-center" style='width: 8%;'>Qty</th>
					<th class="text-center" style='width: 8%;'>Time (Day)</th>
					<th class="text-center" style='width: 12%;'>Unit</th>
					<th class="text-center" style='width: 20%;'>Note</th>
				</tr>
 			</thead>
 			<tbody>
 				<?php
 				$no=0;
 				foreach($house AS $val => $valx){
 					$no++;
 					?>
 					<tr>
 						<td align='center'><?=$no;?></td>
 						<td><?=strtoupper($valx['spec']);?></td>
            <td align='center'><?=strtoupper(strtolower($valx['qty']));?></td>
						<td align='center'><?=strtoupper(strtolower($valx['jml_orang']));?></td>
						<td align='center'><?=strtoupper(strtolower($valx['area']));?></td>
						<td><?=strtoupper(strtolower($valx['note']));?></td>
 					</tr>
 					<?php
 				}
				if(empty($house)){
					echo "<tr>";
					echo "<td colspan='6'>Data not found ...</td>";
					echo "</tr>";
				}
 				?>
 			</tbody>
 		</table>
		
		 <table class='gridtable' border='1' width='100%' cellpadding='2'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='4' align='left'>Testing</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 5%;'>No</th>
					<th class="text-center">Item Name</th>
					<th class="text-center" style='width: 8%;'>Qty</th>
					<th class="text-center" style='width: 20%;'>Note</th>
				</tr>
 			</thead>
 			<tbody>
 				<?php
 				$no=0;
 				foreach($testing AS $val => $valx){
 					$no++;
 					?>
 					<tr>
 						<td align='center'><?=$no;?></td>
 						<td><?=strtoupper($valx['spec']);?></td>
            <td align='center'><?=$valx['qty'];?></td>
						<td><?=strtoupper(strtolower($valx['note']));?></td>
 					</tr>
 					<?php
 				}
				if(empty($etc)){
					echo "<tr>";
					echo "<td colspan='4'>Data not found ...</td>";
					echo "</tr>";
				}
 				?>
 			</tbody>
 		</table>
		 <table class='gridtable' border='1' width='100%' cellpadding='2'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='4' align='left'>Etc</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 5%;'>No</th>
					<th class="text-center">Item Name</th>
					<th class="text-center" style='width: 8%;'>Qty</th>
					<th class="text-center" style='width: 20%;'>Note</th>
				</tr>
 			</thead>
 			<tbody>
 				<?php
 				$no=0;
 				foreach($etc AS $val => $valx){
 					$no++;
 					?>
 					<tr>
 						<td align='center'><?=$no;?></td>
 						<td><?=strtoupper($valx['spec']);?></td>
            <td align='center'><?=$valx['qty'];?></td>
						<td><?=strtoupper(strtolower($valx['note']));?></td>
 					</tr>
 					<?php
 				}
				if(empty($etc)){
					echo "<tr>";
					echo "<td colspan='4'>Data not found ...</td>";
					echo "</tr>";
				}
 				?>
 			</tbody>
 		</table>
		 <table class='gridtable' border='1' width='100%' cellpadding='2'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='6' align='left'>Mob-Demob Man Power</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 5%;'>No</th>
					<th class="text-center">Item Cost</th>
					<th class="text-center" style='width: 20%;'>Destination</th>
					<th class="text-center" style='width: 8%;'>Qty MP</th>
					<th class="text-center" style='width: 8%;'>Round-Trip</th>
					<th class="text-center" style='width: 20%;'>Note</th>
				</tr>
 			</thead>
 			<tbody>
 				<?php
 				$no=0;
 				foreach($trans AS $val => $valx){
 					$no++;
 					?>
 					<tr>
 						<td align='center'><?=$no;?></td>
 						<td><?=strtoupper($valx['category']." - ".$valx['spec']);?></td>
						<td align='left'><?=strtoupper(strtolower($valx['asal']." - ".$valx['tujuan']));?></td>
						<td align='center'><?=strtoupper(strtolower($valx['jml_orang']));?></td>
						<td align='center'><?=strtoupper(strtolower($valx['pulang_pergi']));?></td>
						<td><?=strtoupper(strtolower($valx['note']));?></td>
 					</tr>
 					<?php
 				}
				if(empty($trans)){
					echo "<tr>";
					echo "<td colspan='6'>Data not found ...</td>";
					echo "</tr>";
				}
 				?>
 			</tbody>
 		</table>
		
         <table class='gridtable' border='1' width='100%' cellpadding='2'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='7' align='left'>Mob Demob Equipment</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 5%;'>No</th>
					<th class="text-center">Item Name</th>
					<th class="text-center" style='width: 10%;'>Area</th>
					<th class="text-center" style='width: 15%;'>Tujuan</th>
					<th class="text-center" style='width: 25%;'>Truck</th>
					<th class="text-center" style='width: 5%;'>Qty</th>
					<th class="text-center" style='width: 10%;'>Note</th>
				</tr>
 			</thead>
 			<tbody>
 				<?php
 				$no=0;
 				foreach($mde AS $val => $valx){
 					$no++;
 					?>
 					<tr>
 						<td align='center'><?=$no;?></td>
 						<td><?=strtoupper($valx['spec']);?></td>
						 <td align='left'><?=$valx['area'];?></td>
						 <td align='left'><?=$valx['tujuan'];?></td>
						 <td align='left'><?=api_get_nm_truck($valx['truck']);?></td>
						 <td align='center'><?=$valx['jml_orang'];?></td>
						<td><?=strtoupper(strtolower($valx['note']));?></td>
 					</tr>
 					<?php
 				}
				if(empty($mde)){
					echo "<tr>";
					echo "<td colspan='7'>Data not found ...</td>";
					echo "</tr>";
				}
 				?>
 			</tbody>
 		</table>
		 
         <table class='gridtable' border='1' width='100%' cellpadding='2'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='6' align='left'>Survey</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 5%;'>No</th>
					<th class="text-center">Item Name</th>
					<th class="text-center" style='width: 8%;'>Jumlah Orang</th>
					<th class="text-center" style='width: 8%;'>Jumlah Qty</th>
					<th class="text-center" style='width: 8%;'>Jumlah Hari</th>
					<th class="text-center" style='width: 20%;'>Note</th>
				</tr>
 			</thead>
 			<tbody>
 				<?php
 				$no=0;
 				foreach($survey AS $val => $valx){
 					$no++;
 					?>
 					<tr>
 						<td align='center'><?=$no;?></td>
 						<td><?=strtoupper($valx['spec']);?></td>
						<td align='center'><?=$valx['jml_orang'];?></td>
            			<td align='center'><?=$valx['qty'];?></td>
            			<td align='center'><?=$valx['jml_hari'];?></td>
						<td><?=strtoupper(strtolower($valx['note']));?></td>
 					</tr>
 					<?php
 				}
				if(empty($etc)){
					echo "<tr>";
					echo "<td colspan='6'>Data not found ...</td>";
					echo "</tr>";
				}
 				?>
 			</tbody>
 		</table>

         <table class='gridtable' border='1' width='100%' cellpadding='2'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='5' align='left'>Covid 19 Protokol</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 5%;'>No</th>
					<th class="text-center">Item Name</th>
					<th class="text-center" style='width: 12%;'>Jumlah</th>
					<th class="text-center" style='width: 12%;'>Durasi</th>
					<th class="text-center" style='width: 20%;'>Note</th>
				</tr>
 			</thead>
 			<tbody>
 				<?php
 				$no=0;
 				foreach($covid AS $val => $valx){
 					$no++;
 					?>
 					<tr>
 						<td align='center'><?=$no;?></td>
 						<td><?=strtoupper($valx['spec']);?></td>
						<td align='right'><?=$valx['jml_orang'];?> Orang</td>
            			<td align='right'><?=$valx['jml_hari'];?> Hari</td>
						<td><?=strtoupper(strtolower($valx['note']));?></td>
 					</tr>
 					<?php
 				}
				if(empty($covid)){
					echo "<tr>";
					echo "<td colspan='5'>Data not found ...</td>";
					echo "</tr>";
				}
 				?>
 			</tbody>
 		</table>

		 <table class='gridtable' border='1' width='100%' cellpadding='2'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='2' align='left'>Include Exclude</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 50%;'>Include</th>
					<th class="text-center" style='width: 50%;'>Exclude</th>
				</tr>
 			</thead>
 			<tbody>
				<tr>
					<td style='vertical-align:top;'>
						<table class='gridtable' width='100%'>
							<?php
								foreach ($arrInclude as $value) {
									echo "<tr>";
										echo "<td>".strtoupper(get_name('include_exclude','name','id',$value))."</td>";
									echo "</tr>";
								}
								foreach ($arrIncludeTxt as $value) {
									if(!empty($value)){
										echo "<tr>";
											echo "<td>".strtoupper($value)."</td>";
										echo "</tr>";
									}
								}
							?>
						</table>
					</td>
					<td style='vertical-align:top;'>
						<table class='gridtable' width='100%'>
							<?php
								foreach ($arrExclude as $value) {
									echo "<tr>";
										echo "<td>".strtoupper(get_name('include_exclude','name','id',$value))."</td>";
									echo "</tr>";
								}
								foreach ($arrExcludeTxt as $value) {
									if(!empty($value)){
										echo "<tr>";
											echo "<td>".strtoupper($value)."</td>";
										echo "</tr>";
									}
								}
							?>
						</table>
					</td>
				</tr>
 			</tbody>
 		</table>
	
	<style type="text/css">
		@page {
			margin-top: 1cm;
			margin-left: 1.5cm;
			margin-right: 1cm;
			margin-bottom: 1cm;
		}
		.mid{
			vertical-align: middle;
		}
		.font{
			font-family: verdana,arial,sans-serif;
			font-size:14px;
		}
		.fontheader{
			font-family: verdana,arial,sans-serif;
			font-size:13px;
			color:#333333;
			border-width: 1px;
			border-color: #666666;
			border-collapse: collapse;
		}
		table.gridtable {
			font-family: verdana,arial,sans-serif;
			font-size:9px;
			color:#333333;
			border-width: 1px;
			border-color: #666666;
			border-collapse: collapse;
		}
		table.gridtable th {
			border-width: 1px;
			padding: 8px;
			border-style: solid;
			border-color: #666666;
			background-color: #f2f2f2;
		}
		table.gridtable th.head {
			border-width: 1px;
			padding: 8px;
			border-style: solid;
			border-color: #666666;
			background-color: #7f7f7f;
			color: #ffffff;
		}
		table.gridtable td {
			border-width: 1px;
			padding: 3px;
			border-style: solid;
			border-color: #666666;
			background-color: #ffffff;
		}
		table.gridtable td.cols {
			border-width: 1px;
			padding: 3px;
			border-style: solid;
			border-color: #666666;
			background-color: #ffffff;
		}

		table.gridtable3 {
			font-family: verdana,arial,sans-serif;
			font-size:9px;
			color:#333333;
			border-width: 0px;
			border-color: #666666;
			border-collapse: collapse;
		}
		table.gridtable3 th {
			border-width: 1px;
			padding: 8px;
			border-style: none;
			border-color: #666666;
			background-color: #f2f2f2;
		}
		table.gridtable3 th.head {
			border-width: 1px;
			padding: 8px;
			border-style: none;
			border-color: #666666;
			background-color: #7f7f7f;
			color: #ffffff;
		}
		table.gridtable3 td {
			border-width: 1px;
			padding: 3px;
			border-style: none;
			border-color: #666666;
			background-color: #ffffff;
		}
		table.gridtable3 td.cols {
			border-width: 1px;
			padding: 3px;
			border-style: none;
			border-color: #666666;
			background-color: #ffffff;
		}


		table.gridtable2 {
			font-family: verdana,arial,sans-serif;
			font-size:10px;
			color:#333333;
			border-width: 1px;
			border-color: #666666;
			border-collapse: collapse;
		}
		table.gridtable2 th {
			border-width: 1px;
			padding: 3px;
			border-style: none;
			border-color: #666666;
			background-color: #f2f2f2;
		}
		table.gridtable2 th.head {
			border-width: 1px;
			padding: 3px;
			border-style: none;
			border-color: #666666;
			background-color: #7f7f7f;
			color: #ffffff;
		}
		table.gridtable2 td {
			border-width: 1px;
			padding: 3px;
			border-style: none;
			border-color: #666666;
			background-color: #ffffff;
		}
		table.gridtable2 td.cols {
			border-width: 1px;
			padding: 3px;
			border-style: none;
			border-color: #666666;
			background-color: #ffffff;
		}
		p {
			margin: 0 0 0 0;
		}
	</style>


	<?php
	$footer = "<p style='font-family: verdana,arial,sans-serif; font-size:10px;'><i>Printed by : ".ucfirst(strtolower($printby)).", ".$today."</i></p>";
	$refX	=  $dHeader[0]['ref_ke'];
	$html = ob_get_contents();
	// exit;
	ob_end_clean();
	// $mpdf->SetWatermarkText('ORI Group');
	$mpdf->showWatermarkText = true;
	$mpdf->SetTitle($no_ipp);
	$mpdf->AddPage();
	$mpdf->SetFooter($footer);
	$mpdf->WriteHTML($html);
	$mpdf->Output('Kebutuhan Project '.$project_code.'_'.date('YmdHis').'.pdf' ,'I');