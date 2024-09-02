
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?= base_url('assets/img/user-icon.png') ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->userdata('fullname'); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
				
				<?php
					$menus = $this->db->get_where('travel_menu', array('parent_id' => 0));
					foreach($menus->result() as $menu) {
						$this->db->order_by('nama_menu', 'ASC');
						$submenus = $this->db->get_where('travel_menu', array('parent_id' => $menu->menu_id));
						if ($submenus->num_rows() > 0) {
							echo "<li class='treeview'>";
								echo "<a href='".site_url($menu->link)."'>
												<i class='".$menu->icon."'></i> <span>".$menu->nama_menu."</span>";
									echo "<span class='pull-right-container'>
													<i class='fa fa-angle-left pull-right'></i>
												</span>";
								echo "</a>";
								echo "<ul class='treeview-menu'>";
									foreach ($submenus->result() AS $submenu) {
										echo "<li><a href='".site_url($submenu->link)."'><i class='".$submenu->icon."'></i> ".$submenu->nama_menu."</a></li>";
									}
								echo "</ul>";
							echo "</li>";
						} else {
							echo "<li><a href='".site_url($menu->link)."'><i class='".$menu->icon."'></i> <span>".$menu->nama_menu."</span></a></li>";
						}
					}
				?>
        <!--<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-circle-o"></i> <span>Dashboard</span></a></li>

        <li>
          <a href="<?php echo site_url('agent'); ?>">
            <i class="ion ion-ios-people"></i> <span>Master Agent</span>
          </a>
        </li>
        <li>
          <a href="<?php echo site_url('trip/master_trip'); ?>">
            <i class="fa fa-plane"></i>
            <span>Master Trip</span>
          </a>
        </li>
        <li>
          <a href="<?php echo site_url('unit'); ?>">
            <i class="ion ion-model-s"></i>
            <span>Master Unit</span>
          </a>
        </li>
        <li>
          <a href="<?php echo site_url('order')?>">
            <i class="ion ion-bag"></i> <span>Monitoring Order</span>
          </a>
        </li>
        <li>
          <a href="<?php echo site_url('trip')?>">
            <i class="ion ion-map"></i> <span>Monitoring Trip</span>
          </a>
        </li>
        <li>
          <a href="<?php echo site_url('invoicing')?>">
            <i class="ion ion-ios-paper-outline"></i> <span>Invoicing</span>
          </a>
        </li>
				<li class="treeview">
          <a href="#">
            <i class="ion ion-ios-paper-outline"></i>
						<span>Report</span>
						<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
					<ul class="treeview-menu">
            <li><a href="<?= site_url('report/pendapatan') ?>"><i class="fa fa-circle-o"></i> Report Pendapatan</a></li>
          </ul>
        </li>-->
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo $title; ?></li>
      </ol>
    </section>