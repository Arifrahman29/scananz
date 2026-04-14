<?php
$conn = new mysqli("localhost","root","","scanner");

$id = $_POST['id'];
$kode = $_POST['kode'];
$judul = $_POST['judul'];
$tipe = $_POST['tipe'];
$qty = intval($_POST['qty']);
$gudang = $_POST['gudang'];

$kilo = floatval($kode);
$tanggal = date("Y-m-d");

if(!$kode) exit;

$conn->query("INSERT INTO data_scan (scan_id,kode,judul,tipe,qty,gudang,kilo)
VALUES ('$id','$kode','$judul','$tipe','$qty','$gudang','$kilo')");

if($tipe=="masuk"){
$conn->query("INSERT INTO histori_masuk (kode,judul,tanggal,total_kilo,total_box,gudang)
VALUES ('$kode','$judul','$tanggal','$kilo','$qty','$gudang')");
}else{
$conn->query("INSERT INTO histori_keluar (kode,judul,tanggal,total_kilo,total_box,gudang)
VALUES ('$kode','$judul','$tanggal','$kilo','$qty','$gudang')");
}

$res = $conn->query("SELECT * FROM stok WHERE kode='$kode' AND gudang='$gudang'");

if($res->num_rows > 0){
$row = $res->fetch_assoc();
$k = $row['total_kilo'];
$b = $row['total_box'];

if($tipe=="masuk"){
$k += $kilo;
$b += $qty;
}else{
$k -= $kilo;
$b -= $qty;
}

$conn->query("UPDATE stok SET total_kilo='$k', total_box='$b' WHERE kode='$kode' AND gudang='$gudang'");
}else{
if($tipe=="masuk"){
$k = $kilo;
$b = $qty;
}else{
$k = -$kilo;
$b = -$qty;
}

$conn->query("INSERT INTO stok (kode,gudang,total_kilo,total_box)
VALUES ('$kode','$gudang','$k','$b')");
}

echo "OK";
?>