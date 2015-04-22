<?php
session_start();
$con = mysqli_connect('localhost','root','','Eatit');

if (mysqli_connect_errno()){
    die('Could not make connection with the database!');
}

if(!isset($_GET['p']) && !isset($_GET['a'])){
    include('inc/template/header.php');
    include('inc/template/body.php');
    include('inc/template/footer.php');
}

if (isset($_GET['p'])) {
    include('inc/template/header.php');
    $dir = 'inc/template/' .$_GET['p']. '.php';
    if(is_file($dir)) {
        include($dir);
    }else{
        include('inc/template/body.php');
    }
    include('inc/template/footer.php');
}
if (isset($_GET['a'])) {
    $dir = 'inc/class/' . $_GET['a'] . '.class.php';
    if (is_file($dir)) {
        include($dir);
    }
}

mysqli_close($con);
?>