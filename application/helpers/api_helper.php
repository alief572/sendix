<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    $URL_SERVER = "http://120.29.159.11:8080";
    
    function api_get_customer(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $URL_SERVER.'/api/api_get_customer',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=mba0r4o70jotmmqc18cbjtdkvd1og3vs'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, TRUE);
    }

    function api_get_pressure(){
        $CI 	=& get_instance();
        $DB2    = $CI->load->database('costing', TRUE);
        $result	= $DB2->order_by('urut','asc')->get_where('list_help', array('group_by'=>'pressure'))->result_array();
        return $result;
    }

    function api_get_liner(){
        $CI 	=& get_instance();
        $DB2    = $CI->load->database('costing', TRUE);
        $result	= $DB2->order_by('urut','asc')->get_where('list_help', array('group_by'=>'liner'))->result_array();
        return $result;
    }

    function api_get_mde_area(){
        $CI 	=& get_instance();
        $DB2    = $CI->load->database('costing', TRUE);
        $result	= $DB2->group_by('area')->order_by('id','asc')->get_where('cost_trucking', array('category'=>'darat'))->result_array();
        return $result;
    }

    function api_get_mde_tujuan($area=null){
        $CI 	=& get_instance();
        $DB2    = $CI->load->database('costing', TRUE);
        $result	= $DB2->group_by('tujuan')->order_by('tujuan','asc')->get_where('cost_trucking', array('category'=>'darat','area'=>$area))->result_array();
        return $result;
    }

    function api_get_mde_kendaraan($area=null, $tujuan){
        $CI 	=& get_instance();
        $DB2    = $CI->load->database('costing', TRUE);
        $result	= $DB2
                    ->select('a.id_truck, b.nama_truck')
                    ->from('cost_trucking a')
                    ->join('truck b','a.id_truck=b.id')
                    ->where('a.category','darat')
                    ->where('a.area',$area)
                    ->where('a.tujuan',$tujuan)
                    ->get()
                    ->result_array();
        return $result;
    }

    function api_get_nm_truck($id){
        $CI 	=& get_instance();
        $DB2    = $CI->load->database('costing', TRUE);
        $result	= $DB2
                    ->select('nama_truck')
                    ->from('truck')
                    ->where('id',$id)
                    ->get()
                    ->result();
        $nm_truck = (!empty($result))?$result[0]->nama_truck:'';
        return $nm_truck;
    }

    function api_get_cost_truck($area=null, $tujuan=null, $id_truck=null){
        $CI 	=& get_instance();
        $DB2    = $CI->load->database('costing', TRUE);
        $result	= $DB2
                    ->select('a.price')
                    ->from('cost_trucking a')
                    ->where('a.category','darat')
                    ->where('a.area',$area)
                    ->where('a.tujuan',$tujuan)
                    ->where('a.id_truck',$id_truck)
                    ->get()
                    ->result();
        $price = (!empty($result))?$result[0]->price:0;
        return $price;
    }



?>