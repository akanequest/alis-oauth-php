<?php
session_start();
require('./alis-api.php');
try{
    getToken_andSet();
    header('Location: ./sample.php');
    exit;
}catch (Exception $e){
    echo $e->getMessage();
}

?>