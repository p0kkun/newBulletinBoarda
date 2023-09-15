<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['message_text'])) {
    // フォームが送信された場合
    $username = $_POST['username'];
    $messageText = $_POST['message_text'];
    // データベースへの接続
    $pdo = new PDO('mysql:host=localhost;dbname=BulletinBoard;charset=utf8', 'root', 'mariadb');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // メッセージをデータベースに挿入
    $query = "INSERT INTO Messages (username, message_text) VALUES (:username, :messageText)";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':username', $username, PDO::PARAM_STR);
    $statement->bindParam(':messageText', $messageText, PDO::PARAM_STR);
    $statement->execute();
    // データベースの接続を閉じる
    $pdo = null;
    // 成功したらレスポンスを返す
    echo json_encode(array('success' => true));
} else {
    // エラーの場合
    echo json_encode(array('success' => false));
}
?>
