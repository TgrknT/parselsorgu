<?php

try {
    $db = new PDO("mysql:host=localhost;dbname=proje;charset-utf8","root","");
    //echo "basarili" ;
} catch (PDOExpection $db) {
    echo $db->getMessage();
    //echo "basarisiz";
}






?>