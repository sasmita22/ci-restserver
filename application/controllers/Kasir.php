<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require 'Kelas.php';
use Restserver\Libraries\REST_Controller;

class Kasir extends REST_Controller {
    
    function index_get(){
        
        $query = $this->db->query("SELECT a.no_meja, b.tanggal, b.no_nota FROM meja a, nota b, rincian_kasir c, makanan d"
                . " where b.status = 1 AND a.no_meja = b.no_meja AND b.no_nota = c.no_nota AND c.id_makanan = d.id_makanan group by a.no_meja");
        
        $kasir = array();
        foreach ($query->result() as $row){
            
            $query1 = $this->db->query("SELECT d.id_makanan, d.nama, c.qty, d.harga FROM meja a, nota b, rincian_kasir c, makanan d"
                    . " where a.no_meja = '". $row->no_meja."' AND a.no_meja = b.no_meja "
                    . "AND b.no_nota = c.no_nota AND c.id_makanan = d.id_makanan");
            
            $makanan = array();
            $total = 0;
            foreach ($query1->result() as $row1){

                $makanan[] = new Makanan_Kasir($row1->id_makanan,$row1->nama,$row1->qty,$row1->harga);
                $total = $total + ($row1->qty*$row1->harga);
            }
            $kasir[] = new Cashier($row->no_meja,$row->tanggal,$row->no_nota,$total,$makanan);
        } 
        
        if(count($kasir) != 0){
            $this->response($kasir,200);
        }else{
            $this->response(array('status' => 'kosong'), 502);
        }
    }
    
    function konfirmasi_put(){
        //$data = $this->uri->uri_to_assoc();  //http://localhost/ci-restserver/index.php/Kasir/konfirmasi/no_nota/7

        $nota = $this->put('no_nota');
        $query = $this->db->query("update nota set status = 2 where no_nota = '$nota' and no_nota in (select DISTINCT no_nota from rincian_kasir)");
        
        
        if($this->db->affected_rows() == 0){
            $this->response('0 affected rows');
        }else if($query){
            $this->response('Confirmed',200);
        }else{
            $this->response(array('status' => 'fail', 502));
        }  
    }

    function totalbayar_get(){
        $no_meja = $this->get('no_meja');
        $totalbayar = 0;
        $query = $this->db->query("SELECT a.qty,b.harga from rincian_kasir a, makanan b, nota c where c.no_meja = '$no_meja' and a.id_makanan = b.id_makanan and a.no_nota = c.no_nota");

        foreach ($query->result_array() as $data){
            $totalbayar = $totalbayar + ($data['harga']*$data['qty']);
        }

        $this->response($totalbayar);
    }

    function daftarmeja_get(){
        
        $query = $this->db->query("SELECT * from meja order by no_meja asc");

        $meja = array();
        foreach ($query->result_array() as $data){
            $tanggal = null;
            $no_nota  = 0;
            $query1 = $this->db->query("SELECT * from nota where no_meja = ".$data['no_meja']." and status = 1"); 

            $totalbayar = 0;
            $listpesanan = array();
            foreach ($query1->result_array() as $data1) {

                $query2 = $this->db->query("SELECT a.id_makanan,b.nama,a.qty,b.harga from rincian_kasir a, makanan b, nota c where c.no_meja = ".$data1['no_meja']." and a.id_makanan = b.id_makanan and a.no_nota = c.no_nota");
                
                foreach ($query2->result_array() as $data2){
                    $totalbayar = $totalbayar + ($data2['harga']*$data2['qty']);
                    $listpesanan[] = array('id_makanan'=>$data2['id_makanan'],'nama'=>$data2['nama'],'qty'=>$data2['qty'],'harga'=>$data2['harga']);
                }
                $tanggal = $data1['tanggal'];
                $no_nota = $data1['no_nota'];
            }       
            //$meja[] = new Meja($data['no_meja'],$data['status'],$totalbayar,$listpesanan);   
            $meja[] = array("no_meja" => $data['no_meja'], "no_nota" => $no_nota,"status" => $data['status'],"tanggal" => date('d-m-Y',strtotime($tanggal)),"waktu"=>date('H:i:s',strtotime($tanggal)),"total" => $totalbayar, "listpesanan" => $listpesanan);   
        }

        $this->response($meja);
    }

    
}