<?php
session_start();
// ログアウトメッセージをセット
$_SESSION['logout_message'] = "ログアウトしました。";
// セッションを破棄してログアウト
session_destroy();
header("location: login.php");
exit;
?>