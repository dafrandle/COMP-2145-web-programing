<?php 
session_start();
if(!$_SESSION['login']){
   header("location:index.php");
   die;
}
$user = $_SESSION['user'];
$userEmail = $_SESSION['userEmail'];
?>
<!DOCTYPE html>
<!--
    Created on : Oct 11, 2017, 7:59:08 PM
    Author     : Dustin Frandle
-->
<html>
    <head>
        <title>Your Account</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap CSS --><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- jQuery --><script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <!-- Bootstrap JS --><script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- CSS --><link rel="stylesheet" type="text/css" href="final.css" />
        <!-- Java Script --><script src="final.js"></script>
    </head>
    <body>
        <?php
        $self = $_SERVER["PHP_SELF"];
        require 'conn.php';
        
        if(($_GET['logout'])){
            session_destroy();
            header("Location: http://localhost/PhpFinal/index.php");
        }
        ?>
        
        
         <nav class="navbar" id="header">
            <div class="container-fluid">
                <div class="navbar-header" style="width: 100%" class="card">
                    <img src="logo.png" alt="Logo" class="text-center pull-left" style="padding-right: 5%; padding-left: 5%"><h1 class="text-center pull-left">Software Incorporated</h1>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                </div>
            </div>
        </nav>
        <div class="navbar" id="navagation">
            <div class="container-fluid">
                <div id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li><a class="active" href=""><?php echo $user; ?></a></li>
                        <li><a href="inventory.php">Inventory</a></li>
                        <li ><a href="userMng.php">User Management</a></li>
                        <li><a href="?logout=true">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container-fluid text-center" id="body">
            <h1><?php echo "Hello " . $user ?></h1>
            <hr/>
            <h4>You have no new messages.</h4>
            <hr/>
            <h4>Account Details</h4>
            <br/>
            <h4><?php echo "Username: " . $user ?></h4>
            <br/>
            <h4><?php echo "Email: " . $userEmail ?></h4>
        </div>

        <footer id="footer" class="container-fluid text-center footer navbar-fixed-bottom">
            <p class="pull-left">This page is not copyrighted.</p><p class="pull-right">Version: 0.1</p>
        </footer>
        
        
    </body>
</html>