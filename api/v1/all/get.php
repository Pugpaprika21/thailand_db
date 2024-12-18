<?php

include_once __DIR__ . "../../../../app/config/Database.php";

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

try {
    $pdo = (new Database)->getConnection();
    
    $province_id = intval($_POST['province_id']);
    $district_id = intval($_POST['district_id']);
    $subdistrict_id = intval($_POST['subdistrict_id']);
    
    $stmt = $pdo->prepare("
        SELECT
            t.row_number AS row_number,
            p.name_in_thai AS province_name,
            d.name_in_thai AS district_name,
            s.name_in_thai AS subdistrict_name
        FROM
            (
                SELECT
                    @row_number := IFNULL(@row_number, 0) + 1 AS row_number, 
                    p.id AS province_id,
                    d.id AS district_id,
                    s.id AS subdistrict_id
                FROM
                    `provinces` AS p
                LEFT JOIN `districts` AS d ON p.id = d.province_id
                LEFT JOIN `subdistricts` AS s ON d.id = s.district_id
                WHERE
                    p.id = :province_id
                    AND d.id = :district_id
                    AND s.id = :subdistrict_id
                ORDER BY
                    p.id, d.id, s.id
            ) AS t
        JOIN `provinces` AS p ON t.province_id = p.id
        JOIN `districts` AS d ON t.district_id = d.id
        JOIN `subdistricts` AS s ON t.subdistrict_id = s.id;
    ");

    $stmt->bindParam(':province_id', $province_id, PDO::PARAM_INT);
    $stmt->bindParam(':district_id', $district_id, PDO::PARAM_INT);
    $stmt->bindParam(':subdistrict_id', $subdistrict_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['message' => '', 'code' => 100, 'data' => $rows]);
} catch (PDOException $e) {
    echo json_encode(['message' => $e->getMessage(), 'code' => 0, 'data' => null]);
}
