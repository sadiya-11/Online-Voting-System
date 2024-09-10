<?php
session_start();
require_once ("../admin/incc/config.php");

if ($_SESSION['key'] != "VotersKey") {
    echo "<script> location.assign('../admin/incc/logout.php'); </script>";
    die;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voters Panel-Online Voting System</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-dark">

    <div class="container-fluid">
        <div class="row bg-black text-white">
            <div class="col-1">
            <img src="../assets/img/logo.gif" width="150px" />
            </div>
            <div class="col-11 my-auto">
                <h3>ONLINE VOTING SYSTEM - <small> Welcome <?php echo $_SESSION['username']; ?></small></h3>
            </div>
        </div>
   
