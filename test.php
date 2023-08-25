<?php 
if (!isset($_SESSION)) session_start ();

$connect = new PDO("mysql:host=localhost;dbname=pic_comments_db", "sqluser", "password");

$received_data = json_decode(file_get_contents("php://input"));
$dt=$received_data->captchaInput;

if($_SESSION['code']==$dt){
echo json_encode($dt);}
else {
    echo json_encode($data="error");}
?>



