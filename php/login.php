<?php
require_once __DIR__."/config.php";

$in = getJsonInput();
$email = strtolower(trim($in['email'] ?? ''));
$pass = $in['password'] ?? '';

$st = $pdo->prepare("SELECT * FROM users WHERE email=?");
$st->execute([$email]);
$u = $st->fetch();

if (!$u || !password_verify($pass, $u['password'])) {
    echo json_encode(['success'=>false,'message'=>'Invalid credentials']);
    exit;
}

$token = bin2hex(random_bytes(32));
$redis->setex("session:$token",3600,$u['id']);

unset($u['password']);
echo json_encode(['success'=>true,'token'=>$token,'user'=>$u]);
