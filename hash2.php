<?php
require_once 'config.php';

// 輸入欲測試的帳號與密碼
$username = 'root'; // 請改成你要測試的帳號
$password = '123456';   // 請改成你要測試的密碼

// 從資料庫取得該帳號的密碼雜湊值
$stmt = $pdo->prepare("SELECT password FROM users WHERE username = ?");
$stmt->execute([$username]);
$db_hash = $stmt->fetchColumn();

if ($db_hash === false) {
    echo "查無此帳號";
    exit;
}

// 顯示資料庫中的雜湊值
echo "資料庫密碼雜湊：" . $db_hash . "<br>";

// 驗證密碼
if (password_verify($password, $db_hash)) {
    echo "密碼正確";
} else {
    echo "密碼錯誤";
}
?>
