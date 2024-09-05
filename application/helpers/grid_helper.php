<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
		date_default_timezone_set("Asia/Bangkok");
		function Enkripsi($sData, $sKey='200881173_HyunJoo'){
			$sResult = '';
			for($i=0;$i<strlen($sData);$i++){
				$sChar    = substr($sData, $i, 1);
				$sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
				$sChar    = chr(ord($sChar) + ord($sKeyChar));
				$sResult .= $sChar;
			}
			return Enkripsi_base64($sResult);
		}

		function Dekripsi($sData, $sKey='200881173_HyunJoo'){
			$sResult = '';
			$sData   = Dekripsi_base64($sData);
			for($i=0;$i<strlen($sData);$i++){
				$sChar    = substr($sData, $i, 1);
				$sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
				$sChar    = chr(ord($sChar) - ord($sKeyChar));
				$sResult .= $sChar;
			}
			return $sResult;
		}

		function Enkripsi_base64($sData){
			$sBase64 = base64_encode($sData);
			return strtr($sBase64, '+/', '-_');
		}

		function Dekripsi_base64($sData){
			$sBase64 = strtr($sData, '-_', '+/');
			return base64_decode($sBase64);
		}

		function history($desc=NULL){
			$CI 			=& get_instance();
			$path			= $CI->uri->segment(1);
			$data_session	= $CI->session->userdata;
			$userID			= $data_session['ORI_User']['username'];
			$Date			= date('Y-m-d H:i:s');
			$IP_Address		= $CI->input->ip_address();

			$DataHistory=array();
			$DataHistory['user_id']		= $userID;
			$DataHistory['path']		= $path;
			$DataHistory['description']	= $desc;
			$DataHistory['ip_address']	= $IP_Address;
			$DataHistory['created']		= $Date;
			$CI->db->insert('histories',$DataHistory);
		}

		function cryptSHA1($fields){
			$key			='-SonHyunJoo173';
			$Encrpt_Kata	= sha1($fields.$key);
			return $Encrpt_Kata;
		}

		function getRomawi($bulan){
			$month	= intval($bulan);
			switch($month){
				case "1":
					$romawi	='I';
					break;
				case "2":
					$romawi	='II';
					break;
				case "3":
					$romawi	='III';
					break;
				case "4":
					$romawi	='IV';
					break;
				case "5":
					$romawi	='V';
					break;
				case "6":
					$romawi	='VI';
					break;
				case "7":
					$romawi	='VII';
					break;
				case "8":
					$romawi	='VIII';
					break;
				case "9":
					$romawi	='IX';
					break;
				case "10":
					$romawi	='X';
					break;
				case "11":
					$romawi	='XI';
					break;
				case "12":
					$romawi	='XII';
					break;
			}
			return $romawi;
		}

		function getColsChar($colums){
			// Palleng by jester

			if($colums>26)
			{
				$modCols = floor($colums/26);
				$ExCols = $modCols*26;
				$totCols = $colums-$ExCols;

				if($totCols==0)
				{
					$modCols=$modCols-1;
					$totCols+=26;
				}

				$lets1 = getLetColsLetter($modCols);
				$lets2 = getLetColsLetter($totCols);
				return $letsi = $lets1.$lets2;
			}
			else
			{
				$lets = getLetColsLetter($colums);
				return $letsi = $lets;
			}
		}

		function getLetColsLetter($numbs){
			// Palleng by jester
			switch($numbs){
				case 1:
				$Chars = 'A';
				break;
				case 2:
				$Chars = 'B';
				break;
				case 3:
				$Chars = 'C';
				break;
				case 4:
				$Chars = 'D';
				break;
				case 5:
				$Chars = 'E';
				break;
				case 6:
				$Chars = 'F';
				break;
				case 7:
				$Chars = 'G';
				break;
				case 8:
				$Chars = 'H';
				break;
				case 9:
				$Chars = 'I';
				break;
				case 10:
				$Chars = 'J';
				break;
				case 11:
				$Chars = 'K';
				break;
				case 12:
				$Chars = 'L';
				break;
				case 13:
				$Chars = 'M';
				break;
				case 14:
				$Chars = 'N';
				break;
				case 15:
				$Chars = 'O';
				break;
				case 16:
				$Chars = 'P';
				break;
				case 17:
				$Chars = 'Q';
				break;
				case 18:
				$Chars = 'R';
				break;
				case 19:
				$Chars = 'S';
				break;
				case 20:
				$Chars = 'T';
				break;
				case 21:
				$Chars = 'U';
				break;
				case 22:
				$Chars = 'V';
				break;
				case 23:
				$Chars = 'W';
				break;
				case 24:
				$Chars = 'X';
				break;
				case 25:
				$Chars = 'Y';
				break;
				case 26:
				$Chars = 'Z';
				break;
			}

			return $Chars;
		}

		function getColsLetter($char){
			//	Palleng by jester
			$len = strlen($char);
			if($len==1)
			{
				$numb = getLetColsNumber($char);
			}
			elseif($len==2)
			{
				$i=1;
				$j=0;
				$jm=1;
				while($i<$len)
				{
					$let_fst = substr($char, $j,1);
					$dv = getLetColsNumber($let_fst);
					$jm = $dv * 26;

					$i++;
					$j++;
				}
				$let_last = substr($char, $j,1);
				$numb = $jm + getLetColsNumber($let_last);
			}

			return $numb;
		}

		function getLetColsNumber($char)
		{
			// Palleng by jester

			switch($char){
				case 'A':$numb = 1;break;
				case 'B':$numb = 2;break;
				case 'C':$numb = 3;break;
				case 'D':$numb = 4;break;
				case 'E':$numb = 5;break;
				case 'F':$numb = 6;break;
				case 'G':$numb = 7;break;
				case 'H':$numb = 8;break;
				case 'I':$numb = 9;break;
				case 'J':$numb = 10;break;
				case 'K':$numb = 11;break;
				case 'L':$numb = 12;break;
				case 'M':$numb = 13;break;
				case 'N':$numb = 14;break;
				case 'O':$numb = 15;break;
				case 'P':$numb = 16;break;
				case 'Q':$numb = 17;break;
				case 'R':$numb = 18;break;
				case 'S':$numb = 19;break;
				case 'T':$numb = 20;break;
				case 'U':$numb = 21;break;
				case 'V':$numb = 22;break;
				case 'W':$numb = 23;break;
				case 'X':$numb = 24;break;
				case 'Y':$numb = 25;break;
				case 'Z':$numb = 26;break;
			}

			return $numb;
		}

		function getAcccesmenu($controller=NULL){
			$CI 			=& get_instance();
			$data_session	= $CI->session->userdata;
			$group			= $data_session['ORI_User']['group_id'];
			$Action=array();
			if($group=='1'){
				$action["read"]		= 1;
				$action["create"]	= 1;
				$action["update"]	= 1;
				$action["delete"]	= 1;
				$action["download"]	= 1;
				$action["approve"]	= 1;
			}else{
				$qMenu		= $CI->db->get_where('menus',array('LOWER(path)'=>strtolower($controller)));
				$dataMenu	= $qMenu->result();
				// echo $controller;
				// echo "<pre>"; print_r($dataMenu);exit;
				// echo $dataMenu[0]->id; exit;
				if($qMenu->num_rows() > 0){
					$qAccess	= $CI->db->get_where('group_menus',array('menu_id'=>$dataMenu[0]->id,'group_id'=>$group));
					$DataAccess	= $qAccess->result();
					if($DataAccess){
						$action["read"]=(isset($DataAccess[0]->read) && $DataAccess[0]->read)?$DataAccess[0]->read:0;
						$action["create"]=(isset($DataAccess[0]->create) && $DataAccess[0]->create)?$DataAccess[0]->create:0;
						$action["update"]=(isset($DataAccess[0]->update) && $DataAccess[0]->update)?$DataAccess[0]->update:0;
						$action["delete"]=(isset($DataAccess[0]->delete) && $DataAccess[0]->delete)?$DataAccess[0]->delete:0;
						$action["download"]=(isset($DataAccess[0]->download) && $DataAccess[0]->download)?$DataAccess[0]->download:0;
						$action["approve"]=(isset($DataAccess[0]->approve) && $DataAccess[0]->approve)?$DataAccess[0]->approve:0;
					}
				}

			}
			return $action;
		}

		function generate_tree($data=array(),$depth=0,$nilai=array()){
			$ArrDept=array(0=>10,1=>40,2=>70,3=>100);
			if(isset($data) && $data){
				foreach($data as $key=>$value){
					echo create_datas($value,$ArrDept[$depth],$nilai);
					if(array_key_exists('child',$value)){
						generate_tree($value['child'],($depth + 1),$nilai);
					}
				}
			}
		}

		function create_datas($value=array(),$padding=NULL,$data=array()){
				$template='<tr>';
				$state['read']		= (isset($data[$value['id']]['read']) && $data[$value['id']]['read'] == 1) ? ' checked="checked"' : '';
				$state['create']	= (isset($data[$value['id']]['create']) && $data[$value['id']]['create'] == 1) ? ' checked="checked"' : '';
				$state['update']	= (isset($data[$value['id']]['update']) && $data[$value['id']]['update'] == 1) ? ' checked="checked"' : '';
				$state['delete']	= (isset($data[$value['id']]['delete']) && $data[$value['id']]['delete'] == 1) ? ' checked="checked"' : '';
				$state['download']	= (isset($data[$value['id']]['download']) && $data[$value['id']]['download'] == 1) ? ' checked="checked"' : '';
				$state['approve']	= (isset($data[$value['id']]['approve']) && $data[$value['id']]['approve'] == 1) ? ' checked="checked"' : '';
				$template.=		'<td align="left" style="padding-left:'.$padding.'px;"><input type="hidden" name="tree['.$value['id'].'][menu_id]" value="'.$value['id'].'">  '.$value['name'].'</td>';
				$template.=		'<td align="center"><input type="checkbox" id="read'.$value['id'].'" class="minimal" name="tree['.$value['id'].'][read]" value="1"'.$state['read'].'></td>';
				$template.=		'<td align="center"><input type="checkbox" id="create'.$value['id'].'" class="minimal" name="tree['.$value['id'].'][create]" value="1"'.$state['create'].'></td>';
				$template.=		'<td align="center"><input type="checkbox" id="update'.$value['id'].'" class="minimal" name="tree['.$value['id'].'][update]" value="1"'.$state['update'].'></td>';
				$template.=		'<td align="center"><input type="checkbox" id="delete'.$value['id'].'" class="minimal" name="tree['.$value['id'].'][delete]" value="1"'.$state['delete'].'></td>';
				$template.=		'<td align="center"><input type="checkbox" id="approve'.$value['id'].'" class="minimal" name="tree['.$value['id'].'][approve]" value="1"'.$state['approve'].'></td>';
				$template.=		'<td align="center"><input type="checkbox" id="download'.$value['id'].'" class="minimal" name="tree['.$value['id'].'][download]" value="1"'.$state['download'].'></td>';
				$template.='</tr>';
			//echo $template;
			return $template;
		}

		function group_menus_access(){
			$CI 			=& get_instance();
			$data_session	= $CI->session->userdata;
			$groupID		= $data_session['ORI_User']['group_id'];

			$ArrMenu	= array();
			if($groupID=='1'){
				$Query	= "SELECT * FROM menus WHERE flag_active='1' ORDER BY parent_id,weight,id ASC";
			}else{
				$Query	= "SELECT menus.* FROM menus INNER JOIN group_menus ON menus.id=group_menus.menu_id WHERE menus.flag_active='1' AND group_menus.group_id='$groupID' ORDER BY menus.parent_id,menus.weight,menus.id ASC";
			}

			$jumlah		= $CI->db->query($Query)->num_rows();

			if($jumlah > 0){
				$hasil		= $CI->db->query($Query)->result_array();

				foreach($hasil as $key=>$val){
					$ArrMenu[$key]['Menu']['id']		= $val['id'];
					$ArrMenu[$key]['Menu']['name']		= $val['name'];
					$ArrMenu[$key]['Menu']['path']		= $val['path'];
					$ArrMenu[$key]['Menu']['parent_id']	= $val['parent_id'];
					$ArrMenu[$key]['Menu']['weight']	= $val['weight'];
					$ArrMenu[$key]['Menu']['icon']		= $val['icon'];
				}
			}
			$Menus	= rebuild_structure($ArrMenu);
			return $Menus;
		}


		//echo"<pre>";print_r($Menus);	exit;


		function rebuild_structure($data){
			$childs = array();

			foreach($data as &$item){
				$childs[$item['Menu']['parent_id']][] = &$item['Menu'];
				unset($item);
			}

			foreach($data as &$item){
				if (isset($childs[$item['Menu']['id']])){
					$item['Menu']['child'] = $childs[$item['Menu']['id']];
					unset($childs[$item['Menu']['id']]);
				}
			}

		//	pr($childs);exit;
		//	menu that has no parent, append it as parent
			if(count($childs) > 0){
				foreach($childs as $key => $child){
					if($key != 0){
						$childs[0][] = $child[0];
						unset($childs[$key]);
					}
				}
			}

			return isset($childs[0]) ? $childs[0] : array();
		}

		function render_left_menus($fixed_structure=array(),$dept=0){
			//if first render echo wrapper
			if($dept==0){
				echo '<ul class="sidebar-menu" id="menu">';
				echo '<li class="header">MAIN NAVIGATION</li>';
			}

			//loop children
			foreach($fixed_structure as $key=>$value){
				$path=$value['path']==''?'#':base_url().''.strtolower($value['path']);
				$icons=$value['icon'];

				if(array_key_exists('child',$value)){
					echo'<li class="treeview"><a href="'.$path.'"><i class="fa '.$icons.'"></i>'.$value['name'].'<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
					echo('<ul class="treeview-menu">');
					render_left_menus($value['child'],$dept+1);
					echo('</ul>');
				}else{
					echo'<li class="treeview"><a href="'.$path.'"><i class="fa '.$icons.'"></i>'.$value['name'].'</a>';
				}
				echo('</li>');
			}
			if($dept==0)echo('</ul>');
		}

		function group_access($groupID){
			$CI 			=& get_instance();
			$data_session	= $CI->session->userdata;
			// $groupID		= $data_session['ORI_User']['group_id'];
			$MenusAccess	= array();
			$Query	= "SELECT menus.*,group_menus.id AS kode_group,group_menus.read,group_menus.create,group_menus.update,group_menus.delete,group_menus.approve,group_menus.download FROM menus LEFT JOIN group_menus ON menus.id=group_menus.menu_id AND group_menus.group_id='$groupID' WHERE menus.flag_active='1' ORDER BY menus.parent_id,menus.weight,menus.id ASC";
			$jumlah		= $CI->db->query($Query);
			//echo"ono bro ".$jumlah;exit;
			if($jumlah->num_rows() > 0){
				$hasil		= $jumlah->result_array();

				foreach($hasil as $key=>$val){
					if($groupID=='1'){
						$MenusAccess[$val['id']]['read']=1;
						$MenusAccess[$val['id']]['create']=1;
						$MenusAccess[$val['id']]['update']=1;
						$MenusAccess[$val['id']]['delete']=1;
						$MenusAccess[$val['id']]['approve']=1;
						$MenusAccess[$val['id']]['download']=1;
					}else{
						if(isset($val['kode_group']) && $val['kode_group']){
							$MenusAccess[$val['id']]['read']=$val['read'];
							$MenusAccess[$val['id']]['create']=$val['create'];
							$MenusAccess[$val['id']]['update']=$val['update'];
							$MenusAccess[$val['id']]['delete']=$val['delete'];
							$MenusAccess[$val['id']]['approve']=$val['approve'];
							$MenusAccess[$val['id']]['download']=$val['download'];
						}
					}

				}
			}

			return $MenusAccess;
		}

		function reconstruction_tree($parent_id=0,$data=array()){
			$menus=array();
			foreach($data as $key=>$value){
				$index=count($menus);
				if($value['parent_id']==$parent_id){
					$menus[$index]=$value;
					if(count($value) >1){
						$menus[$index]['detail']=$value;
					}
					//unset print
					unset($data[$key]);
					if($child=reconstruction_tree($value['id'],$data)){
						$menus[$index]['child']=$child;
					}
				}
			}
			return $menus;
		}

		function implode_data($data=array(),$key='key'){
			if(strtolower($key)=='key'){
				$det_imp	="";
				foreach($data as $key=>$val){
					if(!empty($det_imp))$det_imp.="','";
					$det_imp	.=$key;
				}
			}else{
				$det_imp	=implode("','",$data);
			}
			return $det_imp;
		}

		function getExtension($str) {

			 $i = strrpos($str,".");
			 if (!$i) { return ""; }

			 $l = strlen($str) - $i;
			 $ext = substr($str,$i+1,$l);
			 return $ext;
		}

		function ImageResizes($data,$location,$NewName=NULL){
			 $CI 			=& get_instance();
			 $image 		= $data["name"];
			 $uploadedfile 	= $data['tmp_name'];
			 $Arr_Return	= array();
			 if ($image){
				$filename 	= stripslashes($data['name']);
				$extension 	= getExtension($filename);
				$extension 	= strtolower($extension);
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
						$Arr_Return	= array(
							'status'	=> 2,
							'pesan'		=> 'File ekstension tidak valid.....'
						);


				}else{
					$size	= filesize($data['tmp_name']);
					// cek image size
					if ($size > (3840*3840))	{
						$Arr_Return	= array(
							'status'	=> 2,
							'pesan'		=> 'Ukuran File terlalu besar......'
						);

					}else{

						if($extension=="jpg" || $extension=="jpeg" ){
							$uploadedfile = $data['tmp_name'];
							$src = imagecreatefromjpeg($uploadedfile);
						}else if($extension=="png"){
							$uploadedfile = $data['tmp_name'];
							$src = imagecreatefrompng($uploadedfile);
						}else {
							$src = imagecreatefromgif($uploadedfile);
						}

						list($width,$height)=getimagesize($uploadedfile);

						$newwidth	= 1024;
						$newheight	= ($height/$width)*$newwidth;
						$tmp		= imagecreatetruecolor($newwidth,$newheight);
						imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
						$uploaddir 	= $inputFileName = './assets/images/'.$location.'/';
						if($NewName){
							$filename = $uploaddir.$NewName.'.'.$extension;
						}else{
							$filename = $uploaddir.$data['name'];
						}
						unlink($filename);
						imagejpeg($tmp,$filename,100);

						imagedestroy($src);
						imagedestroy($tmp);
						$Arr_Return	= array(
							'status'	=> 1,
							'pesan'		=> 'Upload Image Success....'
						);
					}

				}
			}

			return $Arr_Return;
		}

		function akses_server_side(){
			$Arr_Balik	= array(
				'hostname'	=> '203.160.58.170',
				'hostuser'	=> 'root',
				'hostpass'	=> 'sentral2022**',
				'hostdb'	=> 'db_sendix'
			);
			return $Arr_Balik;
		}

		function group_company(){
			$CI 			=& get_instance();
			$data_session	= $CI->session->userdata;

			$Query			= "SELECT * FROM identitas";

			$jumlah			= $CI->db->query($Query)->num_rows();
			$balik_data		= array();
			if($jumlah > 0){
				$hasil		= $CI->db->query($Query)->result();
				$balik_data	= $hasil[0];
			}

			return $balik_data;
		}


		function rate($code_group, $unit, $category, $region){
			$CI 			=& get_instance();
			$Query		= "SELECT rate FROM price_ref WHERE code_group='".$code_group."' AND unit_material='".$unit."' AND region='".$region."' LIMIT 1 ";
			// echo $Query;
			$result		= $CI->db->query($Query)->result();
			$rate			= (!empty($result[0]->rate))?$result[0]->rate:'0';
			return $rate;
		}

		function rate2($code_group, $unit, $region){
			$CI 			=& get_instance();
			$Query		= "SELECT rate FROM price_ref WHERE code_group='".$code_group."' AND unit_material='".$unit."' AND region='".$region."' LIMIT 1 ";
			// echo $Query;
			$result		= $CI->db->query($Query)->result();
			$rate			= (!empty($result[0]->rate))?$result[0]->rate:'0';
			return $rate;
		}

		// public function get_rate($code, $category, $unit, ){
		// 	$code 		= $this->uri->segment(3);
		// 	$category = str_replace('_', ' ', $this->uri->segment(4));
		// 	$unit 		= $this->uri->segment(5);
		// 	$time 		= $this->uri->segment(6);
		// 	$qty 			= $this->uri->segment(7);
		// 	$region 	= $this->uri->segment(8);
		// 	if($category == 'man power' OR $category == 'consumable'){
		// 		$region 	= "all region";
		// 	}
		//
		// 	$pengali 	= 1;
		// 	if($unit == 'week'){
		// 		$pengali 	= 6;
		// 	}
		// 	if($unit == 'month'){
		// 		$pengali 	= 22;
		// 	}
		// 	if($unit == 'six_months'){
		// 		$pengali 	= 132;
		// 	}
		// 	if($unit == 'year'){
		// 		$pengali 	= 164;
		// 	}
		//
		// 	$rate = rate($code, $unit, $category, $region) / $pengali;
		// 	return $rate;
		// }

		function rate_ak($code_group, $unit, $category, $region){
			$CI 			=& get_instance();
			$Query		= "SELECT rate FROM price_ref WHERE code_group='".$code_group."' AND unit_material='".$unit."' AND category='".$category."' AND region='".$region."' LIMIT 1 ";
			// echo $Query;
			$result		= $CI->db->query($Query)->result();
			$rate			= (!empty($result[0]->rate))?$result[0]->rate:'0';
			return $rate;
		}

		function getMP($code_group, $table){
			$CI 			=& get_instance();
			$Query		= "SELECT category, spec FROM $table WHERE code_group='".$code_group."'  LIMIT 1 ";
			$result		= $CI->db->query($Query)->result();
			$rate			= (!empty($result))?strtoupper($result[0]->category)." - ".strtoupper($result[0]->spec):'NOT FOUND';
			return $rate;
		}

		function getStatus($project_code){
			$CI 			=& get_instance();
			$Query		= "SELECT status FROM project_header WHERE project_code='".$project_code."'  LIMIT 1 ";
			$result		= $CI->db->query($Query)->result();
			$status			= (!empty($result))?strtoupper($result[0]->status):'NOT FOUND';
			return $status;
		}

		function getColor($status){
			$CI 			=& get_instance();
			$sqlColor = "SELECT * FROM color_status WHERE status='".$status."' ";
			$restColor = $CI->db->query($sqlColor)->result();
			$hslColor			= (!empty($restColor))?strtoupper($restColor[0]->warna):'black';

			return $hslColor;
		}

		function getIPP($project_code){
			$CI 			=& get_instance();
			$sqlColor = "SELECT no_ipp FROM project_header WHERE project_code='".$project_code."' ";
			$restColor = $CI->db->query($sqlColor)->result_array();
			$hslColor = $restColor[0]['no_ipp'];

			return $hslColor;
		}

		function get_name($table, $field, $field_whare, $value){
			$CI 		=& get_instance();
			$query	= $CI->db->query("SELECT $field FROM $table WHERE $field_whare='".$value."' LIMIT 1")->result();
			$data 	= (!empty($query))?$query[0]->$field:'Not found';
			return $data;
		}

		function get_sql_meal_ot($code_project){
			$query =  "	SELECT
							a.project_code,
							a.project_code_det,
							a.code_work_detail,
							a.code_group,
							a.category,
							a.spec,
							MAX(a.qty) AS qty,
							SUM((SELECT SUM(b.std_time) FROM project_detail_header b WHERE b.project_code_det=a.project_code_det )) AS std_time
						FROM
							project_detail_process a
						WHERE
							a.tipe = 'man power'
							AND a.project_code = '".$code_project."'
						GROUP BY 
							a.code_group
							";
			return $query;
		}

		function get_tipe_instalasi(){
			$CI 		=& get_instance();
			$query	= $CI->db->query("SELECT category FROM list WHERE origin='tipe instalasi'")->result_array();
			return $query;
		}

?>
