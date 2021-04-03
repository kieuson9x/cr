<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";

$data = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'months' =>  $_POST["months"],
        'title' =>  $_POST["title"],
        'department_code' =>  $_POST["department_code"],
        'year' =>  $_POST["year"],
        'number_of_sale_goods' =>  $_POST["number_of_sale_goods"]
    ];

    foreach ($data["months"] as $month) {
        $sql = 'INSERT INTO kang_cr (Name, Department_code, month, year, number_of_sale_goods)
            VALUES("'.$data["title"].'", "'.$data["Department_code"].'", '.$month.', '.$data["year"].', '.$data["number_of_sale_goods"].')';
        $sth = $dbo->prepare($sql);
        $sth->execute();
    }
}


