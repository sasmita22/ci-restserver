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
    //android version 1.0
    // function index_get(){
    //     $query = $this->db->query("SELECT * FROM makanan");
        
    //     $makanan = array();
    //     foreach ($query->result() as $row){
            
    //         $query1 = $this->db->query("SELECT id_bahan,qty FROM rincian_bahan where id_makanan = '" .$row->id_makanan."'");
            
    //         $bahan = array();
    //         foreach ($query1->result() as $row1){
                
    //             $bahan[] = new DetailBahan($row1->id_bahan,$row1->qty);
    //         }
    //         $makanan[] = new MakananTemp($row->id_makanan,$row->nama,$row->jenis,$row->tag,$row->deskripsi,$row->harga,$row->path,$bahan);
    //     } 

    //     if(count($makanan) != 0){
    //         $this->response($makanan,200);
    //     }else{
    //         $this->response(array('status' => 'kosong'), 502);
    //     }
    // }
    
    function index_get(){
        $query = $this->db->query("SELECT * FROM makanan");
        
        $makanan = array();
        foreach ($query->result() as $row){
            
            $query1 = $this->db->query("SELECT a.id_bahan,b.nama_bahan,a.qty,b.tgl_kadaluarsa,b.stok FROM rincian_bahan a, bahan b where a.id_makanan = '" .$row->id_makanan."' and a.id_bahan = b.id_bahan");
            
            $bahan = array();
            foreach ($query1->result() as $row1){
                date_default_timezone_set('Asia/Jakarta');
                if(date('Y-m-d') > $row1->tgl_kadaluarsa){
                    $status = 'Kadaluarsa';
                }else if($row1->qty > $row1->stok){
                    $status = 'Stok Tidak Cukup';
                }else{
                    $status = 'Tersedia';
                }
                $bahan[] = new DetailBahan($row1->id_bahan,$row1->nama_bahan,$row1->qty,$row1->stok,$row1->tgl_kadaluarsa,$status);
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
        $error = 0;
        $this->db->trans_begin();
        $makanan = array(
                    'id_makanan' => '',
                    'nama' => $this->post('nama'),
                    'jenis' => $this->post('jenis'),
                    'tag' => $this->post('tag'),
                    'deskripsi' => $this->post('deskripsi'),
                    'harga' => $this->post('harga'),
                    'path' => $this->post('path'));

        
        $listbahan = $this->post('bahan');

        


        
        $insert = $this->db->insert('makanan',$makanan);

        if(!$insert){
            $error++;
        }else{
            $this->db->select_max('id_makanan');
            $getId = $this->db->get('makanan'); 
            $getId = $getId->result();
            $id = $getId[0]->id_makanan;
        }

        foreach ($listbahan as $row) {
            $rincian_bahan = array();
            $rincian_bahan = array(
                "id_makanan" => $id,
                "id_bahan"=>$row['id_bahan'],
                "qty"=>$row['qty']);
            $query = $this->db->insert('rincian_bahan',$rincian_bahan);

            if(!$query){
                $error++;
            }
        }
        
        if($error==0){
            $this->db->trans_commit();
            $this->response('Berhasil Menambah makanan',200);
        }else{
            $this->db->trans_rollback();
            $this->response(array('status' => 'fail', 502));
        }     
    }
    
    function index_put(){
        $id = $this->put('id_makanan');
        $makanan = array(
            'nama' => $this->put('nama'),
            'jenis' => $this->put('jenis'),
            'tag' => $this->put('tag'),
            'deskripsi' => $this->put('deskripsi'),
            'harga' => $this->put('harga'),
            'path' => $this->put('path')
        );
        
        $this->db->where('id_makanan',$id);
        $update = $this->db->update('makanan',$makanan);
        if ($this->db->affected_rows() > 0){
            $this->response('Update data dengan id : '.$id.' berhasil',200);
        }else{
            $this->response(array('status'=>'fail', 502));
        }
    }

    function index_delete($id){
        $error = 0;
        $this->db->trans_begin();
        $id1 = (int) $id;

        $this->db->where('id_makanan',$id1);
        $delete = $this->db->delete('rincian_bahan');

        $this->db->where('id_makanan',$id1);
        $delete1 = $this->db->delete('makanan'); 
        if($this->db->affected_rows() == 0){
            $error++;
        }
           
        if ($error == 0) {
            $this->db->trans_commit();
            $this->response(array("status"=>"success"),200);
        }else{
            $this->db->trans_rollback();
            $this->response(array('status'=>'fail', 502));
        }
    }
}

