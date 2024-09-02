<?php
$this->load->view('include/side_menu');
?>
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title"><?php echo $title;?></h3>
		<div class="box-tool pull-right">		
		</div><br><br>
		<?php
			if($akses_menu['create']=='1'){ 
			?>
				<div class="dropdown">
                    <button class="btn btn-success btn-sm" type="button" onclick="data_add()">
                        <i class="fa fa-plus">&nbsp;</i> Tambah
                    </button>
                </div>
			<?php
			}
		?>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
        <table id="mytabledata" class="table table-bordered table-striped">
            <thead>
            <tr>
				<th width="5">#</th>
				<th>Nama</th>
				<th>Pengelola</th>
				<th>Keterangan</th>
				<th width="120">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(!empty($row)){
                $numb=0; foreach($row AS $record){ $numb++; ?>
            <tr>
				<td><?= $numb; ?></td>
				<td><?= $record->nama?></td>
				<td><?= $record->pengelola?></td>
				<td><?= $record->keterangan?></td>
				<td>
                <?php if($akses_menu['read']=='1'){ ?>
                   <a class="btn btn-success btn-sm edit" href="javascript:void(0)" title="Edit" onclick="data_edit('<?=$record->id?>')"><i class="fa fa-edit"></i></a>
                <?php }
                if($akses_menu['delete']=='1'){?>
                   <a class="btn btn-danger btn-sm delete" href="javascript:void(0)" title="Hapus" onclick="data_delete('<?=$record->id?>')"><i class="fa fa-trash"></i></a>
                    <?php
				} ?>
                </td>
            </tr>
            <?php
                }
            }  ?>
            </tbody>
        </table>
	</div>
</div>
<div class="box box-success">
	<div class="box-header">
		<h3 class="box-title">Master Expense</h3><br /><br />
		  <div class="box-tools pull-right">
		<?php if($akses_menu['create']=='1'){ ?>
			<button class="btn btn-success btn-xs" type="button" onclick="edit_coa('coa_expense')">
				<i class="fa fa-edit">&nbsp;</i> Edit
			</button>
		<?php } ?>
		   <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		  </div>
	</div>
	<div class="box-body">
       <?php
		$coa_expense=$this->db->query("SELECT * FROM ms_generate where tipe='coa_expense'")->row();
		$datacoa=array();
		if($coa_expense){
			echo '<ul class="list-group">';
			$coa=str_replace(";","','",$coa_expense->kode_text);
			$records=$this->db->query("select * from ".DBACC.".coa_master where no_perkiraan in ('".$coa."') order by no_perkiraan")->result();
			foreach($records AS $rows){
				echo '<li class="list-group-item">'.$rows->no_perkiraan.' - '. $rows->nama.'</li>';				
			}
			echo '</ul>';
		}
	   ?>
	</div>
</div>
<div class="box box-success">
	<div class="box-header">
		<h3 class="box-title">Master Kasbon</h3><br /><br />
		  <div class="box-tools pull-right">
		<?php if($akses_menu['create']=='1'){ ?>
			<button class="btn btn-success btn-xs" type="button" onclick="edit_coa('coa_kasbon')">
				<i class="fa fa-edit">&nbsp;</i> Edit
			</button>
		<?php } ?>
		   <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		  </div>
	</div>
	<div class="box-body">
       <?php
		$coa_kasbon=$this->db->query("SELECT * FROM ms_generate where tipe='coa_kasbon'")->row();
		$datacoa=array();
		if($coa_kasbon){
			echo '<ul class="list-group">';
			$coa=str_replace(";","','",$coa_kasbon->kode_text);
			$records=$this->db->query("select * from ".DBACC.".coa_master where no_perkiraan in ('".$coa."') order by no_perkiraan")->result();
			foreach($records AS $rows){
				echo '<li class="list-group-item">'.$rows->no_perkiraan.' - '. $rows->nama.'</li>';				
			}
			echo '</ul>';
		}
	   ?>
	</div>
</div>
<div class="box box-success">
	<div class="box-header">
		<h3 class="box-title">Master Transportasi</h3><br /><br />
		  <div class="box-tools pull-right">
		<?php if($akses_menu['create']=='1'){ ?>
			<button class="btn btn-success btn-xs" type="button" onclick="edit_coa('coa_transportasi')">
				<i class="fa fa-edit">&nbsp;</i> Edit
			</button>
		<?php } ?>
		   <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		  </div>

	</div>
	<div class="box-body">
       <?php
		$coa_transportasi=$this->db->query("SELECT * FROM ms_generate where tipe='coa_transportasi'")->row();
		$datacoa=array();
		if($coa_transportasi){
			echo '<ul class="list-group">';
			$coa=str_replace(";","','",$coa_transportasi->kode_text);
			$records=$this->db->query("select * from ".DBACC.".coa_master where no_perkiraan in ('".$coa."') order by no_perkiraan")->result();
			foreach($records AS $rows){
				echo '<li class="list-group-item">'.$rows->no_perkiraan.' - '. $rows->nama.'</li>';				
			}
			echo '</ul>';
		}
	   ?>
	</div>
</div>

<div class="box box-success">
	<div class="box-header">
		<h3 class="box-title">Kode Bank</h3><br /><br />
		  <div class="box-tools pull-right">
		<?php if($akses_menu['create']=='1'){ ?>
			<button class="btn btn-success btn-xs" type="button" onclick="form_bank(0)">
				<i class="fa fa-plus">&nbsp;</i> Add
			</button>
		<?php } ?>
		   <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		  </div>

	</div>
	<div class="box-body">
       <?php
		$generate_jurnal=$this->db->query("SELECT a.*, b.no_perkiraan, b.nama FROM ms_generate a left join ".DBACC.".coa_master b on a.info=b.no_perkiraan where a.tipe='kode_bank' ")->result();
		if($generate_jurnal){
			echo '<ul class="todo-list">';
			foreach($generate_jurnal AS $rows){
				echo '<li class="list-group-item"> ('.$rows->kode_1.') '.$rows->no_perkiraan.' - '. $rows->nama.'
                  <div class="tools">
                    <i class="fa fa-edit" onclick="form_bank('.$rows->id.')"></i>
                    <i class="fa fa-trash-o" onclick="del_bank('.$rows->id.')"></i>
                  </div>				
				</li>';				
			}
			echo '</ul>';
		}
	   ?>
	</div>
</div>

<div class="box box-success">
	<div class="box-header">
		<h3 class="box-title">Penomoran Jurnal</h3><br /><br />
		  <div class="box-tools pull-right">
		   <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		  </div>

	</div>
	<div class="box-body">
       <?php
		$generate_jurnal=$this->db->query("SELECT * FROM ms_generate where tipe='ms_generate_jurnal'")->result();
		if($generate_jurnal){
			echo '<ul class="list-group">';
			foreach($generate_jurnal AS $rows){
				echo '<li class="list-group-item">'.$rows->info.' - '. $rows->kode_text.'</li>';				
			}
			echo '</ul>';
		}
	   ?>
	</div>
</div>

<div id="form-data"></div>
<?php $this->load->view('include/footer'); ?>
<script>
	var url_add = base_url+'pettycash/create/';
	var url_edit = base_url+'pettycash/edit/';
	var url_delete = base_url+'pettycash/delete/';
	//View
  	function edit_coa(tipe){
		$(".box").hide();
		$("#form-data").show();
		$("#form-data").load(base_url+'pettycash/coa_edit/'+tipe);
	}
  	function form_bank(ids){
		$(".box").hide();
		$("#form-data").show();
		$("#form-data").load(base_url+'pettycash/form_bank/'+ids);
	}
  	function del_bank(ids){
		swal({
		  title: "Anda Yakin?",
		  text: "Data Akan Dihapus!",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonText: "Ya, hapus!",
		  cancelButtonText: "Tidak!",
		  closeOnConfirm: false,
		  closeOnCancel: true
		},
		function(isConfirm){
		  if (isConfirm) {
		  	$.ajax({
		            url: 'pettycash/delete_bank/'+ids,
		            dataType : "json",
		            type: 'POST',
		            success: function(msg){
		                if(msg['delete']=='1'){
		                    swal({
		                      title: "Terhapus!",
		                      text: "Data berhasil dihapus",
		                      type: "success",
		                      timer: 1500,
		                      showConfirmButton: false
		                    });
		                    window.location.reload();
		                } else {
		                    swal({
		                      title: "Gagal!",
		                      text: "Data gagal dihapus",
		                      type: "error",
		                      timer: 1500,
		                      showConfirmButton: false
		                    });
		                };
						console.log(msg);
		            },
		            error: function(msg){
		                swal({
	                      title: "Gagal!",
	                      text: "Gagal Eksekusi Ajax",
	                      type: "error",
	                      timer: 1500,
	                      showConfirmButton: false
	                    });
						console.log(msg);
		            }
		        });
		  }
		});
	}
</script>
<script src="<?= base_url('assets/js/basic.js')?>"></script>
