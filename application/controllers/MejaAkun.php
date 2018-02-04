<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require 'Kelas.php';
use Restserver\Libraries\REST_Controller;

class MejaAkun extends REST_Controller {
	function index_get(){
        $no_meja = $this->get('no_meja');
        
        if($no_meja == ''){
            $this->db->select('pass,no_meja,status');
            $this->db->order_by('no_meja','asc');
            $akun_meja = $this->db->get('akun_meja')->result();
        }else{
            $this->db->select('pass,no_meja,status');
            $this->db->order_by('no_meja','asc');
            $this->db->where('no_meja',$no_meja);
            $akun_meja = $this->db->get('akun_meja')->result();
        }
        
     
        $this->response($akun_meja,200);
    }

    function index_post(){
        // $this->db->trans_begin();

        // $akun_meja = array(
        //     "username" => $this->post('username');          
        //     "pass" => $this->post("pass");          
        //     "no_meja" => $this->post('no_meja');                           
        // );

        // $insert = $this->db->insert('akun_meja',$akun_meja);
        // $insert2 = $this->db->insert('meja', array("no_meja"=>$akun_meja['no_meja']));
        
        // if($insert){
        //     $this->db->trans_commit();
        //     $this->response('Berhasil Menambah akun meja',200);
        // }else{
        //     $this->db->trans_rollback();
        //     $this->response(array('status' => 'fail', 502));
        // }
        echo "string";
    }

    function index_put(){
        $username = $this->put('username');
        $akun_meja = array(                   
            "pass" => $this->put("pass");          
            "no_meja" => $this->put('no_meja');                  
        );

        $this->db->where('username',$username);
        $update = $this->db->update('akun_meja',$akun_meja);
        if ($update){
            $this->response('Update akun : '.$username.' berhasil',200);
        }else{
            $this->response(array('status'=>'fail', 502));
        }
    }

    function index_delete($username){
        $username = $this->delete('username');
        $this->db->where('username',$username);
        $delete = $this->db->delete('akun_meja');

        if ($delete) {
            $this->response('Delete akun : '.$id.' berhasil',200);
        }else{
            $this->response(array('status'=>'fail', 502));
        }
    }
}