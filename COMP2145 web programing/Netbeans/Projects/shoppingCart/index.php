<?php 
session_start();
?>
<!DOCTYPE html>
<!--
index.php
Dustin Frandle - dafrandle@yahoo.com
Project: Shopping Cart
Written - 3/25/2016
-->
<html>
    <head>
        <link href="https://fonts.googleapis.com/css?family=Wendy+One" rel="stylesheet"> 
        <!-- Bootstrap 4 --><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="shopCartStyle.css" /> <!-- css code link -->
    </head>
    <body>
        <?php
        /************************************************************************************
          PHP FUNCTIONS / CODE
         * **********************************************************************************/
        $self = $_SERVER["PHP_SELF"];

        if (array_key_exists('submitFlag', $_POST)) {/* on load area -- checks for hidden flags or builds the array again. If case is not matched an error msg is echoed */
            $submitFlag = $_POST['submitFlag'];
            $gameArray = unserialize(urldecode($_SESSION['serializedArray']));
            switch ($submitFlag) {
                case 5: deleteRecord();
                    break;
                case 1: addRecord();
                    break;
                default :
                    echo("This Form has encounterd an error, please contact support");
                    showList();
            }
        } else {
            $gameArray = array
                (
                array(1, "Starcraft 2", "Blizzard", 39.99),
                array(2, "Battlefield 4", "DICE", 59.99),
                array(3, "RimWorld", "Ludeon Studios", 29.99),
                array(4, "Sunless Sea", "Failbetter Games", 18.99),
                array(5, "Mount & Blade: Warband", "TaleWorlds Entertainment", 19.99),
                array(6, "Arma 3", "Bohemia Interactive", 39.99),
                array(7, "Borderlands 2: Game of the Year Edition", "Gearbox Software", 39.99),
                array(8, "Grand Theft Auto V", "Rockstar North", 59.99)
            );
        }
        function showList() { /* showList() shows a table with the array items */
            global $gameArray;
            echo('<table class="table borderless table-striped text-center " summary="list of games and their prices">');
            echo('<tr>');
            echo('<th>ID</th>');
            echo('<th>Game</th>');
            echo('<th>Developer</th>');
            echo('<th>Price (USD)</th>');
            echo('</tr>');

            foreach ($gameArray as $list) {
                echo('<tr>');
                foreach ($list as $value) {
                    echo("<td>" . $value . "</td>");
                }
                echo('</tr>');
            }
            echo('</table>');
            $_SESSION['serializedArray'] = urlencode(serialize($gameArray));
        }

        function deleteRecord() {/* deleteRecord() selects and item by its ID which is fill autonomously */
            global $gameArray;
            
            $delete = $_POST['listItem'] - 1;/* as 0 is not used in the list it is necessary to decrement */
            unset($gameArray[$delete]);
            $_SESSION['serializedArray'] = urlencode(serialize($gameArray));
        }
        function addRecord(){/* addRecord() get data from text box and an autonomously calulated ID and put into array */
            global $gameArray;
            $ID = 1;
            foreach ($gameArray as $list){/* for each row */
                while ($ID == $list[0]){/* if $ID matches the data in the 1st column it is incremented */
                    $ID++;
                }
            }
            $gameArray[] = array($_POST['id'] = $ID, $_POST['textBoxName'], $_POST['textBoxDev'], $_POST['textBoxPrice']);
            sort($gameArray);
            
            $_SESSION['serializedArray'] = urlencode(serialize($gameArray));
        }
        /************************************************************************************
          END PHP FUNCTIONS
         ************************************************************************************/
        ?>
        <div id="dataView" class="container-fluid col-xs-1 center-block text-center">
            </br></br></br></br></br>
            <div id="table">
                <h1> Game List </h1>
                <p>
                    <?php showList(); ?>
                </p>
            </div>
        </div>
        </br></br></br></br></br>
        <div class="container">
            <div class="row">
                <div id="add" class="col">
                    <h3>Add Record:</h3>
                    <form action="index.php" method="post" name="addRecord">
                        Name:
                        <input type="text" name="textBoxName" id="textBoxName" value="Titanfall 2" />
                        <br /><br />
                        Dev:
                        <input type="text" name="textBoxDev" id="textBoxDev" value="Respawn Entertainment"/>
                        <br /><br />
                        Price:
                        <input type="text" name="textBoxPrice" id="textBoxPrice" value="29.99"/>
                        <br/><br />
                        <input type="hidden" name="submitFlag" value="1">
                        <input type="submit" name="addSubmitBtn" value="Add record">
                    </form>
                </div>
                <div id="del" class="col">
                    <h3>Delete Record:</h3>
                    <form action="index.php" method="post" name="delRecord">
                        Select record: <select name="listItem" size="1">
                            <?php
                            foreach ($gameArray as $record) {
                                echo("<option value='" . $record[0] . "'>" . $record[1] . "</option>\n");
                            }
                            ?>
                        </select>
                        </br></br>
                        <input type="hidden" name="submitFlag" value="5">
                        <input name="delSubmitBtn" type="submit" value="Delete record">
                    </form>
                </div>
            </div>
        </div>
        </br></br></br></br></br></br></br></br></br></br></br></br>
    </body>
</html>