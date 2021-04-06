<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $params = getParams();
    $year = (int) (data_get($params, 'year') ?? date("Y"));
    // Nếu phòng IT => show tất
    if ($currentDepartment['title'] === 'TCKT - IT') {
        $results = getAllTargetDepartments($db, $year);
    } else {
        // getTargetDepartments($db, $currentDepartment, $year);
    }

    echo json_encode($results);
}

function getAllTargetDepartments($db, $year)
{
    $stmt = $db->prepare("SELECT DISTINCT t.target_id
                            FROM target_departments as t
                            WHERE year = :year
                            ");

    dbBind($stmt, ':year', $year);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);

    $newResults = array_map(function ($item) use ($db, $year) {
        $stmt = $db->prepare("SELECT t.name, td.id, td.department_id, td.month, td.year, td.amount, td.target_id
                        FROM target_departments as td
                        JOIN targets as t
                        ON t.id = td.target_id
                        WHERE td.target_id = :target_id and td.year = :year");
        dbBind($stmt, ':year', $year);
        dbBind($stmt, ':target_id', $item->target_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }, $results);

    return $newResults;
}
