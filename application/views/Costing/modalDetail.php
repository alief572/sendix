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
	<div class="box-body">
		<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
			<thead id='head_table'>
				<tr class='bg-blue'>
					<th colspan='11'>&nbsp;&nbsp;&nbsp;Man Power & Uang Makan</th>
				</tr>
				<tr class='bg-purple'>
					<th class='text-center' style='width: 10%;'>Name Man Power</th>
					<th class='text-center'>Capacity</th>
					<th class='text-center' style='width: 6%;'>Qty (Orang)</th>
					<th class='text-center' style='width: 6%;'>Qty (Hari)</th>
					<th class='text-center' style='width: 6%;'>Qty OT (Jam)</th>
					<th class='text-center' style='width: 7%;'>Rate MP</th>
					<th class='text-center' style='width: 7%;'>Rate OT</th>
					<th class='text-center' style='width: 7%;'>Rate US</th>
					<th class='text-center' style='width: 7%;'>Rate UM</th>
					<th class='text-center' style='width: 9%;'>Cost MP (Day)</th>
					<th class='text-center' style='width: 9%;'>Total Cost</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$SUM_MP = 0;
				if(!empty($mp)){
					$no_mp = 0;
					foreach ($mp as $key => $value) {
					$no_mp++;
					$SUM_MP += $value['total_rate'];
					echo "<tr>";
						echo "<td class='text-left'>".strtoupper($value['category'])."</td>";
						echo "<td class='text-left'>".strtoupper($value['spec'])."</td>";
						echo "<td class='text-center'>".strtoupper($value['qty'])."</td>";
						echo "<td class='text-center'>".$value['jml_hari']."</td>";
						echo "<td class='text-center'>".$value['jml_jam']."</td>";
						echo "<td class='text-right'>".number_format($value['rate'])."</td>";
						echo "<td class='text-right'>".number_format($value['rate_ot'])."</td>";
						echo "<td class='text-right'>".number_format($value['rate_us'])."</td>";
						echo "<td class='text-right'>".number_format($value['rate_um'])."</td>";
						echo "<td class='text-right'>".number_format($value['rate_unit'])."</td>";
						echo "<td class='text-right sum_mp'>".number_format($value['total_rate'])."</td>";
					echo "</tr>";
					}
				}
				?>
				</tbody>
			<tfoot>
				<tr>
					<td colspan="10"><b>TOTAL</b></td>
					<td class='text-right text-bold'><?=number_format($SUM_MP);?></td>
				</tr>
			</tfoot>
		</table>

		<table class='table table-sm table-striped table-bordered table-hover table-condensed' width='100%'>
			<thead id='head_table'>
				<tr class='bg-blue'>
					<th colspan='8'>&nbsp;&nbsp;&nbsp;Consumable & Material</th>
				</tr>
				<tr class='bg-purple'>
					<th class='text-center' style='width: 20%;'>Consumable Name</th>
					<th class='text-center'>Capacity</th>
					<th class='text-center' style='width: 8%;'>Qty</th>
					<th class='text-center' style='width: 8%;'>Unit</th>
					<th class='text-center' style='width: 10%;'>Cost/Unit</th>
					<th class='text-center' style='width: 10%;'>Total Cost</th>


				</tr>
			</thead>
			<tbody>
				<?php
                $SUM_CN = 0;
                if(!empty($cn)){
                  $no_cn = 0;
                  foreach ($cn as $key => $value) {
                    $no_cn++;
                    $SUM_CN += $value['total_rate'];
                    echo "<tr>";
						echo "<td class='text-left'>".strtoupper($value['category'])."</td>";
						echo "<td class='text-left'>".strtoupper($value['spec'])."</td>";
						echo "<td class='text-center'>".strtoupper($value['qty'])."</td>";
						echo "<td class='text-center'>".$value['unit']."</td>";
						echo "<td class='text-right'>".number_format($value['rate'])."</td>";
						echo "<td class='text-right'>".number_format($value['total_rate'])."</td>";
                    echo "</tr>";
                  }
                }
                ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5"><b>TOTAL</b></td>
					<td class='text-right text-bold'><?=number_format($SUM_CN);?></td>
				</tr>
			</tfoot>
		</table>

		<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
			<thead id='head_table'>
				<tr class='bg-blue'>
					<th colspan='6'>&nbsp;&nbsp;&nbsp;Tools Equipment</th>
				</tr>
				<tr class='bg-purple'>
					<th class='text-center' style='width: 20%;'>Name Alat</th>
					<th class='text-center'>Capacity</th>
					<th class='text-center' style='width: 8%;'>Qty</th>
					<th class='text-center' style='width: 8%;'>Unit</th>
					<th class='text-center' style='width: 10%;'>Cost/Unit</th>
					<th class='text-center' style='width: 10%;'>Total Cost</th>
				</tr>
			</thead>
			<tbody>
				<?php
                $SUM_VT = 0;
                if(!empty($vt)){
                  foreach ($vt as $key => $value) {
                    $SUM_VT += $value['total_rate'];
                    echo "<tr>";
                      	echo "<td class='text-left'>".strtoupper($value['category'])."</td>";
                      	echo "<td class='text-left'>".strtoupper($value['spec'])."</td>";
                      	echo "<td class='text-center'>".strtoupper($value['qty'])."</td>";
						echo "<td class='text-center'>".$value['unit']."</td>";
  						echo "<td class='text-right'>".number_format($value['rate'])."</td>";
                      	echo "<td class='text-right'>".number_format($value['total_rate'])."</td>";
                    echo "</tr>";
                  }
                }
                ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5"><b>TOTAL</b></td>
					<td class='text-right text-bold'><?=number_format($SUM_VT);?></td>
				</tr>
			</tfoot>
		</table>

		<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
			<thead id='head_table'>
				<tr class='bg-blue'>
					<th colspan='8'>&nbsp;&nbsp;&nbsp;Heavy & Rental Equipment</th>
				</tr>
				<tr class='bg-purple'>
					<th class='text-center' style='width: 20%;'>Name Alat</th>
					<th class='text-center'>Capacity</th>
					<th class='text-center' style='width: 8%;'>Qty</th>
					<th class='text-center' style='width: 8%;'>Time (Day)</th>
					<th class='text-center' style='width: 10%;'>Cost</th>
					<th class='text-center' style='width: 10%;'>Cost Unit (Day)</th>
					<th class='text-center' style='width: 10%;'>Total Cost</th>
				</tr>
			</thead>
			<tbody>
				<?php
			$SUM_HE = 0;
			if(!empty($he)){
				foreach ($he as $key => $value) {
				$SUM_HE += $value['total_rate'];
				echo "<tr>";
					echo "<td class='text-left'>".strtoupper($value['category'])."</td>";
					echo "<td class='text-left'>".strtoupper($value['spec'])."</td>";
					echo "<td class='text-center'>".strtoupper($value['qty'])."</td>";
					echo "<td class='text-right' style='padding-right:40px;'>".$value['jml_hari']."</td>";
					echo "<td class='text-right'>".number_format($value['rate'])."</td>";
					echo "<td class='text-right'>".number_format($value['rate_unit'])."</td>";
					echo "<td class='text-right' >".number_format($value['total_rate'])."</td>";
				echo "</tr>";
				}
			}
			?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6"><b>TOTAL</b></td>
					<td class='text-right text-bold'><?=number_format($SUM_HE);?></td>
				</tr>
			</tfoot>
		</table>

		<table class='table table-sm table-striped table-bordered table-hover table-condensed' width='100%'>
			<thead>
				<tr class='bg-blue'>
					<th colspan='8'>&nbsp;&nbsp;&nbsp;Acomodation & Transportation on Site</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
					<th class="text-center" style='width: 30%;'>Item Cost</th>
					<th class="text-center" style='width: 15%;'>Qty</th>
					<th class="text-center" style='width: 8%;'>Time (Day)</th>
					<th class="text-center" style='width: 8%;'>Unit</th>
					<th class="text-center">Note</th>
					<th class="text-center" style='width: 10%;'>Cost</th>
					<th class="text-center" style='width: 10%;'>Total Cost</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$no=0;
					$SUM_HOUSE = 0;
					foreach($house AS $val => $valx){
						$no++;
						$SUM_HOUSE += $valx['total_rate'];
						?>
				<tr>
					<td align='center'><?=$no;?></td>
					<td><?=strtoupper($valx['spec']);?></td>
					<td align='center'><?=strtoupper(strtolower($valx['qty']));?></td>
					<td align='center'><?=strtoupper(strtolower($valx['jml_orang']));?></td>
					<td align='center'><?=strtoupper(strtolower($valx['area']));?></td>
					<td><?=strtoupper(strtolower($valx['note']));?></td>
					<td align='right'><?= number_format($valx['rate']);?></td>
					<td align='right'><?= number_format($valx['total_rate']);?></td>
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
			<?php if($tanda2 == 2){?>
			<tfoot>
				<tr>
					<td colspan="7"><b>TOTAL</b></td>
					<td align='right'><b><?=number_format($SUM_HOUSE);?></b></td>
				</tr>
			</tfoot>
			<?php } ?>
		</table>

		<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
			<thead>
				<tr class='bg-blue'>
					<th colspan='6'>&nbsp;&nbsp;&nbsp;Testing</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
					<th class="text-center">Item Name</th>
					<th class="text-center" style='width: 8%;'>Qty</th>
					<th class="text-center" style='width: 20%;'>Note</th>
					<th class="text-center" style='width: 10%;'>Cost</th>
					<th class="text-center" style='width: 10%;'>Total Cost</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no=0;
				$SUM_TESTING = 0;
				foreach($testing AS $val => $valx){
					$no++;
					$SUM_TESTING += $valx['total_rate'];
					?>
					<tr>
						<td align='center'><?=$no;?></td>
						<td><?=strtoupper($valx['spec']);?></td>
						<td align='center'><?=$valx['qty'];?></td>
						<td><?=strtoupper(strtolower($valx['note']));?></td>
						<td align='right'><?= number_format($valx['rate']);?></td>
						<td align='right'><?= number_format($valx['total_rate']);?></td>
					</tr>
					<?php
				}
				if(empty($testing)){
					echo "<tr>";
					echo "<td colspan='6'>Data not found ...</td>";
					echo "</tr>";
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5"><b>TOTAL</b></td>
					<td align='right'><b><?=number_format($SUM_TESTING);?></b></td>
				</tr>
			</tfoot>
		</table>
		
		<table class='table table-sm table-striped table-bordered table-hover table-condensed' width='100%'>
			<thead>
				<tr class='bg-blue'>
					<th colspan='6'>&nbsp;&nbsp;&nbsp;Etc</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
					<th class="text-center" style='width: 30%;'>Item Name</th>
					<th class="text-center" style='width: 8%;'>Qty</th>
					<th class="text-center">Note</th>
					<th class="text-center" style='width: 10%;'>Cost</th>
					<th class="text-center" style='width: 10%;'>Total Cost</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$no=0;
					$SUM_ETC = 0;
					foreach($etc AS $val => $valx){
						$no++;
						$SUM_ETC += $valx['total_rate'];
						?>
				<tr>
					<td align='center'><?=$no;?></td>
					<td><?=strtoupper($valx['spec']);?></td>
					<td align='center'><?=$valx['qty'];?></td>
					<td><?=strtoupper(strtolower($valx['note']));?></td>
					<td align='right'><?= number_format($valx['rate']);?></td>
					<td align='right'><?= number_format($valx['total_rate']);?></td>
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
			<tfoot>
				<tr>
					<td colspan="5"><b>TOTAL</b></td>
					<td align='right'><b><?=number_format($SUM_ETC);?></b></td>
				</tr>
			</tfoot>
		</table>

		<table class='table table-sm table-striped table-bordered table-hover table-condensed' width='100%'>
			<thead>
				<tr class='bg-blue'>
					<th colspan='8'>&nbsp;&nbsp;&nbsp;Mob-Demob Man Power</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
					<th class="text-center" style='width: 30%;'>Item Cost</th>
					<th class="text-center" style='width: 15%;'>Destination</th>
					<th class="text-center" style='width: 8%;'>Qty MP</th>
					<th class="text-center" style='width: 8%;'>Round-Trip</th>
					<th class="text-center">Note</th>
					<th class="text-center" style='width: 10%;'>Cost</th>
					<th class="text-center" style='width: 10%;'>Total Cost</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$no=0;
					$SUM_TRANS = 0;
					foreach($trans AS $val => $valx){
						$no++;
						$SUM_TRANS += $valx['total_rate'];
						?>
				<tr>
					<td align='center'><?=$no;?></td>
					<td><?=strtoupper($valx['category']." - ".$valx['spec']);?></td>
					<td align='left'><?=strtoupper(strtolower($valx['asal']." - ".$valx['tujuan']));?></td>
					<td align='center'><?=strtoupper(strtolower($valx['jml_orang']));?></td>
					<td align='center'><?=strtoupper(strtolower($valx['pulang_pergi']));?></td>
					<td><?=strtoupper(strtolower($valx['note']));?></td>
					<td align='right'><?= number_format($valx['rate']);?></td>
					<td align='right'><?= number_format($valx['total_rate']);?></td>
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
			<?php if($tanda2 == 2){?>
			<tfoot>
				<tr>
					<td colspan="7"><b>TOTAL</b></td>
					<td align='right'><b><?=number_format($SUM_TRANS);?></b></td>
				</tr>
			</tfoot>
			<?php } ?>
		</table>

		<table class='table table-sm table-striped table-bordered table-hover table-condensed' width='100%'>
			<thead>
				<tr class='bg-blue'>
					<th colspan='9'>&nbsp;&nbsp;&nbsp;Mob Demob Equipment</th>
				</tr>
				<tr class='bg-purple'>
					<th class='text-center' style='width: 4%;'>No</th>
					<th class='text-center' style='width: 22%;'>Item Name</th>
					<th class="text-center" style='width: 10%;'>Area</th>
					<th class="text-center" style='width: 15%;'>Tujuan</th>
					<th class="text-center" style='width: 20%;'>Truck</th>
					<th class="text-center" style='width: 5%;'>Qty</th>
					<th class='text-center'>Note</th>
					<th class='text-center' style='width: 9%;'>Cost</th>
					<th class='text-center' style='width: 9%;'>Total Cost</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no=0;
			$SUM_MDE = 0;
				foreach($mde AS $val => $valx){
					$no++;
					$SUM_MDE += $valx['total_rate'];
					?>
					<tr>
						<td align='center'><?=$no;?></td>
						<td><?=strtoupper($valx['spec']);?></td>
						<td align='left'><?=$valx['area'];?></td>
						<td align='left'><?=$valx['tujuan'];?></td>
						<td align='left'><?=api_get_nm_truck($valx['truck']);?></td>
						<td align='center'><?=$valx['jml_orang'];?></td>
						<td><?=strtoupper(strtolower($valx['note']));?></td>
						<td align='right'><?= number_format($valx['rate']);?></td>
						<td class='text-right sum_mde'><?= number_format($valx['total_rate']);?></td>
					</tr>
					<?php
				}
				if(empty($mde)){
					echo "<tr>";
					echo "<td colspan='9'>Data not found ...</td>";
					echo "</tr>";
				}
				?>
			</tbody>
			<?php if($tanda2 == 2){?>
				<tfoot>
				<tr>
					<td colspan="8"><b>TOTAL</b></td>
					<td align='right'><b><?=number_format($SUM_MDE);?></b></td>
				</tr>
				</tfoot>
			<?php } ?>
		</table>

		<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
			<thead>
				<tr class='bg-blue'>
					<th colspan='8'>&nbsp;&nbsp;&nbsp;Survey</th>
				</tr>
				<tr class='bg-purple'>
					<th class="text-center" style='width: 4%;'>No</th>
					<th class="text-center">Item Name</th>
					<th class="text-center" style='width: 8%;'>Jumlah Orang</th>
					<th class="text-center" style='width: 8%;'>Jumlah Qty</th>
					<th class="text-center" style='width: 8%;'>Jumlah Hari</th>
					<th class="text-center" style='width: 20%;'>Note</th>
					<th class="text-center" style='width: 10%;'>Cost</th>
					<th class="text-center" style='width: 10%;'>Total Cost</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no=0;
				$SUM_SURVEY = 0;
				foreach($survey AS $val => $valx){
					$no++;
					$SUM_SURVEY += $valx['total_rate'];
					?>
					<tr>
						<td align='center'><?=$no;?></td>
						<td><?=strtoupper($valx['spec']);?></td>
						<td align='center'><?=$valx['jml_orang'];?></td>
						<td align='center'><?=$valx['qty'];?></td>
						<td align='center'><?=$valx['jml_hari'];?></td>
						<td><?=strtoupper(strtolower($valx['note']));?></td>
						<td align='right'><?= number_format($valx['rate']);?></td>
						<td align='right'><?= number_format($valx['total_rate']);?></td>
					</tr>
					<?php
				}
				if(empty($testing)){
					echo "<tr>";
					echo "<td colspan='8'>Data not found ...</td>";
					echo "</tr>";
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="7"><b>TOTAL</b></td>
					<td align='right'><b><?=number_format($SUM_SURVEY);?></b></td>
				</tr>
			</tfoot>
		</table>

		<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
			<thead>
				<tr class='bg-blue'>
					<th colspan='9'>&nbsp;&nbsp;&nbsp;Covid 19 Protokol</th>
				</tr>
				<tr class='bg-purple'>
				<th class="text-center" style='width: 4%;'>No</th>
					<th class='text-center' style='width: 30%;'>Item Name</th>
					<th class="text-center" style='width: 10%;'>Jumlah</th>
					<th class="text-center" style='width: 8%;'>Satuan</th>
					<th class="text-center" style='width: 10%;'>Durasi</th>
					<th class="text-center" style='width: 8%;'>Satuan</th>
					<th class='text-center'>Note</th>
					<th class='text-center' style='width: 9%;'>Cost</th>
					<th class='text-center' style='width: 9%;'>Total Cost</th>
			</thead>
			<tbody>
					<?php
					$no=0;
				$SUM_COVID = 0;
					foreach($covid AS $val => $valx){
						$no++;
					$SUM_COVID += $valx['total_rate'];
						?>
						<tr>
							<td align='center'><?=$no;?></td>
							<td><?=strtoupper($valx['spec']);?></td>
							<td align='center'><?=$valx['jml_orang'];?></td>
							<td align='center'>Orang</td>
							<td align='center'><?=$valx['jml_hari'];?></td>
							<td align='center'>Hari</td>
							<td><?=strtoupper(strtolower($valx['note']));?></td>
							<td align='right'><?= number_format($valx['rate']);?></td>
							<td class='text-right sum_covid'><?= number_format($valx['total_rate']);?></td>
						</tr>
						<?php
					}
				if(empty($covid)){
					echo "<tr>";
					echo "<td colspan='9'>Data not found ...</td>";
					echo "</tr>";
				}
					?>
				</tbody>
			<tfoot>
				<tr>
					<td colspan="8"><b>TOTAL</b></td>
					<td align='right'><b><?=number_format($SUM_COVID);?></b></td>
				</tr>
				<tr>
					<td colspan="8"><b>TOTAL ALL</b></td>
					<td align='right'>
						<b><?=number_format($SUM_VT + $SUM_HE + $SUM_MP + $SUM_CN + $SUM_COVID + $SUM_HOUSE + $SUM_TRANS + $SUM_ETC + $SUM_TESTING + $SUM_SURVEY + $SUM_MDE);?></b>
					</td>
				</tr>
			</tfoot>
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
		<?php
		if(!empty($budget)){ ?>
		<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
			<thead>
				<tr class='bg-blue'>
					<th colspan='9'>&nbsp;&nbsp;&nbsp;Budget</th>
				</tr>
				<tr class='bg-purple'>
					<tr class='bg-purple'>
					<th class="text-center" >Item Cost</th>
					<th class="text-center" style='width: 9%;'>Total Cost</th>
					<th class="text-center" style='width: 9%;'>Profit</th>
					<th class="text-center" style='width: 9%;'>Total+Profit</th>
					<th class="text-center" style='width: 9%;'>Allowance</th>
					<th class="text-center" style='width: 9%;'>ED</th>
					<th class="text-center" style='width: 9%;'>Interest</th>
					<th class="text-center" style='width: 9%;'>PPH21</th>
					<th class="text-center" style='width: 9%;'>Selling Price</th>
				</tr>
			</thead>
			<tbody>
			<?php
            $nomor = 0;
            foreach ($budget as $key => $value) {  $nomor++;
			$bold = '';
				if($value['view_'] == '7' OR $value['view_'] == '13' OR $value['view_'] == '14' OR $value['view_'] == '15' OR $value['view_'] == '16'){
					$bold = 'text-bold';
				}
				$warna = '';
				if($value['view_'] == '14'){
					$warna = 'text-blue';
				}
				if($value['view_'] == '15'){
					$warna = 'text-green';
				}
				if($value['view_'] == '16'){
					$warna = 'text-purple';
				}

				$total_cost = '';
				$profit = '';
				$total_profit = '';
				$allowance = '';
				$ed = '';
				$interest = '';
				$pph = '';
				$selling_price = number_format($value['selling_price']);
				if($value['view_'] < 15){
					$total_cost = number_format($value['total_cost']);
					$profit	 = number_format($value['profit']);
					$total_profit = number_format($value['total_profit']);
					$allowance = number_format($value['allowance']);
					$ed = number_format($value['ed']);
					$interest = number_format($value['interest']);
					$pph = number_format($value['pph']);
					$selling_price = number_format($value['selling_price']);
				}
				if($value['view_'] == 16){
					$selling_price = number_format($value['selling_price'],2);
				}
                ?>
                <tr>
                  	<td class='<?=$bold;?> <?=$warna;?>'><?= strtoupper($value['jenis_profit']);?></td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right'><?= $total_cost;?></td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right'><?= $profit;?></td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right'><?= $total_profit;?></td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right'><?= $allowance;?></td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right'><?= $ed;?></td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right'><?= $interest;?></td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right'><?= $pph;?></td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right'><?= $selling_price;?></td>
                </tr>
            <?php } ?>
			</tbody>
		</table> 
		<?php } ?>
	</div>
</div>

<script>
	swal.close();
</script>