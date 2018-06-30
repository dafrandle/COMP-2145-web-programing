<?php
session_start();
$_SESSION['user'] = "";
$_SESSION['userEmail'] = "";
?>
<!DOCTYPE html>
<!--
    Created on : Oct 11, 2017, 5:49:08 PM
    Author     : Dustin Frandle
-->
<html>
    <head>
        <title>Login/Register</title>
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
        //database information
        $dbHost = 'localhost';
        $dbUsername = 'root';
        $dbPass = 'mysql';
        //connect database server
        try {
            $loginConn = new PDO("mysql:host=$dbHost", $dbUsername, $dbPass);
            $loginConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo("<script>doLog('Database server connected successfully.', false)</script>");
        } catch (Exception $ex) {
            $error = preg_replace('/\s+/', ' ', htmlspecialchars($ex->getMessage()));
            echo("<script>doLog('Database server failed to connect.', true)</script>");
            echo("<script>doLog('" . $error . "', true)</script>");
            echo("<script>doAlert('Failed to connect to account server. Please try again later.')</script>");
        }
        //build databse and tables
        $loginConn->exec("CREATE DATABASE IF NOT EXISTS phpFinal;");
        $loginConn->exec("USE phpFinal;");
        $tableQuery = "CREATE TABLE IF NOT EXISTS users (
                        accountID int(11) NOT NULL AUTO_INCREMENT,
                        username varchar(25) NOT NULL,
                        email varchar(50) NOT NULL,
                        password varchar(100) NOT NULL,
                        hash varchar(100) NOT NULL,
                        PRIMARY KEY (accountID));
                       CREATE TABLE IF NOT EXISTS games (
                        gameID int(11) NOT NULL AUTO_INCREMENT,
                        Title varchar(35) NOT NULL,
                        Publisher varchar(35) NOT NULL,
                        Cost varchar(8) NOT NULL,
                        PRIMARY KEY (gameID)
                        );";
        $loginConn->exec($tableQuery);
//INSERT INTO `phpFinal`.`games` (`gameID`, `Title`, `Publisher`, `Cost`) VALUES 
//(NULL, 'Counter-Strike: Global Offensive', 'Valve Corporation', '14.99'), 
//(NULL, 'Grand Theft Auto V', 'Rockstar Games', '59.99'),
//(NULL, 'Titanfall 2', 'Electronic Arts', '39.99'),
//(NULL, 'Starcraft II', 'Blizzard Entertainment', '39.99'),
//(NULL, 'Far Cry 3', 'Ubisoft', '19.99');
//        //end setup
        //--------------------------------------------SESSION FLAGS
        if (array_key_exists('submitFlag', $_POST)) {
            $submitFlag = $_POST['submitFlag'];
            switch ($submitFlag) {
                case 1:
                    tryLogin();
                    break;
                case 2:
                    register();
                default :
                    echo("<script>doAlert('This Form has encounterd an error, please contact support')</script>");
            }
        }

        //--------------------------------------------SESSION FLAGS
        function tryLogin() {
            global $loginConn;
            $correctInfo = false;
            $username = $_POST["loginUsername"];
            $password = $_POST["loginPass"];
            $sqlQurry = "SELECT COUNT(username) FROM users WHERE username='" . $username . "' AND password='" . $password . "';";
            $email = "";
            $result = $loginConn->query($sqlQurry);
            foreach ($result as $row) {
                if ($row[0] == 0) {
                    echo("<script>doAlert('Invalid password or username')</script>");
                    $correctInfo = false;
                    break;
                }
                if ($row[0] == 1) {
                    $correctInfo = true;
                    $mailRes = $loginConn->query("SELECT email FROM users WHERE username='" . $username . "' AND password='" . $password . "';");
                    foreach ($mailRes as $row) {
                        foreach ($row as $value) {
                            $email = $value;
                        }
                    }
                    break;
                }
                if ($row[0] > 1) {
                    echo("<script>doAlert('Error multiple users with the same username, please contact support ')</script>");
                    $correctInfo = false;
                    break;
                }
            }
            if ($correctInfo) {
                $_SESSION['login'] = true;
                $_SESSION['user'] = $username;
                $_SESSION['userEmail'] = $email;
                $loginConn = null;
                header("Location: http://localhost/PhpFinal/accntPage.php");
            }
        }
        
        function register(){
            global $loginConn;
            $username = $_POST["loginNewUsername"];
            $pass = $_POST["loginNewPass"];
            $email = $_POST["loginNewEmail"];
            $sql = "INSERT INTO `users` (`accountID`, `username`, `email`, `password`, `hash`) VALUES (NULL, '". $username ."','". $email ."','". $pass ."', '')";
            $loginConn ->exec($sql);
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

        <div class="container-fluid text-center" id="body">
            <div id="login" class="container-fluid">
                <div class="row ">
                    <ul class="nav col loginOpt pull-left">
                        <li id="loginBtn" class="active loginOptBtn"><a>Login</a></li>
                    </ul>
                    <ul class="nav col loginOpt pull-right">
                        <li id="regBtn" class="loginOptBtn"><a>Register</a></li>
                    </ul>
                </div>
                <div class="row">
                    <iframe id="loginIFrame" srcdoc='<html>
                            <head>
                            <title>Login/Register</title>
                            <meta charset="utf-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
                            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                            <link rel="stylesheet" type="text/css" href="final.css" />
                            <script src="final.js"></script>
                            </head>
                            <body>
                            <div id="loginModal" class="container-fluid text-center">
                            <form action="index.php" method="post" name="login" target="_top">
                            <label class="pull-left loginLable">Username:</label>
                            <input type="text" class="loginTxt pull-right" name="loginUsername" id="loginUsername" value="" />
                            <br /><br />
                            <label class="pull-left loginLable">Password:</label>
                            <input type="text" class="loginTxt pull-right" name="loginPass" id="loginPass" value=""/>
                            <br /><br />
                            <input type="hidden" name="submitFlag" value="1">
                            <input type="submit" name="loginBtn" class="btn btn-default" value="Login">
                            </form>
                            </div>
                            </body>
                            </html>' src="demo_iframe.htm"></iframe>
                </div>
            </div>
        </div>


        <footer id="footer" class="container-fluid text-center footer navbar-fixed-bottom">
            <p class="pull-left">This page is not copyrighted.</p><p class="pull-right">Version: 0.1</p>
        </footer>



    </body>
</html>