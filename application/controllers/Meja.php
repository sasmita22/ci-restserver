<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Meja extends REST_Controller {
    public function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }
    
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
    
    function passlog_get(){
        $username = $this->get('username');
        
        $this->db->select('pass,no_meja,status');
        $this->db->where('username',$username);
        $akun_meja = $this->db->get('akun_meja')->result();
     
        $this->response($akun_meja,200);
    }
    
    function login_put(){
        // $data = $this->uri->uri_to_assoc();  //http://localhost/ci-restserver/index.php/meja/login/no_meja/7
        // $no_meja = $data['no_meja'];
        // $no_meja =  $this->input->get('no_meja'); //http://localhost/ci-restserver/index.php/meja/login?no_meja=7
        $no_meja = $this->put('no_meja');
        
        $this->db->where('no_meja',$no_meja);
        $berhasil = $this->db->update('akun_meja',array('status'=>1));
        if($berhasil){
            $this->response('Update data '.$no_meja.' berhasil',200);
        }else{
            $this->response(array('status'=>'fail', 502));
        }
    }
    
    function logout_put(){
        // $data = $this->uri->uri_to_assoc();  //http://localhost/ci-restserver/index.php/meja/logout/no_meja/7
        // $no_meja = $data['no_meja'];
        // $no_meja =  $this->input->get('no_meja'); //http://localhost/ci-restserver/index.php/meja/logout?no_meja=7
        $no_meja = $this->put('no_meja');
        
        $this->db->where('no_meja',$no_meja);
        $berhasil = $this->db->update('akun_meja',array('status'=>0));
        if($berhasil){
            $this->response('Update data '.$no_meja.' berhasil',200);
        }else{
            $this->response(array('status'=>'fail', 502));
        }
    }
    
    function onservice_put(){
        // $data = $this->uri->uri_to_assoc();  //http://localhost/ci-restserver/index.php/meja/onservices/no_meja/7
        // $no_meja = $data['no_meja'];
        //$no_meja =  $this->input->get('no_meja'); //http://localhost/ci-restserver/index.php/meja/onservices?no_meja=7
        $no_meja = $this->put('no_meja');

        $this->db->where('no_meja',$no_meja);
        $berhasil = $this->db->update('meja',array('status'=>1));
        if($berhasil){
            $this->response('Update data '.$no_meja.' berhasil',200);
        }else{
            $this->response(array('status'=>'fail', 502));
        }
    }
    
    function outofservice_put(){
        // $data = $this->uri->uri_to_assoc();  //http://localhost/ci-restserver/index.php/meja/outofservice/no_meja/7
        // $no_meja = $data['no_meja'];
        //$no_meja =  $this->input->get('no_meja'); //http://localhost/ci-restserver/index.php/meja/outofservice?no_meja=7
        $no_meja = $this->put('no_meja');

        $this->db->where('no_meja',$no_meja);
        $berhasil = $this->db->update('meja',array('status'=>0));
        if($berhasil){
            $this->response('Update data '.$no_meja.' berhasil',200);
        }else{
            $this->response(array('status'=>'fail', 502));
        }
    }
}

