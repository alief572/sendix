<?php
$arrInclude 	= (!empty($header[0]->include_check))?json_decode($header[0]->include_check):array();
$arrExclude 	= (!empty($header[0]->exclude_check))?json_decode($header[0]->exclude_check):array();
$arrIncludeTxt 	= (!empty($header[0]->include_text))?json_decode($header[0]->include_text):array();
$arrExcludeTxt 	= (!empty($header[0]->exclude_text))?json_decode($header[0]->exclude_text):array();
?>
<div class="box box-primary">
	<div class="box-body">
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>Project Name</b></label>
			<div class='col-sm-4'>
				<?php
				 echo form_input(array('id'=>'project_name','name'=>'project_name','class'=>'form-control input-sm','readonly'=>'readonly'),strtoupper($header[0]->project_name));
				?>
			</div>
			<label class='label-control col-sm-2'><b>Region | Tipe Instalasi</b></label>
			<div class='col-sm-2'>
				<?php
				 echo form_input(array('id'=>'region','name'=>'region','class'=>'form-control input-sm','readonly'=>'readonly'),strtoupper($header[0]->region));
				?>
			</div>
			<div class='col-sm-2'>
				<?php
				 echo form_input(array('id'=>'tipe','name'=>'tipe','class'=>'form-control input-sm','readonly'=>'readonly'),strtoupper($header[0]->tipe));
				?>
			</div>
		</div>
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>Project Location</b></label>
			<div class='col-sm-4'>
				<?php
				 echo form_textarea(array('id'=>'location','name'=>'location','class'=>'form-control input-sm','rows'=>'3','readonly'=>'readonly'),strtoupper($header[0]->location));
				?>
			</div>
			<label class='label-control col-sm-2'><b>Time/Day (Hours)</b></label>
			<div class='col-sm-4'>
				<?php
				 echo form_input(array('id'=>'total_time','name'=>'total_time','class'=>'form-control input-sm','readonly'=>'readonly'),strtoupper($header[0]->total_time));
				?>
			</div>
		</div>
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>BQ Project</b></label>
			<div class='col-sm-10'>
				<table  class="table table-striped table-bordered table-hover table-condensed" width="100%">
					<thead id='head_table_bq'>
						<tr class='bg-purple'>
							<th class="text-center" width='15%'>DN (mm)</th>
							<th class="text-center" width='15%'>DN (inch)</th>
							<th class="text-center" width='15%'>Qty</th>
							<th class="text-center" width='15%'>Satuan</th>
							<th class="text-center">Total Time (Hours)</th>
							<th class="text-center" width='15%'>Dia/Inch</th>
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
							<td class='text-center'>
								<span class='text-blue text-bold'><?=$header[0]->bq_qty;?></b>
							</td>
							<td class='text-center'>-</td>
							<td class='text-center'>
								<span class='text-blue text-bold'><?=$header[0]->bq_ct;?></b>
							</td>
							<td class='text-center'>
								<span class='text-blue text-bold'><?=$header[0]->day_in_total;?></b>
							</td>
						</tr>
						<tr>
							<td colspan='5' class='text-right'></td>
							<td class='text-center'>
								<b>Total Man Power</b><br>
								<span class='text-blue text-bold'><?=$header[0]->bq_mp;?></b>
							</td>
						</tr>
						<tr>
							<td colspan='5'></td>
							<td class='text-center'>
								<b>Time Est. (days)</b><br>
								<span class='text-blue text-bold'><?=$header[0]->bq_total;?></b>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>BQ Custom</b></label>
			<div class='col-sm-10'>
				<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
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
			</div>
		</div>
	</div>
</div>
<div class="box box-success">
	<div class="box-header">
		<h3 class="box-title">List Job Process</h3>
	</div>
	<div class="box-body">
	<?php if($tanda == 1){?>

		<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
			<thead>
				<tr class='bg-purple'>
					<th class="text-center">No</th>
					<th class="text-left">Job Name</th>
					<th class="text-left">Total Time</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no=0;
				foreach($restDetail AS $val => $valx){ $val++;
					$no++;
					$restDet1 = $this->db->get_where('project_detail_process', array('project_code_det'=>$valx['project_code_det'],'tipe'=>'heavy equipment'))->result_array();
					echo "<tr>";
						echo "<td align='center'  width='10%'>".$val."</td>";
						echo "<td align='left'  width='50%'>".strtoupper($valx['category'])."</td>";
						echo "<td align='left'  width='40%'>".$valx['std_time']."</td>";
					echo"</tr>";
					if(!empty($restDet1) OR !empty($restDet2) OR !empty($restDet3)){
						echo "<tr><td></td><td colspan='2'>";
							echo "<table class='table table-sm table-bordered table-striped' width='100%'>";
								echo "<tr class='bg-blue'>";
									echo "<th class='text-center' width='100%'>Heavy & Rental Equipment</th>";
								echo "</tr>";
								echo "<tr>";
									echo "<td align='center'>";
										echo "<table class='table table-sm table-bordered table-striped' width='100%'>";
										echo "<tr  class='bg-purple'>";
											echo "<th width='30%'>Category</th>";
											echo "<th width='30%'>Spesifikasi</th>";
											echo "<th width='20%' class='text-center'>Qty</th>";
											echo "<th width='20%' class='text-center'>Durasi</th>";
										echo "<tr>";
										foreach($restDet1 AS $val1 => $valx1){
											echo "<tr>";
												echo "<td width='30%'>".strtoupper(strtolower($valx1['category']))."</td>";
												echo "<td width='30%'>".strtoupper(strtolower($valx1['spec']))."</td>";
												echo "<td width='20%' align='center'>".$valx1['qty']."</td>";
												echo "<td width='20%' align='center'>".$valx1['qty']."</td>";
											echo "<tr>";
										}
										echo "</table>";
									echo "</td>";
								echo "</tr>";
							echo "</table>";
						echo "</td></tr>";
					}
					?>
					<?php
				}
				?>
			</tbody>
		</table>
	<?php } ?>
	</div>
 </div>

 <!-- akomodasi -->
 <div class="box box-info">
	 <!-- <div class="box-header">
		 <h3 class="box-title">List Akomodasi</h3>
	 </div> -->
 	<div class="box-body">
	<!-- Man Power -->
	<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='5'>&nbsp;&nbsp;&nbsp;Man Power & Uang Makan</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
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
						<td align='right' style='padding-right:40px;'><?=number_format($valx['jml_hari'],2);?></td>
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

		 <table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='5'>&nbsp;&nbsp;&nbsp;Consumable & Material</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
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
		<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='5'>&nbsp;&nbsp;&nbsp;Tools Equipment</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
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
		 <table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='5'>&nbsp;&nbsp;&nbsp;Heavy & Rental Equipment</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
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
						<td align='right' style='padding-right:40px;'><?=number_format($valx['std_time'],2);?></td>
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
		

		 <table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='7'>&nbsp;&nbsp;&nbsp;Acomodation & Transportation on Site</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
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
					echo "<td colspan='7'>Data not found ...</td>";
					echo "</tr>";
				}
 				?>
 			</tbody>
 		</table>
		
		 <table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='4'>&nbsp;&nbsp;&nbsp;Testing</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
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
		 <table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='4'>&nbsp;&nbsp;&nbsp;Etc</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
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
		 <table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='8'>&nbsp;&nbsp;&nbsp;Mob-Demob Man Power</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
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
					echo "<td colspan='8'>Data not found ...</td>";
					echo "</tr>";
				}
 				?>
 			</tbody>
 		</table>
		
		 <table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='7'>&nbsp;&nbsp;&nbsp;Mob Demob Equipment</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
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
		 

		 

		 <table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='6'>&nbsp;&nbsp;&nbsp;Survey</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
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

		 <table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='6'>&nbsp;&nbsp;&nbsp;Covid 19 Protokol</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
					<th class="text-center">Item Name</th>
					<th class="text-center" style='width: 8%;'>Jumlah</th>
					<th class="text-center" style='width: 8%;'>Durasi</th>
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

		 <table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
 			<thead>
				<tr class='bg-blue'>
					<th colspan='2'>&nbsp;&nbsp;&nbsp;INCLUDE EXCLUDE</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 50%;'>Include</th>
					<th class="text-center" style='width: 50%;'>Exclude</th>
				</tr>
 			</thead>
 			<tbody>
				<tr>
					<td>
						<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
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
					<td>
						<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
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

		 
 	</div>
</div>

<script>
swal.close();

</script>
