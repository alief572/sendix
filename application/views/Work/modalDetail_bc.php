<?php
$project_code = $this->uri->segment(3);

$qHeader 		= "SELECT * FROM project_header WHERE project_code='".$project_code."'";
$qDetail 		= "SELECT * FROM project_detail_w_header WHERE project_code='".$project_code."' AND deleted='N'";

$header 		= $this->db->query($qHeader)->result();
$restDetail = $this->db->query($qDetail)->result_array();

$qDetailBQ 	= "SELECT * FROM project_detail_bq WHERE project_code='".$project_code."' AND deleted='N'";
$detail_bq 	= $this->db->query($qDetailBQ)->result_array();

// echo $qHeader."<br>".$qDetail;


?>
<div class="box box-primary">
	<div class="box-body">
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>Project Name</b></label>
			<div class='col-sm-4'>
				<?php
				 echo form_input(array('id'=>'project_name','name'=>'project_name','class'=>'form-control input-md','autocomplete'=>'off','readonly'=>'readonly'),strtoupper($header[0]->project_name));
				?>
			</div>
			<label class='label-control col-sm-2'><b>Region</b></label>
			<div class='col-sm-4'>
				<?php
				 echo form_input(array('id'=>'region','name'=>'region','class'=>'form-control input-md','autocomplete'=>'off','readonly'=>'readonly'),strtoupper($header[0]->region));
				?>
			</div>
		</div>
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>Project Location</b></label>
			<div class='col-sm-4'>
				<?php
				 echo form_textarea(array('id'=>'location','name'=>'location','class'=>'form-control input-md','rows'=>'3','readonly'=>'readonly'),strtoupper($header[0]->location));
				?>
			</div>
			<label class='label-control col-sm-2'><b>Time Total /day</b></label>
			<div class='col-sm-4'>
				<?php
				 echo form_input(array('id'=>'total_time','name'=>'total_time','class'=>'form-control input-md','autocomplete'=>'off','readonly'=>'readonly'),strtoupper($header[0]->total_time));
				?>
			</div>
		</div>
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>BQ Project</b></label>
			<div class='col-sm-4'>
				<table id="my-grid" class="table table-striped table-bordered table-hover table-condensed" width="100%">
					<thead id='head_table_bq'>
						<tr class='bg-purple'>
							<th class="text-center" style='width: 40%;'>Qty</th>
							<th class="text-center" style='width: 60%;'>Satuan</th>
						</tr>
					</thead>
					<tbody>
					<?php
						foreach ($detail_bq as $key => $value) {
								echo "<tr>";
									echo "<td align='left'>";
									echo "<input type='number' name='ListDetailBq[0$key][qty]' style='text-align:center;' class='form-control input-md' readonly value='".$value['qty']."'>";
									echo "</td>";
									echo "<td align='left' style='text-align: left;'>";
									echo "<input type='text' name='ListDetailBq[0$key][satuan_code]' class='form-control input-md' readonly value='".$value['satuan_code']."'>";
									echo "</td>";
								echo "</tr>";
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
		<table class="table table-bordered table-striped" id="my-grid" width='100%'>
			<thead>
				<tr class='bg-purple'>
					<th class="text-center" width='3%'>No</th>
					<th class="text-center" width='10%'>Work Name</th>
					<th class="text-center">Spesification</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no=0;
				foreach($restDetail AS $val => $valx){
					$no++;

          $qDetail2 = "SELECT * FROM project_detail_w_det WHERE project_code_det='".$valx['project_code_det']."' AND deleted='N'";
          $restDetail2 = $this->db->query($qDetail2)->result_array();
          $numDetail2 = $this->db->query($qDetail2)->num_rows();
					?>
					<tr>
						<td><?=$no;?></td>
						<td><?=ucfirst(strtolower($valx['category']));?></td>
            <td>

                  <table class='table table-bordered table-striped' id="my-grid" width='100%'>
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
                	foreach($restDetail2 AS $val2 => $valx2d){
										$nox++;
                    $qDet1 = "SELECT * FROM project_detail_w_det_vehicle_tool WHERE project_code_det='".$valx['project_code_det']."' AND code_work_detail='".$valx2d['code_work_detail']."' AND deleted='N'";
                    $qDet2 = "SELECT * FROM project_detail_w_det_con_nonmat WHERE project_code_det='".$valx['project_code_det']."' AND code_work_detail='".$valx2d['code_work_detail']."' AND deleted='N'";
                    $qDet3 = "SELECT * FROM project_detail_w_det_man_power WHERE project_code_det='".$valx['project_code_det']."' AND code_work_detail='".$valx2d['code_work_detail']."' AND deleted='N'";
                    $qDet4 = "SELECT * FROM project_detail_w_det_apd WHERE project_code_det='".$valx['project_code_det']."' AND code_work_detail='".$valx2d['code_work_detail']."' AND deleted='N'";
                    $qDet5 = "SELECT * FROM project_detail_w_det_akomodasi WHERE project_code_det='".$valx['project_code_det']."' AND code_work_detail='".$valx2d['code_work_detail']."' AND deleted='N'";

                    $restDet1 = $this->db->query($qDet1)->result_array();
                    $restDet2 = $this->db->query($qDet2)->result_array();
                    $restDet3 = $this->db->query($qDet3)->result_array();
                    $restDet4 = $this->db->query($qDet4)->result_array();
                    $restDet5 = $this->db->query($qDet5)->result_array();
                    echo "<tr>";
										echo "<td align='center'>".$nox."</td>";
                    echo "<td>".ucfirst(strtolower($valx2d['work_process']))."</td>";
                    ?>
                    <td>
											<table class='table table-bordered table-striped' id="my-grid" width='100%'>
        							<?php
        							foreach($restDet1 AS $val1 => $valx1){
												echo "<tr>";
	        								echo "<td width='40%'>".ucfirst(strtolower($valx1['category']))."</td>";
													echo "<td width='40%'>".ucfirst(strtolower($valx1['spec']))."</td>";
													echo "<td width='20%' align='center'>".$valx1['qty']."</td>";
												echo "<tr>";
        							}
        							?>
											</table>
        						</td>
										<td>
											<table class='table table-bordered table-striped' id="my-grid" width='100%'>
        							<?php
											foreach($restDet2 AS $val2 => $valx2){
												echo "<tr>";
	        								echo "<td width='40%'>".ucfirst(strtolower($valx2['category']))."</td>";
													echo "<td width='40%'>".ucfirst(strtolower($valx2['spec']))."</td>";
													echo "<td width='20%' align='center'>".$valx2['qty']."</td>";
												echo "<tr>";
        							}
        							?>
											</table>
        						</td>
										<td>
											<table class='table table-bordered table-striped' id="my-grid" width='100%'>
											<?php
											foreach($restDet3 AS $val3 => $valx3){
												echo "<tr>";
	        								echo "<td width='40%'>".ucfirst(strtolower($valx3['category']))."</td>";
													echo "<td width='40%'>".ucfirst(strtolower($valx3['spec']))."</td>";
													echo "<td width='20%' align='center'>".$valx3['qty']."</td>";
												echo "<tr>";
        							}
											?>
											</table>
										</td>
										<td>
											<table class='table table-bordered table-striped' id="my-grid" width='100%'>
        							<?php
											foreach($restDet4 AS $val4 => $valx4){
												echo "<tr>";
	        								echo "<td width='40%'>".ucfirst(strtolower($valx4['category']))."</td>";
													echo "<td width='40%'>".ucfirst(strtolower($valx4['spec']))."</td>";
													echo "<td width='20%' align='center'>".$valx4['qty']."</td>";
												echo "<tr>";
        							}
        							?>
											</table>
        						</td>
										<td>
											<table class='table table-bordered table-striped' id="my-grid" width='100%'>
											<?php
											foreach($restDet5 AS $val5 => $valx5){
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
	</div>
 </div>

<script>
swal.close();

</script>
