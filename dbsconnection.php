<?php

 $hostName= "localhost";
 $dbUser  = "u795733686_kaizenthebeast";
 $dbPassword = "Godlikes12";
 $dbName   = "u795733686_testing";
 
 $conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
 if(!$conn){
   die("Database connection failed");
 }
 
 