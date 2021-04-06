<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";

$data = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'months' =>  $_POST["months"],
        'title' =>  $_POST["title"],
        'department_id' =>  $_POST["department_id"],
        'year' =>  $_POST["year"],
        'amount' =>  $_POST["amount"]
    ];

    foreach ($data["months"] as $month) {
        $targetId = findOrCreateTarget($db, $data['title']);
        $targetDepartmentId = findOrCreateTargetDepartment($db, $data['department_id'], $targetId, $month, $data['year'], $data['amount']);
    }

    echo json_encode(['success' => true]);
}

function findOrCreateTargetDepartment($db, $departmentId, $targetId, $month, $year, $amount)
{
    // Tìm xem chỉ tiêu theo phòng ban và theo tháng đã có trước đó chưa
    $stmt = $db->prepare("SELECT id
                FROM `target_departments`
                WHERE department_id = :department_id
                      target_id = :target_id
                      month = :month
                      year = :year
                ORDER BY created_at DESC");
    dbBind($stmt, ':department_id', $departmentId);
    dbBind($stmt, ':target_id', $targetId);
    dbBind($stmt, ':month', $month);
    dbBind($stmt, ':year', $year);

    $stmt->execute();
    $targetDepartment = $stmt->fetch(PDO::FETCH_OBJ);

    // Nếu k có chỉ tiêu trong DB => tạo mới
    if (!$targetDepartment) {
        $stmt = $db->prepare("INSERT INTO `target_departments` (department_id, target_id, month, year, amount) VALUES(:department_id, :target_id, :month, :year, :amount )");
        dbBind($stmt, ':department_id', $departmentId);
        dbBind($stmt, ':target_id', $targetId);
        dbBind($stmt, ':month', $month);
        dbBind($stmt, ':year', $year);
        dbBind($stmt, ':amount', $amount);
        $stmt->execute();
        $targetDepartmentId = $db->lastInsertId();
    } else {
        $targetDepartmentId = $targetDepartment->id;
    }

    return $targetDepartmentId;
}


// Tìm hoặc tạo mới chỉ tiêu
function findOrCreateTarget($db, $title)
{
    $title = trim($title);

    // Tìm xem chỉ tiêu đã có trước đó chưa
    $stmt = $db->prepare("SELECT id FROM `targets` WHERE name = :name ORDER BY created_at DESC");
    dbBind($stmt, ':name', $title);
    $stmt->execute();
    $target = $stmt->fetch(PDO::FETCH_OBJ);
    var_dump($target);

    // Nếu k có chỉ tiêu trong DB => tạo mới
    if (!$target) {
        $stmt = $db->prepare("INSERT INTO `targets` (name) VALUES(:name)");
        dbBind($stmt, ':name', $title);
        $stmt->execute();
        $targetId = $db->lastInsertId();
    } else {
        $targetId = $target->id;
    }

    // Trả về chỉ tiêu id

    return $targetId;
}
