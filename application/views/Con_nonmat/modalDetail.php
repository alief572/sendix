
<div class="box box-primary">
	<div class="box-header">

	</div>
	<div class="box-body">
    <div class='form-group row'>
      <label class='label-control col-sm-2'><b>Consumable Category <span class='text-red'>*</span></b></label>
      <div class='col-sm-4'>
        <?php
         echo form_input(array('id'=>'add_category','name'=>'add_category','class'=>'form-control input-md numAlfa','disabled'=>'disabled'),strtoupper($header[0]->category));
        ?>
      </div>
    </div>
    <div class='form-group row'>
      <label class='label-control col-sm-2'><b>Material Name <span class='text-red'>*</span></b></label>
      <div class='col-sm-4'>
        <?php
         echo form_input(array('id'=>'material_name','name'=>'material_name','class'=>'form-control input-md','disabled'=>'disabled','placeholder'=>'Material Name'),strtoupper($header[0]->material_name));
        ?>
      </div>
      <label class='label-control col-sm-2'><b>General Name <span class='text-red'>*</span></b></label>
      <div class='col-sm-4'>
        <?php
         echo form_input(array('id'=>'general_name','name'=>'general_name','class'=>'form-control input-md','disabled'=>'disabled','placeholder'=>'General Name'),strtoupper($header[0]->general_name));
        ?>
      </div>
    </div>
    <div class='form-group row'>
      <label class='label-control col-sm-2'><b>Spesification/Sertification <span class='text-red'>*</span></b></label>
      <div class='col-sm-4'>
        <?php
         echo form_input(array('id'=>'spec','name'=>'spec','class'=>'form-control input-md','disabled'=>'disabled','placeholder'=>'Spesification/Sertification'),strtoupper($header[0]->spec));
        ?>
      </div>
      <label class='label-control col-sm-2'><b>Brand <span class='text-red'>*</span></b></label>
      <div class='col-sm-4'>
        <?php
         echo form_input(array('id'=>'brand','name'=>'brand','class'=>'form-control input-md','disabled'=>'disabled','placeholder'=>'Brand'),strtoupper($header[0]->brand));
        ?>
      </div>
    </div>
    <div class='form-group row'>
      <label class='label-control col-sm-2'><b>Minimal Order Stock <span class='text-red'>*</span></b></label>
      <div class='col-sm-4'>
        <?php
         echo form_input(array('id'=>'min_order','name'=>'min_order','class'=>'form-control input-md','disabled'=>'disabled','placeholder'=>'Minimal Order Stock'),strtoupper($header[0]->min_order));
        ?>
      </div>
      <label class='label-control col-sm-2'><b>Order Point <span class='text-red'>*</span></b></label>
      <div class='col-sm-1'>
        <?php
         echo form_input(array('id'=>'order_opt','name'=>'order_opt','class'=>'form-control input-md','disabled'=>'disabled','placeholder'=>'Order Point'),strtoupper($header[0]->order_opt));
        ?>
      </div>
      <div class='col-sm-3'>
        <?php
         echo form_input(array('id'=>'order_point','name'=>'order_point','class'=>'form-control input-md','readonly'=>'readonly','placeholder'=>'Qty'),strtoupper($header[0]->order_point));
         echo form_input(array('id'=>'order_point_date','name'=>'order_point_date','data-role'=>'datepicker','class'=>'form-control input-md','readonly'=>'readonly','placeholder'=>'Select Date'),strtoupper($header[0]->order_point_date));

        ?>
      </div>
    </div>
    <div class='form-group row'>
      <label class='label-control col-sm-2'><b>Safety Stock (Day) <span class='text-red'>*</span></b></label>
      <div class='col-sm-4'>
        <?php
         echo form_input(array('id'=>'safety_stock','name'=>'safety_stock','class'=>'form-control input-md numberFull','disabled'=>'disabled','placeholder'=>'Safety Stock'),strtoupper($header[0]->safety_stock));
        ?>
      </div>
      <label class='label-control col-sm-2'><b>Lead Time (Day) <span class='text-red'>*</span></b></label>
      <div class='col-sm-4'>
        <?php
         echo form_input(array('id'=>'lead_time','name'=>'lead_time','class'=>'form-control input-md numberFull','disabled'=>'disabled','placeholder'=>'Lead Time'),strtoupper($header[0]->lead_time));
        ?>
      </div>
    </div>
    <div class='form-group row'>
      <label class='label-control col-sm-2'><b>Maximum Stock (Day) <span class='text-red'>*</span></b></label>
      <div class='col-sm-4'>
        <?php
         echo form_input(array('id'=>'max_stock','name'=>'max_stock','class'=>'form-control input-md','disabled'=>'disabled','placeholder'=>'Maximum Stock'),strtoupper($header[0]->max_stock));
        ?>
      </div>
      <label class='label-control col-sm-2'><b>Consumption (Day) <span class='text-red'>*</span></b></label>
      <div class='col-sm-4'>
        <?php
         echo form_input(array('id'=>'konsumsi','name'=>'konsumsi','class'=>'form-control input-md numberFull','disabled'=>'disabled','placeholder'=>'Consumption'),strtoupper($header[0]->konsumsi));
        ?>
      </div>
    </div>
    <div class='form-group row'>
      <label class='label-control col-sm-2'><b>Note <span class='text-red'>*</span></b></label>
      <div class='col-sm-4'>
        <?php
         echo form_input(array('id'=>'note','name'=>'note','class'=>'form-control input-md','disabled'=>'disabled','placeholder'=>'Note'),strtoupper($header[0]->note));
        ?>
      </div>
    </div><br>
		<br>
		<div class='form-group row'>
			<div class='col-sm-4'>
				<table class="table table-bordered table-striped" id="my-grid" width='100%'>
					<thead>
						<tr class='bg-blue'>
							<th class="text-center" colspan='3'> UNIT AND CONVERSION</th>
						</tr>
            <tr class='bg-purple'>
							<th>Unit Material</th>
              <th>Conversion Value</th>
              <th>Smallest Unit</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($konversi AS $val => $valx){
							echo "<tr>";
								echo "<td>".strtoupper($valx['unit_material'])."</td>";
                echo "<td>".strtoupper($valx['value'])."</td>";
                echo "<td>".strtoupper($valx['small_unit'])."</td>";
							echo "</tr>";
						}
            if(empty($konversi)){
              echo "<tr>";
								echo "<td colspan='3'>Data not found...</td>";
							echo "</tr>";
            }
						?>
					</tbody>
				</table>
			</div>
			<div class='col-sm-4'>
				<table class="table table-bordered table-striped" id="my-grid" width='100%'>
					<thead>
						<tr class='bg-blue'>
							<th class="text-center">SIMILAR MATERIAL</th>
						</tr>
            <tr class='bg-purple'>
							<th>Material Name</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($material AS $val => $valx){
							echo "<tr>";
								echo "<td>".strtoupper($valx['value'])."</td>";
							echo "</tr>";
						}
            if(empty($material)){
              echo "<tr>";
								echo "<td>Data not found...</td>";
							echo "</tr>";
            }
						?>
					</tbody>
				</table>
			</div>
			<div class='col-sm-4'>
				<table class="table table-bordered table-striped" id="my-grid" width='100%'>
					<thead>
						<tr class='bg-blue'>
							<th class="text-center">ALTERNATIVE SUPPLIER</th>
						</tr>
            <tr class='bg-purple'>
							<th>Supplier Name</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($supplier AS $val => $valx){
							echo "<tr>";
								echo "<td>".strtoupper($valx['value_2'])."</td>";
							echo "</tr>";
						}
            if(empty($supplier)){
              echo "<tr>";
								echo "<td>Data not found...</td>";
							echo "</tr>";
            }
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
 </div>

<script>
swal.close();
$(document).ready(function(){
  var opt = $("#order_opt").val();
  if(opt == 'QTY' || opt == ''){
    $('#order_point').show();
    $('#order_point_date').hide();
  }
  if(opt == 'DATE'){
    $('#order_point').hide();
    $('#order_point_date').show();
  }
});
</script>
