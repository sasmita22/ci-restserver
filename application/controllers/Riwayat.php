<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require 'Kelas.php';
use Restserver\Libraries\REST_Controller;

class Riwayat extends REST_Controller {
	public function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    function index_get(){
    	
    }

    function index_delete($nota){
        $error = 0;
        $this->db->trans_begin();
        $nota = (int) $nota;

        $this->db->where('no_nota',$nota);
        $this->db->delete('histori_pesanan');
        if($this->db->affected_rows() == 0){
            $error++;
        }

        $this->db->where('no_nota',$nota);
        $this->db->delete('histori_nota'); 
        if($this->db->affected_rows() == 0){
            $error++;
        }
           
        if ($error == 0) {
            $this->db->trans_commit();
            $this->response("Delete riwayat berhasil",200);
        }else{
            $this->db->trans_rollback();
            $this->response(array('status'=>'fail', 502));
        }
    }
}