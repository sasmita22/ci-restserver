<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require 'Kelas.php';
use Restserver\Libraries\REST_Controller;

class Makanan extends REST_Controller {
    public function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }
    
    function index_get(){
        $query = $this->db->query("SELECT * FROM makanan");
        
        $makanan = array();
        foreach ($query->result() as $row){
            
            $query1 = $this->db->query("SELECT id_bahan,qty FROM rincian_bahan where id_makanan = '" .$row->id_makanan."'");
            
            $bahan = array();
            foreach ($query1->result() as $row1){
                
                $bahan[] = new DetailBahan($row1->id_bahan,$row1->qty);
            }
            $makanan[] = new MakananTemp($row->id_makanan,$row->nama,$row->jenis,$row->tag,$row->deskripsi,$row->harga,$row->path,$bahan);
        } 

        if(count($makanan) != 0){
            $this->response($makanan,200);
        }else{
            $this->response(array('status' => 'kosong'), 502);
        }
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

