<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require 'Kelas.php';
use Restserver\Libraries\REST_Controller;

class Koki extends REST_Controller {
    
    function index_get(){
        $query = $this->db->query("SELECT a.kode_pesanan, b.no_meja, a.tanggal, a.catatan FROM koki a, nota b where a.status = 0 AND a.no_nota = b.no_nota");
        
        $koki = array();
        foreach ($query->result() as $row){
            
            $query1 = $this->db->query("SELECT b.id_makanan, c.nama, b.qty FROM koki a, rincian_koki b, makanan c where a.kode_pesanan = '".$row->kode_pesanan."' "
                    . "AND a.kode_pesanan = b.kode_pesanan AND b.id_makanan = c.id_makanan");
            
            $makanan = array();
            foreach ($query1->result() as $row1){
                
                $query2 = $this->db->query("SELECT b.id_bahan, c.nama_bahan, b.qty FROM makanan a, rincian_bahan b, bahan c "
                        . "where a.id_makanan = '".$row1->id_makanan."' AND a.id_makanan = b.id_makanan AND b.id_bahan = c.id_bahan");
                $bahan = array();
                
                foreach ($query2->result() as $row2){
                    $bahan[] = new Bahan($row2->id_bahan,$row2->nama_bahan,$row2->qty);
                }
                $makanan[] = new Makanan_Koki($row1->id_makanan,$row1->nama,$row1->qty,$bahan);
            }
            $koki[] = new Chef($row->kode_pesanan,$row->no_meja,$row->tanggal,$row->catatan,$makanan);
        } 
        if(count($koki) != 0){
            $this->response($koki,200);
        }else{
            $this->response(array('status' => 'kosong'), 502);
        }
    }

    function konfirmasi_put(){
        // $data = $this->uri->uri_to_assoc();  //http://localhost/ci-restserver/index.php/Koki/konfirmasi
        // $pesanan = $data['pesanan'];

        $data = $this->put();
        $query = $this->db->query("update koki set status = 1 where kode_pesanan = '".$data['pesanan']."' ");
        
        
        if($this->db->affected_rows() == 0){
            $this->response('0 affected rows');
        }else if($query){
            $this->response('Confirmed',200);
        }else{
            $this->response(array('status' => 'fail', 502));
        }  
    }
}
