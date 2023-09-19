<?php
session_start();
require 'header.php';
?>
<title>ログインページ</title>
</head>
<body>
    <h2>ログインページ</h2>
    <?php
    if(isset($_SESSION['logout_message'])){
        echo "<p>" . $_SESSION['logout_message'] ."</p>";
        unset($_SESSION['logout_message']);
    }
    ?>
    <form method="POST" action="login.php">
        <label>ユーザー名: </label>
        <input type="text" name="username" required>
        <br>
        <label>パスワード: </label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" name="submit" value="ログイン">
    </form>
    <?php
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $pdo = new PDO('mysql:host=localhost;dbname=BulletinBoard;charset=utf8', 'root', 'mariadb');
        // エラーモードを設定
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            // 会員情報のクエリの実行
            $query = "select * from Users where username = :username";
            $statement = $pdo->prepare($query);
            $statement->bindParam(':username', $username);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            if($user && password_verify($password,$user['password'])){
                session_start();
                $_SESSION['username'] = $username;
                echo "ログイン成功！";
                echo "3秒後に掲示板に飛びます";
                header("refresh:3;url=output.php");
            }else{
                echo "ユーザー名、またはパスワードが正しくありません";
            }
        } catch (PDOException $e) {
            echo "エラー: " . $e->getMessage();
        }
        // データベースの接続を閉じる
        $pdo = null;
    }
    ?>
<?php require 'footer.php'; ?>