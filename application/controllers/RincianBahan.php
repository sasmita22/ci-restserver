<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require 'Kelas.php';
use Restserver\Libraries\REST_Controller;

class RincianBahan extends REST_Controller {

	public function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    function index_post(){
		$id_bahan = $this->post('id_bahan');
		$id_makanan = $this->post('id_makanan');
		$qty = $this->post('qty');

		$data = array(
			"id_makanan" => $id_makanan,
			"id_bahan" => $id_bahan,
			"qty" => $qty
		);

		$insert = $this->db->insert('rincian_bahan',$data);

		if ($this->db->affected_rows() > 0){
            $this->response('Ubah qty dengan id_bahan : '.$id_bahan.' dan id_makanan : '.$id_makanan.' berhasil',200);
        }else{
            $this->response(array('status'=>'fail', 502));
        }
	}

    function index_put(){
		$id_bahan = $this->put('id_bahan');
		$id_makanan = $this->put('id_makanan');
		$qty = $this->put('qty');

		$this->db->where(array("id_bahan"=>$id_bahan,"id_makanan"=>$id_makanan));
		$update = $this->db->update('rincian_bahan',array("qty"=>$qty));

		if ($this->db->affected_rows() > 0){
            $this->response('Ubah qty dengan id_bahan : '.$id_bahan.' dan id_makanan : '.$id_makanan.' berhasil',200);
        }else{
            $this->response(array('status'=>'fail', 502));
        }
	}

	//direct from website
	//function index_delete()

}