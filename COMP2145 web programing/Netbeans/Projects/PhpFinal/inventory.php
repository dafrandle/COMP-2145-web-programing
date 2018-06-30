<?php 
session_start();
if(!$_SESSION['login']){
   header("location:index.php");
   die;
}
$user = $_SESSION['user'];
$searchData = array(array("", "", "", ""));
$_SESSION['searchDataArray'] = urlencode(serialize($searchData));
?>
<!DOCTYPE html>
<!--
    Created on : Oct 11, 2017, 7:59:08 PM
    Author     : Dustin Frandle
-->
<html>
    <head>
        <title>Inventory</title>
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
        if (array_key_exists('recordNum', $_POST)) {
            $recordNum = $_POST['recordNum'];
            if (array_key_exists('delete', $_POST)) {
                if ($_POST['delete'] == 1) {
                    echo("<script>doLog('delete = 1', false)</script>");
                    deleteRec($recordNum);
                } else {
                    echo("<script>doLog('delete != 1 ', false)</script>");
                    updateRecord($recordNum);
                }
            } else {
                echo("<script>doLog('Delete flag does not exist', true)</script>");
                echo("<script>doAlert('Unable to process request')</script>");
            }
        }
        if (array_key_exists('newGame', $_POST)){
            addNewRecord();
        }
        if (array_key_exists('search', $_POST)) {
            search();
        }
        
        
        if(($_GET['logout'])){
            session_destroy();
            header("Location: http://localhost/PhpFinal/index.php");
        }

        function buildTable() {
            //----------------------------------------------
            global $connection;
            $sql = "SELECT * FROM `games`"; //                   query
            $result = $connection->query($sql);
            //----------------------------------------------
            //----------------------------------------------------------------
            $data = array();
            $headings = array();
            foreach ($result as $key => $row) {
                $x = $key;
                $y = 0;
                $yMax = $result->columnCount();
                foreach ($row as $key => $value) {//            Get data
                    if (is_int($key)) {
                        $data[$x][$y] = $value;
                        $y++;
                    }
                }
            }
            for ($x = 0; $x < $result->columnCount(); $x++) {
                $temp = $result->getColumnMeta($x);
                $headings[$x] = $temp['name'];
            }
            //----------------------------------------------------------------
            //----------------------------------------------------------------
            echo "<tr>";
            foreach ($headings as $head) {
                echo ("<th>" . $head . "</th>");
            }
            echo "<th>Edit</th><th>Delete</th></tr>";
            $x = 0;
            foreach ($data as $row) {
                echo("<tr>"); //                             print table
                foreach ($row as $value) {
                    echo("<td>" . $value . "</td>");
                }
                echo('<td>'
                . '<button type="button" onclick="passData('.$x.')" data-toggle="modal" data-target="#editModal" name="editRec' . $x . 'Btn"><span class="glyphicon glyphicon-pencil"></span></button>'
                . '</td>');
                echo('<td>'
                . '<form action="inventory.php" method="post" name="delRec' . $x . '">'
                . '<input type="hidden" name="recordNum" value="' . $x . '">'
                . '<input type="hidden" name="delete" value="1">'
                . '<button type="submit" name="delRec' . $x . 'Btn"><span class="glyphicon glyphicon-trash"></span></button>'
                . '</form>'
                . '</td>');
                echo("</tr>");
                $x++;
            }
            //----------------------------------------------------------------
            // save data for editing
            $_SESSION['dataArray'] = urlencode(serialize($data));
            $_SESSION['headingsArray'] = urlencode(serialize($headings));
        }

        function deleteRec($recNum) {
            global $connection;
            $data = unserialize(urldecode($_SESSION['dataArray']));
            $gameID = $data[$recNum][0];
            echo("<script>doLog('gameID = " . $gameID . "', false)</script>");
            $sql = "DELETE FROM games WHERE gameID='" . $gameID . "'";
            $connection ->exec($sql);
        }
        
        function updateRecord($recNum){
            global $connection;
            $data = unserialize(urldecode($_SESSION['dataArray']));
            $gameID = $data[$recNum][0];
            $title = $_POST["addTitle"];
            $publisher = $_POST["addPublisher"];
            $cost = $_POST["addCost"];
            echo("<script>doLog('gameID = " . $gameID . "', false)</script>");
            $sql = "UPDATE `games` SET `Title` = '" . $title . "', `Cost` = '" . $cost . "', `Publisher` = '" . $publisher . "' WHERE `gameID` = " . $gameID . ";";
            $connection ->exec($sql);
        }
        
        function passArray(){
        $data = unserialize(urldecode($_SESSION['dataArray']));
        echo json_encode($data);
        }
        
        function addNewRecord(){
            global $connection;
            $title = $_POST["newTitle"];
            $publisher = $_POST["newPub"];
            $cost = $_POST["newCost"];
            echo("<script>doLog('adding new record. Title: " . $title . " Publisher: " . $publisher . " Cost: " . $cost . "', false)</script>");
            $sql = "INSERT INTO `games` (`gameID`, `Title`, `Publisher`, `Cost`) VALUES (NULL, '". $title ."','". $publisher ."','". $cost ."')";
            $connection ->exec($sql);
        }
        
        function search() {
            global $connection;
            $searchText = $_POST["searchBox"];
            $sql = "SELECT * FROM `games` WHERE `Title` LIKE '%" . $searchText . "%';";

            $result = $connection->query($sql);
            //----------------------------------------------
            //----------------------------------------------------------------
            $searchData = array();
            $searchHeadings = array();
            foreach ($result as $key => $row) {
                $x = $key;
                $y = 0;
                $yMax = $result->columnCount();
                foreach ($row as $key => $value) {//            Get data
                    if (is_int($key)) {
                        $searchData[$x][$y] = $value;
                        $y++;
                    }
                }
            }
            for ($x = 0; $x < $result->columnCount(); $x++) {
                $temp = $result->getColumnMeta($x);
                $searchHeadings[$x] = $temp['name'];
            }
            //----------------------------------------------------------------
            //----------------------------------------------------------------
            $_SESSION['searchDataArray'] = urlencode(serialize($searchData));
        }

        function printSearch() {
            $searchData = unserialize(urldecode($_SESSION['searchDataArray']));
            $headings = unserialize(urldecode($_SESSION['headingsArray']));
            echo "<tr>";
            foreach ($headings as $head) {
                echo ("<th>" . $head . "</th>");
            }
            foreach ($searchData as $row) {
                echo("<tr>"); //                             print table
                foreach ($row as $value) {
                    echo("<td>" . $value . "</td>");
                }
            }
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
                        <li><a href="accntPage.php"><?php echo $user; ?></a></li>
                        <li><a class="active" href="">Inventory</a></li>
                        <li ><a href="userMng.php">User Management</a></li>
                        <li><a href="?logout=true">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container-fluid text-center" id="body">
            <div  class="container-fluid tableDisplay">
                <div id="userTable" class="row">
                    <table>
                        <?php buildTable() ?>
                    </table>
                </div>
                <div id="newRecForm" class="row">
                    <form action="inventory.php" method="post" name="newGameRec" id="newRecEnter">
                        <span class="row">
                            <label class="col pull-left inputLable">Title:</label>
                            <input type="text" class="col pull-right inputBox" name="newTitle" id="newTitle" value="" />
                        </span>
                        <span class="row">
                            <label class="col pull-left inputLable">Publisher:</label>
                            <input type="text" class="col pull-right inputBox" name="newPub" id="newPub" value=""/>
                        </span>
                        <span class="row">
                            <label class="col pull-left inputLable">Cost:</label>
                            <input type="text" class="col pull-right inputBox" name="newCost" id="newCost" value=""/>
                        </span>
                        <input type="hidden" name="newGame" value="1">
                        <span class="container-fluid text-center">
                            <span class="row pull-right" id="formBtns">
                                <button type="submit" class="btn btn-default col">Add Record</button>
                                <button type="button" class="btn btn-default col" data-dismiss="modal">Cancel</button>
                            </span>
                        </span>
                    </form> 
                </div>
                <div id="search" class="row">
                    <form action="inventory.php" method="post" name="searchGames" id="searchGames">
                        <span class="row">
                            <label class="col pull-left inputLable">Search by Title:</label>
                            <input type="text" class="col pull-right inputBox" name="searchBox" id="searchBox" value=""/>
                        </span>
                        <span class="container-fluid text-center">
                            <span class="row pull-right formBtns">
                                <button type="submit" class="btn btn-default col">Search</button>
                            </span>
                        </span>
                        <input type="hidden" name="search" value="1">
                    </form>
                    <hr/>
                    <div id="resultTable">
                        <table>
                            <?php printSearch() ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="editModal" class="modal fade">
            <div class="modal-dialog" id="addForm"> 
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign"></span></button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <form action="inventory.php" method="post" name="addRecord" class="text-center">
                            <div id="formInputs" class="container-fluid">
                                <span class="row">
                                    <label class="col pull-left inputLable">Title:</label>
                                    <input type="text" class="col pull-right inputBox" name="addTitle" id="addTitle" value="" />
                                </span>
                                <span class="row">
                                    <label class="col pull-left inputLable">Publisher:</label>
                                    <input type="text" class="col pull-right inputBox" name="addPublisher" id="addPublisher" value=""/>
                                </span>
                                <span class="row">
                                    <label class="col pull-left inputLable">Cost:</label>
                                    <input type="text" class="col pull-right inputBox" name="addCost" id="addCost" value=""/>
                                </span>
                                <input type="hidden" name="recordNum" value="" id="addInvHidden">
                                <input type="hidden" name="delete" value="0">
                            </div>
                            <span class="container-fluid text-center">
                                <span class="row pull-right formBtns">
                                    <button type="submit" class="btn btn-default col">Update</button>
                                    <button type="button" class="btn btn-default col" data-dismiss="modal">Cancel</button>
                                </span>
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <footer id="footer" class="container-fluid text-center footer navbar-fixed-bottom">
            <p class="pull-left">This page is not copyrighted.</p><p class="pull-right">Version: 0.1</p>
        </footer>
        <script type="text/javascript">
                    function passData(recNum) {
                        var rawData = <?php passArray() ?>;
                        data = JSON.parse(JSON.stringify(rawData));
                        doLog("passData() got data array from php", false);
                        document.getElementById("addInvHidden").value = recNum;
                        document.getElementById("addTitle").value = data[recNum][1];
                        document.getElementById("addCost").value = data[recNum][3];
                        document.getElementById("addPublisher").value = data[recNum][2];
                    }
        </script>
</html>