<?php
require 'header.php';
?>
<title>会員登録フォーム</title>
</head>
<body>
    <h2>会員登録フォーム</h2>
    <form method="POST" action="reservation.php">
        <label>ユーザー名: </label>
        <input type="text" name="username" required>
        <br>
        <label>パスワード: </label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" name="submit" value="登録">
    </form>
    <?php
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        // パスワードの要件をチェック
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/", $password)) {
            echo "パスワードの要件を満たしていません。";
        } else {
            // データベースへの接続
            $pdo = new PDO('mysql:host=localhost;dbname=BulletinBoard;charset=utf8', 'root', 'mariadb');
            // エラーモードを設定
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                // 会員情報のクエリの実行
                $query1 = "INSERT INTO Users (username, password)
                           VALUES (:username, :password)";
                $statement1 = $pdo->prepare($query1);
                $statement1->bindParam(':username', $username);
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $statement1->bindParam(':password', $hashedPassword);
                $statement1->execute();
                echo "会員登録が成功しました！";
                echo "3秒後にログインページに飛びます";
                header("refresh:3;url=login.php");
            } catch (PDOException $e) {
                echo "エラー: " . $e->getMessage();
            }
            // データベースの接続を閉じる
            $pdo = null;
        }
    }
    ?>
<?php require 'footer.php'; ?>