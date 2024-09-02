
<div class="box box-primary">
	<div class="box-header">

	</div>
	<div class="box-body">
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>Work Name</b></label>
			<div class='col-sm-10'>: <?=strtoupper($restHeader[0]->category);?></div>
		</div>
		<div class='form-group row'>
			<label class='label-control col-sm-2'><b>Total Time (Day)</b></label>
			<div class='col-sm-10'>: <?=strtoupper($restHeader[0]->total_time);?></div>
		</div>
		<div class='form-group row' hidden>
			<label class='label-control col-sm-2'><b>Tipe Instalasi</b></label>
			<div class='col-sm-10'>: <?=strtoupper($restHeader[0]->tipe);?> GROUND</div>
		</div>
		<br>
		<div class='form-group row'>
			<div class='col-sm-12'>
				<table class="table table-bordered table-striped" id="my-grid" width='100%'>
					<thead>
						<tr class='bg-purple'>
							<th class="text-center">Heavy Equipment</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($heavy_equipment AS $val => $valx){
							echo "<tr>";
								echo "<td>".strtoupper($valx['category']." - ".$valx['spec'])."</td>";
							echo "</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
			<!-- <div class='col-sm-3'>
				<table class="table table-bordered table-striped" id="my-grid" width='100%'>
					<thead>
						<tr class='bg-purple'>
							<th class="text-center">Tools</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($vehicle_tool AS $val => $valx){
							echo "<tr>";
								echo "<td>".strtoupper($valx['category']." - ".$valx['spec'])."</td>";
							echo "</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
			<div class='col-sm-3'>
				<table class="table table-bordered table-striped" id="my-grid" width='100%'>
					<thead>
						<tr class='bg-purple'>
							<th class="text-center">Consumable & APD</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($con_nonmat AS $val => $valx){
							echo "<tr>";
								echo "<td>".strtoupper($valx['category']." - ".$valx['spec'])."</td>";
							echo "</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
			<div class='col-sm-3'>
				<table class="table table-bordered table-striped" id="my-grid" width='100%'>
					<thead>
						<tr class='bg-purple'>
							<th class="text-center">Man Power</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($man_power AS $val => $valx){
							echo "<tr>";
								echo "<td>".strtoupper($valx['category']." - ".$valx['spec'])."</td>";
							echo "</tr>";
						}
						?>
					</tbody>
				</table>
			</div> -->
		</div>
	</div>
 </div>

<script>
swal.close();
</script>
