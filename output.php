<?php 
    session_start();
    require 'header.php'; ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>掲示板</title>
    <style>
        /* 投稿フォームのスタイル */
        .message-form {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #F0F0F0; /* ここで正しいカラーコードを指定 */
            padding: 10px;
            border-top: 1px solid #ccc;
        }
        /* メッセージのスタイル */
        .message {
            background-color: #F9F9F9; /* ここで正しいカラーコードを指定 */
            padding: 10px;
            margin: 2px 0;
            border: 1px solid #ccc;
        }
        .message:nth-child(odd) {
            background-color: #E0E0E0; /* ここで正しいカラーコードを指定 */
        }
        #message-container{
            margin-bottom: 120px;
        }
        .timestamp {
            font-size: 12px;
            color: #888;
        }
        .button{
            padding: 0;
            margin: 0;
        }
        .likes{
            margin: 0;
            margin-bottom: 10px;
        }
        .dislikes{
            margin: 0;
        }
    </style>
</head>
<body>
    <h2>掲示板</h2>
    <p><a href="logout.php">ログアウト</a></p>
    <!-- メッセージを表示するコンテナ -->
    <div id="message-container">
    <?php
    // データベースへの接続
    $pdo = new PDO('mysql:host=localhost;dbname=BulletinBoard;charset=utf8', 'root', 'mariadb');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // メッセージを取得して表示
    $query = "SELECT * FROM Messages ORDER BY timestamp DESC";
$statement = $pdo->prepare($query);
$statement->execute();
$messages = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($messages as $message) {
    $username = htmlspecialchars($message['username']);
    $messageText = htmlspecialchars($message['message_text']);
    $timestamp = date('Y-m-d H:i:s', strtotime($message['timestamp']));
    $messageID = $message['message_id']; // メッセージのIDを取得
    $likes = $message['likes']; // 高評価のカウント
    $dislikes = $message['dislikes']; // 低評価のカウント
    // メッセージの表示部分
    echo "<div class='message'><strong>$username:</strong><p>$messageText</p><p class='timestamp'>$timestamp</p>";
    echo "<div class='button'><p class='likes evaluation'><button onclick='rateMessage($messageID, 1)'>高評価</button> $likes</p>";
    echo "<p class='dislikes evaluation'><button onclick='rateMessage($messageID, -1)'>低評価</button> $dislikes</p></div>";
    // echo "<button onclick='rateMessage($messageID, 1)'>高評価</button>";
    // echo "<button onclick='rateMessage($messageID, -1)'>低評価</button>";
    echo "</div>";
}
    ?>
</div>
<script>
    function rateMessage(messageID, rating) {
        // 高評価または低評価ボタンがクリックされたときの処理
        var button = document.getElementById('like-button'+messageID);
        //ボタンを無効にする
        button.disabled=true;
        // Ajaxリクエストを送信して評価情報をデータベースに送信する
        fetch('rate_message.php', {
            method: 'POST',
            body: new URLSearchParams({
                message_id: messageID,
                rating: rating
            }),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // 成功したら何か処理を追加できます（例：評価情報を更新して再表示）
            } else {
                // エラー処理を追加
                console.error('評価の送信に失敗しました。');
            }
        })
        .catch(error => {
            // エラー処理を追加
            console.error('エラー:', error);
        });
    }
</script>
    <!-- 新しいメッセージを投稿するフォーム -->
    <div class="message-form">
        <form id="message-form" method="POST" action="input.php">
            <label>ユーザー名: <?php echo $_SESSION['username']; ?></label><input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
            <label>メッセージ: </label>
            <textarea name="message_text" rows="4" cols="50" required></textarea>
            <input type="submit" name="submit" value="投稿">
        </form>
    </div>
    <?php require 'footer.php'; ?>
</body>
</html>
