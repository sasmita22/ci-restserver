<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Pesanan extends REST_Controller {
	function index_get(){
		
		$meja = $this->get('meja');
		$pesanan = array();
		$query = $this->db->query("SELECT c.kode_pesanan,c.status FROM meja a, nota b, koki c WHERE a.no_meja = '$meja' AND a.no_meja=b.no_meja AND b.no_nota = c.no_nota");
		$kode_pesanan = array();

		foreach ($query->result_array() as $data){
			$query2 = $this->db->query("SELECT id_makanan,qty FROM rincian_koki WHERE kode_pesanan = '".$data['kode_pesanan']."'");
			$listmakanan = array();

			foreach ($query2->result_array() as $data1){
				$listmakanan[] = $data1;

			}
 
			$pesanan[] = $listmakanan;
		}

		echo $meja;
		if (count($pesanan)==0){
				$this->response(array('status'=>'data kosong',502));
		}else {

			$xpesanan = $pesanan[0];

			if (count($pesanan) > 1){
					
					//$arraymakanan = array();

				$cpesanan = count($pesanan);
				for ($i = 1; $i < $cpesanan;$i++){ //perulangan banyak pemesanan
					$npesanan = $pesanan[$i];

					$arraymakanan = array();
					$cnpesanan = count($npesanan);
					for ($x = 0; $x < $cnpesanan; $x++){ //perulangan ke-2 atau lebih pesanan
						$sama = false;
							
						$cxpesanan = count($xpesanan);
						for($y = 0;$y < $cxpesanan;$y++){ //perulangan pesanan pertama
							if ($xpesanan[$y]['id_makanan'] == $npesanan[$x]['id_makanan']){
								$xpesanan[$y]['qty'] += $npesanan[$x]['qty'];
								$sama = true;
							} 
						}
						if(!$sama){
							$arraymakanan[] = $npesanan[$x];
						}

					}

					for($s = 0; $s < count($arraymakanan);$s++){
						$xpesanan[] = $arraymakanan[$s];
					}
				}

			}

			$this->response($xpesanan);
		}
	}

	

}