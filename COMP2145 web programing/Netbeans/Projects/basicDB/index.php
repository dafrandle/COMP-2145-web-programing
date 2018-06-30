<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // ampps credentals = user id=root password=mysql
        $dbHost = 'localhost';
        $dbUserName = 'root';
        $dbUserPass = 'mysql';
        //$dbDBF = 'dynamicSite';
        try {
            $con = new PDO("mysql:host=$dbHost", $dbUserName, $dbUserPass);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch (Exception $ex) {
            echo "Connection unsuccessful " . $ex->getMessage();
        }
        //--------------------------------------select db---------------------------------------------
        $sqlQuery = "USE dynamicSite";
        $con->exec($sqlQuery);
        //-----------------------------------------get table headings------------------------------------------
        $sqlQuery = "SELECT * FROM `games`";
        $result = $con->query($sqlQuery);
        for ($x = 0; $x < $result->columnCount(); $x++) {
            $head = $result->getColumnMeta($x);
            $columnsHead[] = $head['name'];
        }
        //-----------------------------------------get data-----------------------------------------------
        $data = array();
        foreach($result as $key=>$row){
            $x=$key;
            $y=0;
            foreach ($row as $key=>$value){
                if (is_int($key)){
                    $data[$x][$y] = $value;
                    $y++;
                }
            }
        }
        //--------------------------------------print table---------------------------------------------
        echo ("<table border='1'><tr>");
        foreach($columnsHead as $head){
            echo ("<th>" . $head . "</th>");
        }
        echo ("</tr>");
        foreach ($data as $row){
            echo("<tr>");
            foreach ($row as $data){
                echo("<td>" . $data . "</td>");
            }
            echo("</tr>");
        }
        echo("</table>");
        ?>
    </body>
</html>
