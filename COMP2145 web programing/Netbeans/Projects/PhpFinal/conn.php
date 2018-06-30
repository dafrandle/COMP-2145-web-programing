<?php

//database information
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPass = 'mysql';
//connect database server
try {
    $connection = new PDO("mysql:host=$dbHost", $dbUsername, $dbPass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo("<script>doLog('Database server connected successfully.', false)</script>");
} catch (Exception $ex) {
    $error = preg_replace('/\s+/', ' ', htmlspecialchars($ex->getMessage()));
    echo("<script>doLog('Database server failed to connect.', true)</script>");
    echo("<script>doLog('" . $error . "', true)</script>");
    echo("<script>doAlert('Failed to connect to server. Please try again later.')</script>");
}
try {
    $connection->exec("USE phpFinal;");
    echo("<script>doLog('Database successfully selected.', false)</script>");
} catch (Exception $ex) {
    $error = preg_replace('/\s+/', ' ', htmlspecialchars($ex->getMessage()));
    echo("<script>doLog('required database may not exsist.', true)</script>");
    echo("<script>doLog('" . $error . "', true)</script>");
    echo("<script>doAlert('Failed to connect to server. Please try again later.')</script>");
}