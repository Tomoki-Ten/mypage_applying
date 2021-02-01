<?php

    require_once '/var/www/html/pages/functions/functions.php';

    session_start();
    session_regenerate_id();
    
    // トークン、作成
    $token = token_creator();

    // エラー、受け取り
    $message = $_GET['message'] ?? '';
    if (isset($message)) {
        $message = sanitizer($message);
    }

    // ページ数、受け取り、デフォルト１
    $page_num = $_GET['page_num'] ?? '1';
    if (isset($page_num)) {
        $page_num = sanitizer($page_num);
    }
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- CSS -->
    <link rel="stylesheet" href="/css/styles.css">
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

            <!-- <li><a href="">API</a></li> -->

            <?php
                // ログアウト
                // ログイン中のみ、表示
                if (isset($_SESSION['login'])) {
                    echo '<li><a href="/pages/manager/manager_logout.php">Logout</a></li>';
                } else {
                    echo '<li><a href="/index.php">Login</a></li>';
                }
            ?>
        </ul>
    </nav>

    <?php
        // 投稿ボタン
        // ログイン中のみ、表示
        if (isset($_SESSION['login'])) {
            echo <<< _CREATE_BTN
                <div class="controller">

                    <span id="btn_create">
                        Create
                    </span>

                    <p class="message">Message: {$message}</p>

                </div>
            _CREATE_BTN;
        }
    ?>

    <?php
        // モーダルウィンドウ
        // ログイン中のみ、表示
        if (isset($_SESSION['login'])) {
            require_once '/var/www/html/pages/components/modal_post.php';
        }
    ?>

    
    <!-- メイン、主に投稿内容 -->
    <main class="main">
        
        <div class="main_contents">
                
            <?php
                // 投稿内容、表示、処理
                require_once '/var/www/html/pages/components/action_index.php';
            ?>

        </div>
        
    </main>
    
    <?php
        // Javascrip、読み込み
        // ログイン中のみ
        if(isset($_SESSION['login'])) {
            echo '<script src="/js/modal_create.js"></script>';
        }
    ?>
</body>
</html>