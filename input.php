<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
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
    header('Location: output.php');
    // 成功したらリダイレクトまたはJSONレスポンスを返すなどの適切な処理を追加できます。
    // 例：header('Location: output.php'); など
}
?>
