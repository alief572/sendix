
<div class="box box-success">
	<div class="box-header">
		<h3 class="box-title">Plan Instalasi</h3>
	</div>
	<div class="box-body">
		<!-- <div style="overflow-x:auto; overflow-y:auto; height:500px;"> -->
		<div class="tableFixHead" style="height:500px;">
			<table class="table table-bordered table-striped table-fixed" id="my-grid">
	 			<thead class="thead">
					<tr class='bg-purple'>
						<th class="text-center th">Tahapan Pekerjaan</th>
						<th class="text-center th">Time (Day)</th>
						<th class="text-center th">Category</th>
						<th class="text-center th">Item</th>
						<?php
						for($a=1; $a<=$sum_day[0]->day; $a++){
							echo "<th class='text-center th' style='width: 75px;'>Day ".$a."</th>";
						}
						?>
					</tr>
				</thead>
				<tbody class="tbody">
					<?php
					foreach ($detail as $key => $value) {
						echo "<tr>";
							echo "<td>".strtoupper($value['category'])."</td>";
							echo "<td>".$value['std_time']."</td>";
							echo "<td>".strtoupper($value['tipe'])."</td>";
							echo "<td>".strtoupper($value['cat_tools'].' - '.$value['spec'])."</td>";

							for($a=1; $a<=$sum_day[0]->day; $a++){
								
								$qty = "";
								if(in_array($a, json_decode($value['timeline']))){
									$qty = $value['qty'];
								}
								echo "<td class='text-center' style='width: 75px;'>$qty</td>";
							}
						echo "</tr>";
					}
					?>
	 			</tbody>
	 		</table>
		</div>
	</div>
 </div>
<style>

/* JUST COMMON TABLE STYLES... */
.table { border-collapse: collapse; width: 100%; }
.td { background: #fff; padding: 8px 16px; }


.tableFixHead {
  overflow: auto;
  height: 100px;
}

.tableFixHead .thead .th {
  position: sticky;
  top: 0;
	background: #605ca8;
}

</style>
<script>
swal.close();
window.onload = function(){
  var tableCont = document.querySelector('#table-cont')
  /**
   * scroll handle
   * @param {event} e -- scroll event
   */
  function scrollHandle (e){
    var scrollTop = this.scrollTop;
    this.querySelector('thead').style.transform = 'translateY(' + scrollTop + 'px)';
  }

  tableCont.addEventListener('scroll',scrollHandle)
}
</script>
