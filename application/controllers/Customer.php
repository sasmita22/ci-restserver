<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require 'Kelas.php';
use Restserver\Libraries\REST_Controller;

class Customer extends REST_Controller {
    
    function pesan_post(){
        $data = $this->uri->uri_to_assoc();  //http://localhost/ci-restserver/index.php/Customer/pesan/pemesanan/1
        $pemesanan = $data['pemesanan'];
        
        $pesanan = $this->post();
        $berhasil = true;
        $error = 0;

        $this->db->trans_begin();
        
        if ($pemesanan == 1){
            $berhasil = false;

            $query = $this->db->query("update meja set status=1 where no_meja='".$pesanan['meja']."'");

            if ($query){
	           $this->response('Suksek ganti status meja',200);
            

    		    $query = $this->db->query("insert into nota(no_nota,tanggal,no_meja) values('".$pesanan['no_nota']."','".$pesanan['tanggal']."','".$pesanan['meja']."')");

    		    if ($query){
                    $this->response('Sukses membuat row nota',200);
                    $berhasil = true;
                } else {
                    $this->response(array('status' => 'fail'), 502);
    		    }
            }    
	
    	} 
        
        if ($berhasil){
            $listmakanan = $pesanan['pesanan'];

            $query = $this->db->query("insert into koki(no_nota,tanggal,catatan,status) values('".$pesanan['no_nota']."','".$pesanan['tanggal']."','".$pesanan['catatan']."', 0)");

            if ($query){
		        $this->response('Sukses koki',200);

		        $query1 = $this->db->query("SELECT max(kode_pesanan) as kode_pesanan FROM koki");

		        foreach ($query1->result() as $row){
                    $kode_pesanan = $row->kode_pesanan;
		        }

		        for ($i=0; $i < count($listmakanan); $i++) { 
                    $query2 = $this->db->query("insert into rincian_koki values('".$kode_pesanan."','".$listmakanan[$i]['id_makanan']."','".$listmakanan[$i]['qty']."')");

                    if ($query2){
			            $this->response('Sukses rincian',200);
                    } else {
                        $error++;
                    }
								
		        }
            } else {
                $this->response(array('status' => $this->db->_error_message()), 502);
                $error++;
            }
				
        }

        if ($berhasil and ($error == 0)){
            $this->db->trans_commit();
        }else {
            $this->db->trans_rollback();
        }
    }
    
    function askbill_put(){
        /*$data = $this->uri->uri_to_assoc();  //http://localhost/ci-restserver/index.php/Customer/askbill/no_nota/7
        $nota = $data['no_nota'];*/
        
        $data = $this->put('no_nota');
        

        //$query = $this->db->query("update nota set status = 1 where no_nota = '$data' and no_nota in (select DISTINCT no_nota from koki)");
        $query = $this->db->query("update nota set status = 1 where no_nota = '$data'");


        if($this->db->affected_rows() > 0){
            $this->response('Confirmed',200);
        }else{
            $this->response(array('status' => $query, 502));
        } 
    }
    
    function feedback_put(){
        $feedback = $this->put('feedback');
        $no_nota = $this->put('no_nota');

        $this->db->set('feedback', $feedback);
        $this->db->where('no_nota', $no_nota);
        $this->db->update('nota');

        if($this->db->affected_rows() == 0){
            $this->response('0 affected rows');
        }else if($this->db->affected_rows() > 0){
            $this->response('Confirmed',200);
        } else{
            $this->response(array('status' => 'fail', 502));
        } 
    }
    
    function nota_get(){
        $this->db->select_max('no_nota');
        $result = $this->db->get('nota'); 
        $nota = $result->row()->no_nota+1;
        $response = array("no_nota"=>$nota);
        $this->response($response);   
    }

}