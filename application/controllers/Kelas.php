<?php

class BahanClass
{
    public $id_bahan;
    public $nama_bahan;
    public $tgl_kadaluarsa;
    public $stok;
    public $status;
		
    function __construct($id_bahan, $nama_bahan, $tgl_kadaluarsa, $stok, $status){
    	$this->id_bahan = $id_bahan;
    	$this->nama_bahan = $nama_bahan;
        $this->tgl_kadaluarsa = $tgl_kadaluarsa;
    	$this->stok = $stok;
        $this->status = $status;
    }
}


class MakananTemp {
    public $id_makanan;
    public $nama;
    public $jenis;
    public $tag;
    public $deskripsi;
    public $harga;
    public $path;
    public $bahan = array();

    function __construct($id_makanan, $nama, $jenis, $tag , $deskripsi, $harga, $path, $bahan){
        $this->id_makanan = $id_makanan;
        $this->nama = $nama;
        $this->jenis = $jenis;
        $this->tag = $tag;
        $this->deskripsi = $deskripsi;
        $this->harga = $harga;
        $this->path = $path;
        $this->bahan = $bahan;
    }
}

class Meja {
    public $no_meja;
    public $status;
    public $total;
    public $listpesanan;

    function __construct($no_meja,$status,$total,$listpesanan){
        $this->no_meja = $no_meja;
        $this->status = $status;
        $this->total = $total;
        $this->listpesanan = $listpesanan; 
    }
}


class DetailBahan{
    public $id_bahan;
    public $nama;
    public $kadaluarsa;
    public $qty;
    public $stok;
    public $status;
        
    function __construct($id_bahan, $nama, $qty, $stok,$kadaluarsa, $status){
        $this->id_bahan = $id_bahan;
        $this->nama = $nama;
        $this->qty = $qty;
        $this->stok = $stok;
        $this->kadaluarsa = $kadaluarsa;
        $this->status = $status;
    }
}

class Makanan_Koki
{
    public $id_makanan;
    public $nama;
    public $qty;
    public $bahan = array();

    function __construct($id_makanan, $nama, $qty, $bahan){
        $this->id_makanan = $id_makanan;
        $this->nama = $nama;
        $this->qty = $qty;
        $this->bahan = $bahan;
    }
}

class Makanan_Kasir
{
    public $id_makanan;
    public $nama;
    public $qty;
    public $harga;

    function __construct($id_makanan, $nama, $qty, $harga){
	$this->id_makanan = $id_makanan;
	$this->nama = $nama;
	$this->qty = $qty;
	$this->harga = $harga;
    }
}

class Cashier
{
    public $meja;
    public $tanggal;
    public $no_nota;
    public $total;
    public $listpesanan = array();

    function __construct($meja, $tanggal, $no_nota, $total,$listpesanan){
	$this->meja = $meja;
	$this->tanggal = $tanggal;
	$this->no_nota = $no_nota;
    $this->total = $total;
	$this->listpesanan = $listpesanan;
    }
}

class Chef
{
    public $meja;
    public $kode_pesanan;
    public $tanggal;
    public $catatan;
    public $listmakanan = array();
		
    function __construct($kode_pesanan, $meja, $tanggal, $catatan, $listmakanan ){
	$this->meja = $meja;
	$this->kode_pesanan = $kode_pesanan;
	$this->tanggal = $tanggal;
	$this->catatan = $catatan;
	$this->listmakanan = $listmakanan;
    }
}

