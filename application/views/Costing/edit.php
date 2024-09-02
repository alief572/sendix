<?php
$this->load->view('include/side_menu');

$qSat	 	= "SELECT * FROM list WHERE category='tempat tinggal' AND category_='satuan' AND flag='N' ORDER BY urut ASC";
$restSat = $this->db->query($qSat)->result_array();

?>
<form action="#" method="POST" id="form_work" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?php echo $title;?></h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Project Name</b></label>
				<div class='col-sm-4'>
					<input type='hidden' id='project_code' name='project_code' class='form-control input-md' readonly='readonly' value='<?=$header[0]->project_code;?>'>
					<input type='text' id='project_name' name='project_name' class='form-control input-md' readonly='readonly' value='<?= strtoupper($header[0]->project_name);?>'>
				</div>
				<label class='label-control col-sm-2'><b>Region | Tipe Instalasi </b></label>
				<div class='col-sm-2'>
					<input type='text' id='region_code' name='region_code' class='form-control input-md' readonly='readonly' value='<?= strtoupper($header[0]->region_code);?>'>
				</div>
				<div class='col-sm-2'>
					<input type='text' id='tipe' name='tipe' class='form-control input-md' readonly='readonly' value='<?= strtoupper($header[0]->tipe);?>'>
				</div>
			</div>
			<div class='form-group row'>
				<label class='label-control col-sm-2'><b>Project Location</b></label>
				<div class='col-sm-4'>
					<textarea type='text' id='location' name='location' class='form-control input-md' readonly='readonly' rows='3'><?= strtoupper($header[0]->location);?></textarea>
				</div>
				<label class='label-control col-sm-2'><b>Time/Day (Hours)</b></label>
				<div class='col-sm-4'>
					<input type='text' id='total_time' name='total_time' class='form-control input-md' readonly='readonly' value='<?= strtoupper($header[0]->total_time);?>'>
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
			
			<!-- man power -->
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">Man Power & Uang Makan</h3>
					<button type="button" id='btn_sh_mp' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
				</div>
				<div class="box-body sh_mp">
					<?php
					echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success','style'=>'float:right; margin-bottom:5px;','data-project'=>$header[0]->project_code,'value'=>'Update Price','content'=>'Update Price','id'=>'update_mp'));
					?>
					<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<thead id='head_table'>
							<tr class='bg-purple'>
								<th class='text-center' style='width: 10%;'>Name Man Power</th>
								<th class='text-center'>Capacity</th>
								<th class='text-center' style='width: 6%;'>Qty (Orang)</th>
								<th class='text-center' style='width: 6%;'>Qty (Hari)</th>
								<th class='text-center' style='width: 6%;'>Qty OT (Jam)</th>
								<th class='text-center' style='width: 8%;'>Unit</th>
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
						if(!empty($mp)){
							$no_mp = 0;
							foreach ($mp as $key => $value) {
							$no_mp++;
							echo "<tr>";
								echo "<td>";
								echo "<input type='hidden' name='ListMP[0".$no_mp."][id]' class='form-control input-sm' value='".$value['id']."'>";
								echo "<input type='text' name='ListMP[0".$no_mp."][category]' class='form-control input-sm' readonly value='".strtoupper($value['category'])."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListMP[0".$no_mp."][spec]' class='form-control input-sm' readonly value='".strtoupper($value['spec'])."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListMP[0".$no_mp."][qty]' class='form-control input-sm text-center' readonly value='".$value['qty']."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListMP[0".$no_mp."][jml_hari]' class='form-control input-sm text-center' readonly value='".$value['jml_hari']."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListMP[0".$no_mp."][jml_jam]' class='form-control input-sm text-center autoNumeric change_mp' value='".$value['jml_jam']."'>";
								echo "</td>";
								echo "<td>";
								echo "<select name='ListMP[0".$no_mp."][unit]'  data-code='".$value['code_group']."' data-category='man_power' class='chosen_select form-control inline-blockd clSelect ch_rate'>";
								foreach($restSat AS $val_vtList => $valx_vtList){
									$selx = ($valx_vtList['category_list'] == $value['unit'])?'selected':'';
									echo "<option value='".$valx_vtList['category_list']."' ".$selx.">".strtoupper($valx_vtList['view_'])."</option>";
								}
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListMP[0".$no_mp."][rate]' class='form-control input-sm text-right autoNumeric0 change_mp' value='".$value['rate']."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListMP[0".$no_mp."][rate_ot]' class='form-control input-sm text-right autoNumeric0 change_mp' value='".$value['rate_ot']."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListMP[0".$no_mp."][rate_us]' class='form-control input-sm text-right autoNumeric0 change_mp' value='".$value['rate_us']."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListMP[0".$no_mp."][rate_um]' class='form-control input-sm text-right autoNumeric0 change_mp' value='".$value['rate_um']."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListMP[0".$no_mp."][rate_unit]' class='form-control input-sm text-right' readonly value='".number_format($value['rate_unit'])."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListMP[0".$no_mp."][total_rate]' class='form-control input-sm text-right sum_mp' readonly value='".number_format($value['total_rate'])."'>";
								echo "</td>";
							echo "</tr>";
							}
						}
						?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="11"><b>TOTAL</b></td>
								<td><input type='text' id='sum_mp' class='form-control input-sm text-right' readonly></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- end man power -->
			
			<!-- consumable -->
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">Consumable & Material </h3>
					<button type="button" id='btn_sh_cn' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
				</div>
				<div class="box-body sh_cn">
					<?php
					echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success','style'=>'float:right; margin-bottom:5px;','data-project'=>$header[0]->project_code,'value'=>'Update Price','content'=>'Update Price','id'=>'update_cn'));
					?>
					<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<thead id='head_table'>
							<tr class='bg-purple'>
								<th class='text-center' style='width: 20%;'>Consumable Name</th>
								<th class='text-center'>Capacity</th>
								<th class='text-center' style='width: 10%;'>Qty</th>
								<th class='text-center' style='width: 10%;'>Unit</th>
								<th class='text-center' style='width: 9%;'>Cost/Unit</th>
								<th class='text-center' style='width: 9%;' hidden>Cost Unit</th>
								<th class='text-center' style='width: 9%;'>Total Cost</th>
							</tr>
						</thead>
						<tbody>
						<?php
						if(!empty($cn)){
							$no_cn = 0;
							foreach ($cn as $key => $value) {
							$no_cn++;
							echo "<tr>";
								echo "<td>";
								echo "<input type='hidden' name='ListCN[0".$no_cn."][id]' class='form-control input-sm' value='".$value['id']."'>";
								echo "<input type='text' name='ListCN[0".$no_cn."][category]' class='form-control input-sm' readonly value='".strtoupper($value['category'])."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListCN[0".$no_cn."][spec]' class='form-control input-sm' readonly value='".strtoupper($value['spec'])."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListCN[0".$no_cn."][qty]' class='form-control input-sm text-center' readonly value='".$value['qty']."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListCN[0".$no_cn."][unit]' class='form-control input-sm text-center' readonly value='".$value['unit']."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListCN[0".$no_cn."][rate]' class='form-control input-sm text-right' readonly value='".number_format($value['rate'])."'>";
								echo "</td>";
								echo "<td hidden>";
								echo "<input type='text' name='ListCN[0".$no_cn."][rate_unit]' class='form-control input-sm text-right' readonly value='".number_format($value['rate'] * $value['qty'])."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListCN[0".$no_cn."][total_rate]' class='form-control input-sm text-right sum_cn' readonly value='".number_format($value['rate'] * $value['qty'])."'>";
								echo "</td>";
							echo "</tr>";
							}
						}
						?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="5"><b>TOTAL</b></td>
								<td><input type='text' id='sum_cn' class='form-control input-sm text-right' readonly></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- end consumable -->

			<!-- tools equipment -->
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title">Tools Equipment</h3>
					<button type="button" id='btn_sh_vt' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
				</div>
				<div class="box-body sh_vt">
					<?php
					echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success','style'=>'float:right; margin-bottom:5px;','data-project'=>$header[0]->project_code,'value'=>'Update Price','content'=>'Update Price','id'=>'update_vt'));
					?>
					<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<thead id='head_table'>
						<tr class='bg-purple'>
							<th class='text-center' style='width: 17%;'>Name Alat</th>
							<th class='text-center'>Capacity</th>
							<th class='text-center' style='width: 10%;'>Qty</th>
							<th class='text-center' style='width: 8%;' hidden>Time (Day)</th>
							<th class='text-center' style='width: 10%;'>Unit</th>
							<th class='text-center' style='width: 9%;'>Cost/Unit</th>
							<th class='text-center' style='width: 9%;' hidden>Cost Unit (Day)</th>
							<th class='text-center' style='width: 9%;'>Total Cost</th>
						</tr>
						</thead>
						<tbody>
						<?php
						if(!empty($vt)){
							$no_vt = 0;
							foreach ($vt as $key => $value) {
							$no_vt++;
							echo "<tr>";
								echo "<td>";
								echo "<input type='hidden' name='ListVT[0".$no_vt."][id]' class='form-control input-sm' value='".$value['id']."'>";
								echo "<input type='text' name='ListVT[0".$no_vt."][category]' class='form-control input-sm' readonly value='".strtoupper($value['category'])."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListVT[0".$no_vt."][spec]' class='form-control input-sm' readonly value='".strtoupper($value['spec'])."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListVT[0".$no_vt."][qty]' class='form-control input-sm text-center' readonly value='".$value['qty']."'>";
								echo "</td>";
								echo "<td hidden>";
								echo "<input type='text' name='ListVT[0".$no_vt."][jml_hari]' class='form-control input-sm text-center' readonly value='1'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListVT[0".$no_vt."][unit]' class='form-control input-sm text-center' readonly value='".$value['unit']."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListVT[0".$no_vt."][rate]' class='form-control input-sm text-right' readonly value='".number_format($value['rate'])."'>";
								echo "</td>";
								echo "<td hidden>";
								echo "<input type='text' name='ListVT[0".$no_vt."][rate_unit]' class='form-control input-sm text-right' readonly value='".number_format($value['rate_unit'])."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListVT[0".$no_vt."][total_rate]' class='form-control input-sm text-right sum_vt' readonly value='".number_format($value['total_rate'])."'>";
								echo "</td>";
							echo "</tr>";
							}
						}
						?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="5"><b>TOTAL</b></td>
								<td><input type='text' id='sum_vt' class='form-control input-sm text-right' readonly></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- end tools equipment -->

			<!-- heavy equipment -->
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title">Heavy & Rental Equipment</h3>
					<button type="button" id='btn_sh_he' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
				</div>
				<div class="box-body sh_he">
					<?php
					echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success','style'=>'float:right; margin-bottom:5px;','data-project'=>$header[0]->project_code,'value'=>'Update Price','content'=>'Update Price','id'=>'update_he'));
					?>
					<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<thead id='head_table'>
							<tr class='bg-purple'>
								<th class='text-center' style='width: 17%;'>Name Alat</th>
								<th class='text-center'>Capacity</th>
								<th class='text-center' style='width: 6%;'>Qty</th>
								<th class='text-center' style='width: 8%;'>Time (Day)</th>
								<th class='text-center' style='width: 10%;'>Unit</th>
								<th class='text-center' style='width: 9%;'>Cost</th>
								<th class='text-center' style='width: 9%;'>Cost Unit (Day)</th>
								<th class='text-center' style='width: 9%;'>Total Cost</th>
							</tr>
						</thead>
						<tbody>
						<?php
						if(!empty($he)){
							$no_vt = 0;
							foreach ($he as $key => $value) {
							$no_vt++;
							echo "<tr>";
								echo "<td>";
								echo "<input type='hidden' name='ListHE[0".$no_vt."][id]' class='form-control input-sm' value='".$value['id']."'>";
								echo "<input type='text' name='ListHE[0".$no_vt."][category]' class='form-control input-sm' readonly value='".strtoupper($value['category'])."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListHE[0".$no_vt."][spec]' class='form-control input-sm' readonly value='".strtoupper($value['spec'])."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListHE[0".$no_vt."][qty]' class='form-control input-sm text-center' readonly value='".$value['qty_']."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListHE[0".$no_vt."][jml_hari]' class='form-control input-sm text-center' readonly value='".$value['jml_hari_']."'>";
								echo "</td>";
								echo "<td>";
								echo "<select name='ListHE[0".$no_vt."][unit]' data-code='".$value['code_group']."' data-category='vehicle_tool' class='chosen_select form-control inline-blockd clSelect ch_rate'>";
									foreach($restSat AS $val_vtList => $valx_vtList){
											$selx = ($valx_vtList['category_list'] == $value['unit'])?'selected':'';
										echo "<option value='".$valx_vtList['category_list']."' ".$selx.">".strtoupper($valx_vtList['view_'])."</option>";
									}
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListHE[0".$no_vt."][rate]' class='form-control input-sm text-right' readonly value='".number_format($value['rate'])."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListHE[0".$no_vt."][rate_unit]' class='form-control input-sm text-right' readonly value='".number_format($value['rate_unit'])."'>";
								echo "</td>";
								echo "<td>";
								echo "<input type='text' name='ListHE[0".$no_vt."][total_rate]' class='form-control input-sm text-right sum_he' readonly value='".number_format($value['total_rate'])."'>";
								echo "</td>";
							echo "</tr>";
							}
						}
						?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="7"><b>TOTAL</b></td>
								<td><input type='text' id='sum_he' class='form-control input-sm text-right' readonly></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- end heavy equipment -->

			<!-- Acomodation & Transportation on Site -->
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">Acomodation & Transportation on Site</h3>
					<button type="button" id='btn_sh_house' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
				</div>
				<div class="box-body sh_house">
					<?php
						echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success','style'=>'float:right; margin-bottom:5px;','data-project'=>$header[0]->project_code,'value'=>'Update Price','content'=>'Update Price','id'=>'update_house'));
					?>
					<input type='hidden' name='numberHouse' id='numberHouse' value='0'>
					<button type='button' id='add_house' style='margin-top:10px; margin-bottom:3px; margin-left:0px; float:left;' class='btn btn-success btn-sm'>Add</button>
					<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<thead id='head_table'>
						<tr class='bg-purple'>
							<th class='text-center' style='width: 30%;'>Item Cost</th>
							<th class='text-center' style='width: 13%;'>Qty</th>
							<th class='text-center' style='width: 10%;'>Total (Day)</th>
							<th class='text-center' style='width: 10%;'>Unit</th>
							<th class='text-center'>Note</th>
							<th class='text-center' style='width: 9%;'>Cost</th>
							<th class='text-center' style='width: 9%;'>Total Cost</th>
						</tr>
						</thead>
						<tbody>
						<?php
							$qHouse	 	= "SELECT * FROM akomodasi_new WHERE id_category='2' ORDER BY category ASC, spec ASC";
							$restHouse	= $this->db->query($qHouse)->result_array();

							if(!empty($house_)){
							$no_house = 0;
							foreach ($house_ as $key => $value) {
								$no_house++;
								echo 	"<tr>";
								echo 		"<td align='left'>";
								echo "<input type='hidden' name='ListDetailHouse2[0".$no_house."][id]' class='form-control input-sm' value='".$value['id']."'>";
								echo "<div class='input-group'>";
								echo "<select name='ListDetailHouse2[0".$no_house."][code_group]' id='codeh_0".$no_house."' data-nomor='0".$no_house."' class='chosen_select form-control inline-blockd clSelect houseSelect'>";
								foreach($restHouse AS $val_vtList => $valx_vtList){
								$sel1 = ($valx_vtList['code_group'] == $value['code_group'])?'selected':'';
								echo "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['spec'])."</option>";
								}
								echo 		"</select>";
								echo 		"<span class='input-group-addon cldelete delRowsH'><i class='fa fa-close'></i></span>";
								echo 		"</div>";
								echo 		"</td>";
								echo 		"<td align='left'>";
								echo				"<input type='text' name='ListDetailHouse2[0".$no_house."][qty]' id='qtyh_0".$no_house."' class='form-control input-sm autoNumeric0 qty_house' placeholder='Qty' value='".$value['qty']."'>";
								echo 		"</td>";
								echo		"<td align='left'>";
								echo				"<input type='text' name='ListDetailHouse2[0".$no_house."][value]' id='valueh_0".$no_house."' class='form-control input-sm autoNumeric day_house' placeholder='Value' value='".$value['jml_orang']."'>";
								echo 		"</td>";
								echo 		"<td align='left'>";
								echo "<select name='ListDetailHouse2[0".$no_house."][satuan]' id='satuanh_0".$no_house."' class='chosen_select form-control inline-blockd clSelect unit_house'>";
								foreach($restSat AS $val_vtList => $valx_vtList){
								$sel2 = ($valx_vtList['category_list'] == $value['area'])?'selected':'';
								echo "<option value='".$valx_vtList['category_list']."' $sel2>".strtoupper($valx_vtList['view_'])."</option>";
								}
								echo 		"</td>";
								echo 		"<td align='left'>";
								echo				"<input type='text' name='ListDetailHouse2[0".$no_house."][note]' id='noteh_0".$no_house."' class='form-control input-sm' placeholder='Note' value='".$value['note']."'>";
								echo 		"</td>";
								echo 		"<td align='left'>";
								echo				"<input type='text' name='ListDetailHouse2[0".$no_house."][rate]' id='rateh_0".$no_house."' class='form-control input-sm text-right rate rate_house' placeholder='Cost' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['rate'])."'>";
								echo 		"</td>";
								echo 		"<td align='left'>";
								echo				"<input type='text' name='ListDetailHouse2[0".$no_house."][total_rate]' readonly id='total_rateh_0".$no_house."' class='form-control input-sm text-right rate sum_house' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['total_rate'])."'>";
								echo 		"</td>";
								echo 	"</tr>";
							}
							}
							?>
						</tbody>
						<tbody id='detail_body_house'></tbody>
						<tbody id='detail_body_house_empty'>
						<tr>
							<td colspan='7'>List empty ...</td>
						</tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="6"><b>TOTAL</b></td>
								<td><input type='text' id='sum_house' class='form-control input-sm text-right' readonly></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- End Acomodation & Transportation on Site -->
			
			<!-- List Testing -->
			<div class="box box-danger">
				<div class="box-header">
					<h3 class="box-title">Testing</h3>
					<button type="button" id='btn_sh_testing' style='width:100px; float:right;' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
				</div>
				<div class="box-body sh_testing">
					<input type='hidden' name='numberTesting' id='numberTesting' value='0'>
					<!-- <button type='button' id='add_testing' style='width:130px; margin-top:10px; margin-bottom:3px; margin-left:0px; float:left;' class='btn btn-success btn-sm'>Add</button> -->
						<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<thead id='head_table'>
						<tr class='bg-purple'>
							<th class='text-center' style='width: 30%;'>Item Name</th>
							<th class='text-center' style='width: 13%;'>Qty</th>
							<th class='text-center'>Note</th>
							<th class='text-center' style='width: 9%;'>Cost</th>
							<th class='text-center' style='width: 9%;'>Total Cost</th>
						</tr>
						</thead>
						<tbody>
						<?php
						if(!empty($test_)){
							$no_etc = 0;
							foreach ($test_ as $key => $value) {
							$no_etc++;
							echo 	"<tr>";
							echo 		"<td align='left'  width='10%'>";
							echo 			"<input type='hidden' name='ListDetailTest2[0".$no_etc."][id]' class='form-control input-sm' value='".$value['id']."'>";
							echo			"<input type='text' name='ListDetailTest2[0".$no_etc."][spec]' id='spece_0".$no_etc."' class='form-control input-sm' placeholder='Spesification' value='".strtoupper($value['spec'])."' readonly>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailTest2[0".$no_etc."][qty]' id='qtye_0".$no_etc."' class='form-control input-sm autoNumeric0 qty_testing' placeholder='Qty' value='".$value['qty']."'>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailTest2[0".$no_etc."][note]' id='notee_0".$no_etc."' class='form-control input-sm' placeholder='Note' value='".$value['note']."'>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailTest2[0".$no_etc."][rate]' id='ratee_0".$no_etc."' class='form-control input-sm text-right rate rate_testing' placeholder='Cost' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['rate'])."'>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailTest2[0".$no_etc."][total_rate]' id='total_ratee_0".$no_etc."' class='form-control input-sm text-right rate sum_testing' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['total_rate'])."'>";
							echo 		"</td>";
							echo 	"</tr>";
							}
						}
						?>
						</tbody>
						<!-- <tbody id='detail_body_testing'></tbody>
						<tbody id='detail_body_testing_empty'>
						<tr>
							<td colspan='5'>List empty ...</td>
						</tr> -->
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4"><b>TOTAL</b></td>
								<td><input type='text' id='sum_testing' class='form-control input-sm text-right' readonly></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- End List Testing -->

			<!-- List Etc -->
			<div class="box box-danger">
				<div class="box-header">
					<h3 class="box-title">Etc</h3>
					<button type="button" id='btn_sh_etc' style='width:100px; float:right;' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
				</div>
				<div class="box-body sh_etc">
					<?php
						echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success','style'=>'float:right; margin-bottom:5px;','data-project'=>$header[0]->project_code,'value'=>'Update Price','content'=>'Update Price','id'=>'update_etc'));
					?>
					<input type='hidden' name='numberEtc' id='numberEtc' value='0'>
					<button type='button' id='add_etc' style='width:130px; margin-top:10px; margin-bottom:3px; margin-left:0px; float:left;' class='btn btn-success btn-sm'>Add</button>
						<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<thead id='head_table'>
						<tr class='bg-purple'>
							<th class='text-center' style='width: 30%;'>Item Name</th>
							<th class='text-center' style='width: 13%;'>Qty</th>
							<th class='text-center'>Note</th>
							<th class='text-center' style='width: 9%;'>Cost</th>
							<th class='text-center' style='width: 9%;'>Total Cost</th>
						</tr>
						</thead>
						<tbody>
						<?php
						$qEtc	 	= "SELECT * FROM akomodasi_new WHERE category='biaya lain lain' ORDER BY category ASC, spec ASC";
						$etc = $this->db->query($qEtc)->result_array();
						if(!empty($etc_)){
							$no_etc = 0;
							foreach ($etc_ as $key => $value) {
							$no_etc++;
							echo 	"<tr>";
							echo 		"<td align='left'  width='10%'>";
							echo "<input type='hidden' name='ListDetailEtc2[0".$no_etc."][id]' class='form-control input-sm' value='".$value['id']."'>";
							echo "<div class='input-group'>";
							echo "<select name='ListDetailEtc2[0".$no_etc."][code_group]' id='item_coste_0".$no_etc."' data-nomor='0".$no_etc."' class='chosen_select form-control inline-blockd clSelect etcSelect'>";
							foreach($etc AS $val_vtList => $valx_vtList){
								$sel1 = ($valx_vtList['code_group'] == $value['code_group'])?'selected':'';
								echo "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['spec'])."</option>";
							}
							echo 		"</select>";
							echo 		"<span class='input-group-addon cldelete delRowsH'><i class='fa fa-close'></i></span>";
							echo 		"</div>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailEtc2[0".$no_etc."][qty]' id='qtye_0".$no_etc."' class='form-control input-sm autoNumeric0 qty_etc' placeholder='Qty' value='".$value['qty']."'>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailEtc2[0".$no_etc."][note]' id='notee_0".$no_etc."' class='form-control input-sm' placeholder='Note' value='".$value['note']."'>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailEtc2[0".$no_etc."][rate]' id='ratee_0".$no_etc."' class='form-control input-sm text-right rate rate_etc' placeholder='Cost' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['rate'])."'>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailEtc2[0".$no_etc."][total_rate]' id='total_ratee_0".$no_etc."' class='form-control input-sm text-right rate sum_etc' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['total_rate'])."'>";
							echo 		"</td>";
							echo 	"</tr>";
							}
						}
						?>
						</tbody>
						<tbody id='detail_body_etc'></tbody>
						<tbody id='detail_body_etc_empty'>
						<tr>
							<td colspan='5'>List empty ...</td>
						</tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4"><b>TOTAL</b></td>
								<td><input type='text' id='sum_etc' class='form-control input-sm text-right' readonly></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- End List Etc -->

			<!-- OPC to Site Transportation -->
			<div class="box box-warning">
				<div class="box-header">
					<h3 class="box-title">Mob-Demob Man Power</h3>
					<button type="button" id='btn_sh_trans' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
				</div>
				<div class="box-body sh_trans">
					<input type='hidden' name='numberTrans' id='numberTrans' value='0'>
					<button type='button' id='add_trans' style='min-width:130px; margin-top:10px; margin-bottom:3px; margin-left:0px; float:left;' class='btn btn-success btn-sm'>Add</button>
					<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<thead id='head_table'>
							<tr class='bg-purple'>
								<th class='text-center' style='width: 18%;'>Item Cost</th>
								<th class='text-center' style='width: 12%;'>Transportation</th>
								<th class='text-center' style='width: 10%;'>Origin</th>
								<th class='text-center' style='width: 10%;'>Destination</th>
								<th class='text-center' style='width: 10%;'>Total MP (Day)</th>
								<th class='text-center' style='width: 10%;'>Round-Trip</th>
								<th class='text-center'>Note</th>
								<th class='text-center' style='width: 9%;'>Cost</th>
								<th class='text-center' style='width: 9%;'>Total Cost</th>
							</tr>
						</thead>
						<tbody>
						<?php
						if(!empty($trans)){
							$no_trans = 0;
							foreach ($trans as $key => $value) {
							$qTransport	 	= "SELECT * FROM list WHERE category='transportasi' AND category_='".$value['category']."' AND flag='N' ORDER BY urut ASC";
							$restTransx	= $this->db->query($qTransport)->result_array();
							$ArrTransY = array();
							foreach($restTransx AS $val => $valx){
								$ArrTransY[$valx['category_list']] = strtoupper($valx['view_']);
							}
							$no_trans++;
								echo 	"<tr>";
								echo 		"<td align='left'  width='10%'>";
								echo "<input type='hidden' name='ListDetailTrans2[0".$no_trans."][id]' class='form-control input-sm' value='".$value['id']."'>";
								echo "<div class='input-group'>";
								echo "<select name='ListDetailTrans2[0".$no_trans."][item_cost]' class='chosen_select form-control inline-blockd clSelect'>";
								foreach($transx AS $val_vtList => $valx_vtList){
								$sel1 = ($valx_vtList['category_list'] == $value['category'])?'selected':'';
								echo "<option value='".$valx_vtList['category_list']."' $sel1>".strtoupper($valx_vtList['view_'])."</option>";
								}
								echo 		"</select>";
								echo 		"<span class='input-group-addon cldelete delRowsT'><i class='fa fa-close'></i></span>";
								echo 		"</div>";
								echo 		"</td>";
								echo 		"<td align='left'>";
								echo "<select name='ListDetailTrans2[0".$no_trans."][kendaraan]' class='chosen_select form-control inline-blockd clSelect'>";
								foreach($restTransx AS $val_vtList => $valx_vtList){
								$sel1 = ($valx_vtList['category_list'] == $value['spec'])?'selected':'';
								echo "<option value='".$valx_vtList['category_list']."' $sel1>".strtoupper($valx_vtList['view_'])."</option>";
								}
								echo 		"</select>";
								echo 		"</td>";
								echo 		"<td align='left'>";
								echo				"<input type='text' name='ListDetailTrans2[0".$no_trans."][asal]' id='asalt_0".$no_trans."' class='form-control input-sm' placeholder='Origin' value='".strtoupper($value['asal'])."'>";
								echo 		"</td>";
								echo 		"<td align='left'>";
								echo				"<input type='text' name='ListDetailTrans2[0".$no_trans."][tujuan]' id='tujuant_0".$no_trans."' class='form-control input-sm' placeholder='Destination' value='".strtoupper($value['tujuan'])."'>";
								echo 		"</td>";
								echo 		"<td align='left'>";
								echo				"<input type='text' name='ListDetailTrans2[0".$no_trans."][value]' id='valuet_0".$no_trans."' class='form-control input-sm numberFull' placeholder='Value' value='".$value['jml_orang']."'>";
								echo 		"</td>";
								echo 		"<td align='left'>";
								echo				"<input type='text' name='ListDetailTrans2[0".$no_trans."][pulang_pergi]' id='pulang_pergit_0".$no_trans."' class='form-control input-sm numberFull pp_trans' placeholder='Round-Trip' value='".$value['pulang_pergi']."'>";
								echo 		"</td>";
								echo 		"<td align='left'>";
								echo				"<input type='text' name='ListDetailTrans2[0".$no_trans."][note]' id='notet_0".$no_trans."' class='form-control input-sm' placeholder='Note' value='".$value['note']."'>";
								echo 		"</td>";
								echo 		"<td align='left'>";
								echo				"<input type='text' name='ListDetailTrans2[0".$no_trans."][rate]' id='ratet_0".$no_trans."' class='form-control input-sm text-right rate rate_trans' placeholder='Cost' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['rate'])."'>";
								echo 		"</td>";
								echo 		"<td align='left'>";
								echo				"<input type='text' name='ListDetailTrans2[0".$no_trans."][total_rate]' id='total_ratet_0".$no_trans."' readonly class='form-control input-sm text-right rate sum_trans' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['total_rate'])."'>";
								echo 		"</td>";
								echo 	"</tr>";
							}
						}
						?>
						</tbody>
						<tbody id='detail_body_trans'></tbody>
						<tbody id='detail_body_trans_empty'>
						<tr>
							<td colspan='9'>List empty ...</td>
						</tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="8"><b>TOTAL</b></td>
								<td><input type='text' id='sum_trans' class='form-control input-sm text-right' readonly></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- End OPC to Site Transportation -->
			
			<!-- List MDE -->
			<div class="box box-danger">
				<div class="box-header">
					<h3 class="box-title">Mob Demob Equipment</h3>
					<button type="button" id='btn_sh_mde' style='width:100px; float:right;' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
				</div>
				<div class="box-body sh_mde">
					<?php
						echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success','style'=>'float:right; margin-bottom:5px;','data-project'=>$header[0]->project_code,'value'=>'Update Price','content'=>'Update Price','id'=>'update_mde'));
					?>
					<!-- <input type='hidden' name='numberSurvey' id='numberSurvey' value='0'> -->
					<!-- <button type='button' id='add_survey' style='width:130px; margin-top:10px; margin-bottom:3px; margin-left:0px; float:left;' class='btn btn-success btn-sm'>Add</button> -->
						<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<thead id='head_table'>
						<tr class='bg-purple'>
							<th class='text-center' style='width: 25%;'>Item Name</th>
							<th class="text-center" style='width: 12%;'>Area</th>
							<th class="text-center" style='width: 12%;'>Tujuan</th>
							<th class="text-center" style='width: 15%;'>Kendaraan</th>
							<th class="text-center" style='width: 8%;'>Qty</th>
							<th class='text-center'>Note</th>
							<th class='text-center' style='width: 9%;'>Cost</th>
							<th class='text-center' style='width: 9%;'>Total Cost</th>
						</tr>
						</thead>
						<tbody>
						<?php
						$qEtc	 	= "SELECT * FROM akomodasi_new WHERE id_category='7' ORDER BY category ASC, spec ASC";
						$etc = $this->db->query($qEtc)->result_array();
						if(!empty($mde_)){
							$no_etc = 0;
							foreach ($mde_ as $key => $value) {
							$no_etc++;
							$jml_orang 	= ($value['jml_orang'] > 0)?$value['jml_orang']:'';
							echo 	"<tr>";
							echo 		"<td align='left'  width='10%'>";
							echo "<input type='hidden' name='ListDetailMDE2[0".$no_etc."][id]' class='form-control input-sm' value='".$value['id']."'>";
							echo "<div class='input-group'>";
							echo "<select name='ListDetailMDE2[0".$no_etc."][code_group]' id='item_coste_0".$no_etc."' data-nomor='0".$no_etc."' class='chosen_select form-control inline-blockd clSelect etcSelect'>";
							foreach($etc AS $val_vtList => $valx_vtList){
								$sel1 = ($valx_vtList['code_group'] == $value['code_group'])?'selected':'';
								echo "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['spec'])."</option>";
							}
							echo 		"</select>";
							echo 		"<span class='input-group-addon cldelete delRowsH'><i class='fa fa-close'></i></span>";
							echo 		"</div>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo 			"<select name='ListDetailMDE2[0".$no_etc."][area]' class='form-control input-sm chosen-select area_mde'>";
							echo 				"<option value='".$value['area']."'>".strtoupper($value['area'])."</option>";
							echo 			"</select>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo 			"<select name='ListDetailMDE2[0".$no_etc."][tujuan]' class='form-control input-sm chosen-select tujuan_mde'>";
											echo "<option value='".$value['tujuan']."'>".strtoupper($value['tujuan'])."</option>";
							echo 			"</select>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo 			"<select name='ListDetailMDE2[0".$no_etc."][kendaraan]' class='form-control input-sm chosen-select kendaraan_mde'>";
											echo "<option value='".$value['truck']."'>".strtoupper(api_get_nm_truck($value['truck']))."</option>";
							echo 			"</select>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailMDE2[0".$no_etc."][jml_orang]' class='form-control input-sm text-center autoNumeric0 qty_mde' value='".$jml_orang."'>";
							echo 		"</td>";
							// echo 		"<td align='left'>";
							// echo				"<input type='text' name='ListDetailMDE2[0".$no_etc."][unit]' class='form-control input-sm text-center' readonly value='".$value['unit']."'>";
							// echo 		"</td>";
							// echo 		"<td align='left'>";
							// echo				"<input type='text' name='ListDetailMDE2[0".$no_etc."][jml_hari]' class='form-control input-sm text-center autoNumeric0 qty_mde' value='".$value['pulang_pergi']."'>";
							// echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailMDE2[0".$no_etc."][note]' class='form-control input-sm' placeholder='Note' value='".$value['note']."'>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailMDE2[0".$no_etc."][rate]' class='form-control input-sm text-right rate rate_mde' placeholder='Cost' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['rate'])."'>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailMDE2[0".$no_etc."][total_rate]' class='form-control input-sm text-right rate sum_mde' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['total_rate'])."'>";
							echo 		"</td>";
							echo 	"</tr>";
							}
						}
						?>
						</tbody>
						<!-- <tbody id='detail_body_survey'></tbody>
						<tbody id='detail_body_survey_empty'>
						<tr>
							<td colspan='5'>List empty ...</td>
						</tr>
						</tbody> -->
						<tfoot>
							<tr>
								<td colspan="7"><b>TOTAL</b></td>
								<td><input type='text' id='sum_mde' class='form-control input-sm text-right' readonly></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- End List Survey -->
			
			<!-- List Survey -->
			<div class="box box-danger">
				<div class="box-header">
					<h3 class="box-title">Survey</h3>
					<button type="button" id='btn_sh_survey' style='width:100px; float:right;' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
				</div>
				<div class="box-body sh_survey">
					<?php
						echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success','style'=>'float:right; margin-bottom:5px;','data-project'=>$header[0]->project_code,'value'=>'Update Price','content'=>'Update Price','id'=>'update_survey'));
					?>
					<input type='hidden' name='numberSurvey' id='numberSurvey' value='0'>
					<!-- <button type='button' id='add_survey' style='width:130px; margin-top:10px; margin-bottom:3px; margin-left:0px; float:left;' class='btn btn-success btn-sm'>Add</button> -->
						<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<thead id='head_table'>
						<tr class='bg-purple'>
							<th class='text-center' style='width: 30%;'>Item Name</th>
							<th class='text-center' style='width: 10%;'>Jumlah Orang</th>
							<th class='text-center' style='width: 10%;'>Jumlah Qty</th>
							<th class='text-center' style='width: 10%;'>Jumlah Hari</th>
							<th class='text-center'>Note</th>
							<th class='text-center' style='width: 9%;'>Cost</th>
							<th class='text-center' style='width: 9%;'>Total Cost</th>
						</tr>
						</thead>
						<tbody>
						<?php
						$qEtc	 	= "SELECT * FROM akomodasi_new WHERE id_category='5' ORDER BY category ASC, spec ASC";
						$etc = $this->db->query($qEtc)->result_array();
						if(!empty($survey_)){
							$no_etc = 0;
							foreach ($survey_ as $key => $value) {
							$no_etc++;
							$jml_orang 	= ($value['jml_orang'] > 0)?$value['jml_orang']:'';
							$qty 		= ($value['qty'] > 0)?$value['qty']:'';
							echo 	"<tr>";
							echo 		"<td align='left'  width='10%'>";
							echo "<input type='hidden' name='ListDetailSurvey2[0".$no_etc."][id]' class='form-control input-sm' value='".$value['id']."'>";
							echo "<div class='input-group'>";
							echo "<select name='ListDetailSurvey2[0".$no_etc."][code_group]' id='item_coste_0".$no_etc."' data-nomor='0".$no_etc."' class='chosen_select form-control inline-blockd clSelect etcSelect'>";
							foreach($etc AS $val_vtList => $valx_vtList){
								$sel1 = ($valx_vtList['code_group'] == $value['code_group'])?'selected':'';
								echo "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['spec'])."</option>";
							}
							echo 		"</select>";
							echo 		"<span class='input-group-addon cldelete delRowsH'><i class='fa fa-close'></i></span>";
							echo 		"</div>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailSurvey2[0".$no_etc."][jml_orang]' class='form-control input-sm autoNumeric0 qty_survey' value='".$jml_orang."'>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailSurvey2[0".$no_etc."][qty]' class='form-control input-sm autoNumeric0 qty_survey' value='".$qty."'>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailSurvey2[0".$no_etc."][jml_hari]' class='form-control input-sm autoNumeric qty_survey' value='".$value['jml_hari']."'>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailSurvey2[0".$no_etc."][note]' class='form-control input-sm' placeholder='Note' value='".$value['note']."'>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailSurvey2[0".$no_etc."][rate]' class='form-control input-sm text-right rate rate_survey' placeholder='Cost' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['rate'])."'>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailSurvey2[0".$no_etc."][total_rate]' class='form-control input-sm text-right rate sum_survey' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['total_rate'])."'>";
							echo 		"</td>";
							echo 	"</tr>";
							}
						}
						?>
						</tbody>
						<!-- <tbody id='detail_body_survey'></tbody>
						<tbody id='detail_body_survey_empty'>
						<tr>
							<td colspan='5'>List empty ...</td>
						</tr>
						</tbody> -->
						<tfoot>
							<tr>
								<td colspan="6"><b>TOTAL</b></td>
								<td><input type='text' id='sum_survey' class='form-control input-sm text-right' readonly></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- End List Survey -->
			
			<!-- List Covid -->
			<div class="box box-danger">
				<div class="box-header">
					<h3 class="box-title">Covid 19 Protokol</h3>
					<button type="button" id='btn_sh_covid' style='width:100px; float:right;' class="btn btn-primary btn-sm btn_show_up">SHOW</button>
				</div>
				<div class="box-body sh_covid">
				<?php
						echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success','style'=>'float:right; margin-bottom:5px;','data-project'=>$header[0]->project_code,'value'=>'Update Price','content'=>'Update Price','id'=>'update_covid'));
					?>
					<!-- <input type='hidden' name='numberSurvey' id='numberSurvey' value='0'> -->
					<!-- <button type='button' id='add_survey' style='width:130px; margin-top:10px; margin-bottom:3px; margin-left:0px; float:left;' class='btn btn-success btn-sm'>Add</button> -->
						<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<thead id='head_table'>
						<tr class='bg-purple'>
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
						$qEtc	 	= "SELECT * FROM akomodasi_new WHERE id_category='6' ORDER BY category ASC, spec ASC";
						$etc = $this->db->query($qEtc)->result_array();
						if(!empty($covid_)){
							$no_etc = 0;
							foreach ($covid_ as $key => $value) {
							$no_etc++;
							$jml_orang 	= ($value['jml_orang'] > 0)?$value['jml_orang']:'';
							echo 	"<tr>";
							echo 		"<td align='left'  width='10%'>";
							echo "<input type='hidden' name='ListDetailCovid2[0".$no_etc."][id]' class='form-control input-sm' value='".$value['id']."'>";
							echo "<div class='input-group'>";
							echo "<select name='ListDetailCovid2[0".$no_etc."][code_group]' id='item_coste_0".$no_etc."' data-nomor='0".$no_etc."' class='chosen_select form-control inline-blockd clSelect etcSelect'>";
							foreach($etc AS $val_vtList => $valx_vtList){
								$sel1 = ($valx_vtList['code_group'] == $value['code_group'])?'selected':'';
								echo "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['spec'])."</option>";
							}
							echo 		"</select>";
							echo 		"<span class='input-group-addon cldelete delRowsH'><i class='fa fa-close'></i></span>";
							echo 		"</div>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailCovid2[0".$no_etc."][jml_orang]' class='form-control input-sm text-center autoNumeric0 qty_covid' value='".$jml_orang."'>";
							echo 		"</td>";
							echo 		"<td align='center'>Orang</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailCovid2[0".$no_etc."][jml_hari]' class='form-control input-sm text-center autoNumeric0 qty_covid' value='".$value['jml_hari']."'>";
							echo 		"</td>";
							echo 		"<td align='center'>Hari</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailCovid2[0".$no_etc."][note]' class='form-control input-sm' placeholder='Note' value='".$value['note']."'>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailCovid2[0".$no_etc."][rate]' class='form-control input-sm text-right rate rate_covid' placeholder='Cost' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['rate'])."'>";
							echo 		"</td>";
							echo 		"<td align='left'>";
							echo				"<input type='text' name='ListDetailCovid2[0".$no_etc."][total_rate]' class='form-control input-sm text-right rate sum_covid' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['total_rate'])."'>";
							echo 		"</td>";
							echo 	"</tr>";
							}
						}
						?>
						</tbody>
						<!-- <tbody id='detail_body_survey'></tbody>
						<tbody id='detail_body_survey_empty'>
						<tr>
							<td colspan='5'>List empty ...</td>
						</tr>
						</tbody> -->
						<tfoot>
							<tr>
								<td colspan="7"><b>TOTAL</b></td>
								<td><input type='text' id='sum_covid' class='form-control input-sm text-right' readonly></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- End List Survey -->

			<div class="box">
				<div class="box-body ">
					<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<tfoot>
							<tr>
								<td colspan="4"><b>TOTAL ALL</b></td>
								<td style='width: 9%;'><input type='text' id='sum_total' class='form-control input-sm text-right' readonly></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>

		</div>
		<!-- /.box-body -->
		<div class='box-footer'>
			<?php
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-primary','value'=>'save','content'=>'Save','id'=>'save_work')).' ';
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','value'=>'back','content'=>'Back','id'=>'back_work'));
			?>
		</div>
		<!-- /.box-footer -->
	 </div>
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
</style>
<script>
	$(document).ready(function(){
   	 	$("#cost_id").hide();
		$(".hideSP").hide();
		// $("#save_work").hide();
   		 $(".rate").maskMoney();

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

		$(document).on('click','#btn_sh_he', function(){
			$('.sh_he').slideToggle("slow");
			var htmL = $(this).html();
			if(htmL == 'SHOW'){ $(this).html('HIDE') }
			if(htmL == 'HIDE'){ $(this).html('SHOW') }
		});

		$(document).on('click','#btn_sh_vt', function(){
			$('.sh_vt').slideToggle("slow");
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

		$(document).on('click','#btn_sh_survey', function(){
			$('.sh_survey').slideToggle("slow");
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

		$(document).on('click','#btn_sh_covid', function(){
			$('.sh_covid').slideToggle("slow");
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

		//auto cal overtime
		var time 				= getNum($("#total_time").val()) - 7;
		if(time < 0){
			var time 				= 0;
		}
		$('.jam_ot').val(time);
		$(".jam_ot" ).each(function() {
			var rate 				= getNum($(this).parent().parent().find("td:nth-child(6) input").val().split(",").join(""));
			var day 				= getNum($(this).parent().parent().find("td:nth-child(3) input").val());
			var people 			= getNum($(this).parent().parent().find("td:nth-child(2) input").val());
			var jam 				= getNum($(this).parent().parent().find("td:nth-child(4) input").val());
			var rate_total 	= $(this).parent().parent().find("td:nth-child(7) input");
			var rate_t 			= rate * day * people * jam;
			$(rate_total).val(number_format(rate_t));
			get_sum();
		});

		get_sum();

    $(".rate_meal" ).each(function() {
      var rate 				= getNum($(this).val().split(",").join(""));
      var day 				= getNum($(this).parent().parent().find("td:nth-child(4) input").val());
      var people 			= getNum($(this).parent().parent().find("td:nth-child(3) input").val());
      var rate_total 	= $(this).parent().parent().find("td:nth-child(8) input");
      var rate_t 			= rate * day * people;
      $(rate_total).val(number_format(rate_t));
			get_sum();
    });

    $(".rate_house" ).each(function() {
      var rate 				= getNum($(this).val().split(",").join(""));
      var qty 				= getNum($(this).parent().parent().find("td:nth-child(2) input").val());
      var day 				= getNum($(this).parent().parent().find("td:nth-child(3) input").val());
      var rate_total 	= $(this).parent().parent().find("td:nth-child(7) input");
      var rate_t 			= rate * day * qty;
      $(rate_total).val(number_format(rate_t));
			get_sum();
    });

    $(".rate_etc" ).each(function() {
      var rate 				= getNum($(this).val().split(",").join(""));
      var qty 				= getNum($(this).parent().parent().find("td:nth-child(2) input").val());
      var rate_total 	= $(this).parent().parent().find("td:nth-child(5) input");
      var rate_t 			= rate * qty;
      $(rate_total).val(number_format(rate_t));
			get_sum();
    });

	$(document).on('keypress keyup blur', '.rate_trans, .pp_trans', function(){
		var rate 				= getNum($(this).parent().parent().find("td:nth-child(8) input").val().split(",").join(""));
		var people 			= getNum($(this).parent().parent().find("td:nth-child(5) input").val());
		var pp 					= getNum($(this).parent().parent().find("td:nth-child(6) input").val());
		var rate_total 	= $(this).parent().parent().find("td:nth-child(9) input");
		var rate_t 			= rate * 2 * pp;
		$(rate_total).val(number_format(rate_t));
		get_sum();
	});

	$(document).on('keypress keyup blur', '.day_meal', function(){
		var rate 				= getNum($(this).parent().parent().find("td:nth-child(6) input").val().split(",").join(""));
		var day 				= getNum($(this).parent().parent().find("td:nth-child(4) input").val());
		var people 			= getNum($(this).parent().parent().find("td:nth-child(3) input").val());
		var rate_total 	= $(this).parent().parent().find("td:nth-child(7) input");
		var rate_t 			= rate * day * people;
		$(rate_total).val(number_format(rate_t));
		get_sum();
	});

	$(document).on('keypress keyup blur', '.qty_house, .day_house, .rate_house', function(){
		var rate 				= getNum($(this).parent().parent().find("td:nth-child(6) input").val().split(",").join(""));
		var qty 				= getNum($(this).parent().parent().find("td:nth-child(2) input").val());
		var day 				= getNum($(this).parent().parent().find("td:nth-child(3) input").val());
		var rate_total 	= $(this).parent().parent().find("td:nth-child(7) input");
		var rate_t 			= rate * day * qty;
		$(rate_total).val(number_format(rate_t));
		get_sum();
	});

	$(document).on('keypress keyup blur', '.qty_etc, .rate_etc', function(){
		var rate 				= getNum($(this).parent().parent().find("td:nth-child(4) input").val().split(",").join(""));
		var qty 				= getNum($(this).parent().parent().find("td:nth-child(2) input").val());
		var rate_total 	= $(this).parent().parent().find("td:nth-child(5) input");
		var rate_t 			= rate * qty;
		$(rate_total).val(number_format(rate_t));
		get_sum();
	});

	$(document).on('keypress keyup blur', '.qty_testing, .rate_testing', function(){
		var rate 				= getNum($(this).parent().parent().find("td:nth-child(4) input").val().split(",").join(""));
		var qty 				= getNum($(this).parent().parent().find("td:nth-child(2) input").val().split(",").join(""));
		var rate_total 	= $(this).parent().parent().find("td:nth-child(5) input");
		var rate_t 			= rate * qty;
		$(rate_total).val(number_format(rate_t));
		get_sum();
	});

	$(document).on('keypress keyup blur', '.qty_survey, .rate_survey', function(){
		var rate 		= getNum($(this).parent().parent().find("td:nth-child(6) input").val().split(",").join(""));
		var jml_orang 	= getNum($(this).parent().parent().find("td:nth-child(2) input").val().split(",").join(""));
		var jml_qty 	= getNum($(this).parent().parent().find("td:nth-child(3) input").val().split(",").join(""));
		var jml_hari 	= getNum($(this).parent().parent().find("td:nth-child(4) input").val().split(",").join(""));
		var rate_total 	= $(this).parent().parent().find("td:nth-child(7) input");
		var rate_t1 		= rate * jml_orang;
		var rate_t2 		= rate * jml_qty;
		var rate_t 			= (rate_t1 + rate_t2) * jml_hari;
		$(rate_total).val(number_format(rate_t));
		get_sum();
	});

	$(document).on('keypress keyup blur', '.qty_mde, .rate_mde', function(){
		var rate 		= getNum($(this).parent().parent().find("td:nth-child(7) input").val().split(",").join(""));
		var qty 		= getNum($(this).parent().parent().find("td:nth-child(5) input").val().split(",").join(""));
		var rate_total 	= $(this).parent().parent().find("td:nth-child(8) input");
		var rate_t 			= (rate * qty);
		$(rate_total).val(number_format(rate_t));
		get_sum();
	});

	$(document).on('keypress keyup blur', '.qty_covid, .rate_covid', function(){
		var rate 		= getNum($(this).parent().parent().find("td:nth-child(7) input").val().split(",").join(""));
		var jumlah 		= getNum($(this).parent().parent().find("td:nth-child(2) input").val().split(",").join(""));
		var durasi 		= getNum($(this).parent().parent().find("td:nth-child(4) input").val().split(",").join(""));
		if(durasi < 1){
			var durasi 		= 1;
		}
		var rate_total 	= $(this).parent().parent().find("td:nth-child(8) input");
		var rate_t 			= (rate * jumlah) * durasi;
		$(rate_total).val(number_format(rate_t));
		get_sum();
	});

	$(document).on('keypress keyup blur', '.qty_covid', function(){
		var durasi 		= getNum($(this).parent().parent().find("td:nth-child(4) input").val().split(",").join(""));
		if(durasi < 1){
			$(this).parent().parent().find("td:nth-child(4) input").val(1)
		}
	});

	$(document).on('keypress keyup blur', '.change_mp', function(){
		var qty_man 	= getNum($(this).parent().parent().find("td:nth-child(3) input").val().split(",").join(""));
		var qty_day 	= getNum($(this).parent().parent().find("td:nth-child(4) input").val().split(",").join(""));
		var qty_ot 		= getNum($(this).parent().parent().find("td:nth-child(5) input").val().split(",").join(""));
		var rate_mp 	= getNum($(this).parent().parent().find("td:nth-child(7) input").val().split(",").join(""));
		var rate_ot 	= getNum($(this).parent().parent().find("td:nth-child(8) input").val().split(",").join(""));
		var rate_us 	= getNum($(this).parent().parent().find("td:nth-child(9) input").val().split(",").join(""));
		var rate_um 	= getNum($(this).parent().parent().find("td:nth-child(10) input").val().split(",").join(""));
		var total_mp 	= $(this).parent().parent().find("td:nth-child(11) input");
		var rate_total 	= $(this).parent().parent().find("td:nth-child(12) input");

		var cal_tot_mp 	= qty_man * qty_day * rate_mp;
		var cal_tot_ot 	= qty_man * qty_day * qty_ot * rate_ot;
		var cal_tot_us 	= qty_man * qty_day * rate_us;
		var cal_tot_um 	= qty_man * qty_day * rate_um;

		var cal_tot 	= cal_tot_mp + cal_tot_ot + cal_tot_us + cal_tot_um;

		$(total_mp).val(number_format(cal_tot_mp));
		$(rate_total).val(number_format(cal_tot));
		get_sum();
	});

		$(document).on('keypress keyup blur', '#total_time', function(){
			var time 				= getNum($(this).val());
			if(time < 0){
				var time 				= 0;
			}
			$('.jam_ot').val(time);
			$(".jam_ot" ).each(function() {
				var rate 				= getNum($(this).parent().parent().find("td:nth-child(6) input").val().split(",").join(""));
				var day 				= getNum($(this).parent().parent().find("td:nth-child(3) input").val());
				var people 			= getNum($(this).parent().parent().find("td:nth-child(2) input").val());
				var jam 				= getNum($(this).parent().parent().find("td:nth-child(4) input").val());
				var rate_total 	= $(this).parent().parent().find("td:nth-child(7) input");
				var rate_t 			= rate * day * people * jam;
				$(rate_total).val(rate_t);
				get_sum();
			});
		});

		$(document).on('change', '.ch_rate', function(){
			var code				= $(this).data('code');
			var category		= $(this).data('category');
			var unit				= $(this).val();
			var region			= $("#region_code").val();

			var time 				= getNum($(this).parent().parent().find("td:nth-child(4) input").val());
			var qty 				= getNum($(this).parent().parent().find("td:nth-child(3) input").val());

			var cost 				= $(this).parent().parent().find("td:nth-child(6) input"); //cost
			var cost_unit 	= $(this).parent().parent().find("td:nth-child(7) input"); //cost_unit
			var total_cost 	= $(this).parent().parent().find("td:nth-child(8) input"); //total_cost

			loading_spinner();
  		//tempat tinggal
  		$.ajax({
  			url: base_url+ active_controller+'/get_rate'+'/'+code+'/'+category+'/'+unit+'/'+time+'/'+qty+'/'+region,
  			cache: false,
  			type: "POST",
  			dataType: "json",
  			success: function(data){
  			  $(cost).val(data.cost);
					$(cost_unit).val(data.cost_unit);
					$(total_cost).val(data.total_cost);
  				swal.close();
					get_sum();
  			},
  			error: function() {
          swal({
            title				: "Error Message !",
            text				: 'Connection Time Out. Please try again..',
            type				: "warning",
            timer				: 3000
          });
        }
  		});
		});

    $(document).on('change', '.etcSelect', function(){
      var nomor 	    = $(this).data('nomor');
      var code_group 	= $(this).val();
  		var unit 				= 'day';
  		var region 		  = $('#region_code').val();
      var category 		= 'akomodasi';
      var qty 		    = getNum($('#qtye_'+nomor).val());
  		loading_spinner();
  		//tempat tinggal
  		$.ajax({
  			url: base_url+ active_controller+'/getPrice'+'/'+code_group+'/'+unit+'/'+region+'/'+category,
  			cache: false,
  			type: "POST",
  			dataType: "json",
  			success: function(data){
  			  $("#ratee_"+nomor).val(data.rate);
          var ratex = data.rate.split(",").join("");
          $("#total_ratee_"+nomor).val(ratex * qty);
  				swal.close();
					get_sum();
  			},
  			error: function() {
          swal({
            title				: "Error Message !",
            text				: 'Connection Time Out. Please try again..',
            type				: "warning",
            timer				: 3000
          });
        }
  		});
		});

    $(document).on('change', '.houseSelect, .unit_house', function(){
      var nomor 	    = $(this).data('nomor');
      var code_group 	= $('#codeh_'+nomor).val();
  		var unit 				= $('#satuanh_'+nomor).val();
  		var region 		  = $('#region_code').val();
      var category 		= 'akomodasi';
      var qty 		    = getNum($('#qtyh_'+nomor).val());
      var day 		    = getNum($('#valueh_'+nomor).val());
  		loading_spinner();
  		//tempat tinggal
  		$.ajax({
  			url: base_url+ active_controller+'/getPrice'+'/'+code_group+'/'+unit+'/'+region+'/'+category,
  			cache: false,
  			type: "POST",
  			dataType: "json",
  			success: function(data){
  			  $("#rateh_"+nomor).val(data.rate);
          var ratex = data.rate.split(",").join("");
          $("#total_rateh_"+nomor).val(ratex * qty * day);
  				swal.close();
					get_sum();
  			},
  			error: function() {
          swal({
            title				: "Error Message !",
            text				: 'Connection Time Out. Please try again..',
            type				: "warning",
            timer				: 3000
          });
        }
  		});
		});

		//update price
		$('#update_cn').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
      		var project = $(this).data('project');
			swal({
				  title: "Are you sure?",
				  text: "You will update price!",
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
						var baseurl		= base_url + active_controller +'/update_price_consumable/'+project;
						$.ajax({
							url			: baseurl,
							type		: "POST",
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
										  timer	: 3000
										});
									window.location.href = base_url + active_controller+'/edit/'+data.code;
								}
								else{
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 3000
									});
								}

								$('#update_cn').prop('disabled',false);
							},
							error: function() {

								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',
								  type				: "warning",
								  timer				: 3000
								});
								$('#update_cn').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#update_cn').prop('disabled',false);
					return false;
				  }
			});
		});

		$('#update_vt').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
      		var project = $(this).data('project');
			swal({
				  title: "Are you sure?",
				  text: "You will update price!",
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
						var baseurl		= base_url + active_controller +'/update_price_tools/'+project;
						$.ajax({
							url			: baseurl,
							type		: "POST",
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
										  timer	: 3000
										});
									window.location.href = base_url + active_controller+'/edit/'+data.code;
								}
								else{
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 3000
									});
								}

								$('#update_vt').prop('disabled',false);
							},
							error: function() {

								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',
								  type				: "warning",
								  timer				: 3000
								});
								$('#update_vt').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#update_vt').prop('disabled',false);
					return false;
				  }
			});
		});

		$('#update_mp').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
      		var project = $(this).data('project');
			swal({
				  title: "Are you sure?",
				  text: "You will update price!",
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
						var baseurl		= base_url + active_controller +'/update_price_man_power/'+project;
						$.ajax({
							url			: baseurl,
							type		: "POST",
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
										  timer	: 3000
										});
									window.location.href = base_url + active_controller+'/edit/'+data.code;
								}
								else{
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 3000
									});
								}
								$('#update_mp').prop('disabled',false);
							},
							error: function() {

								swal({
								  title		: "Error Message !",
								  text		: 'An Error Occured During Process. Please try again..',
								  type		: "warning",
								  timer		: 3000
								});
								$('#update_mp').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#update_mp').prop('disabled',false);
					return false;
				  }
			});
		});

		$('#update_he').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
      		var project = $(this).data('project');
			swal({
				  title: "Are you sure?",
				  text: "You will update price!",
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
						var baseurl		= base_url + active_controller +'/update_price_heavy/'+project;
						$.ajax({
							url			: baseurl,
							type		: "POST",
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
										  timer	: 3000
										});
									window.location.href = base_url + active_controller+'/edit/'+data.code;
								}
								else{
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 3000
									});
								}

								$('#update_he').prop('disabled',false);
							},
							error: function() {

								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',
								  type				: "warning",
								  timer				: 3000
								});
								$('#update_he').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#update_he').prop('disabled',false);
					return false;
				  }
			});
		});

		$('#update_house').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
      		var project = $(this).data('project');
			swal({
				  title: "Are you sure?",
				  text: "You will update price!",
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
						var baseurl		= base_url + active_controller +'/update_price_house/'+project;
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
										  timer	: 3000
										});
									window.location.href = base_url + active_controller+'/edit/'+data.code;
								}
								else{
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 3000
									});
								}

								$('#update_house').prop('disabled',false);
							},
							error: function() {

								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',
								  type				: "warning",
								  timer				: 3000
								});
								$('#update_house').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#update_house').prop('disabled',false);
					return false;
				  }
			});
		});

		$('#update_etc').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
      		var project = $(this).data('project');
			swal({
				  title: "Are you sure?",
				  text: "You will update price!",
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
						var baseurl		= base_url + active_controller +'/update_price_etc/'+project;
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
										  timer	: 3000
										});
									window.location.href = base_url + active_controller+'/edit/'+data.code;
								}
								else{
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 3000
									});
								}

								$('#update_etc').prop('disabled',false);
							},
							error: function() {

								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',
								  type				: "warning",
								  timer				: 3000
								});
								$('#update_etc').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#update_etc').prop('disabled',false);
					return false;
				  }
			});
		});

		$('#update_mde').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
      		var project = $(this).data('project');
			swal({
				  title: "Are you sure?",
				  text: "You will update price!",
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
						var baseurl		= base_url + active_controller +'/update_price_mde/'+project;
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
										  timer	: 3000
										});
									window.location.href = base_url + active_controller+'/edit/'+data.code;
								}
								else{
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 3000
									});
								}

								$('#update_mde').prop('disabled',false);
							},
							error: function() {

								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',
								  type				: "warning",
								  timer				: 3000
								});
								$('#update_mde').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#update_mde').prop('disabled',false);
					return false;
				  }
			});
		});

		$('#update_survey').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
      		var project = $(this).data('project');
			swal({
				  title: "Are you sure?",
				  text: "You will update price!",
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
						var baseurl		= base_url + active_controller +'/update_price_survey/'+project;
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
										  timer	: 3000
										});
									window.location.href = base_url + active_controller+'/edit/'+data.code;
								}
								else{
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 3000
									});
								}

								$('#update_survey').prop('disabled',false);
							},
							error: function() {

								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',
								  type				: "warning",
								  timer				: 3000
								});
								$('#update_survey').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#update_survey').prop('disabled',false);
					return false;
				  }
			});
		});

		$('#update_covid').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
      		var project = $(this).data('project');
			swal({
				  title: "Are you sure?",
				  text: "You will update price!",
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
						var baseurl		= base_url + active_controller +'/update_price_covid/'+project;
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
										  timer	: 3000
										});
									window.location.href = base_url + active_controller+'/edit/'+data.code;
								}
								else{
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 3000
									});
								}

								$('#update_covid').prop('disabled',false);
							},
							error: function() {

								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',
								  type				: "warning",
								  timer				: 3000
								});
								$('#update_covid').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#update_covid').prop('disabled',false);
					return false;
				  }
			});
		});


		//save
		$('#save_work').click(function(e){
			e.preventDefault();
			$(this).prop('disabled',true);
      // alert("Development Process");
      // return false;
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
						var baseurl		= base_url + active_controller +'/edit';
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
										  timer	: 3000
										});
									window.location.href = base_url + active_controller;
								}
								else if(data.status == 2 || data.status == 3){
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning",
									  timer	: 3000
									});
								}

								$('#save_work').prop('disabled',false);
							},
							error: function() {

								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',
								  type				: "warning",
								  timer				: 3000
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
			window.location.href = base_url + active_controller;
		});

    //add Housing
		var nomor_house	= 1;
    $(document).on('click', '#add_house', function(e){
			e.preventDefault();
			loading_spinner();
			var nilaiAwal	= parseInt($("#numberHouse").val());
			var nilaiAkhir	= nilaiAwal + 1;
			$("#numberHouse").val(nilaiAkhir);

			AppendBarisHouse(nomor_house, nilaiAkhir);
			$('.chosen_select').chosen({width: '100%'});

			$("#detail_body_house_empty").hide();
			swal.close();
		});

    $(document).on('click','.delRowsH', function(){
			$(this).parent().parent().parent().remove();

			var updatemax	=	$("#numberHouse").val() - 1;
			$("#numberHouse").val(updatemax);

			var maxLine = $("#numberHouse").val();
			if(maxLine == 0){
				$("#detail_body_house_empty").show();
			}

			get_sum();
		});

    //add Trans
		var nomor_trans	= 1;
    $(document).on('click', '#add_trans', function(e){
			e.preventDefault();
			loading_spinner();
			var nilaiAwal	= parseInt($("#numberTrans").val());
			var nilaiAkhir	= nilaiAwal + 1;
			$("#numberTrans").val(nilaiAkhir);

			AppendBarisTrans(nomor_trans, nilaiAkhir);
			$('.chosen_select').chosen({width: '100%'});

			$("#detail_body_trans_empty").hide();
			$('#save_work').show();
			swal.close();
		});

    $(document).on('click','.delRowsT', function(){
			$(this).parent().parent().parent().remove();

			var updatemax	=	$("#numberTrans").val() - 1;
			$("#numberTrans").val(updatemax);

			var maxLine = $("#numberTrans").val();
			if(maxLine == 0){
				$("#detail_body_trans_empty").show();
			}
		});

    //add Etc
		var nomor_etc	= 1;
    $(document).on('click','#add_etc', function(e){
			e.preventDefault();
			loading_spinner();
			var nilaiAwal	= parseInt($("#numberEtc").val());
			var nilaiAkhir	= nilaiAwal + 1;
			$("#numberEtc").val(nilaiAkhir);

			AppendBarisEtc(nomor_etc, nilaiAkhir);
			$('.chosen_select').chosen({width: '100%'});

			$("#detail_body_etc_empty").hide();
			$('#save_work').show();
			swal.close();
		});

    $(document).on('click','.delRowsE', function(){
			$(this).parent().parent().parent().remove();

			var updatemax	=	$("#numberEtc").val() - 1;
			$("#numberEtc").val(updatemax);

			var maxLine = $("#numberEtc").val();
			if(maxLine == 0){
				$("#detail_body_etc_empty").show();
			}
		});

	});

  function AppendBarisHouse(intd){
		var nomor	= 1;
		var valuex	= $('#detail_body_house').find('tr').length;
		if(valuex > 0){
			var akhir	= $('#detail_body_house tr:last').attr('id');
			// console.log(akhir);
			var det_id	= akhir.split('_');
			var nomor	= parseInt(det_id[1])+1;
		}

		var Rows	 = 	"<tr id='trak_"+nomor+"'>";
			Rows	+= 		"<td align='left'  width='10%'>";
      Rows  +=    "<div class='input-group'>";
			Rows	+=			"<select name='ListDetailHouse["+nomor+"][code_group]' id='codeh_"+nomor+"' data-nomor='"+nomor+"' class='chosen_select form-control inline-block houseSelect'></select>";
      Rows	+= 		"<span class='input-group-addon cldelete delRowsH'><i class='fa fa-close'></i></span>";
      Rows	+= 		"</div>";
      Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][qty]' id='qtyh_"+nomor+"' class='form-control input-sm numberFull qty_house' placeholder='Qty'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][value]' id='valueh_"+nomor+"' class='form-control input-sm numberFull day_house' placeholder='Value'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=			"<select name='ListDetailHouse["+nomor+"][satuan]' id='satuanh_"+nomor+"' id='satuanh_"+nomor+"' class='chosen_select form-control inline-block unit_house'></select>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][note]' id='noteh_"+nomor+"' class='form-control input-sm' placeholder='Note'>";
			Rows	+= 		"</td>";
      Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][rate]' id='rateh_"+nomor+"' class='form-control input-sm text-right rate rate_house' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
			Rows	+= 		"</td>";
      Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailHouse["+nomor+"][total_rate]' id='total_rateh_"+nomor+"' class='form-control input-sm text-right rate sum_house' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
			Rows	+= 		"</td>";
			Rows	+= 	"</tr>";

		$('#detail_body_house').append(Rows);

		var item_cost 	= '#codeh_'+nomor;
		var satuan 	= '#satuanh_'+nomor;
		loading_spinner();
		//tempat tinggal
		$.ajax({
			url: base_url+'index.php/instalation/list_tempat_tinggal',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$(item_cost).html(data.option).trigger("chosen:updated");
				swal.close();
			},
			error: function() {
        swal({
          title				: "Error Message !",
          text				: 'Connection Time Out. Please try again..',
          type				: "warning",
          timer				: 3000
        });
      }
		});
		//satuan
		$.ajax({
			url: base_url+'index.php/instalation/list_satuan',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$(satuan).html(data.option).trigger("chosen:updated");
				swal.close();
			},
			error: function() {
				swal({
					title				: "Error Message !",
					text				: 'Connection Time Out. Please try again..',
					type				: "warning",
					timer				: 3000
				});
			}
		});
    $(".rate").maskMoney();
		number_full();
		ex_petik()
	}

  function AppendBarisTrans(intd){
		var nomor	= 1;
		var valuex	= $('#detail_body_trans').find('tr').length;
		if(valuex > 0){
			var akhir	= $('#detail_body_trans tr:last').attr('id');
			// console.log(akhir);
			var det_id	= akhir.split('_');
			var nomor	= parseInt(det_id[1])+1;
		}

		var Rows	 = 	"<tr id='trak_"+nomor+"'>";
			Rows	+= 		"<td align='left'  width='10%'>";
      Rows  +=    "<div class='input-group'>";
			Rows	+=			"<select name='ListDetailTrans["+nomor+"][item_cost]' id='item_costt_"+nomor+"' class='chosen_select form-control inline-block'></select>";
      Rows	+= 		"<span class='input-group-addon cldelete delRowsT'><i class='fa fa-close'></i></span>";
      Rows	+= 		"</div>";
      Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=			"<select name='ListDetailTrans["+nomor+"][kendaraan]' id='kendaraant_"+nomor+"' class='chosen_select form-control inline-block'></select>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][asal]' id='asalt_"+nomor+"' class='form-control input-sm' placeholder='Origin'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][tujuan]' id='tujuant_"+nomor+"' class='form-control input-sm' placeholder='Destination'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][value]' id='valuet_"+nomor+"' class='form-control input-sm numberFull' placeholder='Value'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][pulang_pergi]' id='pulang_pergit_"+nomor+"' class='form-control input-sm numberFull pp_trans' placeholder='Round-Trip'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][note]' id='notet_"+nomor+"' class='form-control input-sm' placeholder='Note'>";
			Rows	+= 		"</td>";
      Rows	+= 		"<td align='left'>";
      Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][rate]' id='ratet_"+nomor+"' class='form-control input-sm text-right rate rate_trans' placeholder='Cost' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
      Rows	+= 		"</td>";
      Rows	+= 		"<td align='left'>";
      Rows	+=				"<input type='text' name='ListDetailTrans["+nomor+"][total_rate]' id='total_ratet_"+nomor+"' readonly class='form-control input-sm text-right rate sum_trans' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
      Rows	+= 		"</td>";
			Rows	+= 	"</tr>";

		$('#detail_body_trans').append(Rows);

		var item_cost 	= '#item_costt_'+nomor;
		var kendaraan 	= '#kendaraant_'+nomor;
		loading_spinner();
		//tempat tinggal
		$.ajax({
			url: base_url+'index.php/instalation/list_tiket',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$(item_cost).html(data.option).trigger("chosen:updated");
				swal.close();
			},
			error: function() {
        swal({
          title				: "Error Message !",
          text				: 'Connection Time Out. Please try again..',
          type				: "warning",
          timer				: 3000
        });
      }
		});

		$(document).on('change',item_cost,function(){
			var ad = $(this).val();

			$.ajax({
				url: base_url+'index.php/instalation/list_sewa_kendaraan/'+ad,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data){
					$(kendaraan).html(data.option).trigger("chosen:updated");
					swal.close();
				},
				error: function() {
	        swal({
	          title				: "Error Message !",
	          text				: 'Connection Time Out. Please try again..',
	          type				: "warning",
	          timer				: 3000
	        });
	      }
			});
		});
    $(".rate").maskMoney();
		number_full();
		ex_petik()
	}

  function AppendBarisEtc(intd){
		var nomor	= 1;
		var valuex	= $('#detail_body_etc').find('tr').length;
		if(valuex > 0){
			var akhir	= $('#detail_body_etc tr:last').attr('id');
			// console.log(akhir);
			var det_id	= akhir.split('_');
			var nomor	= parseInt(det_id[1])+1;
		}

		var Rows	 = 	"<tr id='trak_"+nomor+"'>";
			Rows	+= 		"<td align='left'  width='10%'>";
      Rows  +=    "<div class='input-group'>";
			Rows	+=			"<select name='ListDetailEtc["+nomor+"][code_group]' id='item_coste_"+nomor+"' data-nomor='"+nomor+"' class='chosen_select form-control inline-block etcSelect'></select>";
      Rows	+= 		"<span class='input-group-addon cldelete delRowsE'><i class='fa fa-close'></i></span>";
      Rows	+= 		"</div>";
      Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailEtc["+nomor+"][qty]' id='qtye_"+nomor+"' class='form-control input-sm numberFull qty_etc' placeholder='Value'>";
			Rows	+= 		"</td>";
			Rows	+= 		"<td align='left'>";
			Rows	+=				"<input type='text' name='ListDetailEtc["+nomor+"][note]' id='notee_"+nomor+"' class='form-control input-sm' placeholder='Note'>";
			Rows	+= 		"</td>";
      Rows	+= 		"<td align='left'>";
      Rows	+=				"<input type='text' name='ListDetailEtc["+nomor+"][rate]' id='ratee_"+nomor+"' class='form-control input-sm text-right rate' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
      Rows	+= 		"</td>";
      Rows	+= 		"<td align='left'>";
      Rows	+=				"<input type='text' name='ListDetailEtc["+nomor+"][total_rate]' id='total_ratee_"+nomor+"' readonly class='form-control input-sm text-right rate sum_etc' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
      Rows	+= 		"</td>";
			Rows	+= 	"</tr>";

		$('#detail_body_etc').append(Rows);

		var item_cost 	= '#item_coste_'+nomor;
		loading_spinner();
		//tempat tinggal
		$.ajax({
			url: base_url+'index.php/instalation/list_etc',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(data){
				$(item_cost).html(data.option).trigger("chosen:updated");
				swal.close();
			},
			error: function() {
        swal({
          title				: "Error Message !",
          text				: 'Connection Time Out. Please try again..',
          type				: "warning",
          timer				: 3000
        });
      }
		});
    $(".rate").maskMoney();
		number_full();
		ex_petik();
	}

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
			SUM_HE += Number(getNum($(this).val().split(",").join("")));
   	 	});
		$(".sum_vt" ).each(function() {
			SUM_VT += Number(getNum($(this).val().split(",").join("")));
   	 	});
		$(".sum_cn" ).each(function() {
			SUM_CN += Number(getNum($(this).val().split(",").join("")));
    	});
		$(".sum_mp" ).each(function() {
			SUM_MP += Number(getNum($(this).val().split(",").join("")));
    	});
		$(".sum_house" ).each(function() {
			SUM_HOUSE += Number(getNum($(this).val().split(",").join("")));
   		});
		$(".sum_trans" ).each(function() {
			SUM_TRANS += Number(getNum($(this).val().split(",").join("")));
    	});
		$(".sum_etc" ).each(function() {
			SUM_ETC += Number(getNum($(this).val().split(",").join("")));
    	});
		$(".sum_testing" ).each(function() {
			SUM_TESTING += Number(getNum($(this).val().split(",").join("")));
    	});
		$(".sum_survey" ).each(function() {
			SUM_SURVEY += Number(getNum($(this).val().split(",").join("")));
    	});
		$(".sum_mde" ).each(function() {
			SUM_MDE += Number(getNum($(this).val().split(",").join("")));
    	});
		$(".sum_covid" ).each(function() {
			SUM_COVID += Number(getNum($(this).val().split(",").join("")));
    	});

		$("#sum_he").val(number_format(SUM_HE));
		$("#sum_vt").val(number_format(SUM_VT));
		$("#sum_cn").val(number_format(SUM_CN));
		$("#sum_mp").val(number_format(SUM_MP));
		$("#sum_house").val(number_format(SUM_HOUSE));
		$("#sum_trans").val(number_format(SUM_TRANS));
		$("#sum_etc").val(number_format(SUM_ETC));
		$("#sum_testing").val(number_format(SUM_TESTING));
		$("#sum_survey").val(number_format(SUM_SURVEY));
		$("#sum_mde").val(number_format(SUM_MDE));
		$("#sum_covid").val(number_format(SUM_COVID));
		$("#sum_total").val(number_format(SUM_VT + SUM_HE + SUM_CN + SUM_MP + SUM_HOUSE + SUM_TRANS + SUM_ETC + SUM_TESTING + SUM_SURVEY + SUM_MDE + SUM_COVID));
	}
</script>
