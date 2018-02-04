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
    	$query = $this->db->get("histori_nota");
        $histori = array();
        foreach ($query->result() as $row) {
            $query1 = $this->db->get_where("histori_pesanan",array("no_nota"=>$row->no_nota));
            $listpesanan = array();
            foreach ($query1->result() as $row1) {
                $listpesanan[] = array("nama_makanan" => $row1->makanan, "harga"=>$row1->harga, "qty"=>$row1->qty);
            }
            $histori[] = array("no_nota"=>$row->no_nota,"feedback"=>$row->feedback,"tanggal"=>$row->tanggal,"total"=>$row->total,"pesanan"=>$listpesanan);
        }

        if(count($histori) > 0){
            $this->response($histori,200);
        }else{
            $this->response(array('status' => 'kosong'), 502);
        }
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
            $this->response(array('status'=>'success'),200);
        }else{
            $this->db->trans_rollback();
            $this->response(array('status'=>'fail', 502));
        }
    }

    
}