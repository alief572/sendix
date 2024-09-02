
<form action="#" method="POST" id="form_adjustment" enctype="multipart/form-data" autocomplete='off'> 
<div class="box-body">
	<input type="hidden" name='project_code' id='project_code' value='<?= $project_code;?>'>
	<input type="hidden" name='tipex' id='tipex' value='<?= $tipe;?>'>
	<h4>Daftar Add</h4>
	<table class="table table-striped table-bordered table-hover table-condensed" width="100%">
		<thead>
			<tr class='bg-blue'>
                <th class="text-center" style='vertical-align:middle;' width='5%'>#</th>
				<th class="text-center" style='vertical-align:middle;'>Name</th>
                <th class="text-center no-sort" style='vertical-align:middle;' width='10%'>Qty</th>
				<?php if($tipe == 'consumable' OR $tipe == 'tools' OR $tipe == 'safety'){ ?>
                	<th class="text-center no-sort" style='vertical-align:middle;' width='10%'>Unit</th>
				<?php }else{ ?>
					<th class="text-center no-sort" style='vertical-align:middle;' width='10%'>Durasi</th>
				<?php } ?>
				<th class="text-center no-sort" style='vertical-align:middle;' width='10%'>Hapus</th>
			</tr>
		</thead>
		<tbody id='body_req'>
			<?php
			if(!empty($data_result)){
				foreach ($data_result as $key => $value) {$key++;
					$readonly = 'readonly';
					if($tipe == 'man power'){
						$readonly = '';
					}
					if($tipe != 'man power'){
						if($tipe == 'consumable'){
							$table = 'con_nonmat_new';
						}
						if($tipe == 'tools'){
							$table = 'vehicle_tool_new';
						}
						if($tipe == 'safety'){
							$table = 'vehicle_tool_new';
						}
						$get_sat_master = $this->db->get_where($table, array('code_group'=>$value['code_group']))->result();
						$sat_unit = (!empty($get_sat_master))?$get_sat_master[0]->unit:'';
					}
					if(!empty($value['id_unit'])){
						$sat_unit = $value['id_unit'];
					}

					echo "<tr>";
						echo "<td class='text-center'>$key</td>";
						echo "<td>".strtoupper($value['category']." - ".$value['spec'])."</td>";
						echo "<td>";
							echo "<input type='hidden' name='detail[$key][id]' value='".$value['code_group']."'>";
							echo "<input type='text' name='detail[$key][qty]' class='form-control input-sm text-center autoNumeric0' value='".$value['qty']."'>";
						echo "</td>";
						if($tipe != 'man power'){
						echo "<td>";
							echo "<select name='detail[".$key."][durasi]' class='durasi form-control input-sm chosen-select'>";
							foreach ($satuan as $key2 => $value2) {
								if($value['jml_hari'] > 0){
									$sel = (strtolower($value2['id']) == number_format($value['jml_hari']))?'selected':'';
								}
								else{
									$sel = (strtolower($value2['id']) == $sat_unit)?'selected':'';
								}
								echo "<option value='".$value2['id']."' $sel>".strtoupper($value2['unit'])."</option>";
							}
							echo "</select";
						echo "</td>";
						}
						if($tipe == 'man power'){
							echo "<td>";
								echo "<input type='text' name='detail[$key][durasi]' class='form-control input-sm text-center autoNumeric' $readonly value='".$value['jml_hari']."'>";
							echo "</td>";
							}
						echo "<td class='text-center'><button type='button' class='btn btn-danger btn-sm hapus_req' data-id='".$key."' title='Delete'><i class='fa fa-trash'></i></button></td>";
					echo "</tr>";
				}
			}
			else{
				echo "<tr>";
					echo "<td colspan='5'>Empty list.</td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
    <?php
		echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-success','style'=>'float:right; margin: 10px 0px 5px 0px;','value'=>'Save','content'=>'Process','id'=>'request_material'));
	?>
	<?php if($tipe != 'safety'){ ?>
	<br><br>
	<h4>Daftar</h4>
	<table id="my-grid2" class="table table-striped table-bordered table-hover table-condensed" width="100%">
		<thead>
			<tr class='bg-blue'>
				<th class="text-center" style='vertical-align:middle;' width='5%'>#</th>
				<th class="text-center" style='vertical-align:middle;'>Name</th>
                <th class="text-center no-sort" style='vertical-align:middle;' width='10%'>Qty</th>
				<?php if($tipe == 'man power'){ ?>
                <th class="text-center no-sort" style='vertical-align:middle;' width='10%'>Durasi</th>
				<?php } ?>
				<th class="text-center no-sort" style='vertical-align:middle;' width='10%'>Pilih</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	<?php } ?>
</div>
</form>
<script>
	$(document).ready(function(){
        swal.close();
		console.clear()
		
		$('.chosen-select').chosen({width:'100%'});
		var tipex = $('#tipex').val();
		DataTables2(tipex);
    

		let arrayRequest = [];
		let arrayDataCheck = [];


		arrayRequest.splice(0,arrayRequest.length)
		arrayDataCheck.splice(0,arrayDataCheck.length)

		let arrayRequest2 	= JSON.parse('<?php echo json_encode($arrayRequest);?>');
		let arrayDataCheck2 = JSON.parse('<?php echo json_encode($arrayDataCheck);?>');
		let Satuan 			= JSON.parse('<?php echo json_encode($satuanHTML);?>');
		
		arrayDataCheck = arrayDataCheck.concat(arrayDataCheck2);
		arrayRequest = arrayRequest.concat(arrayRequest2);

		// console.log(arrayRequest);
		// console.log(arrayDataCheck);

		$(document).on('click','.pindahkan', function(){
			var tipex 		= $('#tipe').val();
			let id 			= $(this).parent().parent().parent().find('.id').val();
			let qty 		= $(this).parent().parent().parent().find('.qty').val();
			let durasi 		= $(this).parent().parent().parent().find('.durasi').val();
			let material 	= $(this).parent().parent().parent().find('.material').val();
			let tipe 		= $(this).parent().parent().parent().find('.tipe').val();

			if(tipex != 'man power'){
				durasi 		= $(this).parent().parent().parent().find('.durasi').val();
			}

			let check = arrayDataCheck.includes(id);
			// console.log(arrayDataCheck);
			// console.log(check);
			if(check === false){
				let dataArr = {
					'id' : id,
					'qty' : qty,
					'material' : material,
					'tipe' : tipe,
					'durasi' : durasi
				}
				arrayRequest.push(dataArr);
				arrayDataCheck.push(id);
				console.log(arrayRequest);
				// console.log(arrayDataCheck);
				viewRequest();
			}
			// else{
			// 	alert('Material sudah ada dalam daftar !!!')
			// }
		});

		$(document).on('click', '.hapus_req', function(){
			let id = $(this).data('id');
			delete arrayRequest[id]
			delete arrayDataCheck[id]
			viewRequest();
		});

		const viewRequest = () => {
			let DataAppend = "";
			let nomor = 0;
			let readonly;
			let sel;
			var tipex = $('#tipex').val();
			// console.log(arrayRequest)
			arrayRequest.map((row,idx)=>{
				var sat_html = ""
				Satuan.map((row2,idx2)=>{
					sel = (row2.id == Number(row.durasi)) ? 'selected' : ''
					sat_html += "<option value='"+row2.id+"' "+sel+">"+row2.unit+"</option>"
				})	
				// if(row.tipe == tipe){
					nomor++
					readonly = 'readonly';
					if(row.tipe == 'man power'){
						readonly = '';
					}
					DataAppend += "<tr>"
						DataAppend += "<td class='text-center'>"+nomor+"</td>"
						DataAppend += "<td>"+row.material+"</td>"
						DataAppend += "<td>"
							DataAppend += "<input type='hidden' name='detail["+idx+"][id]' value='"+row.id+"'>"
							DataAppend += "<input type='text' name='detail["+idx+"][qty]' class='form-control input-sm text-center autoNumeric0' value='"+row.qty+"'>"
						DataAppend += "</td>"
						if(tipex != 'man power'){
							DataAppend += "<td>"
								DataAppend += "<select name='detail["+idx+"][durasi]' class='form-control input-sm text-left chosen-select'>"+sat_html+"</select>"
							DataAppend += "</td>"
						}
						if(tipex == 'man power'){
							DataAppend += "<td>"
								DataAppend += "<input type='text' name='detail["+idx+"][durasi]' class='form-control input-sm text-center autoNumeric' "+readonly+" value='"+row.durasi+"'>"
							DataAppend += "</td>"
						}
						DataAppend += "<td class='text-center'><button type='button' class='btn btn-danger btn-sm hapus_req' data-id='"+idx+"' title='Delete'><i class='fa fa-trash'></i></button></td>"
					DataAppend += "</tr>"
				// }
			})

			$('#body_req').html(DataAppend)
			$('.chosen-select').chosen();
			$('.autoNumeric').autoNumeric();
			$('.autoNumeric0').autoNumeric('init', {mDec: '0', aPad: false});
		}

	});
</script>