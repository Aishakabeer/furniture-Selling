<?php
    $connection = mysqli_connect("127.0.0.1:3308", "root", "root");
    if (!$connection) die("<ERROR: Cannot connect to database>");
    $database = mysqli_select_db($connection, "furniture_db");
    if (!$database) die("<ERROR:Cannot select database>");
