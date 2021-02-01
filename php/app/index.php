<?php
    session_start();
    session_regenerate_id();
    require_once '/var/www/html/pages/functions/functions.php';

    use Classes\DB;
    
    // トークン、作成
    $token = token_creator();

    // エラー、受け取り
    $message = $_GET['message'] ?? '';

    if (isset($message)) {
        $message = sanitizer($message);
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <title>Manager_Login</title>
</head>
<body>

    <header>
        <h1>Tomoki Ten's Page</h1>
    </header>

    <!-- メニュー -->
    <nav class="menu">
        <ul class="nav_list_container">
            <li><a href="/post.php">Home</a></li>
            <li><a href="/pages/api/api.php">API</a></li>
            <li><a href="/pages/manager/manager_register.php">Register</a></li>
        </ul>
    </nav>

    <?php
        // エラー、表示
        echo '<p class="login_msg">' . $message . '</p>';
    ?>

    <main>
        <form action="/pages/validation/validation_login.php" method="POST">
            <div>
                <label for="email_form">メールアドレス : </label>
                <input type="email" name="manager_email" id="email_form" required>
            </div>
            <div>
                <label for="pass_form">パスワード : </label>
                <input type="password" name="manager_pass" id="pass_form" required>
            </div>
            <div>
                <label for="confirm_pass">確認用パスワード : </label>
                <input type="password" name="confirm_pass" required>
            </div>

            <input type="hidden" name="token" value="<?= $token ?>">

            <input type="submit" value="Login">
        </form>
    </main>
    
</body>
</html>