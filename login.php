<?php
session_start();
require 'header.php';
?>
<title>ログインページ</title>
<link rel="stylesheet" href="style.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <!-- <h2>ログインページ</h2> -->
    <?php
    if (isset($_SESSION['logout_message'])) {
        echo "<p>" . $_SESSION['logout_message'] . "</p>";
        unset($_SESSION['logout_message']);
    }
    ?>
    <!-- <form method="POST" action="login.php">
        <p class="name"><label>ユーザー名:
                <input type="text" name="username" required></label></p>
        <br>
        <p class="pass"><label>パスワード:
                <input type="password" name="password" required></label></p>
        <br>
        <p class="submit"><input type="submit" name="submit" value="ログイン"></p>
    </form> -->

    <div class="login-page">
        <div class="form">
            <form class="login-form" method="POST" action="login.php">
                <input type="text" name="username" required placeholder="ユーザー名" />
                <input type="password" name="password" required placeholder="パスワード" />
                <button type="submit" name="submit">ログイン</button>
                <p class="message">登録されていませんか？ <a href="#">アカウントを作成</a></p>
            </form>
            <form class="register-form" method="POST" action="reservation.php">
                <input type="text" name="username" required placeholder="ユーザー名" />
                <input type="password" name="password" required placeholder="パスワード" />
                <button type="submit" name="submit">登録</button>
                <p class="message">すでにアカウントをお持ちですか？ <a href="#">ログイン</a></p>
            </form>
        </div>
    </div>


    <br>
    <div class="button008">
        <a href="reservation.php">登録がまだの方</a>
    </div>
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
            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['username'] = $username;
                echo "ログイン成功！";
                echo "3秒後に掲示板に飛びます";
                header("refresh:3;url=output.php");
            } else {
                echo "ユーザー名、またはパスワードが正しくありません";
            }
        } catch (PDOException $e) {
            echo "エラー: " . $e->getMessage();
        }
        // データベースの接続を閉じる
        $pdo = null;
    }
    ?>
<script>
    $(document).ready(function(){
    $('.message a').click(function(){
        $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
    });
});
</script>
    <?php require 'footer.php'; ?>