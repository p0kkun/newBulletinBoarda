<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_id']) && isset($_POST['rating'])) {
    // フォームが送信された場合
    $messageID = $_POST['message_id'];
    $rating = $_POST['rating'];
    // データベースへの接続
    $pdo = new PDO('mysql:host=localhost;dbname=BulletinBoard;charset=utf8', 'root', 'mariadb');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // 評価情報をデータベースに更新
    if ($rating == 1) {
        $query = "UPDATE Messages SET likes = likes + 1 WHERE message_id = :messageID";
    } elseif ($rating == -1) {
        $query = "UPDATE Messages SET dislikes = dislikes + 1 WHERE message_id = :messageID";
    }
    $statement = $pdo->prepare($query);
    $statement->bindParam(':messageID', $messageID, PDO::PARAM_INT);
    $statement->execute();
    // データベースの接続を閉じる
    $pdo = null;
    echo json_encode(array('success' => true));
} else {
    // エラーの場合
    echo json_encode(array('success' => false));
}
?>
