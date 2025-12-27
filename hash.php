<?php

require_once 'config.php';
// 取得資料庫中的 hash，例如
$stmt = $pdo->prepare("SELECT password FROM users WHERE username = ?");
$stmt->execute(['你的帳號']);
$db_hash = $stmt->fetchColumn();

$password = '123456';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "密碼雜湊為：$hash";


if (password_verify('123456', $db_hash)) {
    echo "密碼正確";
} else {
    echo "密碼錯誤";
}
?>