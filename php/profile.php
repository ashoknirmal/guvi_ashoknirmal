<?php
require_once __DIR__."/config.php";

$token = getBearerToken();
$uid = validateToken($redis,$token);
if (!$uid) {
    http_response_code(401);
    echo json_encode(['success'=>false,'message'=>'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD']==='GET') {
    $st=$pdo->prepare("SELECT id,name,email,age,dob,contact FROM users WHERE id=?");
    $st->execute([$uid]);
    $u=$st->fetch();
    echo json_encode(['success'=>true,'user'=>$u]);
    exit;
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $in = getJsonInput();
    $fields=[]; $params=[];
    foreach(['name','age','dob','contact'] as $f){
        if (isset($in[$f]) && $in[$f]!==''){
            $fields[]="$f=?";
            $params[]=$in[$f];
        }
    }
    if(!$fields){ echo json_encode(['success'=>false,'message'=>'No fields']); exit; }
    $params[]=$uid;
    $sql="UPDATE users SET ".implode(',',$fields)." WHERE id=?";
    $pdo->prepare($sql)->execute($params);
    echo json_encode(['success'=>true,'message'=>'Updated']);
}
