//branchjoint
public function branchjoint(){
  if($this->input->post()){
    $data = $this->input->post();
    $data_session			= $this->session->userdata;
    $mY		=  date('ym');
    $ListDetail_Glass		= $data['glass'];
    $ListDetail_resinnadd		= $data['resinnadd'];
    //print_r($ListDetail_Glass);
    $glass = array();
    $resinnadd = array();
    $count = 0;
    //echo $ListDetail_Glass['id_material'][0];

    $ArrDet1 = array();
    $ArrIl = array();
    $ArrOl = array();
    $no_il = $data['no_il'];
    $no_ol = $data['no_ol'];

    /*foreach ($glass as $key => $value) {
      $idm = $value['id_material'];
      $dataMaterial	= $this->db->query("SELECT nm_material, id_category, nm_category FROM raw_materials WHERE id_material='".$value['id_material']."' LIMIT 1")->result_array();
      $glass[$key]['nm_category'] = $dataMaterial[0]['nm_category'];
      foreach ($value as $k => $val) {
        //$glass[$k][$key] = $val;
        //echo $key." -> ".$k." -> ".$val."<br>";
      }
    }
    foreach ($glass as $key => $value) {

      foreach ($value as $k => $val) {
        //$glass[$k][$key] = $val;
        echo $key." -> ".$k." -> ".$val."<br>";
      }
    }
    exit;*/
    /*
    $ListDetail		= $data['ListDetail'];
    $ListDetail2	= $data['ListDetail2'];
    $ListDetail3	= $data['ListDetail3'];
    $ListDetailPlus		= $data['ListDetailPlus'];
    $ListDetailPlus2	= $data['ListDetailPlus2'];
    $ListDetailPlus3	= $data['ListDetailPlus3'];
    $ListDetailPlus4	= $data['ListDetailPlus4'];

    $numberMax_liner		= $data['numberMax_liner'];
    $numberMax_strukture	= $data['numberMax_strukture'];
    $numberMax_external		= $data['numberMax_external'];
    $numberMax_topcoat		= $data['numberMax_topcoat'];

    if($numberMax_liner != 0){
      $ListDetailAdd1	= $data['ListDetailAdd_Liner'];
    }
    if($numberMax_strukture != 0){
      $ListDetailAdd2	= $data['ListDetailAdd_Strukture'];
    }
    if($numberMax_external != 0){
      $ListDetailAdd3	= $data['ListDetailAdd_External'];
    }
    if($numberMax_topcoat != 0){
      $ListDetailAdd4	= $data['ListDetailAdd_TopCoat'];
    }
    // echo "<pre>";
    // print_r($ListDetailPlus);
    // print_r($ListDetailPlus);
    // print_r($ListDetailPlus);
    // exit;
    */

    //pengurutan kode
    $DataSeries2	= $this->db->query("SELECT * FROM component_group WHERE kode_group='".$data['series']."' LIMIT 1 ")->result_array();

    $resin_sistem	= $DataSeries2[0]['resin_system'];
    $liner			= $DataSeries2[0]['liner'];
    $pressure		= $DataSeries2[0]['pressure'];
    $diameter_1		= $data['diameter_1'];
    $diameter_2		= $data['diameter_2'];

    $KdResinSystem	= ($resin_sistem == 'ISO THALIC')?'I':'V';
    $KdPressure		= sprintf('%02s',$pressure);
    $KdDiameter_1		= sprintf('%04s',$diameter_1);
    $KdDiameter_2		= sprintf('%04s',$diameter_2);
    $KdLiner		= $liner;
    $KDCust			=	$data['cust'];

    if ($KDCust != 'C100-1903000') {
      $kode_product	= "BJ-".$KdResinSystem."PN".$KdPressure."DN".$KdDiameter_1."-".$KdLiner."-DN".$KdDiameter_2."-".$KdLiner."-".$KDCust;
      // code...
    }else {
      $kode_product	= "BJ-".$KdResinSystem."PN".$KdPressure."DN".$KdDiameter_1."-".$KdLiner."-DN".$KdDiameter_2."-".$KdLiner;
    }
    //$kode_product	= "BJ-".$KdResinSystem."PN".$KdPressure."DN".$KdDiameter_1."-".$KdLiner."-DN".$KdDiameter_2."-".$KdLiner."-".$KDCust;
    // echo $kode_product; exit;
    $srcType		= "SELECT id_product FROM component_header WHERE id_product='".$kode_product."' ";
    $NumRow			= $this->db->query($srcType)->num_rows();

    if($NumRow > 0){
      $Arr_Kembali	= array(
        'pesan'		=>'Specifications are already in the list. Check again ...',
        'status'	=> 3
      );
    }
    else{

      $DataCust	= $this->db->query("SELECT nm_customer FROM customer WHERE id_customer='".$data['top_toleran']."' LIMIT 1 ")->result_array();
      //$DataApp	= $this->db->query("SELECT * FROM list_help WHERE name='".$data['aplikasi_product']."' LIMIT 1 ")->result_array();
      $DataSeries	= $this->db->query("SELECT * FROM component_group WHERE kode_group='".$data['series']."' LIMIT 1 ")->result_array();

      $ArrHeader	= array(
        'id_product'					=> $kode_product,
        'cust'								=> $KDCust,
        'parent_product'			=> 'branch joint',
        'nm_product'					=> $data['top_type_1']." X ".$diameter_2,
        'series'							=> $data['series'],
        'resin_sistem'				=> $DataSeries[0]['resin_system'],
        'pressure'						=> $DataSeries[0]['pressure'],
        'diameter'						=> $data['diameter_1'],
        'liner'								=> $DataSeries[0]['liner'],
        //'aplikasi_product'		=> $data['top_app'],
        //'criminal_barier'			=> $data['criminal_barier'],
        //'vacum_rate'					=> $data['vacum_rate'],
        //'stiffness'						=> $DataApp[0]['data2'],
        //'design_life'					=> $data['design_life'],
        'standart_by'					=> $data['top_toleran'],
        'standart_toleransi'	=> $DataCust[0]['nm_customer'],
        'diameter2'						=> $data['diameter_2'],
        'panjang'							=> $data['minimum_width'],
        //'design'							=> $data['top_tebal_design'],
        //'radius'							=> $data['radius'],
        //'area'								=> $data['area'],
        //'est'									=> $data['top_tebal_est'],
        //'min_toleransi'				=> $data['top_min_toleran'],
        //'max_toleransi'				=> $data['top_max_toleran'],
        //'waste'								=> $data['waste'],
        'pipe_thickness'			=> $data['pipe_thickness'],
        'joint_thickness'			=> $data['joint_thickness'],
        'factor_thickness'		=> $data['factor_thickness'],
        'created_by'					=> $data_session['ORI_User']['username'],
        'created_date'				=> date('Y-m-d H:i:s')
      );

      // print_r($ArrHeader); exit;
      foreach ($ListDetail_Glass as $key => $value) {
        foreach ($value as $k => $val) {
          $idm = $ListDetail_Glass['id_material'][$k];
          $dataMaterial	= $this->db->query("SELECT nm_material, id_category, nm_category FROM raw_materials WHERE id_material='".$idm."' LIMIT 1")->result_array();

          $glass[$k][$key] = $val;
          $glass[$k]['id_product'] 	= $kode_product;
          //$glass[$k]['detail_name'] 	= $data['detail_name'];
          //$glass[$k]['acuhan'] 		= $data['acuhan_1'];
          //$glass[$k]['id_category'] 	= $dataMaterial[0]['id_category'];
          $glass[$k]['nm_category'] 	= $dataMaterial[0]['nm_category'];
          //$glass[$k]['id_material'] 	= $valx['id_material'];
          $glass[$k]['nm_material'] 	= $dataMaterial[0]['nm_material'];
            $valueM							= (!empty($ListDetail_Glass['value'][$k]))?$ListDetail_Glass['value'][$k]:'';
          $glass[$k]['value'] 			= $valueM;
            $thicknessM						= (!empty($ListDetail_Glass['thickness'][$k]))?$ListDetail_Glass['thickness'][$k]:'';
          $glass[$k]['thickness'] 		= $thicknessM;
            $pengaliM						= (!empty($ListDetail_Glass['fak_pengali'][$k]))?$ListDetail_Glass['fak_pengali'][$k]:'';
          $glass[$k]['fak_pengali'] 	= $pengaliM;
            $bwM							= (!empty($ListDetail_Glass['bw'][$k]))?$ListDetail_Glass['bw'][$k]:'';
          $glass[$k]['bw'] 			= $bwM;
            $jumlahM						= (!empty($ListDetail_Glass['jumlah'][$k]))?$ListDetail_Glass['jumlah'][$k]:'';
          $glass[$k]['jumlah'] 		= $jumlahM;
            $layerM							= (!empty($ListDetail_Glass['layer'][$k]))?$ListDetail_Glass['layer'][$k]:'';
          $glass[$k]['layer'] 			= $layerM;;
            $containingM					= (!empty($ListDetail_Glass['containing'][$k]))?$ListDetail_Glass['containing'][$k]:'';
          $glass[$k]['containing'] 	= $containingM;
            $total_thicknessM				= (!empty($ListDetail_Glass['total_thickness'][$k]))?$ListDetail_Glass['total_thickness'][$k]:'';
          $glass[$k]['total_thickness'] = $total_thicknessM;
            $lastfullM			= (!empty($ListDetail_Glass['last_full'][$k]))?$ListDetail_Glass['last_full'][$k]:'';
          $glass[$k]['last_full'] 		= $lastfullM;
            $lastcostM			= (!empty($ListDetail_Glass['last_cost'][$k]))?$ListDetail_Glass['last_cost'][$k]:'';
          $glass[$k]['last_cost'] 		= $lastcostM;
        }

      }
      foreach ($ListDetail_resinnadd as $key => $value) {
        foreach ($value as $k => $val) {
          $idm = $ListDetail_resinnadd['id_material'][$k];
          $dataMaterial	= $this->db->query("SELECT nm_material, id_category, nm_category FROM raw_materials WHERE id_material='".$idm."' LIMIT 1")->result_array();

          $resinnadd[$k][$key] = $val;
          $resinnadd[$k]['id_product'] 	= $kode_product;
          //$resinnadd[$k]['detail_name'] 	= $data['detail_name'];
          //$resinnadd[$k]['acuhan'] 		= $data['acuhan_1'];
          //$resinnadd[$k]['id_category'] 	= $dataMaterial[0]['id_category'];
          //$resinnadd[$k]['nm_category'] 	= $dataMaterial[0]['nm_category'];
          $resinnadd[$k]['nm_category'] 	= $ListDetail_resinnadd['nm_category'][$k];
          //$resinnadd[$k]['id_material'] 	= $valx['id_material'];
          $resinnadd[$k]['nm_material'] 	= $dataMaterial[0]['nm_material'];
            $valueM							= (!empty($ListDetail_resinnadd['value'][$k]))?$ListDetail_resinnadd['value'][$k]:'';
          $resinnadd[$k]['value'] 			= $valueM;
            $thicknessM						= (!empty($ListDetail_resinnadd['thickness'][$k]))?$ListDetail_resinnadd['thickness'][$k]:'';
          $resinnadd[$k]['thickness'] 		= $thicknessM;
            $pengaliM						= (!empty($ListDetail_resinnadd['fak_pengali'][$k]))?$ListDetail_resinnadd['fak_pengali'][$k]:'';
          $resinnadd[$k]['fak_pengali'] 	= $pengaliM;
            $bwM							= (!empty($ListDetail_resinnadd['bw'][$k]))?$ListDetail_resinnadd['bw'][$k]:'';
          $resinnadd[$k]['bw'] 			= $bwM;
            $jumlahM						= (!empty($ListDetail_resinnadd['jumlah'][$k]))?$ListDetail_resinnadd['jumlah'][$k]:'';
          $resinnadd[$k]['jumlah'] 		= $jumlahM;
            $layerM							= (!empty($ListDetail_resinnadd['layer'][$k]))?$ListDetail_resinnadd['layer'][$k]:'';
          $resinnadd[$k]['layer'] 			= $layerM;;
            $containingM					= (!empty($ListDetail_resinnadd['containing'][$k]))?$ListDetail_resinnadd['containing'][$k]:'';
          $resinnadd[$k]['containing'] 	= $containingM;
            $total_thicknessM				= (!empty($ListDetail_resinnadd['total_thickness'][$k]))?$ListDetail_resinnadd['total_thickness'][$k]:'';
          $resinnadd[$k]['total_thickness'] = $total_thicknessM;
            $lastfullM			= (!empty($ListDetail_resinnadd['last_full'][$k]))?$ListDetail_resinnadd['last_full'][$k]:'';
          $resinnadd[$k]['last_full'] 		= $lastfullM;
            $lastcostM			= (!empty($ListDetail_resinnadd['last_cost'][$k]))?$ListDetail_resinnadd['last_cost'][$k]:'';
          $resinnadd[$k]['last_cost'] 		= $lastcostM;
        }

      }
      for ($i=0; $i < $no_il; $i++) {
        $ArrIl[$i]['id_product']		=	$kode_product;
        $ArrIl[$i]['detail_name']		=	'Inside Lamination';
        $ArrIl[$i]['lapisan'] 			= $data['lapisan_'.($i+1)];
        $ArrIl[$i]['std_glass'] 		= $data['std_'.($i+1)];
        $ArrIl[$i]['width'] 				= $data['width_'.($i+1)];
        $ArrIl[$i]['stage'] 				= $data['stage_1'];
        $ArrIl[$i]['glass'] 				= $data['glassconfiguration_'.($i+1)];
        $ArrIl[$i]['thickness_1'] 	= $data['thickness1_'.($i+1)];
        $ArrIl[$i]['thickness_2'] 	= $data['thickness2_'.($i+1)];
        $ArrIl[$i]['glass_length'] 	= $data['glasslength_1'];
        $ArrIl[$i]['weight_veil'] 	= $data['veil_weight_'.($i+1)];
        $ArrIl[$i]['weight_csm'] 		= $data['csm_weight_'.($i+1)];
        $ArrIl[$i]['weight_wr'] 		= $data['wr_weight_'.($i+1)];
      }
      for ($i=0; $i < $no_ol; $i++) {
        $ArrOl[$i]['id_product']		=	$kode_product;
        $ArrOl[$i]['detail_name']		=	'Outside Lamination';
        $ArrOl[$i]['lapisan'] 			= $data['o_lapisan_'.($i+1)];
        $ArrOl[$i]['std_glass'] 		= $data['o_std_'.($i+1)];
        $ArrOl[$i]['width'] 				= $data['o_width_'.($i+1)];
        $ArrOl[$i]['stage'] 				= $data['o_stage_ke_'.($i+1)];
        $ArrOl[$i]['glass'] 				= $data['o_glassconfiguration_'.($i+1)];
        $ArrOl[$i]['thickness_1'] 	= $data['o_thickness1_'.($i+1)];
        $ArrOl[$i]['thickness_2'] 	= $data['o_thickness2_'.($i+1)];
        if (isset($data['o_glasslength_'.($i+1)]) && !empty($data['o_glasslength_'.($i+1)])) {
          $ArrOl[$i]['glass_length'] 	= $data['o_glasslength_'.($i+1)];
        }else {
          $ArrOl[$i]['glass_length'] 	= 0;
        }
        $ArrOl[$i]['weight_veil'] 	= $data['o_veil_weight_'.($i+1)];
        $ArrOl[$i]['weight_csm'] 		= $data['o_csm_weight_'.($i+1)];
        $ArrOl[$i]['weight_wr'] 		= $data['o_wr_weight_'.($i+1)];
      }



      $this->db->trans_start();
        $this->db->insert('component_header', $ArrHeader);
        $this->db->insert_batch('component_detail', $glass);
        $this->db->insert_batch('component_detail', $resinnadd);
        $this->db->insert_batch('component_lamination', $ArrIl);
        $this->db->insert_batch('component_lamination', $ArrOl);
        /*$this->db->insert_batch('component_detail', $ArrDetail13);
        $this->db->insert_batch('component_detail_plus', $ArrDetailPlus1);
        $this->db->insert_batch('component_detail_plus', $ArrDetailPlus2);
        $this->db->insert_batch('component_detail_plus', $ArrDetailPlus3);
        $this->db->insert_batch('component_detail_plus', $ArrDetailPlus4);
        $this->db->insert('component_footer', $ArrFooter);
        $this->db->insert('component_footer', $ArrFooter2);
        $this->db->insert('component_footer', $ArrFooter3);
        if($numberMax_liner != 0){
          $this->db->insert_batch('component__detail_add', $ArrDataAdd1);
        }
        if($numberMax_strukture != 0){
          $this->db->insert_batch('component_detail_add', $ArrDataAdd2);
        }
        if($numberMax_external != 0){
          $this->db->insert_batch('component_detail_add', $ArrDataAdd3);
        }
        if($numberMax_topcoat != 0){
          $this->db->insert_batch('component_detail_add', $ArrDataAdd4);
        }*/
      $this->db->trans_complete();

      if($this->db->trans_status() === FALSE){
        $this->db->trans_rollback();
        $Arr_Kembali	= array(
          'pesan'		=>'Add Calculation failed. Please try again later ...',
          'status'	=> 2
        );
      }
      else{
        $this->db->trans_commit();
        $Arr_Kembali	= array(
          'pesan'		=>'Add Calculation Success. Thank you & have a nice day ...',
          'status'	=> 1
        );
        history('Add estimation code '.$kode_product);
      }
    }
    echo json_encode($Arr_Kembali);
  }
  else{
    //List Dropdown
    $ListProduct					= $this->db->query("SELECT * FROM product WHERE parent_product='branch joint' AND deleted='N'")->result_array();
    $ListILamination			= $this->db->query("SELECT * FROM raw_material_lamination WHERE kategori='INSIDE LAMINATION'")->result_array();
    $ListOLamination			= $this->db->query("SELECT * FROM raw_material_lamination WHERE kategori='OUTSIDE LAMINATION'")->result_array();
    $ListResinSystem			= $this->db->query("SELECT * FROM list_help WHERE group_by ='resin_system' AND sts='Y'")->result_array();
    $ListPressure					= $this->db->query("SELECT * FROM list_help WHERE group_by ='pressure' AND sts='Y'")->result_array();
    $ListLiner						= $this->db->query("SELECT * FROM list_help WHERE group_by ='liner' AND sts='Y'")->result_array();

    $ListSeries						= $this->db->query("SELECT kode_group FROM component_group WHERE deleted ='N' AND status='Y' GROUP BY kode_group ORDER BY resin_system ASC, pressure ASC, liner ASC ")->result_array();

    $ListCriminalBarier		= $this->db->query("SELECT * FROM list_help WHERE group_by ='fluida' AND sts='Y'")->result_array();
    $ListAplikasiProduct	= $this->db->query("SELECT * FROM list_help WHERE group_by ='app' AND sts='Y'")->result_array();
    $ListVacumRate				= $this->db->query("SELECT * FROM list_help WHERE group_by ='vacum_rate' AND sts='Y'")->result_array();
    $ListDesignLife				= $this->db->query("SELECT * FROM list_help WHERE group_by ='design_life' AND sts='Y'")->result_array();

    $ListCustomer					= $this->db->query("SELECT id_customer, nm_customer FROM customer ORDER BY nm_customer ASC")->result_array();
    $ListCustomer2				= $this->db->query("SELECT id_customer, nm_customer FROM customer ORDER BY id_customer ASC")->result_array();

    //Realease Agent Sementara Sama dengan Plastic Firm
    $List_Realese					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0008' ORDER BY nm_material ASC")->result_array();
    $List_PlasticFirm			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0008' AND nm_material NOT LIKE 'PE%' AND nm_material NOT LIKE 'POLYESTER%' ORDER BY nm_material ASC")->result_array();

    $List_Veil						= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0003' ORDER BY nm_material ASC")->result_array();
    $List_Resin						= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0001' ORDER BY nm_material ASC")->result_array();
    $List_MatCsm					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0004' ORDER BY nm_material ASC")->result_array();

    $List_MatKatalis			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0002' ORDER BY nm_material ASC")->result_array();
    $List_MatSm						= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0024' ORDER BY nm_material ASC")->result_array();
    $List_MatCobalt				= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0021' ORDER BY nm_material ASC")->result_array();
    $List_MatDma					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0021' ORDER BY nm_material ASC")->result_array();
    $List_MatHydo					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0026' ORDER BY nm_material ASC")->result_array();
    $List_MatMethanol			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0026' ORDER BY nm_material ASC")->result_array();
    $List_MatAdditive			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0019' ORDER BY nm_material ASC")->result_array();
    $List_MatWR						= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0006' ORDER BY nm_material ASC")->result_array();
    $List_MatRooving			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0005' ORDER BY nm_material ASC")->result_array();
    $List_MatColor				= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0007' ORDER BY nm_material ASC")->result_array();
    $List_MatTinuvin			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0019' ORDER BY nm_material ASC")->result_array();
    $List_MatChl					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0019' ORDER BY nm_material ASC")->result_array();
    $List_MatWax					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0008' OR id_category='TYP-0019' ORDER BY nm_material ASC")->result_array();
    $List_MatMchl					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0024' ORDER BY nm_material ASC")->result_array();

    $dataStandart					= $this->db->query("SELECT * FROM identitas")->result_array();
    $data = array(
      'title'							=> 'Estimation Branch Joint',
      'action'						=> 'branchjoint',
      'product'						=> $ListProduct,
      'resin_system'			=> $ListResinSystem,
      'pressure'					=> $ListPressure,
      'liner'							=> $ListLiner,
      'series'						=> $ListSeries,

      'ILamination'				=> $ListILamination,
      'OLamination'				=> $ListOLamination,

      'criminal_barier'		=> $ListCriminalBarier,
      'aplikasi_product'	=> $ListAplikasiProduct,
      'vacum_rate'				=> $ListVacumRate,
      'design_life'				=> $ListDesignLife,
      'standard'					=> $ListCustomer,
      'customer'					=> $ListCustomer2,

      'ListRealise'				=> $List_Realese,
      'ListPlastic'				=> $List_PlasticFirm,
      'ListVeil'					=> $List_Veil,
      'ListResin'					=> $List_Resin,
      'ListMatCsm'				=> $List_MatCsm,

      'ListMatKatalis'		=> $List_MatKatalis,
      'ListMatSm'					=> $List_MatSm,
      'ListMatCobalt'			=> $List_MatCobalt,
      'ListMatDma'				=> $List_MatDma,
      'ListMatHydo'				=> $List_MatHydo,
      'ListMatMethanol'		=> $List_MatMethanol,
      'ListMatAdditive'		=> $List_MatAdditive,

      'ListMatWR'					=> $List_MatWR,
      'ListMatRooving'		=> $List_MatRooving,

      'ListMatColor'			=> $List_MatColor,
      'ListMatTinuvin'		=> $List_MatTinuvin,
      'ListMatChl'				=> $List_MatChl,
      'ListMatStery'			=> $List_MatSm,
      'ListMatWax'				=> $List_MatWax,
      'ListMatMchl'				=> $List_MatMchl
    );

    $this->load->view('Component/est/branchjoint', $data);
  }
}

//fieldjoint
public function fieldjoint(){
  if($this->input->post()){
    $data = $this->input->post();
    $data_session			= $this->session->userdata;
    $mY		=  date('ym');
    $ListDetail_Glass		= $data['glass'];
    $ListDetail_resinnadd		= $data['resinnadd'];
    //print_r($ListDetail_Glass);
    $glass = array();
    $resinnadd = array();
    $count = 0;
    //echo $ListDetail_Glass['id_material'][0];

    $ArrDet1 = array();

    $ArrIl = array();
    $ArrOl = array();
    $no_il = $data['no_il'];
    $no_ol = $data['no_ol'];
    /*foreach ($glass as $key => $value) {
      $idm = $value['id_material'];
      $dataMaterial	= $this->db->query("SELECT nm_material, id_category, nm_category FROM raw_materials WHERE id_material='".$value['id_material']."' LIMIT 1")->result_array();
      $glass[$key]['nm_category'] = $dataMaterial[0]['nm_category'];
      foreach ($value as $k => $val) {
        //$glass[$k][$key] = $val;
        //echo $key." -> ".$k." -> ".$val."<br>";
      }
    }
    foreach ($glass as $key => $value) {

      foreach ($value as $k => $val) {
        //$glass[$k][$key] = $val;
        echo $key." -> ".$k." -> ".$val."<br>";
      }
    }
    exit;*/
    /*
    $ListDetail		= $data['ListDetail'];
    $ListDetail2	= $data['ListDetail2'];
    $ListDetail3	= $data['ListDetail3'];
    $ListDetailPlus		= $data['ListDetailPlus'];
    $ListDetailPlus2	= $data['ListDetailPlus2'];
    $ListDetailPlus3	= $data['ListDetailPlus3'];
    $ListDetailPlus4	= $data['ListDetailPlus4'];

    $numberMax_liner		= $data['numberMax_liner'];
    $numberMax_strukture	= $data['numberMax_strukture'];
    $numberMax_external		= $data['numberMax_external'];
    $numberMax_topcoat		= $data['numberMax_topcoat'];

    if($numberMax_liner != 0){
      $ListDetailAdd1	= $data['ListDetailAdd_Liner'];
    }
    if($numberMax_strukture != 0){
      $ListDetailAdd2	= $data['ListDetailAdd_Strukture'];
    }
    if($numberMax_external != 0){
      $ListDetailAdd3	= $data['ListDetailAdd_External'];
    }
    if($numberMax_topcoat != 0){
      $ListDetailAdd4	= $data['ListDetailAdd_TopCoat'];
    }
    // echo "<pre>";
    // print_r($ListDetailPlus);
    // print_r($ListDetailPlus);
    // print_r($ListDetailPlus);
    // exit;
    */

    //pengurutan kode
    $DataSeries2	= $this->db->query("SELECT * FROM component_group WHERE kode_group='".$data['series']."' LIMIT 1 ")->result_array();

    $resin_sistem	= $DataSeries2[0]['resin_system'];
    $liner			= $DataSeries2[0]['liner'];
    $pressure		= $DataSeries2[0]['pressure'];
    $diameter_1		= $data['diameter_1'];
    //$diameter_2		= $data['diameter_2'];

    $KdResinSystem	= ($resin_sistem == 'ISO THALIC')?'I':'V';
    $KdPressure		= sprintf('%02s',$pressure);
    $KdDiameter_1		= sprintf('%04s',$diameter_1);
    //$KdDiameter_2		= sprintf('%04s',$diameter_2);
    $KdLiner		= $liner;
    $KDCust			=	$data['cust'];

    if ($KDCust != 'C100-1903000') {
      $kode_product	= "FJ-".$KdResinSystem."PN".$KdPressure."DN".$KdDiameter_1."-".$KdLiner."-".$KDCust;
    }else {
      $kode_product	= "FJ-".$KdResinSystem."PN".$KdPressure."DN".$KdDiameter_1."-".$KdLiner;
    }
    //$kode_product	= "FJ-".$KdResinSystem."PN".$KdPressure."DN".$KdDiameter_1."-".$KdLiner."-".$KDCust;
    // echo $kode_product; exit;
    $srcType		= "SELECT id_product FROM component_header WHERE id_product='".$kode_product."' ";
    $NumRow			= $this->db->query($srcType)->num_rows();

    if($NumRow > 0){
      $Arr_Kembali	= array(
        'pesan'		=>'Specifications are already in the list. Check again ...',
        'status'	=> 3
      );
    }
    else{

      $DataCust	= $this->db->query("SELECT nm_customer FROM customer WHERE id_customer='".$data['top_toleran']."' LIMIT 1 ")->result_array();
      //$DataApp	= $this->db->query("SELECT * FROM list_help WHERE name='".$data['aplikasi_product']."' LIMIT 1 ")->result_array();
      $DataSeries	= $this->db->query("SELECT * FROM component_group WHERE kode_group='".$data['series']."' LIMIT 1 ")->result_array();

      $ArrHeader	= array(
        'id_product'					=> $kode_product,
        'cust'								=> $KDCust,
        'parent_product'			=> 'field joint',
        'nm_product'					=> $data['top_type_1'],
        'series'							=> $data['series'],
        'resin_sistem'				=> $DataSeries[0]['resin_system'],
        'pressure'						=> $DataSeries[0]['pressure'],
        'diameter'						=> $data['diameter_1'],
        'liner'								=> $DataSeries[0]['liner'],
        //'aplikasi_product'		=> $data['top_app'],
        //'criminal_barier'			=> $data['criminal_barier'],
        //'vacum_rate'					=> $data['vacum_rate'],
        //'stiffness'						=> $DataApp[0]['data2'],
        //'design_life'					=> $data['design_life'],
        'standart_by'					=> $data['top_toleran'],
        'standart_toleransi'	=> $DataCust[0]['nm_customer'],
        //'diameter2'						=> $data['diameter_2'],
        'panjang'							=> $data['minimum_width'],
        //'design'							=> $data['top_tebal_design'],
        //'radius'							=> $data['radius'],
        //'area'								=> $data['area'],
        //'est'									=> $data['top_tebal_est'],
        //'min_toleransi'				=> $data['top_min_toleran'],
        //'max_toleransi'				=> $data['top_max_toleran'],
        //'waste'								=> $data['waste'],
        'pipe_thickness'			=> $data['pipe_thickness'],
        'joint_thickness'			=> $data['joint_thickness'],
        'factor_thickness'		=> $data['factor_thickness'],
        'created_by'					=> $data_session['ORI_User']['username'],
        'created_date'				=> date('Y-m-d H:i:s')
      );

      // print_r($ArrHeader); exit;
      foreach ($ListDetail_Glass as $key => $value) {
        foreach ($value as $k => $val) {
          $idm = $ListDetail_Glass['id_material'][$k];
          $dataMaterial	= $this->db->query("SELECT nm_material, id_category, nm_category FROM raw_materials WHERE id_material='".$idm."' LIMIT 1")->result_array();

          $glass[$k][$key] = $val;
          $glass[$k]['id_product'] 	= $kode_product;
          //$glass[$k]['detail_name'] 	= $data['detail_name'];
          //$glass[$k]['acuhan'] 		= $data['acuhan_1'];
          //$glass[$k]['id_category'] 	= $dataMaterial[0]['id_category'];
          $glass[$k]['nm_category'] 	= $dataMaterial[0]['nm_category'];
          //$glass[$k]['id_material'] 	= $valx['id_material'];
          $glass[$k]['nm_material'] 	= $dataMaterial[0]['nm_material'];
            $valueM							= (!empty($ListDetail_Glass['value'][$k]))?$ListDetail_Glass['value'][$k]:'';
          $glass[$k]['value'] 			= $valueM;
            $thicknessM						= (!empty($ListDetail_Glass['thickness'][$k]))?$ListDetail_Glass['thickness'][$k]:'';
          $glass[$k]['thickness'] 		= $thicknessM;
            $pengaliM						= (!empty($ListDetail_Glass['fak_pengali'][$k]))?$ListDetail_Glass['fak_pengali'][$k]:'';
          $glass[$k]['fak_pengali'] 	= $pengaliM;
            $bwM							= (!empty($ListDetail_Glass['bw'][$k]))?$ListDetail_Glass['bw'][$k]:'';
          $glass[$k]['bw'] 			= $bwM;
            $jumlahM						= (!empty($ListDetail_Glass['jumlah'][$k]))?$ListDetail_Glass['jumlah'][$k]:'';
          $glass[$k]['jumlah'] 		= $jumlahM;
            $layerM							= (!empty($ListDetail_Glass['layer'][$k]))?$ListDetail_Glass['layer'][$k]:'';
          $glass[$k]['layer'] 			= $layerM;;
            $containingM					= (!empty($ListDetail_Glass['containing'][$k]))?$ListDetail_Glass['containing'][$k]:'';
          $glass[$k]['containing'] 	= $containingM;
            $total_thicknessM				= (!empty($ListDetail_Glass['total_thickness'][$k]))?$ListDetail_Glass['total_thickness'][$k]:'';
          $glass[$k]['total_thickness'] = $total_thicknessM;
            $lastfullM			= (!empty($ListDetail_Glass['last_full'][$k]))?$ListDetail_Glass['last_full'][$k]:'';
          $glass[$k]['last_full'] 		= $lastfullM;
            $lastcostM			= (!empty($ListDetail_Glass['last_cost'][$k]))?$ListDetail_Glass['last_cost'][$k]:'';
          $glass[$k]['last_cost'] 		= $lastcostM;
        }

      }
      foreach ($ListDetail_resinnadd as $key => $value) {
        foreach ($value as $k => $val) {
          $idm = $ListDetail_resinnadd['id_material'][$k];
          $dataMaterial	= $this->db->query("SELECT nm_material, id_category, nm_category FROM raw_materials WHERE id_material='".$idm."' LIMIT 1")->result_array();

          $resinnadd[$k][$key] = $val;
          $resinnadd[$k]['id_product'] 	= $kode_product;
          //$resinnadd[$k]['detail_name'] 	= $data['detail_name'];
          //$resinnadd[$k]['acuhan'] 		= $data['acuhan_1'];
          //$resinnadd[$k]['id_category'] 	= $dataMaterial[0]['id_category'];
          $resinnadd[$k]['nm_category'] 	= $dataMaterial[0]['nm_category'];
          //$resinnadd[$k]['id_material'] 	= $valx['id_material'];
          $resinnadd[$k]['nm_material'] 	= $dataMaterial[0]['nm_material'];
            $valueM							= (!empty($ListDetail_resinnadd['value'][$k]))?$ListDetail_resinnadd['value'][$k]:'';
          $resinnadd[$k]['value'] 			= $valueM;
            $thicknessM						= (!empty($ListDetail_resinnadd['thickness'][$k]))?$ListDetail_resinnadd['thickness'][$k]:'';
          $resinnadd[$k]['thickness'] 		= $thicknessM;
            $pengaliM						= (!empty($ListDetail_resinnadd['fak_pengali'][$k]))?$ListDetail_resinnadd['fak_pengali'][$k]:'';
          $resinnadd[$k]['fak_pengali'] 	= $pengaliM;
            $bwM							= (!empty($ListDetail_resinnadd['bw'][$k]))?$ListDetail_resinnadd['bw'][$k]:'';
          $resinnadd[$k]['bw'] 			= $bwM;
            $jumlahM						= (!empty($ListDetail_resinnadd['jumlah'][$k]))?$ListDetail_resinnadd['jumlah'][$k]:'';
          $resinnadd[$k]['jumlah'] 		= $jumlahM;
            $layerM							= (!empty($ListDetail_resinnadd['layer'][$k]))?$ListDetail_resinnadd['layer'][$k]:'';
          $resinnadd[$k]['layer'] 			= $layerM;;
            $containingM					= (!empty($ListDetail_resinnadd['containing'][$k]))?$ListDetail_resinnadd['containing'][$k]:'';
          $resinnadd[$k]['containing'] 	= $containingM;
            $total_thicknessM				= (!empty($ListDetail_resinnadd['total_thickness'][$k]))?$ListDetail_resinnadd['total_thickness'][$k]:'';
          $resinnadd[$k]['total_thickness'] = $total_thicknessM;
            $lastfullM			= (!empty($ListDetail_resinnadd['last_full'][$k]))?$ListDetail_resinnadd['last_full'][$k]:'';
          $resinnadd[$k]['last_full'] 		= $lastfullM;
            $lastcostM			= (!empty($ListDetail_resinnadd['last_cost'][$k]))?$ListDetail_resinnadd['last_cost'][$k]:'';
          $resinnadd[$k]['last_cost'] 		= $lastcostM;
        }

      }
      for ($i=0; $i < $no_il; $i++) {
        $ArrIl[$i]['id_product']		=	$kode_product;
        $ArrIl[$i]['detail_name']		=	'Inside Lamination';
        $ArrIl[$i]['lapisan'] 			= $data['lapisan_'.($i+1)];
        $ArrIl[$i]['std_glass'] 		= $data['std_'.($i+1)];
        $ArrIl[$i]['width'] 				= $data['width_'.($i+1)];
        $ArrIl[$i]['stage'] 				= $data['stage_1'];
        $ArrIl[$i]['glass'] 				= $data['glassconfiguration_'.($i+1)];
        $ArrIl[$i]['thickness_1'] 	= $data['thickness1_'.($i+1)];
        $ArrIl[$i]['thickness_2'] 	= $data['thickness2_'.($i+1)];
        $ArrIl[$i]['glass_length'] 	= $data['glasslength_1'];
        $ArrIl[$i]['weight_veil'] 	= $data['veil_weight_'.($i+1)];
        $ArrIl[$i]['weight_csm'] 		= $data['csm_weight_'.($i+1)];
        $ArrIl[$i]['weight_wr'] 		= $data['wr_weight_'.($i+1)];
      }
      for ($i=0; $i < $no_ol; $i++) {
        $ArrOl[$i]['id_product']		=	$kode_product;
        $ArrOl[$i]['detail_name']		=	'Outside Lamination';
        $ArrOl[$i]['lapisan'] 			= $data['o_lapisan_'.($i+1)];
        $ArrOl[$i]['std_glass'] 		= $data['o_std_'.($i+1)];
        $ArrOl[$i]['width'] 				= $data['o_width_'.($i+1)];
        $ArrOl[$i]['stage'] 				= $data['o_stage_ke_'.($i+1)];
        $ArrOl[$i]['glass'] 				= $data['o_glassconfiguration_'.($i+1)];
        $ArrOl[$i]['thickness_1'] 	= $data['o_thickness1_'.($i+1)];
        $ArrOl[$i]['thickness_2'] 	= $data['o_thickness2_'.($i+1)];
        if (isset($data['o_glasslength_'.($i+1)]) && !empty($data['o_glasslength_'.($i+1)])) {
          $ArrOl[$i]['glass_length'] 	= $data['o_glasslength_'.($i+1)];
        }else {
          $ArrOl[$i]['glass_length'] 	= 0;
        }
        $ArrOl[$i]['weight_veil'] 	= $data['o_veil_weight_'.($i+1)];
        $ArrOl[$i]['weight_csm'] 		= $data['o_csm_weight_'.($i+1)];
        $ArrOl[$i]['weight_wr'] 		= $data['o_wr_weight_'.($i+1)];
      }



      $this->db->trans_start();
        $this->db->insert('component_header', $ArrHeader);
        $this->db->insert_batch('component_detail', $glass);
        $this->db->insert_batch('component_detail', $resinnadd);
        $this->db->insert_batch('component_lamination', $ArrIl);
        $this->db->insert_batch('component_lamination', $ArrOl);
        /*$this->db->insert_batch('component_detail', $ArrDetail13);
        $this->db->insert_batch('component_detail_plus', $ArrDetailPlus1);
        $this->db->insert_batch('component_detail_plus', $ArrDetailPlus2);
        $this->db->insert_batch('component_detail_plus', $ArrDetailPlus3);
        $this->db->insert_batch('component_detail_plus', $ArrDetailPlus4);
        $this->db->insert('component_footer', $ArrFooter);
        $this->db->insert('component_footer', $ArrFooter2);
        $this->db->insert('component_footer', $ArrFooter3);
        if($numberMax_liner != 0){
          $this->db->insert_batch('component__detail_add', $ArrDataAdd1);
        }
        if($numberMax_strukture != 0){
          $this->db->insert_batch('component_detail_add', $ArrDataAdd2);
        }
        if($numberMax_external != 0){
          $this->db->insert_batch('component_detail_add', $ArrDataAdd3);
        }
        if($numberMax_topcoat != 0){
          $this->db->insert_batch('component_detail_add', $ArrDataAdd4);
        }*/
      $this->db->trans_complete();

      if($this->db->trans_status() === FALSE){
        $this->db->trans_rollback();
        $Arr_Kembali	= array(
          'pesan'		=>'Add Calculation failed. Please try again later ...',
          'status'	=> 2
        );
      }
      else{
        $this->db->trans_commit();
        $Arr_Kembali	= array(
          'pesan'		=>'Add Calculation Success. Thank you & have a nice day ...',
          'status'	=> 1
        );
        history('Add estimation code '.$kode_product);
      }
    }
    echo json_encode($Arr_Kembali);
  }
  else{
    //List Dropdown
    $ListProduct					= $this->db->query("SELECT * FROM product WHERE parent_product='field joint' AND deleted='N'")->result_array();
    $ListILamination			= $this->db->query("SELECT * FROM raw_material_lamination WHERE kategori='INSIDE LAMINATION'")->result_array();
    $ListOLamination			= $this->db->query("SELECT * FROM raw_material_lamination WHERE kategori='OUTSIDE LAMINATION'")->result_array();
    $ListResinSystem			= $this->db->query("SELECT * FROM list_help WHERE group_by ='resin_system' AND sts='Y'")->result_array();
    $ListPressure					= $this->db->query("SELECT * FROM list_help WHERE group_by ='pressure' AND sts='Y'")->result_array();
    $ListLiner						= $this->db->query("SELECT * FROM list_help WHERE group_by ='liner' AND sts='Y'")->result_array();

    $ListSeries						= $this->db->query("SELECT kode_group FROM component_group WHERE deleted ='N' AND status='Y' GROUP BY kode_group ORDER BY resin_system ASC, pressure ASC, liner ASC ")->result_array();

    $ListCriminalBarier		= $this->db->query("SELECT * FROM list_help WHERE group_by ='fluida' AND sts='Y'")->result_array();
    $ListAplikasiProduct	= $this->db->query("SELECT * FROM list_help WHERE group_by ='app' AND sts='Y'")->result_array();
    $ListVacumRate				= $this->db->query("SELECT * FROM list_help WHERE group_by ='vacum_rate' AND sts='Y'")->result_array();
    $ListDesignLife				= $this->db->query("SELECT * FROM list_help WHERE group_by ='design_life' AND sts='Y'")->result_array();

    $ListCustomer					= $this->db->query("SELECT id_customer, nm_customer FROM customer ORDER BY nm_customer ASC")->result_array();
    $ListCustomer2				= $this->db->query("SELECT id_customer, nm_customer FROM customer ORDER BY id_customer ASC")->result_array();

    //Realease Agent Sementara Sama dengan Plastic Firm
    $List_Realese					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0008' ORDER BY nm_material ASC")->result_array();
    $List_PlasticFirm			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0008' AND nm_material NOT LIKE 'PE%' AND nm_material NOT LIKE 'POLYESTER%' ORDER BY nm_material ASC")->result_array();

    $List_Veil						= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0003' ORDER BY nm_material ASC")->result_array();
    $List_Resin						= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0001' ORDER BY nm_material ASC")->result_array();
    $List_MatCsm					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0004' ORDER BY nm_material ASC")->result_array();

    $List_MatKatalis			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0002' ORDER BY nm_material ASC")->result_array();
    $List_MatSm						= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0024' ORDER BY nm_material ASC")->result_array();
    $List_MatCobalt				= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0021' ORDER BY nm_material ASC")->result_array();
    $List_MatDma					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0021' ORDER BY nm_material ASC")->result_array();
    $List_MatHydo					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0026' ORDER BY nm_material ASC")->result_array();
    $List_MatMethanol			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0026' ORDER BY nm_material ASC")->result_array();
    $List_MatAdditive			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0019' ORDER BY nm_material ASC")->result_array();
    $List_MatWR						= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0006' ORDER BY nm_material ASC")->result_array();
    $List_MatRooving			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0005' ORDER BY nm_material ASC")->result_array();
    $List_MatColor				= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0007' ORDER BY nm_material ASC")->result_array();
    $List_MatTinuvin			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0019' ORDER BY nm_material ASC")->result_array();
    $List_MatChl					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0019' ORDER BY nm_material ASC")->result_array();
    $List_MatWax					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0008' OR id_category='TYP-0019' ORDER BY nm_material ASC")->result_array();
    $List_MatMchl					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0024' ORDER BY nm_material ASC")->result_array();

    $dataStandart					= $this->db->query("SELECT * FROM identitas")->result_array();
    $data = array(
      'title'							=> 'Estimation Field Joint',
      'action'						=> 'fieldjoint',
      'product'						=> $ListProduct,
      'resin_system'			=> $ListResinSystem,
      'pressure'					=> $ListPressure,
      'liner'							=> $ListLiner,
      'series'						=> $ListSeries,

      'ILamination'				=> $ListILamination,
      'OLamination'				=> $ListOLamination,

      'criminal_barier'		=> $ListCriminalBarier,
      'aplikasi_product'	=> $ListAplikasiProduct,
      'vacum_rate'				=> $ListVacumRate,
      'design_life'				=> $ListDesignLife,
      'standard'					=> $ListCustomer,
      'customer'					=> $ListCustomer2,

      'ListRealise'				=> $List_Realese,
      'ListPlastic'				=> $List_PlasticFirm,
      'ListVeil'					=> $List_Veil,
      'ListResin'					=> $List_Resin,
      'ListMatCsm'				=> $List_MatCsm,

      'ListMatKatalis'		=> $List_MatKatalis,
      'ListMatSm'					=> $List_MatSm,
      'ListMatCobalt'			=> $List_MatCobalt,
      'ListMatDma'				=> $List_MatDma,
      'ListMatHydo'				=> $List_MatHydo,
      'ListMatMethanol'		=> $List_MatMethanol,
      'ListMatAdditive'		=> $List_MatAdditive,

      'ListMatWR'					=> $List_MatWR,
      'ListMatRooving'		=> $List_MatRooving,

      'ListMatColor'			=> $List_MatColor,
      'ListMatTinuvin'		=> $List_MatTinuvin,
      'ListMatChl'				=> $List_MatChl,
      'ListMatStery'			=> $List_MatSm,
      'ListMatWax'				=> $List_MatWax,
      'ListMatMchl'				=> $List_MatMchl
    );

    $this->load->view('Component/est/fieldjoint', $data);
  }
}

//shopjoint
public function shopjoint(){
  if($this->input->post()){
    $data = $this->input->post();
    $data_session			= $this->session->userdata;
    $mY		=  date('ym');
    $ListDetail_Glass		= $data['glass'];
    $ListDetail_resinnadd		= $data['resinnadd'];
    //print_r($ListDetail_Glass);
    $glass = array();
    $resinnadd = array();
    $count = 0;
    //echo $ListDetail_Glass['id_material'][0];

    $ArrDet1 = array();
    $ArrIl = array();
    $ArrOl = array();
    $no_il = $data['no_il'];
    $no_ol = $data['no_ol'];
    /*foreach ($glass as $key => $value) {
      $idm = $value['id_material'];
      $dataMaterial	= $this->db->query("SELECT nm_material, id_category, nm_category FROM raw_materials WHERE id_material='".$value['id_material']."' LIMIT 1")->result_array();
      $glass[$key]['nm_category'] = $dataMaterial[0]['nm_category'];
      foreach ($value as $k => $val) {
        //$glass[$k][$key] = $val;
        //echo $key." -> ".$k." -> ".$val."<br>";
      }
    }
    foreach ($glass as $key => $value) {

      foreach ($value as $k => $val) {
        //$glass[$k][$key] = $val;
        echo $key." -> ".$k." -> ".$val."<br>";
      }
    }
    exit;*/
    /*
    $ListDetail		= $data['ListDetail'];
    $ListDetail2	= $data['ListDetail2'];
    $ListDetail3	= $data['ListDetail3'];
    $ListDetailPlus		= $data['ListDetailPlus'];
    $ListDetailPlus2	= $data['ListDetailPlus2'];
    $ListDetailPlus3	= $data['ListDetailPlus3'];
    $ListDetailPlus4	= $data['ListDetailPlus4'];

    $numberMax_liner		= $data['numberMax_liner'];
    $numberMax_strukture	= $data['numberMax_strukture'];
    $numberMax_external		= $data['numberMax_external'];
    $numberMax_topcoat		= $data['numberMax_topcoat'];

    if($numberMax_liner != 0){
      $ListDetailAdd1	= $data['ListDetailAdd_Liner'];
    }
    if($numberMax_strukture != 0){
      $ListDetailAdd2	= $data['ListDetailAdd_Strukture'];
    }
    if($numberMax_external != 0){
      $ListDetailAdd3	= $data['ListDetailAdd_External'];
    }
    if($numberMax_topcoat != 0){
      $ListDetailAdd4	= $data['ListDetailAdd_TopCoat'];
    }
    // echo "<pre>";
    // print_r($ListDetailPlus);
    // print_r($ListDetailPlus);
    // print_r($ListDetailPlus);
    // exit;
    */

    //pengurutan kode
    $DataSeries2	= $this->db->query("SELECT * FROM component_group WHERE kode_group='".$data['series']."' LIMIT 1 ")->result_array();

    $resin_sistem	= $DataSeries2[0]['resin_system'];
    $liner			= $DataSeries2[0]['liner'];
    $pressure		= $DataSeries2[0]['pressure'];
    $diameter_1		= $data['diameter_1'];
    //$diameter_2		= $data['diameter_2'];

    $KdResinSystem	= ($resin_sistem == 'ISO THALIC')?'I':'V';
    $KdPressure		= sprintf('%02s',$pressure);
    $KdDiameter_1		= sprintf('%04s',$diameter_1);
    //$KdDiameter_2		= sprintf('%04s',$diameter_2);
    $KdLiner		= $liner;
    $KDCust			=	$data['cust'];

    if ($KDCust != 'C100-1903000') {
      $kode_product	= "SJ-".$KdResinSystem."PN".$KdPressure."DN".$KdDiameter_1."-".$KdLiner."-".$KDCust;
    }else {
      $kode_product	= "SJ-".$KdResinSystem."PN".$KdPressure."DN".$KdDiameter_1."-".$KdLiner;
    }
    //$kode_product	= "SJ-".$KdResinSystem."PN".$KdPressure."DN".$KdDiameter_1."-".$KdLiner."-".$KDCust;
    // echo $kode_product; exit;
    $srcType		= "SELECT id_product FROM component_header WHERE id_product='".$kode_product."' ";
    $NumRow			= $this->db->query($srcType)->num_rows();

    if($NumRow > 0){
      $Arr_Kembali	= array(
        'pesan'		=>'Specifications are already in the list. Check again ...',
        'status'	=> 3
      );
    }
    else{

      $DataCust	= $this->db->query("SELECT nm_customer FROM customer WHERE id_customer='".$data['top_toleran']."' LIMIT 1 ")->result_array();
      //$DataApp	= $this->db->query("SELECT * FROM list_help WHERE name='".$data['aplikasi_product']."' LIMIT 1 ")->result_array();
      $DataSeries	= $this->db->query("SELECT * FROM component_group WHERE kode_group='".$data['series']."' LIMIT 1 ")->result_array();

      $ArrHeader	= array(
        'id_product'					=> $kode_product,
        'cust'								=> $KDCust,
        'parent_product'			=> 'shop joint',
        'nm_product'					=> $data['top_type_1'],
        'series'							=> $data['series'],
        'resin_sistem'				=> $DataSeries[0]['resin_system'],
        'pressure'						=> $DataSeries[0]['pressure'],
        'diameter'						=> $data['diameter_1'],
        'liner'								=> $DataSeries[0]['liner'],
        //'aplikasi_product'		=> $data['top_app'],
        //'criminal_barier'			=> $data['criminal_barier'],
        //'vacum_rate'					=> $data['vacum_rate'],
        //'stiffness'						=> $DataApp[0]['data2'],
        //'design_life'					=> $data['design_life'],
        'standart_by'					=> $data['top_toleran'],
        'standart_toleransi'	=> $DataCust[0]['nm_customer'],
        //'diameter2'						=> $data['diameter_2'],
        'panjang'							=> $data['minimum_width'],
        //'design'							=> $data['top_tebal_design'],
        //'radius'							=> $data['radius'],
        //'area'								=> $data['area'],
        //'est'									=> $data['top_tebal_est'],
        //'min_toleransi'				=> $data['top_min_toleran'],
        //'max_toleransi'				=> $data['top_max_toleran'],
        //'waste'								=> $data['waste'],
        'pipe_thickness'			=> $data['pipe_thickness'],
        'joint_thickness'			=> $data['joint_thickness'],
        'factor_thickness'		=> $data['factor_thickness'],
        'created_by'					=> $data_session['ORI_User']['username'],
        'created_date'				=> date('Y-m-d H:i:s')
      );

      // print_r($ArrHeader); exit;
      foreach ($ListDetail_Glass as $key => $value) {
        foreach ($value as $k => $val) {
          $idm = $ListDetail_Glass['id_material'][$k];
          $dataMaterial	= $this->db->query("SELECT nm_material, id_category, nm_category FROM raw_materials WHERE id_material='".$idm."' LIMIT 1")->result_array();

          $glass[$k][$key] = $val;
          $glass[$k]['id_product'] 	= $kode_product;
          //$glass[$k]['detail_name'] 	= $data['detail_name'];
          //$glass[$k]['acuhan'] 		= $data['acuhan_1'];
          //$glass[$k]['id_category'] 	= $dataMaterial[0]['id_category'];
          $glass[$k]['nm_category'] 	= $dataMaterial[0]['nm_category'];
          //$glass[$k]['id_material'] 	= $valx['id_material'];
          $glass[$k]['nm_material'] 	= $dataMaterial[0]['nm_material'];
            $valueM							= (!empty($ListDetail_Glass['value'][$k]))?$ListDetail_Glass['value'][$k]:'';
          $glass[$k]['value'] 			= $valueM;
            $thicknessM						= (!empty($ListDetail_Glass['thickness'][$k]))?$ListDetail_Glass['thickness'][$k]:'';
          $glass[$k]['thickness'] 		= $thicknessM;
            $pengaliM						= (!empty($ListDetail_Glass['fak_pengali'][$k]))?$ListDetail_Glass['fak_pengali'][$k]:'';
          $glass[$k]['fak_pengali'] 	= $pengaliM;
            $bwM							= (!empty($ListDetail_Glass['bw'][$k]))?$ListDetail_Glass['bw'][$k]:'';
          $glass[$k]['bw'] 			= $bwM;
            $jumlahM						= (!empty($ListDetail_Glass['jumlah'][$k]))?$ListDetail_Glass['jumlah'][$k]:'';
          $glass[$k]['jumlah'] 		= $jumlahM;
            $layerM							= (!empty($ListDetail_Glass['layer'][$k]))?$ListDetail_Glass['layer'][$k]:'';
          $glass[$k]['layer'] 			= $layerM;;
            $containingM					= (!empty($ListDetail_Glass['containing'][$k]))?$ListDetail_Glass['containing'][$k]:'';
          $glass[$k]['containing'] 	= $containingM;
            $total_thicknessM				= (!empty($ListDetail_Glass['total_thickness'][$k]))?$ListDetail_Glass['total_thickness'][$k]:'';
          $glass[$k]['total_thickness'] = $total_thicknessM;
            $lastfullM			= (!empty($ListDetail_Glass['last_full'][$k]))?$ListDetail_Glass['last_full'][$k]:'';
          $glass[$k]['last_full'] 		= $lastfullM;
            $lastcostM			= (!empty($ListDetail_Glass['last_cost'][$k]))?$ListDetail_Glass['last_cost'][$k]:'';
          $glass[$k]['last_cost'] 		= $lastcostM;
        }

      }
      foreach ($ListDetail_resinnadd as $key => $value) {
        foreach ($value as $k => $val) {
          $idm = $ListDetail_resinnadd['id_material'][$k];
          $dataMaterial	= $this->db->query("SELECT nm_material, id_category, nm_category FROM raw_materials WHERE id_material='".$idm."' LIMIT 1")->result_array();

          $resinnadd[$k][$key] = $val;
          $resinnadd[$k]['id_product'] 	= $kode_product;
          //$resinnadd[$k]['detail_name'] 	= $data['detail_name'];
          //$resinnadd[$k]['acuhan'] 		= $data['acuhan_1'];
          //$resinnadd[$k]['id_category'] 	= $dataMaterial[0]['id_category'];
          $resinnadd[$k]['nm_category'] 	= $dataMaterial[0]['nm_category'];
          //$resinnadd[$k]['id_material'] 	= $valx['id_material'];
          $resinnadd[$k]['nm_material'] 	= $dataMaterial[0]['nm_material'];
            $valueM							= (!empty($ListDetail_resinnadd['value'][$k]))?$ListDetail_resinnadd['value'][$k]:'';
          $resinnadd[$k]['value'] 			= $valueM;
            $thicknessM						= (!empty($ListDetail_resinnadd['thickness'][$k]))?$ListDetail_resinnadd['thickness'][$k]:'';
          $resinnadd[$k]['thickness'] 		= $thicknessM;
            $pengaliM						= (!empty($ListDetail_resinnadd['fak_pengali'][$k]))?$ListDetail_resinnadd['fak_pengali'][$k]:'';
          $resinnadd[$k]['fak_pengali'] 	= $pengaliM;
            $bwM							= (!empty($ListDetail_resinnadd['bw'][$k]))?$ListDetail_resinnadd['bw'][$k]:'';
          $resinnadd[$k]['bw'] 			= $bwM;
            $jumlahM						= (!empty($ListDetail_resinnadd['jumlah'][$k]))?$ListDetail_resinnadd['jumlah'][$k]:'';
          $resinnadd[$k]['jumlah'] 		= $jumlahM;
            $layerM							= (!empty($ListDetail_resinnadd['layer'][$k]))?$ListDetail_resinnadd['layer'][$k]:'';
          $resinnadd[$k]['layer'] 			= $layerM;;
            $containingM					= (!empty($ListDetail_resinnadd['containing'][$k]))?$ListDetail_resinnadd['containing'][$k]:'';
          $resinnadd[$k]['containing'] 	= $containingM;
            $total_thicknessM				= (!empty($ListDetail_resinnadd['total_thickness'][$k]))?$ListDetail_resinnadd['total_thickness'][$k]:'';
          $resinnadd[$k]['total_thickness'] = $total_thicknessM;
            $lastfullM			= (!empty($ListDetail_resinnadd['last_full'][$k]))?$ListDetail_resinnadd['last_full'][$k]:'';
          $resinnadd[$k]['last_full'] 		= $lastfullM;
            $lastcostM			= (!empty($ListDetail_resinnadd['last_cost'][$k]))?$ListDetail_resinnadd['last_cost'][$k]:'';
          $resinnadd[$k]['last_cost'] 		= $lastcostM;
        }

      }
      for ($i=0; $i < $no_il; $i++) {
        $ArrIl[$i]['id_product']		=	$kode_product;
        $ArrIl[$i]['detail_name']		=	'Inside Lamination';
        $ArrIl[$i]['lapisan'] 			= $data['lapisan_'.($i+1)];
        $ArrIl[$i]['std_glass'] 		= $data['std_'.($i+1)];
        $ArrIl[$i]['width'] 				= $data['width_'.($i+1)];
        $ArrIl[$i]['stage'] 				= $data['stage_1'];
        $ArrIl[$i]['glass'] 				= $data['glassconfiguration_'.($i+1)];
        $ArrIl[$i]['thickness_1'] 	= $data['thickness1_'.($i+1)];
        $ArrIl[$i]['thickness_2'] 	= $data['thickness2_'.($i+1)];
        $ArrIl[$i]['glass_length'] 	= $data['glasslength_1'];
        $ArrIl[$i]['weight_veil'] 	= $data['veil_weight_'.($i+1)];
        $ArrIl[$i]['weight_csm'] 		= $data['csm_weight_'.($i+1)];
        $ArrIl[$i]['weight_wr'] 		= $data['wr_weight_'.($i+1)];
      }
      for ($i=0; $i < $no_ol; $i++) {
        $ArrOl[$i]['id_product']		=	$kode_product;
        $ArrOl[$i]['detail_name']		=	'Outside Lamination';
        $ArrOl[$i]['lapisan'] 			= $data['o_lapisan_'.($i+1)];
        $ArrOl[$i]['std_glass'] 		= $data['o_std_'.($i+1)];
        $ArrOl[$i]['width'] 				= $data['o_width_'.($i+1)];
        $ArrOl[$i]['stage'] 				= $data['o_stage_ke_'.($i+1)];
        $ArrOl[$i]['glass'] 				= $data['o_glassconfiguration_'.($i+1)];
        $ArrOl[$i]['thickness_1'] 	= $data['o_thickness1_'.($i+1)];
        $ArrOl[$i]['thickness_2'] 	= $data['o_thickness2_'.($i+1)];
        if (isset($data['o_glasslength_'.($i+1)]) && !empty($data['o_glasslength_'.($i+1)])) {
          $ArrOl[$i]['glass_length'] 	= $data['o_glasslength_'.($i+1)];
        }else {
          $ArrOl[$i]['glass_length'] 	= 0;
        }
        $ArrOl[$i]['weight_veil'] 	= $data['o_veil_weight_'.($i+1)];
        $ArrOl[$i]['weight_csm'] 		= $data['o_csm_weight_'.($i+1)];
        $ArrOl[$i]['weight_wr'] 		= $data['o_wr_weight_'.($i+1)];
      }



      $this->db->trans_start();
        $this->db->insert('component_header', $ArrHeader);
        $this->db->insert_batch('component_detail', $glass);
        $this->db->insert_batch('component_detail', $resinnadd);
        $this->db->insert_batch('component_lamination', $ArrIl);
        $this->db->insert_batch('component_lamination', $ArrOl);
        /*$this->db->insert_batch('component_detail', $ArrDetail13);
        $this->db->insert_batch('component_detail_plus', $ArrDetailPlus1);
        $this->db->insert_batch('component_detail_plus', $ArrDetailPlus2);
        $this->db->insert_batch('component_detail_plus', $ArrDetailPlus3);
        $this->db->insert_batch('component_detail_plus', $ArrDetailPlus4);
        $this->db->insert('component_footer', $ArrFooter);
        $this->db->insert('component_footer', $ArrFooter2);
        $this->db->insert('component_footer', $ArrFooter3);
        if($numberMax_liner != 0){
          $this->db->insert_batch('component__detail_add', $ArrDataAdd1);
        }
        if($numberMax_strukture != 0){
          $this->db->insert_batch('component_detail_add', $ArrDataAdd2);
        }
        if($numberMax_external != 0){
          $this->db->insert_batch('component_detail_add', $ArrDataAdd3);
        }
        if($numberMax_topcoat != 0){
          $this->db->insert_batch('component_detail_add', $ArrDataAdd4);
        }*/
      $this->db->trans_complete();

      if($this->db->trans_status() === FALSE){
        $this->db->trans_rollback();
        $Arr_Kembali	= array(
          'pesan'		=>'Add Calculation failed. Please try again later ...',
          'status'	=> 2
        );
      }
      else{
        $this->db->trans_commit();
        $Arr_Kembali	= array(
          'pesan'		=>'Add Calculation Success. Thank you & have a nice day ...',
          'status'	=> 1
        );
        history('Add estimation code '.$kode_product);
      }
    }
    echo json_encode($Arr_Kembali);
  }
  else{
    //List Dropdown
    $ListProduct					= $this->db->query("SELECT * FROM product WHERE parent_product='shop joint' AND deleted='N'")->result_array();
    $ListILamination			= $this->db->query("SELECT * FROM raw_material_lamination WHERE kategori='INSIDE LAMINATION'")->result_array();
    $ListOLamination			= $this->db->query("SELECT * FROM raw_material_lamination WHERE kategori='OUTSIDE LAMINATION'")->result_array();
    $ListResinSystem			= $this->db->query("SELECT * FROM list_help WHERE group_by ='resin_system' AND sts='Y'")->result_array();
    $ListPressure					= $this->db->query("SELECT * FROM list_help WHERE group_by ='pressure' AND sts='Y'")->result_array();
    $ListLiner						= $this->db->query("SELECT * FROM list_help WHERE group_by ='liner' AND sts='Y'")->result_array();

    $ListSeries						= $this->db->query("SELECT kode_group FROM component_group WHERE deleted ='N' AND status='Y' GROUP BY kode_group ORDER BY resin_system ASC, pressure ASC, liner ASC ")->result_array();

    $ListCriminalBarier		= $this->db->query("SELECT * FROM list_help WHERE group_by ='fluida' AND sts='Y'")->result_array();
    $ListAplikasiProduct	= $this->db->query("SELECT * FROM list_help WHERE group_by ='app' AND sts='Y'")->result_array();
    $ListVacumRate				= $this->db->query("SELECT * FROM list_help WHERE group_by ='vacum_rate' AND sts='Y'")->result_array();
    $ListDesignLife				= $this->db->query("SELECT * FROM list_help WHERE group_by ='design_life' AND sts='Y'")->result_array();

    $ListCustomer					= $this->db->query("SELECT id_customer, nm_customer FROM customer ORDER BY nm_customer ASC")->result_array();
    $ListCustomer2				= $this->db->query("SELECT id_customer, nm_customer FROM customer ORDER BY id_customer ASC")->result_array();

    //Realease Agent Sementara Sama dengan Plastic Firm
    $List_Realese					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0008' ORDER BY nm_material ASC")->result_array();
    $List_PlasticFirm			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0008' AND nm_material NOT LIKE 'PE%' AND nm_material NOT LIKE 'POLYESTER%' ORDER BY nm_material ASC")->result_array();

    $List_Veil						= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0003' ORDER BY nm_material ASC")->result_array();
    $List_Resin						= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0001' ORDER BY nm_material ASC")->result_array();
    $List_MatCsm					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0004' ORDER BY nm_material ASC")->result_array();

    $List_MatKatalis			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0002' ORDER BY nm_material ASC")->result_array();
    $List_MatSm						= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0024' ORDER BY nm_material ASC")->result_array();
    $List_MatCobalt				= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0021' ORDER BY nm_material ASC")->result_array();
    $List_MatDma					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0021' ORDER BY nm_material ASC")->result_array();
    $List_MatHydo					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0026' ORDER BY nm_material ASC")->result_array();
    $List_MatMethanol			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0026' ORDER BY nm_material ASC")->result_array();
    $List_MatAdditive			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0019' ORDER BY nm_material ASC")->result_array();
    $List_MatWR						= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0006' ORDER BY nm_material ASC")->result_array();
    $List_MatRooving			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0005' ORDER BY nm_material ASC")->result_array();
    $List_MatColor				= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0007' ORDER BY nm_material ASC")->result_array();
    $List_MatTinuvin			= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0019' ORDER BY nm_material ASC")->result_array();
    $List_MatChl					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0019' ORDER BY nm_material ASC")->result_array();
    $List_MatWax					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0008' OR id_category='TYP-0019' ORDER BY nm_material ASC")->result_array();
    $List_MatMchl					= $this->db->query("SELECT * FROM raw_materials WHERE id_category='TYP-0024' ORDER BY nm_material ASC")->result_array();

    $dataStandart					= $this->db->query("SELECT * FROM identitas")->result_array();
    $data = array(
      'title'							=> 'Estimation Shop Joint',
      'action'						=> 'shopjoint',
      'product'						=> $ListProduct,
      'resin_system'			=> $ListResinSystem,
      'pressure'					=> $ListPressure,
      'liner'							=> $ListLiner,
      'series'						=> $ListSeries,

      'ILamination'				=> $ListILamination,
      'OLamination'				=> $ListOLamination,

      'criminal_barier'		=> $ListCriminalBarier,
      'aplikasi_product'	=> $ListAplikasiProduct,
      'vacum_rate'				=> $ListVacumRate,
      'design_life'				=> $ListDesignLife,
      'standard'					=> $ListCustomer,
      'customer'					=> $ListCustomer2,

      'ListRealise'				=> $List_Realese,
      'ListPlastic'				=> $List_PlasticFirm,
      'ListVeil'					=> $List_Veil,
      'ListResin'					=> $List_Resin,
      'ListMatCsm'				=> $List_MatCsm,

      'ListMatKatalis'		=> $List_MatKatalis,
      'ListMatSm'					=> $List_MatSm,
      'ListMatCobalt'			=> $List_MatCobalt,
      'ListMatDma'				=> $List_MatDma,
      'ListMatHydo'				=> $List_MatHydo,
      'ListMatMethanol'		=> $List_MatMethanol,
      'ListMatAdditive'		=> $List_MatAdditive,

      'ListMatWR'					=> $List_MatWR,
      'ListMatRooving'		=> $List_MatRooving,

      'ListMatColor'			=> $List_MatColor,
      'ListMatTinuvin'		=> $List_MatTinuvin,
      'ListMatChl'				=> $List_MatChl,
      'ListMatStery'			=> $List_MatSm,
      'ListMatWax'				=> $List_MatWax,
      'ListMatMchl'				=> $List_MatMchl
    );

    $this->load->view('Component/est/shopjoint', $data);
  }
}
