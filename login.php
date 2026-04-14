<?php
$conn = new mysqli("localhost","root","","scanner");

$user = $_POST['user'];
$pass = md5($_POST['pass']);

$res = $conn->query("SELECT * FROM users WHERE user='$user' AND pass='$pass'");

if($res->num_rows > 0){
$row = $res->fetch_assoc();
echo md5($user.time())."|".$row['role']."|".$row['user']."|".$row['gudang'];
}else{
echo "FAIL";
}
?>