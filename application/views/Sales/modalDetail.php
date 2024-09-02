<?php
$id_customer 	= (!empty($header[0]->nm_customer))?$header[0]->nm_customer:'0';
$project 			= (!empty($header[0]->project))?strtoupper($header[0]->project):'';
$ref_cust 		= (!empty($header[0]->ref_cust))?strtoupper($header[0]->ref_cust):'';
$validity 		= (!empty($header[0]->validity))?strtoupper($header[0]->validity):'';
$location 		= (!empty($header[0]->location))?strtoupper($header[0]->location):'';
$app 					= (!empty($header[0]->app))?strtoupper($header[0]->app):'above ground';
?>
<div class="box box-primary">
  <div class="box-body">

  <div class="box box-success">
    <div class="box-header">
      <h3 class="box-title">Header</h3>
    </div>
		<div class="box-body">
			<div class='form-group row'>
					<label class='label-control col-sm-2'><b>Customer Name</b></label>
					<div class='col-sm-4'>
						<?php
						 echo form_input(array('disabled'=>'disabled','name'=>'nm_customer','class'=>'form-control input-md','placeholder'=>'Validity & Guarantee'),$id_customer);
						?>
					</div>
					<label class='label-control col-sm-2'><b>Validity & Guarantee</b></label>
					<div class='col-sm-4'>
						<?php
						 echo form_input(array('disabled'=>'disabled','name'=>'validity','class'=>'form-control input-md','placeholder'=>'Validity & Guarantee'),$validity);
						?>
					</div>
				</div>
				<div class='form-group row'>
					<label class='label-control col-sm-2'><b>Project</b></label>
					<div class='col-sm-4'>
						<?php
						 echo form_textarea(array('disabled'=>'disabled','name'=>'project','class'=>'form-control input-md','rows'=>'2','cols'=>'75','placeholder'=>'Project'),$project);
						?>
					</div>
					<label class='label-control col-sm-2'><b>Referensi Customer/Project</b></label>
					<div class='col-sm-4'>
						<?php
						 echo form_textarea(array('disabled'=>'disabled','name'=>'ref_cust','class'=>'form-control input-md','rows'=>'2','cols'=>'75','placeholder'=>'Ref Customer/Project'),$ref_cust);
						?>
					</div>
				</div>
        <div class='form-group row'>
					<label class='label-control col-sm-2'><b>Location</b></label>
					<div class='col-sm-4'>
						<?php
						 echo form_textarea(array('disabled'=>'disabled','name'=>'location','class'=>'form-control input-md','rows'=>'2','cols'=>'75','placeholder'=>'Project'),$location);
						?>
					</div>
				</div>
		</div>
  </div>

	<div class="box box-success">
    <div class="box-header">
      <h3 class="box-title">SPECIFICATION LIST</h3>
    </div>
		<div class="box-body">
      <div class='form-group row'>
					<label class='label-control col-sm-2'><b>Application</b></label>
					<div class='col-sm-4'>
						<?php
						 echo form_input(array('disabled'=>'disabled','name'=>'app','class'=>'form-control input-md'),$app);
						?>
					</div>
				</div>
			<table class="table table-bordered table-striped" id="my-grid" width='100%'>
				<thead>
					<tr class='bg-purple'>
						<th class="text-center" width='25%'>Category</th>
						<th class="text-center" width='25%'>Category Detail</th>
						<th class="text-center" width='15%'>Request</th>
						<th class="text-center" width='35%'>Capacity/Specificity</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no=0;
					foreach($detail AS $val => $valx){
						$no++;
						$spec = ($valx['req'] == 'ori')?$valx['spec']:'-';
            $category = (isset($list) && in_array($valx['id'], $list))?strtoupper($valx['category_']):'';
						?>
						<tr>
							<td><b><?= strtoupper($category);?></b></td>
							<td><?= strtoupper($valx['category_list']);?></td>
							<td><?=strtoupper($valx['req']);?></td>
							<td><?= strtoupper($spec);?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
  </div>
</div>

<script>
	swal.close();
</script>
