 <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";
    $data = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = [
            'month' =>  $_POST["name"],
            'department_id' => $_POST["department_id"],
            'target_id' => $_POST["pk"],
            'year' =>  $_POST["year"],
            'amount' =>  $_POST["value"]
        ];

        findOrCreateTargetDepartment($db, $data['department_id'], $data['target_id'], $data['month'], $data['year'], $data['amount']);

        echo json_encode(['success' => true]);
    }

    function findOrCreateTargetDepartment($db, $departmentId, $targetId, $month, $year, $amount)
    {
        // Tìm xem chỉ tiêu theo phòng ban và theo tháng đã có trước đó chưa
        $stmt = $db->prepare("SELECT id
                FROM `target_departments`
                WHERE department_id = :department_id and
                      target_id = :target_id and
                      month = :month and
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
            $stmt = $db->prepare("INSERT INTO `target_departments` (department_id, target_id, month, year, amount)
                              VALUES(:department_id, :target_id, :month, :year, :amount )");
            dbBind($stmt, ':department_id', $departmentId);
            dbBind($stmt, ':target_id', $targetId);
            dbBind($stmt, ':month', $month);
            dbBind($stmt, ':year', $year);
            dbBind($stmt, ':amount', $amount);
            $stmt->execute();
            $targetDepartmentId = $db->lastInsertId();
        } else {
            // Nếu tồn tại, update đè lại
            $targetDepartmentId = $targetDepartment->id;
            $stmt = $db->prepare("UPDATE `target_departments`
                            SET amount = :amount
                            WHERE id = :id");
            dbBind($stmt, ':id', $targetDepartmentId);
            dbBind($stmt, ':amount', $amount);
            $stmt->execute();
        }

        return $targetDepartmentId;
    }
    ?>