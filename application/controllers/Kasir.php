<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require 'Kelas.php';
use Restserver\Libraries\REST_Controller;

class Kasir extends REST_Controller {
    
    function index_get(){
        $query = $this->db->query("SELECT a.no_meja, b.tanggal, b.no_nota FROM meja a, nota b, rincian_kasir c, makanan d"
                . " where a.status = 2 AND a.no_meja = b.no_meja AND b.no_nota = c.no_nota AND c.id_makanan = d.id_makanan group by a.no_meja");
        
        $kasir = array();
        foreach ($query->result() as $row){
            
            $query1 = $this->db->query("SELECT d.id_makanan, d.nama, c.qty, d.harga_jual FROM meja a, nota b, rincian_kasir c, makanan d"
                    . " where c.status = 0 AND a.no_meja = '". $row->no_meja."' AND a.no_meja = b.no_meja "
                    . "AND b.no_nota = c.no_nota AND c.id_makanan = d.id_makanan");
            
            $makanan = array();
            foreach ($query1->result() as $row1){

                $makanan[] = new Makanan_Kasir($row1->id_makanan,$row1->nama,$row1->qty,$row1->harga);
            }
            $kasir[] = new Cashier($row->no_meja,$row->tanggal,$row->no_nota,$makanan);
        } 
        
        if(count($kasir) != 0){
            $this->response($kasir,200);
        }else{
            $this->response(array('status' => 'kosong'), 502);
        }
    }
    
    function konfirmasi_put(){
        $data = $this->uri->uri_to_assoc();  //http://localhost/ci-restserver/index.php/Kasir/konfirmasi/no_nota/7
        $nota = $data['no_nota'];
        $query = $this->db->query("update nota set status = 2 where no_nota = '$nota' and no_nota in (select DISTINCT no_nota from rincian_kasir)");
        
        
        if($this->db->affected_rows() == 0){
            $this->response('0 affected rows');
        }else if($query){
            $this->response('Confirmed',200);
        }else{
            $this->response(array('status' => 'fail', 502));
        }  
    }
}