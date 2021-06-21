<?php

    //Change values that match your server credentials
    define("SERVER_NAME","localhost");
    define("USERNAME","root");
    define("DB_PASSWORD","usbw");
    define("DATABASE_NAME","userdb_32700");


    $con = new mysqli(SERVER_NAME, USERNAME, DB_PASSWORD, DATABASE_NAME);


    if(!$con){
        echo "<h3>There was an error connection to the database</h3>";
        die();
    }
?>