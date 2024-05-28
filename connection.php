<?php
    
    // Enter your host name, database username, password, and database name.
    // If you have not set database password on localhost then set empty.
    $con = mysqli_connect("database-1.cf22w2y0elok.ap-south-1.rds.amazonaws.com","admin","manasi_2003","museum");
    
    // Check connection
    if (mysqli_connect_errno()){
        echo "Failed to Connect to MySQL: " . mysqli_connect_error();
    }
    // else{echo "Connected";}
?>
