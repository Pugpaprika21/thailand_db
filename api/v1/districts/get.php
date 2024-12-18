<?php

include_once __DIR__ . "../../../../app/config/Database.php";

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

try {
    $pdo = (new Database)->getConnection();
    $stmt = $pdo->prepare("SELECT * FROM districts WHERE province_id = :province_id");
    $stmt->bindParam(':province_id', $_POST['province_id'], PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    echo json_encode(['message' => '', 'code' => 100, 'data' => $rows]);
} catch (PDOException $e) {
    echo json_encode(['message' => $e->getMessage(), 'code' => 0, 'data' => null]);
}
