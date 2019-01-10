<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=", '', '');
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}
?>