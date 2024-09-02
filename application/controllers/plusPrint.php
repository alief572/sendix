<?php
set_time_limit(0);
ob_start();

$Successno			=0;
$ErrorInfo			=0;
$sroot 				= $_SERVER['DOCUMENT_ROOT'];

function PrintBQ($Nama_APP, $project_code, $koneksi, $printby){

	$KONN = array(
		'user' => $koneksi['hostuser'],
		'pass' => $koneksi['hostpass'],
		'db'   => $koneksi['hostdb'],
		'host' => $koneksi['hostname']
	);

	$conn = mysqli_connect($KONN['host'],$KONN['user'],$KONN['pass']);
	mysqli_select_db($conn, $KONN['db']);

	$sroot 		= $_SERVER['DOCUMENT_ROOT'];
	include $sroot."/application/libraries/MPDF57/mpdf.php";
	$mpdf=new mPDF('utf-8','A4-L');

	set_time_limit(0);
	ini_set('memory_limit','1024M');

	ob_start();
	date_default_timezone_set('Asia/Jakarta');
	$today = date('l, d F Y [H:i:s]');

	$qHeader	= "SELECT * FROM project_header WHERE project_code='".$project_code."'";
	$RqHeader	= mysqli_query($conn, $qHeader);
	$header	 = mysqli_fetch_array($RqHeader);

  $qDetail	= "SELECT * FROM project_detail_w_header WHERE project_code='".$project_code."' AND deleted='N'";
	$RqDetail	= mysqli_query($conn, $qDetail);

  $qDetailBQ	= "SELECT * FROM project_detail_bq WHERE project_code='".$project_code."' AND deleted='N'";
	$RqDetailBQ	= mysqli_query($conn, $qDetailBQ);
	$detail_bq	= mysqli_fetch_array($RqDetailBQ);

	?>

	<table class="gridtable2" border='1' width='100%' cellpadding='2'>
		<tr>
			<td align='center'><b>PT  ORI POLYTEC COMPOSITE</b></td>
		</tr>
		<tr>
			<td align='center'><b><h2>BQ INSTALATION PROJECT</h2></b></td>
		</tr>
	</table>
	<br>
	<table class="gridtable2" border='0' width='100%' >
		<tr>
			<td width='20%'>Project Name</td>
			<td width='1%'>:</td>
			<td width='29%'><?= strtoupper($header['project_name']); ?></td>
			<td width='20%'>Region</td>
			<td width='1%'>:</td>
			<td width='29%'><?= strtoupper($header['region']); ?></td>
		</tr>
		<tr>
			<td style='vertical-align:top;'>Project Location</td>
			<td style='vertical-align:top;'>:</td>
			<td style='vertical-align:top;'><?= strtoupper($header['location']); ?></td>
			<td style='vertical-align:top;'>Time Total /day</td>
			<td style='vertical-align:top;'>:</td>
			<td style='vertical-align:top;'><?= $header['total_time']; ?></td>
		</tr>
	</table>
	<br>
	<table class="gridtable" width='100%' border='1' cellpadding='0'>
		<thead align='center'>
			<tr>
				<th align='left' colspan='14'>SPECIFICATION LIST</th>
			</tr>
			<tr>
        <th class="text-center" width='3%'>No</th>
        <th class="text-center" width='10%'>Work Name</th>
        <th class="text-center">Spesification</th>
			</tr>
		</thead>
    <tbody>
      <?php
      $no=0;
      while($valx = mysqli_fetch_array($RqDetail)){
        $no++;

        $qDetail2 = "SELECT * FROM project_detail_w_det WHERE project_code_det='".$valx['project_code_det']."' AND deleted='N'";
        $restDetail2T = mysqli_query($conn, $qDetail2);
        ?>
        <tr>
          <td><?=$no;?></td>
          <td><?=ucfirst(strtolower($valx['category']));?></td>
          <td>

                <table class="gridtable" width='100%' border='1' cellpadding='0'>
                  <tr class='bg-purple'>
                    <th class="text-center" width='3%'>No</th>
                    <th class="text-center" width='10%'>Work Process</th>
                    <th class="text-center" width='16%'>Vehicle Tools</th>
                    <th class="text-center" width='16%'>Consumable</th>
                    <th class="text-center" width='16%'>Man Power</th>
                    <th class="text-center" width='16%'>APD</th>
                    <th class="text-center" width='16%'>Akomodasi</th>
                    <th class="text-center" width='7%'>Time /day</th>
                  </tr>
                <?php
                $nox = 0;
                while($valx2d = mysqli_fetch_array($restDetail2T)){
                  $nox++;
                  $qDet1 = "SELECT * FROM project_detail_w_det_vehicle_tool WHERE project_code_det='".$valx['project_code_det']."' AND code_work_detail='".$valx2d['code_work_detail']."' AND deleted='N'";
                  $qDet2 = "SELECT * FROM project_detail_w_det_con_nonmat WHERE project_code_det='".$valx['project_code_det']."' AND code_work_detail='".$valx2d['code_work_detail']."' AND deleted='N'";
                  $qDet3 = "SELECT * FROM project_detail_w_det_man_power WHERE project_code_det='".$valx['project_code_det']."' AND code_work_detail='".$valx2d['code_work_detail']."' AND deleted='N'";
                  $qDet4 = "SELECT * FROM project_detail_w_det_apd WHERE project_code_det='".$valx['project_code_det']."' AND code_work_detail='".$valx2d['code_work_detail']."' AND deleted='N'";
                  $qDet5 = "SELECT * FROM project_detail_w_det_akomodasi WHERE project_code_det='".$valx['project_code_det']."' AND code_work_detail='".$valx2d['code_work_detail']."' AND deleted='N'";

                  $restDet1 = mysqli_query($conn, $qDet1);
                  $restDet2 = mysqli_query($conn, $qDet2);
                  $restDet3 = mysqli_query($conn, $qDet3);
                  $restDet4 = mysqli_query($conn, $qDet4);
                  $restDet5 = mysqli_query($conn, $qDet5);

                  echo "<tr>";
                  echo "<td align='center'>".$nox."</td>";
                  echo "<td>".ucfirst(strtolower($valx2d['work_process']))."</td>";
                  ?>
                  <td style='vertical-align:top !important;'>
                    <table width='100%' border='0' cellpadding='0' cellspacing='0'>
                    <?php
                    $noxc=0;
                    while($valx1 = mysqli_fetch_array($restDet1)){$noxc++;
                      // echo $noxc.". ".ucfirst(strtolower($valx1['category']))." - ".ucfirst(strtolower($valx1['spec']))." : ".$valx1['qty']." Unit<br>";
                      echo "<tr>";
                        echo "<td width='40%'>".ucfirst(strtolower($valx1['category']))."</td>";
                        echo "<td width='40%'>".ucfirst(strtolower($valx1['spec']))."</td>";
                        echo "<td width='20%' align='center'>".$valx1['qty']."</td>";
                      echo "<tr>";
                    }
                    ?>
                    </table>
                  </td>
                  <td style='vertical-align:top !important;'>
                    <table class="gridtable" width='100%' border='0' cellpadding='0'>
                    <?php
                    while($valx2 = mysqli_fetch_array($restDet2)){
                      echo "<tr>";
                        echo "<td width='40%'>".ucfirst(strtolower($valx2['category']))."</td>";
                        echo "<td width='40%'>".ucfirst(strtolower($valx2['spec']))."</td>";
                        echo "<td width='20%' align='center'>".$valx2['qty']."</td>";
                      echo "<tr>";
                    }
                    ?>
                    </table>
                  </td>
                  <td style='vertical-align:top !important;'>
                    <table class="gridtable" width='100%' border='0' cellpadding='0'>
                    <?php
                    while($valx3 = mysqli_fetch_array($restDet3)){
                      echo "<tr>";
                        echo "<td width='40%'>".ucfirst(strtolower($valx3['category']))."</td>";
                        echo "<td width='40%'>".ucfirst(strtolower($valx3['spec']))."</td>";
                        echo "<td width='20%' align='center'>".$valx3['qty']."</td>";
                      echo "<tr>";
                    }
                    ?>
                    </table>
                  </td>
                  <td style='vertical-align:top !important;'>
                    <table class="gridtable" width='100%' border='0' cellpadding='0'>
                    <?php
                    while($valx4 = mysqli_fetch_array($restDet4)){
                      echo "<tr>";
                        echo "<td width='40%'>".ucfirst(strtolower($valx4['category']))."</td>";
                        echo "<td width='40%'>".ucfirst(strtolower($valx4['spec']))."</td>";
                        echo "<td width='20%' align='center'>".$valx4['qty']."</td>";
                      echo "<tr>";
                    }
                    ?>
                    </table>
                  </td>
                  <td style='vertical-align:top !important;'>
                    <table class="gridtable" width='100%' border='0' cellpadding='0'>
                    <?php
                    while($valx5 = mysqli_fetch_array($restDet5)){
                      echo "<tr>";
                        echo "<td width='40%'>".ucfirst(strtolower($valx5['category']))."</td>";
                        echo "<td width='40%'>".ucfirst(strtolower($valx5['spec']))."</td>";
                        echo "<td width='20%' align='center'>".$valx5['qty']."</td>";
                      echo "<tr>";
                    }
                    ?>
                    </table>
                  </td>
                  <?php
                    echo "<td align='center'>".$valx2d['std_time']."</td>";
                  echo "</tr>";
                }
                echo "</table>";
              ?>
          </td>
        </tr>
        <?php
      }
      ?>
    </tbody>
	</table>
	<br>
	<style type="text/css">
	@page {
		margin-top: 1cm;
		margin-left: 1.5cm;
		margin-right: 1cm;
		margin-bottom: 1cm;
	}
	p.foot1 {
		font-family: verdana,arial,sans-serif;
		font-size:10px;
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

	table.cooltabs {
		font-size:12px;
		font-family: verdana,arial,sans-serif;
		border-width: 1px;
		border-style: solid;
		border-radius: 5px 5px 5px 5px;
	}
	table.cooltabs th.reg {
		font-family: verdana,arial,sans-serif;
		border-radius: 5px 5px 5px 5px;
		background: #e3e0e4;
		padding: 5px;
	}
	table.cooltabs td.reg {
		font-family: verdana,arial,sans-serif;
		border-radius: 5px 5px 5px 5px;
		padding: 5px;
	}
	#cooltabs {
		font-family: verdana,arial,sans-serif;
		border-width: 1px;
		border-style: solid;
		border-radius: 5px 5px 5px 5px;
		background: #e3e0e4;
		padding: 5px;
		width: 800px;
		height: 20px;
	}
	#cooltabs2{
		font-family: verdana,arial,sans-serif;
		border-width: 1px;
		border-style: solid;
		border-radius: 5px 5px 5px 5px;
		background: #e3e0e4;
		padding: 5px;
		width: 180px;
		height: 10px;
	}
	#space{
		padding: 3px;
		width: 180px;
		height: 1px;
	}
	p {
		margin: 0 0 0 0;
	}
	#hrnew {
		border: 0;
		border-bottom: 1px dashed #ccc;
		background: #999;
	}
	</style>


	<?php
	$footer = "<p style='font-family: verdana,arial,sans-serif; font-size:10px;'><i>Printed by : ".ucfirst(strtolower($printby)).", ".$today."</i></p>";
	$html = ob_get_contents();
	// exit;
	ob_end_clean();
	// $mpdf->SetWatermarkText('ORI Group');
	$mpdf->showWatermarkText = true;
	$mpdf->SetTitle($no_ipp);
	$mpdf->AddPage();
	$mpdf->SetFooter($footer);
	$mpdf->WriteHTML($html);
	$mpdf->Output('project.pdf' ,'I');
}

function PrintProject($Nama_APP, $project_code, $koneksi, $printby){

	$KONN = array(
		'user' => $koneksi['hostuser'],
		'pass' => $koneksi['hostpass'],
		'db'   => $koneksi['hostdb'],
		'host' => $koneksi['hostname']
	);

	$conn = mysqli_connect($KONN['host'],$KONN['user'],$KONN['pass']);
	mysqli_select_db($conn, $KONN['db']);

	$sroot 		= $_SERVER['DOCUMENT_ROOT'];
	include $sroot."/application/libraries/MPDF57/mpdf.php";
	// $mpdf=new mPDF('utf-8','A4-L');
	$mpdf=new mPDF('utf-8','A4');

	set_time_limit(0);
	ini_set('memory_limit','1024M');

	ob_start();
	date_default_timezone_set('Asia/Jakarta');
	$today = date('l, d F Y [H:i:s]');

	$qHeader	= "SELECT * FROM project_header WHERE project_code='".$project_code."'";
	$RqHeader	= mysqli_query($conn, $qHeader);
	$header	 = mysqli_fetch_array($RqHeader);

  $qDetail	= "SELECT * FROM project_detail_w_header WHERE project_code='".$project_code."' AND deleted='N'";
	$RqDetail	= mysqli_query($conn, $qDetail);

  $qDetailBQ	= "SELECT * FROM project_detail_bq WHERE project_code='".$project_code."' AND deleted='N'";
	$RqDetailBQ	= mysqli_query($conn, $qDetailBQ);
	$detail_bq	= mysqli_fetch_array($RqDetailBQ);

	?>

	<table class="gridtable2" border='1' width='100%' cellpadding='2'>
		<tr>
			<td align='center'><b>PT  ORI POLYTEC COMPOSITE</b></td>
		</tr>
		<tr>
			<td align='center'><b><h2>COST INSTALATION PROJECT</h2></b></td>
		</tr>
	</table>
	<br>
	<table class="gridtable2" border='0' width='100%' >
		<tr>
			<td width='20%'>Project Name</td>
			<td width='1%'>:</td>
			<td width='29%'><?= strtoupper($header['project_name']); ?></td>
			<td width='20%'>Region</td>
			<td width='1%'>:</td>
			<td width='29%'><?= strtoupper($header['region']); ?></td>
		</tr>
		<tr>
			<td style='vertical-align:top;'>Project Location</td>
			<td style='vertical-align:top;'>:</td>
			<td style='vertical-align:top;'><?= strtoupper($header['location']); ?></td>
			<td style='vertical-align:top;'>Time Total /day</td>
			<td style='vertical-align:top;'>:</td>
			<td style='vertical-align:top;'><?= $header['total_time']; ?></td>
		</tr>
	</table>
	<br>
	<table class="gridtable" width='100%' border='1' cellpadding='0'>
		<thead align='center'>
			<tr>
				<th align='left' colspan='3'>SPECIFICATION LIST</th>
			</tr>
			<tr>
        <th class="text-center" width='5%'>No</th>
        <th class="text-center" width='15%'>Work Name</th>
        <th class="text-center">Spesification</th>
			</tr>
		</thead>
    <tbody>
      <?php
      $no=0;
      while($valx = mysqli_fetch_array($RqDetail)){
        $no++;

        $qDetail2 = "SELECT * FROM project_detail_w_det WHERE project_code_det='".$valx['project_code_det']."' AND deleted='N'";
        $restDetail2T = mysqli_query($conn, $qDetail2);
        ?>
        <tr>
          <td class='list-top' align='center'><?=$no;?></td>
          <td class='list-top'><?=ucfirst(strtolower($valx['category']));?></td>
          <td>
                <table class="gridtable" width='100%' border='1' cellpadding='0'>
                  <tr class='bg-purple'>
										<th class="text-center" width='10%'>No</th>
										<th class="text-center">Work Process</th>
										<th class="text-center" width='24%'>Time Process (Day)</th>
                  </tr>
                <?php
                $nox = 0;
                while($valx2d = mysqli_fetch_array($restDetail2T)){
                  $nox++;
                  $qDet1 = "SELECT * FROM list_rate_project WHERE project_code_det='".$valx['project_code_det']."' ORDER BY code_group ASC";
                  $restDet1 = mysqli_query($conn, $qDet1);

                  echo "<tr>";
	                  echo "<td align='center'>".$nox."</td>";
	                  echo "<td>".ucfirst(strtolower($valx2d['work_process']))."</td>";
	                  echo "<td align='center'>".$valx2d['std_time']."</td>";
                  echo "</tr>";
                }
                echo "</table>";
								echo "<br>";
								$qDet1 = "SELECT * FROM project_detail_w_det_vehicle_tool WHERE project_code_det='".$valx['project_code_det']."' AND deleted='N'";
								$qDet2 = "SELECT * FROM project_detail_w_det_con_nonmat WHERE project_code_det='".$valx['project_code_det']."' AND deleted='N'";
								$qDet3 = "SELECT * FROM project_detail_w_det_man_power WHERE project_code_det='".$valx['project_code_det']."' AND deleted='N'";

								$restDet1 = mysqli_query($conn, $qDet1);
								$restDet2 = mysqli_query($conn, $qDet2);
								$restDet3 = mysqli_query($conn, $qDet3);

								$restDet1Num = mysqli_num_rows($restDet1);
								$restDet2Num = mysqli_num_rows($restDet2);
								$restDet3Num = mysqli_num_rows($restDet3);
								if($restDet1Num != 0 OR $restDet2Num != 0 OR $restDet3Num != 0){
										echo "<table class='gridtable' width='100%' border='1' cellpadding='0'>";
											echo "<tr class='bg-blue'>";
												echo "<th class='text-center' width='20%'>#</th>";
												echo "<th class='text-center' width='20%'>Category</th>";
												echo "<th class='text-center' width='30%'>Spesification</th>";
												echo "<th class='text-center' width='6%'>Qty</th>";
												echo "<th class='text-center' width='12%'>Rate</th>";
												echo "<th class='text-center' width='12%'>Cost</th>";
											echo "</tr>";
										$SUM = 0;
										while($valx1 = mysqli_fetch_array($restDet1)){
											$SUM += $valx1['qty'] * $valx1['rate'];
												echo "<tr style='background-color: antiquewhite;'>";
													echo "<td>Heavy Equipment & Tools</td>";
													echo "<td>".ucfirst(strtolower($valx1['category']))."</td>";
													echo "<td>".ucfirst(strtolower($valx1['spec']))."</td>";
													echo "<td align='center'>".$valx1['qty']."</td>";
													echo "<td align='right'>".number_format($valx1['rate'])."</td>";
													echo "<td align='right'>".number_format($valx1['qty'] * $valx1['rate'])."</td>";
												echo "<tr>";
										}
										while($valx2 = mysqli_fetch_array($restDet2)){
											$SUM += $valx2['qty'] * $valx2['rate'];
												echo "<tr style='background-color: aquamarine;'>";
													echo "<td>Consumable & APD</td>";
													echo "<td>".ucfirst(strtolower($valx2['category']))."</td>";
													echo "<td>".ucfirst(strtolower($valx2['spec']))."</td>";
													echo "<td align='center'>".$valx2['qty']."</td>";
													echo "<td align='right'>".number_format($valx2['rate'])."</td>";
													echo "<td align='right'>".number_format($valx2['qty'] * $valx2['rate'])."</td>";
												echo "<tr>";
										}
										while($valx3 = mysqli_fetch_array($restDet3)){
											$SUM += $valx3['qty'] * $valx3['rate'];
												echo "<tr style='background-color: #ffc4da;'>";
													echo "<td>Man Power</td>";
													echo "<td>".ucfirst(strtolower($valx3['category']))."</td>";
													echo "<td>".ucfirst(strtolower($valx3['spec']))."</td>";
													echo "<td align='center'>".$valx3['qty']."</td>";
													echo "<td align='right'>".number_format($valx3['rate'])."</td>";
													echo "<td align='right'>".number_format($valx3['qty'] * $valx3['rate'])."</td>";
												echo "<tr>";
										}
										echo "<tr>";
											echo "<td colspan='5'><b>TOTAL COST</b></td>";
											echo "<td align='right'><b>".number_format($SUM)."</b></td>";
										echo "<tr>";
									echo "</table>";
								}

              ?>
          </td>
        </tr>
        <?php
      }
      ?>
    </tbody>
	</table>
	<br>
	<style type="text/css">
	@page {
		margin-top: 1cm;
		margin-left: 0.5cm;
		margin-right: 0.5cm;
		margin-bottom: 1cm;
	}
	.list-top{
		vertical-align: top;
	}
	p.foot1 {
		font-family: verdana,arial,sans-serif;
		font-size:10px;
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

	table.cooltabs {
		font-size:12px;
		font-family: verdana,arial,sans-serif;
		border-width: 1px;
		border-style: solid;
		border-radius: 5px 5px 5px 5px;
	}
	table.cooltabs th.reg {
		font-family: verdana,arial,sans-serif;
		border-radius: 5px 5px 5px 5px;
		background: #e3e0e4;
		padding: 5px;
	}
	table.cooltabs td.reg {
		font-family: verdana,arial,sans-serif;
		border-radius: 5px 5px 5px 5px;
		padding: 5px;
	}
	#cooltabs {
		font-family: verdana,arial,sans-serif;
		border-width: 1px;
		border-style: solid;
		border-radius: 5px 5px 5px 5px;
		background: #e3e0e4;
		padding: 5px;
		width: 800px;
		height: 20px;
	}
	#cooltabs2{
		font-family: verdana,arial,sans-serif;
		border-width: 1px;
		border-style: solid;
		border-radius: 5px 5px 5px 5px;
		background: #e3e0e4;
		padding: 5px;
		width: 180px;
		height: 10px;
	}
	#space{
		padding: 3px;
		width: 180px;
		height: 1px;
	}
	p {
		margin: 0 0 0 0;
	}
	#hrnew {
		border: 0;
		border-bottom: 1px dashed #ccc;
		background: #999;
	}
	</style>


	<?php
	$footer = "<p style='font-family: verdana,arial,sans-serif; font-size:10px;'><i>Printed by : ".ucfirst(strtolower($printby)).", ".$today."</i></p>";
	$html = ob_get_contents();
	// exit;
	ob_end_clean();
	// $mpdf->SetWatermarkText('ORI Group');
	$mpdf->forcePortraitHeaders = true;
	$mpdf->showWatermarkText = true;
	$mpdf->SetTitle($project_code." ".strtoupper($header['project_name']));
	// $mpdf->AddPage();
	$mpdf->SetFooter($footer);
	$mpdf->WriteHTML($html);
	$mpdf->Output("PROJECT ".$project_code." ".strtoupper($header['project_name']).".pdf" ,'I');
}

function PrintIPP($Nama_APP, $no_ipp, $koneksi, $printby){

	$KONN = array(
		'user' => $koneksi['hostuser'],
		'pass' => $koneksi['hostpass'],
		'db'   => $koneksi['hostdb'],
		'host' => $koneksi['hostname']
	);

	$conn = mysqli_connect($KONN['host'],$KONN['user'],$KONN['pass']);
	mysqli_select_db($conn, $KONN['db']);

	$sroot 		= $_SERVER['DOCUMENT_ROOT']."/ori_instalasi";
	include $sroot."/application/libraries/MPDF57/mpdf.php";
	$mpdf=new mPDF('utf-8','A4-L');

	set_time_limit(0);
	ini_set('memory_limit','1024M');

	ob_start();
	date_default_timezone_set('Asia/Jakarta');
	$today = date('l, d F Y [H:i:s]');

	$qHeader	= "SELECT * FROM ipp_header WHERE no_ipp='".$no_ipp."'";
	$RqHeader	= mysqli_query($conn, $qHeader);
	$header		= mysqli_fetch_array($RqHeader);
	?>
	<table class="gridtable2" border='1' width='100%' cellpadding='2'>
		<tr>
			<td align='center'><b>PT  ORI POLYTEC COMPOSITE</b></td>
		</tr>
		<tr>
			<td align='center'><b><h2>IDENTIFIKASI PERMINTAAN PELANGGAN (IPP)</h2></b></td>
		</tr>
	</table>
	<br><br>
	<table class="gridtable2" border='0' width='100%' >
		<tr>
			<td width='4%'>1.</td>
			<td width='35%' colspan='2'>IPP Number</td>
			<td width='5%'>:</td>
			<td width='48%'><b><?= strtoupper($header['no_ipp']); ?></b></td>
			<td width='8%'></td>
		</tr>
		<tr>
			<td>2.</td>
			<td colspan='2'>Nama Customer</td>
			<td>:</td>
			<td><?= strtoupper($header['nm_customer']); ?></td>
			<td></td>
		</tr>
		<tr>
			<td>3.</td>
			<td colspan='2'>Nama Project</td>
			<td>:</td>
			<td><?= strtoupper($header['project']); ?></td>
			<td></td>
		</tr>
		<?php
		$nm_product = implode(', ',json_decode($header['nm_product']));
		?>
		<tr>
			<td>4.</td>
			<td colspan='2'>Nama Produk</td>
			<td>:</td>
			<td><b><?= strtoupper($nm_product); ?></b></td>
			<td></td>
		</tr>
		<tr>
			<td>5.</td>
			<td colspan='2'>Referensi Customer / Project</td>
			<td>:</td>
			<td><?= strtoupper($header['ref_cust']); ?></td>
			<td></td>
		</tr>
		<tr>
			<td>6.</td>
			<td colspan='2'>Ruang Lingkup Project</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td>a.</td>
			<td>Perkiraan proyek</td>
			<td>:</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td colspan='4'>Budget Customer, jika ada  (informasi dari customer/ perkiraan harga jual)</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td>Harga Per pcs</td>
			<td>:</td>
			<td><?= strtoupper($header['harga_per_pcs']); ?></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td>Jumlah</td>
			<td>:</td>
			<td><?= strtoupper($header['jumlah']); ?> <?= $header['unit']; ?></td>
			<td></td>
		</tr>
		<?php
		$kapan = "";
		if($header['inspeksi'] == 'ya'){
			$kapan = ", <i>".$header['kapan'].'</i>';
		}
		?>
		<tr>
			<td></td>
			<td></td>
			<td>Inspeksi Pekerjaan</td>
			<td>:</td>
			<td colspan='2'><?= ucwords($header['inspeksi']).$kapan; ?></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td>Alamat Pengiriman</td>
			<td>:</td>
			<td><?= strtoupper($header['alamat']); ?></td>
			<td></td>
		</tr>
		<?php
		$informasi = json_decode($header['informasi']);
		$infoX = "<i>".implode(', ',$informasi).'</i>';
		
		?>
		<tr>
			<td></td>
			<td>b.</td>
			<td>Informasi / dokumen dari customer</td>
			<td>:</td>
			<td><?= $infoX; ?></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td>c.</td>
			<td>Kebutuhan Field Joint/material</td>
			<td>:</td>
			<td><?= ucwords($header['keb_joint']); ?></td>
			<td></td>
		</tr>
		
		<?php
		$jenis_test = json_decode($header['jenis_test']);
		$jenistest = "";
		if($header['test'] == 'ya'){
			$jenistest = ", <i>yaitu ".implode(', ',$jenis_test).'</i>';
		}

		$jenis_sert = json_decode($header['jenis_sertifikat']);
		$jenissert = "";
		if($header['sertifikat'] == 'ya'){
			$jenissert = ", <i>yaitu ".implode(', ',$jenis_sert).'</i>';
		}

		$app = implode(', ',json_decode($header['app']));
		?>
		<tr>
			<td></td>
			<td>d.</td>
			<td>Jenis Instalasi</td>
			<td>:</td>
			<td><?= ucwords($app); ?></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td>e.</td>
			<td>Test</td>
			<td>:</td>
			<td colspan='2'><?= ucfirst($header['test']).$jenistest; ?></td>
		</tr>
		<tr>
			<td></td>
			<td>f.</td>
			<td>Sertifikat</td>
			<td>:</td>
			<td><?= ucfirst($header['sertifikat']).$jenissert; ?></td>
			<td></td>
		</tr>
		<?php
		$syarat = "";
		if($header['syarat'] == 'ada'){
			$syarat = ", yaitu <i>".$header['syarat_cust'].'</i>';
		}
		?>
		<tr>
			<td></td>
			<td>g.</td>
			<td>Persyaratan khusus dari Customer</td>
			<td>:</td>
			<td colspan='2'><?= ucfirst($header['syarat']).$syarat; ?></td>
		</tr>
		<tr>
			<td>7.</td>
			<td colspan='2'>Alat Berat</td>
			<td>:</td>
			<td><?= ucfirst($header['alat_berat']); ?></td>
			<td></td>
		</tr>
		<tr>
			<td>8.</td>
			<td colspan='2'>Scaffolding</td>
			<td>:</td>
			<td><?= ucfirst($header['scaffolding']); ?></td>
			<td></td>
		</tr>
		<tr>
			<td>9.</td>
			<td colspan='2'>Electricity</td>
			<td>:</td>
			<td><?= ucfirst($header['electricity']); ?></td>
			<td></td>
		</tr>
		<tr>
			<td>10.</td>
			<td colspan='2'>Validity & Guarantee</td>
			<td>:</td>
			<td><?= strtoupper($header['validity']); ?></td>
			<td></td>
		</tr>
		<tr>
			<td>11.</td>
			<td colspan='2'>Payment Term</td>
			<td>:</td>
			<td><?= strtoupper($header['payment']); ?></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td colspan='2'>Note</td>
			<td>:</td>
			<td><?= $header['note']; ?></td>
			<td></td>
		</tr>
	</table>
	<br><br>
	<?php
	$tgl_sales = (!empty($header['app_date']))?date('d F Y', strtotime($header['app_date'])):'';
	$nm_sales = (!empty($header['app_by']))?strtoupper(get_name('users','nm_lengkap','username',$header['app_by'])):'';

	$tgl_eng = ($header['sts_confirm'] == 'Y')?date('d F Y', strtotime($header['confirm_date'])):'';
	$nm_eng = ($header['sts_confirm'] == 'Y')?strtoupper(get_name('users','nm_lengkap','username',$header['confirm_by'])):'';
	?>
	<table class="gridtable" border='1' width='100%' >
		<tr>
			<td width='20%' height='50px'>Paraf</td>
			<td width='30%'></td>
			<td width='50%'></td>
		</tr>
		<tr>
			<td>Nama</td>
			<td><?=$nm_sales;?></td>
			<td>Engineer / Estimator : <?=$nm_eng;?></td>
		</tr>
		<tr>
			<td>Tanggal</td>
			<td><?=$tgl_sales;?></td>
			<td>Tanggal : <?=$tgl_eng;?></td>
		</tr>
	</table>
	<style type="text/css">
	@page {
		margin-top: 1cm;
		margin-left: 1.5cm;
		margin-right: 1cm;
		margin-bottom: 1cm;
	}
	p.foot1 {
		font-family: verdana,arial,sans-serif;
		font-size:10px;
	}
	.font{
		font-family: verdana,arial,sans-serif;
		font-size:14px;
	}
	.verT{
		vertical-align: top;
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
		font-size:11px;
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
		font-size:12px;
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
		vertical-align: top;
	}
	table.gridtable2 td.cols {
		border-width: 1px;
		padding: 3px;
		border-style: none;
		border-color: #666666;
		background-color: #ffffff;
		vertical-align: top;
	}

	table.cooltabs {
		font-size:12px;
		font-family: verdana,arial,sans-serif;
		border-width: 1px;
		border-style: solid;
		border-radius: 5px 5px 5px 5px;
	}
	table.cooltabs th.reg {
		font-family: verdana,arial,sans-serif;
		border-radius: 5px 5px 5px 5px;
		background: #e3e0e4;
		padding: 5px;
	}
	table.cooltabs td.reg {
		font-family: verdana,arial,sans-serif;
		border-radius: 5px 5px 5px 5px;
		padding: 5px;
	}
	#cooltabs {
		font-family: verdana,arial,sans-serif;
		border-width: 1px;
		border-style: solid;
		border-radius: 5px 5px 5px 5px;
		background: #e3e0e4;
		padding: 5px;
		width: 800px;
		height: 20px;
	}
	#cooltabs2{
		font-family: verdana,arial,sans-serif;
		border-width: 1px;
		border-style: solid;
		border-radius: 5px 5px 5px 5px;
		background: #e3e0e4;
		padding: 5px;
		width: 180px;
		height: 10px;
	}
	#space{
		padding: 3px;
		width: 180px;
		height: 1px;
	}
	p {
		margin: 0 0 0 0;
	}
	#hrnew {
		border: 0;
		border-bottom: 1px dashed #ccc;
		background: #999;
	}
	</style>


	<?php
	$footer = "<p style='font-family: verdana,arial,sans-serif; font-size:10px;'><i>Printed by : ".ucfirst(strtolower($printby)).", ".$today."</i></p>";
	$html = ob_get_contents();
	// exit;
	ob_end_clean();
	// $mpdf->SetWatermarkText('ORI Group');
	$mpdf->showWatermarkText = true;
	$mpdf->SetTitle("IPP INSTALASION ".$no_ipp);
	$mpdf->AddPage('P');
	$mpdf->SetFooter($footer);
	$mpdf->WriteHTML($html);
	$mpdf->Output("IPP Instalation ".$no_ipp.".pdf" ,'I');
}


?>
