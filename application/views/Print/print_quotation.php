<?php
	$sroot 		= $_SERVER['DOCUMENT_ROOT'];
	include "application/libraries/MPDF57/mpdf.php";
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

<table class="gridtable3" border='1' width='100%' cellpadding='2'>
		<tr>
			<td width='20%'><img src='assets/images/sendigs_logo.png' alt="" height='100' width='100' ></td>
			<td>
				<h3>PT. SENTRAL SISTEM TEHNOLOGI</h3>
				Jl. Akasia II Blok A No.3<br>
				Delta Silicon Industrial Park, Lippo Cikarang Industrial Estate<br>
				Phone : (62 21) 897-2193 (Hunting)<br>
				Email : enquiry@ori.co.id<br>
			</td>
		</tr>
	</table>
	<hr>
	<table class="gridtable2" border='0' width='100%' >
		<tr>
			<td width='20%'>IPP Number</td>
			<td width='1%'>:</td>
			<td width='29%'><?=$header[0]->no_ipp;?></td>
			<td width='20%'></td>
			<td width='1%'></td>
			<td width='29%'></td>
		</tr>
		<tr>
			<td>Client</td>
			<td>:</td>
			<td colspan='3'><?=strtoupper(get_name('ipp_header','nm_customer','no_ipp',$header[0]->no_ipp));?></td>
		</tr>
		<tr>
			<td>Ref No.</td>
			<td>:</td>
			<td colspan='3'><?=$header[0]->reff_no;?></td>
		</tr>
		<tr>
			<td>Subject</td>
			<td>:</td>
			<td colspan='3'><?=$header[0]->subject;?></td>
		</tr>
	</table>
    <br>
    <table class='gridtable' border='1' width='100%' cellpadding='2'>
		<thead>
			<tr>
				<th class="text-center" width='5%'>#</th>
				<th class="text-center">Description</th>
				<th class="text-center" width='10%'>DN (mm)</th>
				<th class="text-center" width='10%'>DN (inch)</th>
				<th class="text-center" width='10%'>Qty Joint</th>
				<th class="text-center" width='10%'>Dia/Inch</th>
				<th class="text-center" width='15%'>Harga Dia/Inch</th>
				<th class="text-center" width='15%'>Total Harga</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$sub_tot_dia = 0;
			foreach ($detail_bq as $key => $value) { $key++;
				$harga_dia = (!empty($value['harga_dia']))?$value['harga_dia']:$header[0]->harga_dia_inch;
				$total_dia = $harga_dia * $value['day_in'];

				$sub_tot_dia += $total_dia;

				echo "<tr>";
					echo "<td align='center'>".$key."</td>";
					echo "<td align='left'>".strtoupper($value['desc'])."</td>";
					echo "<td align='center'>".number_format($value['diameter'])."</td>";
					echo "<td align='center'>".number_format($value['diameter2'])."</td>";
					echo "<td align='center'>".number_format($value['qty'])."</td>";
					echo "<td align='center'>".number_format($value['day_in'],2)."</td>";
					echo "<td align='right'>".number_format($value['harga_dia'])."</td>";
					echo "<td align='right'>".number_format($value['total_harga'])."</td>";
				echo "</tr>";
			}
			echo "<tr>";
				echo "<td></td>";
				echo "<td colspan='6'><b>TOTAL HARGA DIA/INCH</b></td>";
				echo "<td align='right'><b>".number_format($sub_tot_dia)."</b></td>";
			echo "</tr>";
		?>
		</tbody>
    </table>
	<br>
    <table class='gridtable' border='1' width='100%' cellpadding='2'>
		<thead>
			<tr>
				<th class="text-center" width='5%'>#</th>
				<th class="text-center">Description</th>
				<th class="text-center" style='width: 10%;'>Qty</th>
				<th class="text-center" style='width: 10%;'>Satuan</th>
				<th class="text-center" style='width: 15%;'>Harga Satuan</th>
				<th class="text-center" style='width: 15%;'>Total Harga</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$sub_tot_dia = 0;
			foreach ($detail_quo as $key => $value) { $key++;
				$sub_tot_dia += $value['total_harga'];

				echo "<tr>";
					echo "<td align='center'>".$key."</td>";
					echo "<td align='left'>".strtoupper($value['desc'])."</td>";
					echo "<td align='center'>".number_format($value['qty'])."</td>";
					echo "<td align='center'>".$value['satuan']."</td>";
					echo "<td align='right'>".number_format($value['harga_satuan'])."</td>";
					echo "<td align='right'>".number_format($value['total_harga'])."</td>";
				echo "</tr>";
			}
			echo "<tr>";
				echo "<td></td>";
				echo "<td colspan='4'><b>TOTAL</b></td>";
				echo "<td align='right'><b>".number_format($sub_tot_dia)."</b></td>";
			echo "</tr>";
		?>
		</tbody>
    </table>
	<br>
	<table class='gridtable' border='1' width='100%' cellpadding='2'>
		<thead>
			<tr>
				<th class="text-center" style='width: 5%;'>#</th>
				<th class="text-center">Item Include</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$nomor = 0;
				foreach ($arrInclude as $value) { $nomor++;
					echo "<tr>";
						echo "<td align='center'>".$nomor."</td>";
						echo "<td>".strtoupper(get_name('include_exclude','name','id',$value))."</td>";
					echo "</tr>";
				}
				foreach ($arrIncludeTxt as $value) { $nomor++;
					if(!empty($value)){
						echo "<tr>";
							echo "<td align='center'>".$nomor."</td>";
							echo "<td>".strtoupper($value)."</td>";
						echo "</tr>";
					}
				}
			?>	
		</tbody>
	</table>
	<br>
	<table class='gridtable' border='1' width='100%' cellpadding='2'>
		<thead>
			<tr>
				<th class="text-center" style='width: 5%;'>#</th>
				<th class="text-center">Item Exclude</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$nomor = 0;
				foreach ($arrExclude as $value) {$nomor++;
					echo "<tr>";
						echo "<td align='center'>".$nomor."</td>";
						echo "<td>".strtoupper(get_name('include_exclude','name','id',$value))."</td>";
					echo "</tr>";
				}
				foreach ($arrExcludeTxt as $value) {$nomor++;
					if(!empty($value)){
						echo "<tr>";
							echo "<td  align='center'>".$nomor."</td>";
							echo "<td>".strtoupper($value)."</td>";
						echo "</tr>";
					}
				}
			?>
		</tbody>
	</table>
	<br>
	<table class='gridtable' border='1' width='100%' cellpadding='2'>
		<thead>
			<tr>
				<th align='center'>TOTAL EXCLUDE VAT</th>
				<th align='right' width='20%'><?=number_format($header[0]->rate_budget);?></th>
			</tr>
		</thead>
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
			font-size:10px;
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
			font-size:12px;
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
	// $footer = "<p style='font-family: verdana,arial,sans-serif; font-size:10px;'><i>Printed by : ".ucfirst(strtolower($printby)).", ".$today."</i></p>";
	// $refX	=  $dHeader[0]['ref_ke'];
	$html = ob_get_contents();
	// exit;
	ob_end_clean();
	// $mpdf->SetWatermarkText('ORI Group');
	$mpdf->showWatermarkText = true;
	$mpdf->SetTitle($header[0]->no_ipp);
	$mpdf->AddPage();
	// $mpdf->SetFooter($footer);
	$mpdf->WriteHTML($html);
	$mpdf->Output('Quotation '.$header[0]->no_ipp.' '.date('YmdHis').'.pdf' ,'I');