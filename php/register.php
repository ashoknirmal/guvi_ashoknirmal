<?php
require_once __DIR__."/config.php";

$in = getJsonInput();
$name = trim($in['name'] ?? '');
$email = strtolower(trim($in['email'] ?? ''));
$pass = $in['password'] ?? '';
$age = $in['age'] ?? null;
$dob = $in['dob'] ?? null;
$contact = $in['contact'] ?? null;

if (!$name || !$email || !$pass) {
    echo json_encode(['success'=>false,'message'=>'Required fields missing']);
    exit;
}

$st = $pdo->prepare("SELECT id FROM users WHERE email=?");
$st->execute([$email]);
if ($st->fetch()) {
    echo json_encode(['success'=>false,'message'=>'Email already exists']);
    exit;
}

$hash = password_hash($pass, PASSWORD_DEFAULT);
$ins = $pdo->prepare("INSERT INTO users (name,email,password,age,dob,contact) VALUES (?,?,?,?,?,?)");
$ins->execute([$name,$email,$hash,$age,$dob,$contact]);

echo json_encode(['success'=>true,'message'=>'Registered']);
    