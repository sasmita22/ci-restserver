<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Makanan extends REST_Controller {
    public function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }
    
    function list_get(){
        $id = $this->get('id');

        if ($id == ''){
            $makanan = $this->db->get('makanan')->result();
        } else {
            $this->db->where('id_makanan',$id);
            $makanan = $this->db->get('makanan')->result();
        }
        $this->response($makanan,200);
    }
    
    function index_post(){
        $makanan = array(
                    'id_makanan' => $this->post('id_makanan'),
                    'nama' => $this->post('nama'),
                    'jenis' => $this->post('jenis'),
                    'tag' => $this->post('tag'),
                    'deskripsi' => $this->post('deskripsi'),
                    'harga_beli' => $this->post('harga_beli'),
                    'harga_jual' => $this->post('harga_jual'),
                    'path' => $this->post('path'));
        
        $insert = $this->db->insert('makanan',$makanan);
        
        if($insert){
            $this->response('Berhasil Menambah makanan',200);
        }else{
            $this->response(array('status' => 'fail', 502));
        }     
    }
    
    function index_put(){
        $id = $this->put('id_makanan');
        
        $makanan = array(
            'id_makanan' => $this->put('id_makanan'),
            'nama' => $this->put('nama'),
            'jenis' => $this->put('jenis'),
            'tag' => $this->put('tag'),
            'deskripsi' => $this->put('deskripsi'),
            'harga_beli' => $this->put('harga_beli'),
            'harga_jual' => $this->put('harga_jual'),
            'path' => $this->put('path')
        );
        
        $this->db->where('id_makanan',$id);
        $update = $this->db->update('makanan',$makanan);
        if ($update){
            $this->response('Update data dengan id : '.$id.' berhasil',200);
        }else{
            $this->response(array('status'=>'fail', 502));
        }
    }
}

