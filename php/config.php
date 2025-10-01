<?php
// php/config.php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$db_host = '127.0.0.1';
$db_name = 'guvi_intern';
$db_user = 'root';
$db_pass = ''; // set password if you have one

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user, $db_pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'DB connect fail']);
    exit;
}

// Redis
$redis = new Redis();
try {
    $redis->connect('127.0.0.1', 6379);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Redis connect fail']);
    exit;
}

function getJsonInput() {
    return json_decode(file_get_contents('php://input'), true) ?? [];
}

function getBearerToken() {
    $hdr = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (preg_match('/Bearer\s(\S+)/', $hdr, $m)) return $m[1];
    return null;
}

function validateToken($redis, $token) {
    if (!$token) return false;
    $uid = $redis->get("session:$token");
    if ($uid) {
        $redis->expire("session:$token", 3600);
        return intval($uid);
    }
    return false;
}
