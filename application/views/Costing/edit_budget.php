<?php
$this->load->view('include/side_menu');

$profit 	= (!empty($header[0]->profit))?$header[0]->profit:'30';
$allowance 	= (!empty($header[0]->allowance))?$header[0]->allowance:'10';
$ed 		= (!empty($header[0]->ed))?$header[0]->ed:'5';
$interest 	= (!empty($header[0]->interest))?$header[0]->interest:'1';
$pph 		= (!empty($header[0]->pph))?$header[0]->pph:'2';
?>
<form action="#" method="POST" id="form_work" autocomplete="off">

  <div class="box box-primary">
    <div class="box-header">
  		<h3 class="box-title"><?php echo $title;?></h3>
  		<div class="box-tool pull-right">

  		</div>
  	</div>
    <div class="box-body">
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>Project Name</b></label>
			<div class='col-sm-4'>
				<input type='hidden' id='project_code' name='project_code' class='form-control input-sm' readonly='readonly' value='<?=$header[0]->project_code;?>'>
				<input type='text' id='project_name' name='project_name' class='form-control input-sm' readonly='readonly' value='<?= strtoupper($header[0]->project_name);?>'>
			</div>
			<label class='label-control col-sm-2'><b>Region | Tipe Instalasi </b></label>
			<div class='col-sm-2'>
				<input type='text' id='region_code' name='region_code' class='form-control input-sm' readonly='readonly' value='<?= strtoupper($header[0]->region_code);?>'>
			</div>
			<div class='col-sm-2'>
				<input type='text' id='tipe' name='tipe' class='form-control input-sm' readonly='readonly' value='<?= strtoupper($header[0]->tipe);?>'>
			</div>
		</div>
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>Project Location</b></label>
			<div class='col-sm-4'>
				<textarea type='text' id='location' name='location' class='form-control input-sm' readonly='readonly' rows='3'><?= strtoupper($header[0]->location);?></textarea>
			</div>
			<label class='label-control col-sm-2'><b>Time/Day (Hours)</b></label>
			<div class='col-sm-4'>
				<input type='text' id='total_time' name='total_time' class='form-control input-sm' readonly='readonly' value='<?= strtoupper($header[0]->total_time);?>'>
			</div>
		</div>

		<!-- bq project -->
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
								<span class='text-blue text-bold' id='tot_dayin'><?=$header[0]->day_in_total;?></b>
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
		<!-- end bq project -->

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

    
	<div class="box box-success">
  				<div class="box-header">
  					<h3 class="box-title">1. Man Power & Uang Makan</h3>
  					<button type="button" id='btn_sh_mp' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
					  <label id='sum_mp' class='text-bold text-success' style='float:right; font-size:20px; margin-right:30px;'></label>
  				</div>
  				<div class="box-body sh_mp">
          <table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
              <thead id='head_table'>
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
          </div>
        </div>
	

      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">2. Consumable & Material</h3>
          <button type="button" id='btn_sh_cn' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
		  <label id='sum_cn' class='text-bold text-success' style='float:right; font-size:20px; margin-right:30px;'></label>
        </div>
        <div class="box-body sh_cn">
          <table class='table table-sm table-striped table-bordered table-hover table-condensed' width='100%'>
              <thead id='head_table'>
                <tr class='bg-purple'>
                  <th class='text-center' style='width: 20%;'>Item Name</th>
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
						echo "<td class='text-right sum_cn'>".number_format($value['total_rate'])."</td>";
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
          </div>
        </div>

		<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title">3. Tools Equipment</h3>
			<button type="button" id='btn_sh_vt' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
			<label id='sum_vt' class='text-bold text-success' style='float:right; font-size:20px; margin-right:30px;'></label>
		</div>
		<div class="box-body sh_vt">
		<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
              	<thead id='head_table'>
					<tr class='bg-purple'>
						<th class='text-center' style='width: 20%;'>Item Name</th>
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
                      	echo "<td class='text-right sum_vt'>".number_format($value['total_rate'])."</td>";
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
        </div>
    </div>

	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title">4. Heavy & Rental Equipment</h3>
			<button type="button" id='btn_sh_he' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
			<label id='sum_he' class='text-bold text-success' style='float:right; font-size:20px; margin-right:30px;'></label>
		</div>
		<div class="box-body sh_he">
          	<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
              	<thead id='head_table'>
					<tr class='bg-purple'>
						<th class='text-center' style='width: 20%;'>Item Name</th>
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
                      	echo "<td class='text-center'>".strtoupper($value['qty_'])."</td>";
                      	echo "<td class='text-center'>".$value['jml_hari_']."</td>";
  						echo "<td class='text-right'>".number_format($value['rate'])."</td>";
                      	echo "<td class='text-right'>".number_format($value['rate_unit'])."</td>";
                      	echo "<td class='text-right sum_he'>".number_format($value['total_rate'])."</td>";
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
        </div>
    </div>

        

      <div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">5. Acomodation & Transportation on Site</h3>
					<button type="button" id='btn_sh_house' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
					<label id='sum_house' class='text-bold text-success' style='float:right; font-size:20px; margin-right:30px;'></label>
				</div>
				<div class="box-body sh_house">
				<table class='table table-sm table-striped table-bordered table-hover table-condensed' width='100%'>
       			<thead>
      				<tr class='bg-purple'>
      					<th class="text-center" style='width: 4%;'>No</th>
      					<th class="text-center" style='width: 30%;'>Item Cost</th>
      					<th class="text-center" style='width: 8%;'>Qty</th>
      					<th class="text-center" style='width: 8%;'>Total (Day)</th>
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
      						<td class='text-right sum_house'><?= number_format($valx['total_rate']);?></td>
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
            <tfoot>
              <tr>
                <td colspan="7"><b>TOTAL</b></td>
                <td align='right'><b><?=number_format($SUM_HOUSE);?></b></td>
              </tr>
            </tfoot>
       		</table>
        </div>
      </div>

	  <div class="box box-danger">
        <div class="box-header">
          <h3 class="box-title">6. Testing</h3>
          <button type="button" id='btn_sh_testing' style='width:100px; float:right;' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
		  <label id='sum_testing' class='text-bold text-success' style='float:right; font-size:20px; margin-right:30px;'></label>
		</div>
        <div class="box-body sh_testing">
		<table class='table table-sm table-striped table-bordered table-hover table-condensed' width='100%'>
            <thead>
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
            		<td class='text-right sum_testing'><?= number_format($valx['total_rate']);?></td>
            		</tr>
            		<?php
            	}
            if(empty($survey)){
            	echo "<tr>";
            	echo "<td colspan='4'>Data not found ...</td>";
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
        </div>
      </div>

	  <div class="box box-danger">
        <div class="box-header">
          <h3 class="box-title">7. Etc</h3>
          <button type="button" id='btn_sh_etc' style='width:100px; float:right;' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
		  <label id='sum_etc' class='text-bold text-success' style='float:right; font-size:20px; margin-right:30px;'></label>
		</div>
        <div class="box-body sh_etc">
		<table class='table table-sm table-striped table-bordered table-hover table-condensed' width='100%'>
            <thead>
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
            		<td class='text-right sum_etc'><?= number_format($valx['total_rate']);?></td>
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
        </div>
      </div>

      <div class="box box-warning">
        <div class="box-header">
          <h3 class="box-title">8. Mob-Demob Man Power</h3>
          <button type="button" id='btn_sh_trans' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
		  <label id='sum_trans' class='text-bold text-success' style='float:right; font-size:20px; margin-right:30px;'></label>
        </div>
        <div class="box-body sh_trans">
		<table class='table table-sm table-striped table-bordered table-hover table-condensed' width='100%'>
       			<thead>
      				<tr class='bg-purple'>
      					<th class="text-center" style='width: 4%;'>No</th>
      					<th class="text-center" style='width: 30%;'>Item Cost</th>
      					<th class="text-center" style='width: 15%;'>Destination</th>
      					<th class="text-center" style='width: 10%;'>Total MP (Day)</th>
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
       						<td><?=strtoupper($valx['category'].' - '.$valx['spec']);?></td>
      						<td align='left'><?=strtoupper(strtolower($valx['asal']." - ".$valx['tujuan']));?></td>
      						<td align='center'><?=strtoupper(strtolower($valx['jml_orang']));?></td>
      						<td align='center'><?=strtoupper(strtolower($valx['pulang_pergi']));?></td>
      						<td><?=strtoupper(strtolower($valx['note']));?></td>
      						<td align='right'><?= number_format($valx['rate']);?></td>
      						<td class='text-right sum_trans'><?= number_format($valx['total_rate']);?></td>
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
            <tfoot>
              <tr>
                <td colspan="7"><b>TOTAL</b></td>
                <td align='right'><b><?=number_format($SUM_TRANS);?></b></td>
              </tr>
            </tfoot>
       		</table>
        </div>
      </div>

	  <div class="box box-warning">
        <div class="box-header">
          <h3 class="box-title">9. Mob Demob Equipment</h3>
          <button type="button" id='btn_sh_mde' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
		  <label id='sum_mde' class='text-bold text-success' style='float:right; font-size:20px; margin-right:30px;'></label>
        </div>
        <div class="box-body sh_mde">
		<table class='table table-sm table-striped table-bordered table-hover table-condensed' width='100%'>
       			<thead>
      				<tr class='bg-purple'>
					  	<th class='text-center' style='width: 4%;'>No</th>
					  	<th class='text-center' style='width: 22%;'>Item Name</th>
						<th class="text-center" style='width: 8%;'>Area</th>
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
							<td align='left'><?=strtoupper($valx['area']);?></td>
							<td align='left'><?=strtoupper($valx['tujuan']);?></td>
							<td align='left'><?=strtoupper(api_get_nm_truck($valx['truck']));?></td>
							<td align='center'><?=strtoupper(strtolower($valx['jml_orang']));?></td>
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
            <tfoot>
              <tr>
                <td colspan="8"><b>TOTAL</b></td>
                <td align='right'><b><?=number_format($SUM_MDE);?></b></td>
              </tr>
            </tfoot>
       		</table>
        </div>
      </div>
	
	  

      

	  <div class="box box-danger">
        <div class="box-header">
          <h3 class="box-title">10. Survey</h3>
          <button type="button" id='btn_sh_survey' style='width:100px; float:right;' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
		  <label id='sum_survey' class='text-bold text-success' style='float:right; font-size:20px; margin-right:30px;'></label>
		</div>
        <div class="box-body sh_survey">
		<table class='table table-sm table-striped table-bordered table-hover table-condensed' width='100%'>
            <thead>
              <tr class='bg-purple'>
              	<th class="text-center" style='width: 4%;'>No</th>
              	<th class="text-center" style='width: 30%;'>Item Name</th>
              	<th class="text-center" style='width: 9%;'>Jumlah Orang</th>
              	<th class="text-center" style='width: 9%;'>Jumlah Qty</th>
              	<th class="text-center" style='width: 9%;'>Jumlah Hari</th>
              	<th class="text-center">Note</th>
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
            			<td class='text-right sum_survey'><?= number_format($valx['total_rate']);?></td>
            		</tr>
            		<?php
            	}
            if(empty($survey)){
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
        </div>
      </div>

	  <div class="box box-danger">
        <div class="box-header">
          <h3 class="box-title">11. Covid 19 Protokol</h3>
          <button type="button" id='btn_sh_covid' style='width:100px; float:right;' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
		  <label id='sum_covid' class='text-bold text-success' style='float:right; font-size:20px; margin-right:30px;'></label>
		</div>
        <div class="box-body sh_covid">
		<table class='table table-sm table-striped table-bordered table-hover table-condensed' width='100%'>
            <thead>
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
              </tr>
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
            </tfoot>
          </table>
        </div>
      </div>

      <div class="box box-info">
        <div class="box-body ">
          <table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
            <tfoot>
              <tr>
                <td colspan="5"><b>TOTAL ALL</b></td>
                <td align='right' style='width: 8%;'>
                  <?php
                    $SUM_ALL = $SUM_VT + $SUM_HE + $SUM_MP + $SUM_CN + $SUM_HOUSE + $SUM_TRANS + $SUM_ETC + $SUM_SURVEY + $SUM_TESTING + $SUM_MDE + $SUM_COVID;
                    echo form_input(array('type'=>'hidden','id'=>'rate','name'=>'rate','class'=>'form-control input-sm','autocomplete'=>'off','readonly'=>'readonly'),$SUM_ALL);
                  ?>
                <label id='sum_total' class='text-bold text-purple' style='float:right; font-size:20px; margin-right:125px;'></label>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <!-- <div class="box box-warning">
        <div class="box-header">
          <h3 class="box-title">Setting Budget</h3>
        </div>
        <div class="box-body">
          <table class="table table-bordered table-striped" width='50%'>
       			<thead>
      				<tr class='bg-purple'>
      					<th class="text-center" >#</th>
      					<th class="text-center" style='width: 8%;'>Persen (%)</th>
      					<th class="text-center" style='width: 15%;'>#</th>
      				</tr>
       			</thead>
       			<tbody>
            <?php
            $nomor = 0;
            foreach ($list_budget as $key => $value) {  $nomor++;

                $rev = (!empty($value['rev_budget']))?$value['rev_budget']:0;
				$readonly = ($nomor == '4')?'readonly':'';
				$kanan = ($nomor == '4')?'text-right':'';
				$space = ($nomor == '2' OR $nomor == '5' OR $nomor == '6' OR $nomor == '7' OR $nomor == '8')?'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;':'';
              ?>
                <tr>
                  <td class='<?=$kanan;?>' style='vertical-align:middle;'><b><?= $space.strtoupper($value['category_list']);?></b></td>
                  <td>
                    <?php
                     echo form_input(array('type'=>'hidden','id'=>'jenis_profit_'.$nomor,'name'=>'DetailProfit['.$nomor.'][jenis_profit]','class'=>'form-control input-sm'),$value['category_list']);
                     echo form_input(array('type'=>'hidden','id'=>'rev_budget_'.$nomor,'name'=>'DetailProfit['.$nomor.'][rev_budget]','class'=>'form-control input-sm'),$rev);
					 if($nomor != '1' AND $nomor != '3' AND $nomor != '9' AND $nomor != '10'){
                    	 echo form_input(array('type'=>'text','id'=>'persen_'.$nomor,'name'=>'DetailProfit['.$nomor.'][persen]','class'=>'form-control input-sm text-right autoNumeric chBudget profit-size',$readonly=>$readonly),number_format($value['view_']));
					 }
					 ?>
                  </td>
                  <td>
                    <?php
					 if($nomor != '4'){
                     echo form_input(array('id'=>'cost_'.$nomor,'name'=>'DetailProfit['.$nomor.'][cost]','class'=>'form-control input-sm text-right autoNumeric0 profit-size','autocomplete'=>'off','readonly'=>'readonly'));
					 }
					?>
                  </td>
                </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div> -->
	
	  <div class="box box-warning">
	  <div class="box-header">
          <h3 class="box-title">Setting Budget</h3>
        </div>
        <div class="box-body">
          <table class="table table-bordered table-striped" width='50%'>
       			<thead>
      				<tr class='bg-purple'>
      					<th class="text-center" >Item Cost</th>
      					<th class="text-center" style='width: 9%;'>Total Cost</th>
      					<th class="text-center" style='width: 9%;'>Profit</th>
      					<th class="text-center" style='width: 9%;'>Total+Profit</th>
      					<th hidden class="text-center" style='width: 9%;'>Net Profit</th>
      					<th class="text-center" style='width: 9%;'>Allowance</th>
      					<th class="text-center" style='width: 9%;'>ED</th>
      					<th class="text-center" style='width: 9%;'>Interest</th>
      					<th class="text-center" style='width: 9%;'>PPH21</th>
      					<th class="text-center" style='width: 9%;'>Selling Price</th>
      				</tr>
       			</thead>
       			<tbody>
				   <tr>
      					<td class="text-center"></td>
      					<td class="text-center"></td>
      					<td class="text-center"><input type="text" name='profit' id='profit' class='form-control input-md text-center autoNumeric chBudget' value='<?=$profit;?>'></td>
      					<td class="text-center"></td>
      					<td hidden class="text-center"></td>
      					<td class="text-center"><input type="text" name='allowance' id='allowance' class='form-control input-md text-center autoNumeric chBudget' value='<?=$allowance;?>'></td>
      					<td class="text-center"><input type="text" name='ed' id='ed' class='form-control input-md text-center autoNumeric chBudget' value='<?=$ed;?>'></td>
      					<td class="text-center"><input type="text" name='interest' id='interest' class='form-control input-md text-center autoNumeric chBudget' value='<?=$interest;?>'></td>
      					<td class="text-center"><input type="text" name='pph' id='pph' class='form-control input-md text-center autoNumeric chBudget' value='<?=$pph;?>'></td>
      					<td class="text-center"></d>
      				</tr>
            <?php
            $nomor = 0;
            foreach ($list_budget2 as $key => $value) {  $nomor++;
				$bold = '';
				if($value['view_'] == '7' OR $value['view_'] == '13' OR $value['view_'] == '14' OR $value['view_'] == '15' OR $value['view_'] == '16'){
					$bold = 'text-bold';
				}
				$tc_all = 'tc_all';
				$tc_all2 = 'tc_all_value';
				if($value['view_'] == '15' OR $value['view_'] == '16'){
					$tc_all = '';
					$tc_all2 = '';
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
                ?>
                <tr>
                  <td class='<?=$bold;?> <?=$warna;?>'><?= strtoupper($value['category_list']);?>
                    <?php
					if($value['view_'] != '15' AND $value['view_'] != '16'){
						echo form_input(array('type'=>'hidden','name'=>'DetailProfit2['.$nomor.'][jenis_profit]','class'=>'form-control input-sm'),$value['category_list']);
						// echo form_input(array('type'=>'hidden','name'=>'DetailProfit2['.$nomor.'][rev_budget]','class'=>'form-control input-sm'),$rev);
						echo form_input(array('type'=>'hidden','name'=>'DetailProfit2['.$nomor.'][total_cost]','class'=>'form-control input-sm '.$tc_all2,'id'=>'tcv_'.$value['view_']));
						echo form_input(array('type'=>'hidden','name'=>'DetailProfit2['.$nomor.'][profit]','class'=>'form-control input-sm profit_all_val'));
						echo form_input(array('type'=>'hidden','name'=>'DetailProfit2['.$nomor.'][total_profit]','class'=>'form-control input-sm tcprofit_all_val'));
						echo form_input(array('type'=>'hidden','name'=>'DetailProfit2['.$nomor.'][allowance]','class'=>'form-control input-sm allow_all_val'));
						echo form_input(array('type'=>'hidden','name'=>'DetailProfit2['.$nomor.'][ed]','class'=>'form-control input-sm ed_all_val'));
						echo form_input(array('type'=>'hidden','name'=>'DetailProfit2['.$nomor.'][interest]','class'=>'form-control input-sm interest_all_val'));
						echo form_input(array('type'=>'hidden','name'=>'DetailProfit2['.$nomor.'][pph]','class'=>'form-control input-sm pph_all_val'));
						echo form_input(array('type'=>'hidden','name'=>'DetailProfit2['.$nomor.'][selling_price]','class'=>'form-control input-sm selling_all_val'));
					}
					else{
						echo form_input(array('type'=>'hidden','name'=>'DetailProfit2['.$nomor.'][jenis_profit]','class'=>'form-control input-sm'),$value['category_list']);
						echo form_input(array('type'=>'hidden','id'=>'sp2_'.$value['view_'],'name'=>'sp_'.$value['view_'],'class'=>'form-control input-sm selling_all_val'));
					}
					?>
                  </td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right <?=$tc_all;?>' id='tc_<?=$value['view_'];?>'></td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right profit_all' id='p_<?=$value['view_'];?>'></td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right tcprofit_all' id='tp_<?=$value['view_'];?>'></td>
                  	<td hidden class='<?=$bold;?> <?=$warna;?> text-right netprofit_all'></td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right allow_all'></td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right ed_all'></td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right interest_all'></td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right pph_all'></td>
                  	<td class='<?=$bold;?> <?=$warna;?> text-right selling_all' id='sp_<?=$value['view_'];?>'></td>
                </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>

        <div class='box-footer'>
          <?php
          echo form_button(array('type'=>'button','class'=>'btn btn-md btn-primary','value'=>'save','content'=>'Save','id'=>'save_work')).' ';
          echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','value'=>'back','content'=>'Back','id'=>'back_work'));
          ?>
        </div>

       </div>
     </div>
  </div>
  <!-- /.box -->
</form>

<?php $this->load->view('include/footer'); ?>
<style type="text/css">
	.chosen-container-active .chosen-single {
	     border: none;
	     box-shadow: none;
	}
	.chosen-container-single .chosen-single {
		height: 34px;
	    border: 1px solid #d2d6de;
	    border-radius: 0px;
	     background: none;
	    box-shadow: none;
	    color: #444;
	    line-height: 32px;
	}
	.chosen-container-single .chosen-single div{
		top: 5px;
	}
	#save_category {
	  color: white;
	  background-color: #605ca8;
	}
	.labDet{
		font-weight: bold;
		margin: 5px 0px 3px 5px;
		color: #0376c7;
	}
	.labAdd{
		font-weight: bold;
		margin: 5px 0px 3px 5px;
		color: #0aa92c;
	}
	.clSelect{
		width: 100% !important;
	}
  .cldelete{
		cursor : pointer;
		color: white;
		background-color: #ce1111 !important;
	}
  textarea {
    resize: none;
  }
  .aAdd{
		margin-top: 10px;
		min-width: 150px;
	}
  .widCtr{
		width: 50px !important;
	}
	.widC{
		width: 300px !important;
	}
  .btn_show_up{
    width: 100px;
    float: right;
    font-weight: bold;
  }
  .profit-size{
	font-size: 16px;
  }
</style>
<script>
	$(document).ready(function(){
	get_sum();
    // get_budget();
    get_budget2();
    $(".hideSP").hide();
    $(".maskM").maskMoney();

    $(".chBudget").on("keypress keyup blur",function () {
		// get_budget();
		get_budget2();
	});

    //SHOW HIDE
		$('.sh_he').hide();
		$('.sh_vt').hide();
		$('.sh_mp').hide();
		$('.sh_cn').hide();
		$('.sh_ak').hide();
		$('.sh_trans').hide();
		$('.sh_house').hide();
		$('.sh_etc').hide();
		$('.sh_testing').hide();
		$('.sh_survey').hide();
		$('.sh_mde').hide();
		$('.sh_covid').hide();

		$(document).on('click','#btn_sh_vt', function(){
			$('.sh_vt').slideToggle("slow");
			var htmL = $(this).html();
			if(htmL == 'SHOW'){ $(this).html('HIDE') }
			if(htmL == 'HIDE'){ $(this).html('SHOW') }
		});

		$(document).on('click','#btn_sh_he', function(){
			$('.sh_he').slideToggle("slow");
			var htmL = $(this).html();
			if(htmL == 'SHOW'){ $(this).html('HIDE') }
			if(htmL == 'HIDE'){ $(this).html('SHOW') }
		});

		$(document).on('click','#btn_sh_mp', function(){
			$('.sh_mp').slideToggle("slow");
			var htmL = $(this).html();
			if(htmL == 'SHOW'){ $(this).html('HIDE') }
			if(htmL == 'HIDE'){ $(this).html('SHOW') }
		});

		$(document).on('click','#btn_sh_cn', function(){
			$('.sh_cn').slideToggle("slow");
			var htmL = $(this).html();
			if(htmL == 'SHOW'){ $(this).html('HIDE') }
			if(htmL == 'HIDE'){ $(this).html('SHOW') }
		});

		$(document).on('click','#btn_sh_ak', function(){
			$('.sh_ak').slideToggle("slow");
			var htmL = $(this).html();
			if(htmL == 'SHOW'){ $(this).html('HIDE') }
			if(htmL == 'HIDE'){ $(this).html('SHOW') }
		});

		$(document).on('click','#btn_sh_house', function(){
			$('.sh_house').slideToggle("slow");
			var htmL = $(this).html();
			if(htmL == 'SHOW'){ $(this).html('HIDE') }
			if(htmL == 'HIDE'){ $(this).html('SHOW') }
		});

		$(document).on('click','#btn_sh_trans', function(){
			$('.sh_trans').slideToggle("slow");
			var htmL = $(this).html();
			if(htmL == 'SHOW'){ $(this).html('HIDE') }
			if(htmL == 'HIDE'){ $(this).html('SHOW') }
		});

		$(document).on('click','#btn_sh_etc', function(){
			$('.sh_etc').slideToggle("slow");
			var htmL = $(this).html();
			if(htmL == 'SHOW'){ $(this).html('HIDE') }
			if(htmL == 'HIDE'){ $(this).html('SHOW') }
		});

		$(document).on('click','#btn_sh_testing', function(){
			$('.sh_testing').slideToggle("slow");
			var htmL = $(this).html();
			if(htmL == 'SHOW'){ $(this).html('HIDE') }
			if(htmL == 'HIDE'){ $(this).html('SHOW') }
		});

		$(document).on('click','#btn_sh_survey', function(){
			$('.sh_survey').slideToggle("slow");
			var htmL = $(this).html();
			if(htmL == 'SHOW'){ $(this).html('HIDE') }
			if(htmL == 'HIDE'){ $(this).html('SHOW') }
		});

		$(document).on('click','#btn_sh_mde', function(){
			$('.sh_mde').slideToggle("slow");
			var htmL = $(this).html();
			if(htmL == 'SHOW'){ $(this).html('HIDE') }
			if(htmL == 'HIDE'){ $(this).html('SHOW') }
		});

		$(document).on('click','#btn_sh_covid', function(){
			$('.sh_covid').slideToggle("slow");
			var htmL = $(this).html();
			if(htmL == 'SHOW'){ $(this).html('HIDE') }
			if(htmL == 'HIDE'){ $(this).html('SHOW') }
		});

		//save
		$('#save_work').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);

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
						var baseurl		= base_url + active_controller +'/edit_budget';
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
										  timer	: 7000
										});
									window.location.href = base_url + active_controller+'/budget';
								}
								else if(data.status == 2 || data.status == 3){
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 7000
									});
								}

								$('#save_work').prop('disabled',false);
							},
							error: function() {

								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',
								  type				: "warning",
								  timer				: 7000,
								  showCancelButton	: false,
								  showConfirmButton	: false,
								  allowOutsideClick	: false
								});
								$('#save_work').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#save_work').prop('disabled',false);
					return false;
				  }
			});
		});

		$('#back_work').click(function(e){
			window.location.href = base_url + active_controller+'/budget';
		});

	});

	function get_sum(){
		var SUM_HE = 0;
		var SUM_VT = 0;
		var SUM_CN = 0;
		var SUM_MP = 0;
		var SUM_HOUSE = 0;
		var SUM_TRANS = 0;
		var SUM_ETC = 0;
		var SUM_TESTING = 0;
		var SUM_SURVEY = 0;
		var SUM_MDE = 0;
		var SUM_COVID = 0;

		$(".sum_he" ).each(function() {
			SUM_HE += Number(getNum($(this).html().split(",").join("")));
   	 	});
		$(".sum_vt" ).each(function() {
			SUM_VT += Number(getNum($(this).html().split(",").join("")));
   	 	});
		$(".sum_cn" ).each(function() {
			SUM_CN += Number(getNum($(this).html().split(",").join("")));
    	});
		$(".sum_mp" ).each(function() {
			SUM_MP += Number(getNum($(this).html().split(",").join("")));
    	});
		$(".sum_house" ).each(function() {
			SUM_HOUSE += Number(getNum($(this).html().split(",").join("")));
   		});
		$(".sum_trans" ).each(function() {
			SUM_TRANS += Number(getNum($(this).html().split(",").join("")));
    	});
		$(".sum_etc" ).each(function() {
			SUM_ETC += Number(getNum($(this).html().split(",").join("")));
    	});
		$(".sum_testing" ).each(function() {
			SUM_TESTING += Number(getNum($(this).html().split(",").join("")));
    	});
		$(".sum_survey" ).each(function() {
			SUM_SURVEY += Number(getNum($(this).html().split(",").join("")));
    	});
		$(".sum_mde" ).each(function() {
			SUM_MDE += Number(getNum($(this).html().split(",").join("")));
    	});
		$(".sum_covid" ).each(function() {
			SUM_COVID += Number(getNum($(this).html().split(",").join("")));
    	});

		var TOT_ALL = SUM_VT + SUM_HE + SUM_CN + SUM_MP + SUM_HOUSE + SUM_TRANS + SUM_ETC + SUM_TESTING + SUM_SURVEY+ SUM_MDE+ SUM_COVID;

		$("#sum_he").html(number_format(SUM_HE));
		$("#sum_vt").html(number_format(SUM_VT));
		$("#sum_cn").html(number_format(SUM_CN));
		$("#sum_mp").html(number_format(SUM_MP));
		$("#sum_house").html(number_format(SUM_HOUSE));
		$("#sum_trans").html(number_format(SUM_TRANS));
		$("#sum_etc").html(number_format(SUM_ETC));
		$("#sum_testing").html(number_format(SUM_TESTING));
		$("#sum_survey").html(number_format(SUM_SURVEY));
		$("#sum_mde").html(number_format(SUM_MDE));
		$("#sum_covid").html(number_format(SUM_COVID));
		$("#sum_total").html(number_format(TOT_ALL));

		$("#tc_1").html(number_format(SUM_MP));
		$("#tc_2").html(number_format(SUM_CN));
		$("#tc_3").html(number_format(SUM_VT));
		$("#tc_4").html(number_format(SUM_HOUSE));
		$("#tc_5").html(number_format(SUM_TESTING));
		$("#tc_6").html(number_format(SUM_ETC));
		$("#tc_7").html(number_format(SUM_MP + SUM_CN + SUM_VT + SUM_HOUSE + SUM_TESTING + SUM_ETC));
		$("#tc_8").html(number_format(SUM_TRANS));
		$("#tc_9").html(number_format(SUM_MDE));
		$("#tc_10").html(number_format(SUM_HE));
		$("#tc_11").html(number_format(SUM_SURVEY));
		$("#tc_12").html(number_format(SUM_COVID));
		$("#tc_13").html(number_format(SUM_TRANS + SUM_MDE + SUM_HE + SUM_SURVEY + SUM_COVID));
		$("#tc_14").html(number_format(TOT_ALL));

		$("#tcv_1").val(number_format(SUM_MP));
		$("#tcv_2").val(number_format(SUM_CN));
		$("#tcv_3").val(number_format(SUM_VT));
		$("#tcv_4").val(number_format(SUM_HOUSE));
		$("#tcv_5").val(number_format(SUM_TESTING));
		$("#tcv_6").val(number_format(SUM_ETC));
		$("#tcv_7").val(number_format(SUM_MP + SUM_CN + SUM_VT + SUM_HOUSE + SUM_TESTING + SUM_ETC));
		$("#tcv_8").val(number_format(SUM_TRANS));
		$("#tcv_9").val(number_format(SUM_MDE));
		$("#tcv_10").val(number_format(SUM_HE));
		$("#tcv_11").val(number_format(SUM_SURVEY));
		$("#tcv_12").val(number_format(SUM_COVID));
		$("#tcv_13").val(number_format(SUM_TRANS + SUM_MDE + SUM_HE + SUM_SURVEY + SUM_COVID));
		$("#tcv_14").val(number_format(TOT_ALL));
	}

//   function get_budget(){
//     var rate 			= getNum($('#rate').val().split(",").join(""));
//     var persen_profit 	= getNum($('#persen_2').val().split(",").join("")) / 100;
//     var persen_allow 	= getNum($('#persen_5').val().split(",").join("")) / 100;
//     var persen_ed 		= getNum($('#persen_6').val().split(",").join("")) / 100;
//     var persen_interest = getNum($('#persen_7').val().split(",").join("")) / 100;
//     var persen_pph 		= getNum($('#persen_8').val().split(",").join("")) / 100;
// 	var tot_dayin 		= getNum($('#tot_dayin').html().split(",").join(""));

// 	var profit 		= persen_profit * rate;
// 	var costprofit 	= rate + profit;
// 	var net_cost 	= profit / costprofit * 100;
// 	var allow 		= persen_allow * costprofit;
// 	var ed 			= persen_ed * costprofit;
// 	var interest 	= persen_interest * costprofit;
// 	var pph 		= persen_pph * costprofit;

// 	var selling 	= costprofit + allow + ed + interest + pph;

// 	var price_dayin = 0;
// 	if(selling != 0 && tot_dayin != 0){
// 		var price_dayin = selling / tot_dayin;
// 	}

// 	$('#cost_1').val(number_format(rate));
// 	$('#cost_2').val(number_format(profit));
// 	$('#cost_3').val(number_format(costprofit));
// 	$('#persen_4').val(number_format(net_cost,2));
// 	$('#cost_5').val(number_format(allow));
// 	$('#cost_6').val(number_format(ed));
// 	$('#cost_7').val(number_format(interest));
// 	$('#cost_8').val(number_format(pph));
// 	$('#cost_9').val(number_format(selling));
// 	$('#cost_10').val(number_format(price_dayin));

//   }

  	function get_budget2(){
		let total_cost
		let profit
		let total_profit
		let net_profit
		let allowance
		let ed
		let interest
		let pph
		let selling_price

		let persen_profit 	= getNum($('#profit').val().split(",").join("")) / 100;
		var persen_allow 	= getNum($('#allowance').val().split(",").join("")) / 100;
    	var persen_ed 		= getNum($('#ed').val().split(",").join("")) / 100;
    	var persen_interest = getNum($('#interest').val().split(",").join("")) / 100;
    	var persen_pph 		= getNum($('#pph').val().split(",").join("")) / 100;


    	$(".tc_all" ).each(function() {
			total_cost 		=  getNum($(this).html().split(",").join(""));
			profit 			= total_cost * persen_profit
			total_profit 	= total_cost + profit
			net_profit 		= profit / total_profit * 100

			allowance 		= total_profit * persen_allow
			ed 				= total_profit * persen_ed
			interest 		= total_profit * persen_interest
			pph 			= total_profit * persen_pph

			selling_price 	= total_profit + allowance + ed + interest + pph

			$(this).parent().find('.profit_all').html(number_format(profit))
			$(this).parent().find('.tcprofit_all').html(number_format(total_profit))
			$(this).parent().find('.netprofit_all').html(number_format(net_profit,2))

			$(this).parent().find('.allow_all').html(number_format(allowance))
			$(this).parent().find('.ed_all').html(number_format(ed))
			$(this).parent().find('.interest_all').html(number_format(interest))
			$(this).parent().find('.pph_all').html(number_format(pph))
			$(this).parent().find('.selling_all').html(number_format(selling_price))

			$(this).parent().find('.profit_all_val').val(number_format(profit))
			$(this).parent().find('.tcprofit_all_val').val(number_format(total_profit))
			$(this).parent().find('.netprofit_all_val').val(number_format(net_profit,2))

			$(this).parent().find('.allow_all_val').val(number_format(allowance))
			$(this).parent().find('.ed_all_val').val(number_format(ed))
			$(this).parent().find('.interest_all_val').val(number_format(interest))
			$(this).parent().find('.pph_all_val').val(number_format(pph))
			$(this).parent().find('.selling_all_val').val(number_format(selling_price))
    	});

		var tot_dayin 		= getNum($('#tot_dayin').html().split(",").join(""));
    	var total_sp 		= getNum($('#sp_7').html().split(",").join(""));

    	var profit_net 		= getNum($('#p_14').html().split(",").join(""));
    	var tot_profit 		= getNum($('#tp_14').html().split(",").join(""));
		var net_cost 		= profit_net / tot_profit * 100;

		var price_dayin = 0;
		if(total_sp != 0 && tot_dayin != 0){
			var price_dayin = total_sp / tot_dayin;
		}
		$('#sp_15').html(number_format(price_dayin));
		$('#sp_16').html(number_format(net_cost,2));
		$('#sp2_15').val(price_dayin);
		$('#sp2_16').val(net_cost);


  	}
</script>
