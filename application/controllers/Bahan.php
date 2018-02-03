<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require 'Kelas.php';
use Restserver\Libraries\REST_Controller;

class Bahan extends REST_Controller {

	public function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

	function index_get(){
		$query = $this->db->query("SELECT * FROM bahan");
        
        $bahan = array();
        foreach ($query->result() as $row) {
        	date_default_timezone_set('Asia/Jakarta');
        	if(date('Y-m-d') > $row->tgl_kadaluarsa){
                $status = 'Kadaluarsa';
            }else{
            	$status = 'tersedia';
            }
        	$bahan[] = new BahanClass($row->id_bahan,$row->nama_bahan,$row->tgl_kadaluarsa,$row->stok,$status);
        }

        if(count($bahan) != 0){
	        $this->response($bahan,200);
	    }else{
	        $this->response(array('status' => 'kosong'), 502);
	    }
	}

	function index_post(){
		$bahan = array(
			"id_bahan" => $this->post('id_bahan'),		
			"nama_bahan" => $this->post('nama_bahan'),		
			"tgl_kadaluarsa" => $this->post('tgl_kadaluarsa'),			
			"stok" => $this->post('stok')					
		);

		$insert = $this->db->insert('bahan',$bahan);
        
        if($insert){
            $this->response('Berhasil Menambah bahan',200);
        }else{
            $this->response(array('status' => 'fail', 502));
        }   
	}

	function index_put(){
		$id = $this->put('id_bahan');
		$bahan = array(			
			"nama_bahan" => $this->put('nama_bahan'),			
			"tgl_kadaluarsa" => $this->put('tgl_kadaluarsa'),		
			"stok" => $this->put('stok') 				
		);

		$this->db->where('id_bahan',$id);
        $update = $this->db->update('bahan',$bahan);
        if ($this->db->affected_rows() > 0){
            $this->response('Update data dengan id : '.$id.' berhasil',200);
        }else{
            $this->response(array('status'=>'fail', 502));
        }
	}

	function index_delete($id){

		$this->db->where('id_bahan',$id);
        $delete = $this->db->delete('bahan');

        if ($this->db->affected_rows() > 0) {
            $this->response('Delete data dengan id : '.$id.' berhasil',200);
        }else{
            $this->response(array('status'=>'fail', 502));
        }
	}

	
}