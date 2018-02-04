<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class AkunMeja extends REST_Controller {
    public function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    function index_get(){
        $no_meja = $this->get('no_meja');
        
        if($no_meja == ''){
            $this->db->order_by('no_meja','asc');
            $akun_meja = $this->db->get('akun_meja')->result();
        }else{
            $this->db->order_by('no_meja','asc');
            $this->db->where('no_meja',$no_meja);
            $akun_meja = $this->db->get('akun_meja')->result();
        }
        
     
        $this->response($akun_meja,200);
    }

    function index_post(){
        $this->db->trans_begin();
        $error = 0;
        $akun_meja = array(
            "username" => $this->post('username'),          
            "pass" => $this->post("pass"),          
            "no_meja" => $this->post('no_meja')                          
        );

        $insert = $this->db->insert('meja', array("no_meja"=>$akun_meja['no_meja'],"status"=>0));
        if(!$insert){
            $error++;
        }


        $insert2 = $this->db->insert('akun_meja',$akun_meja);
        if(!$insert2){
            $error++;
        }
        
        if($error == 0){
            $this->db->trans_commit();
            $this->response('Berhasil Menambah akun meja',200);
        }else{
            $this->db->trans_rollback();
            $this->response(array('status' => 'fail', 502));
        }
    }

    function index_put(){
        $no_meja = $this->put('no_meja');
        $akun_meja = array(                   
            "username" => $this->put("username"),         
            "pass" => $this->put('pass'),                  
        );

        $this->db->where('no_meja',$no_meja);
        $update = $this->db->update('akun_meja',$akun_meja);
        if ($this->db->affected_rows() > 0){
            $this->response(array('status'=>'success'),200);
        }else{
            $this->response(array('status'=>'fail', 502));
        }
    }

    function index_delete($no_meja){
        $this->db->where('no_meja',$no_meja);
        $delete = $this->db->delete('akun_meja');

        if ($this->db->affected_rows() > 0) {
            $this->response(array('status'=>'success'),200);
        }else{
            $this->response(array('status'=>'fail', 502));
        }
    }
}