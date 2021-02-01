<?php
    session_start();
    session_regenerate_id();
    require_once '/var/www/html/pages/functions/functions.php';

    // トークン、作成
    $token = token_creator();

    // エラー、受け取り
    $message = $_GET['message'] ?? '';

    if (isset($message)) {
        $message = sanitizer($message);
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <title>Document</title>
</head>
<body>
    <header>
        <h1>Tomoki Ten's Page</h1>
    </header>

    <!-- メニュー -->
    <nav class="menu">
        <ul class="nav_list_container">
            <li><a href="/">Home</a></li>
            <li><a href="">API</a></li>
        </ul>
    </nav>

    <?php
        // エラー、表示
        echo '<div class="register_msg">' . $message . '</div>';
    ?>
    
    <main class="main">
    
        <div>
        
        
        <form action="../validation/validation_register.php" method="POST" class="register">

            <div>
            <label for="name_form">管理者名 : </label>
            <input type="text" name="name" id="name_form" placeholder="半角英数字、_ のみ有効" required>
            </div>

            <div>
            <label for="email_form">メールアドレス : </label>
            <input type="email" name="email" id="email_form" required>
            </div>

            <div>
            <label for="pass_form">パスワード : </label>
            <input type="password" name="pass" id="pass_form" required>
            </div>

            <div>
            <label for="confirm_form">確認用パスワード : </label>
            <input type="password" name="confirm_pass" id="confirm_form" required>
            </div>

            <input type="hidden" name="token" value="<?= $token?>">

            <div>
            <input type="submit">
            </div>
            
        </form>

        </div>

    </main>
</body>
</html>