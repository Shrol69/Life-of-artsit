<?php
    $con = new mysqli("localhost", "root","", "life-of-artist"); 
    if ($con->connect_error) {
        die("Database failed to connect".$con->connect_error);
    }
    else{
        // echo "Database Connected Successfully";
    }
?>