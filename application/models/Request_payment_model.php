<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Harboens
 * @copyright Copyright (c) 2022
 *
 * This is Model for Request Payment
 */

class Request_payment_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

    public function GetListDataRequest()
    {
        $data    = $this->db->query("SELECT a.id as ids,a.no_doc,b.nama_karyawan nama,a.tgl_doc,'Transportasi' as keperluan, 'transportasi' as tipe,a.jumlah_expense as jumlah,null as tanggal,a.no_doc as id, a.bank_id, a.accnumber, a.accname FROM " . DBERP . ".tr_transport_req a
		left join user_emp b on a.nama=b.id
		WHERE a.status=1
		union
		SELECT a.id as ids,a.no_doc,b.nama_karyawan ,a.tgl_doc,a.keperluan, 'kasbon' as tipe,a.jumlah_kasbon as jumlah,null as tanggal,a.no_doc as id, a.bank_id, a.accnumber, a.accname FROM " . DBERP . ".tr_kasbon a
		left join user_emp b on a.nama=b.id WHERE a.status=1
		union
		SELECT a.id as ids,a.no_doc,c.nama_karyawan nama,a.tgl_doc,a.informasi as keperluan, 'expense' as tipe,a.jumlah,null as tanggal,a.no_doc as id, bank_id, accnumber, accname FROM " . DBERP . ".tr_expense a left join " . DBACC . ".coa_master as b on a.coa=b.no_perkiraan
		left join user_emp c on a.nama=c.id WHERE a.status=1
		")->result();
/*
		union
		SELECT a.id as ids,a.no_doc,a.pic nama,a.tanggal_doc as tgl_doc,a.info as keperluan, 'nonpo' as tipe,a.nilai_request jumlah,null as tanggal,a.no_doc as id, bank_id, accnumber, accname FROM " . DBERP . ".tr_non_po_header a WHERE a.status=3
		union
		SELECT b.id as ids,a.no_doc,c.nama_karyawan nama,a.tanggal_doc as tgl_doc,b.nama as keperluan, 'periodik' as tipe,b.nilai jumlah,null as tanggal,a.no_doc as id, b.bank_id, b.accnumber, b.accname FROM " . DBERP . ".tr_pengajuan_rutin a join tr_pengajuan_rutin_detail b on a.no_doc=b.no_doc join user_emp c on a.created_by=c.id WHERE a.status='1' and b.id_payment='0'

*/
        return $data;
    }

    // list data payment
    // public function GetListDataPayment($where = '')
    // {
    //     $data    = $this->db->query("SELECT * FROM " . DBERP . ".request_payment WHERE " . $where . " order by id desc")->result();
    //     return $data;
    // }

    /* EDITED BY HIKMAT A.R [18-08-2022] */
    public function GetListDataApproval($where = '')
    {
        $data    = $this->db->query("SELECT * FROM " . DBERP . ".request_payment WHERE " . $where . " order by id desc")->result();
        return $data;
    }

    public function GetListDataPayment($where = '')
    {
        $data    = $this->db->query("SELECT a.*, b.nama as namabank FROM " . DBERP . ".payment_approve a left join " . DBACC . ".coa_master as b on a.bank_coa=b.no_perkiraan WHERE " . $where . " order by a.id desc")->result();
        return $data;
    }

    public function GetListDataJurnal($jenis_jurnal='')
    {
        $data    = $this->db->query("SELECT nomor,tanggal,tipe,no_reff,stspos,sum(kredit) as total FROM " . DBERP . ".jurnaltras ".($jenis_jurnal!=""?" where jenis_jurnal='".$jenis_jurnal."' ":"")." group by nomor order by nomor desc")->result();
        return $data;
    }
}
