<?php

class Bahan
{
    public $id_bahan;
    public $nama_bahan;
    public $jumlah_bahan;
		
    function __construct($id_bahan, $nama_bahan, $jumlah_bahan){
	$this->id_bahan = $id_bahan;
	$this->nama_bahan = $nama_bahan;
	$this->jumlah_bahan = $jumlah_bahan;
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
    public $listpesanan = array();

    function __construct($meja, $tanggal, $no_nota, $listpesanan){
	$this->meja = $meja;
	$this->tanggal = $tanggal;
	$this->no_nota = $no_nota;
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

