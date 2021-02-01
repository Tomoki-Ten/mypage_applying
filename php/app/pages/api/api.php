<?php
    require_once '/var/www/html/pages/functions/functions.php';

    session_start();
    session_regenerate_id();
    
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
    <title>Document</title>
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

    <!-- メイン、主に投稿内容 -->
    <main class="main">
        
        <div class="main_contents">

            <div>
            <!-- <div id="api_body"> -->

                <h2 class="youtube">YouTube API</h2>

                <!-- 検索、フォーム -->
                <div id="search_container">

                    <input type="text" id="search_input">

                    <input type="hidden" id="token_keeper">
                    <input type="hidden" id="key_keeper">

                    <button id="search_btn">SEARCH</button>
                </div>

                <hr>
                
                <!-- 検索結果、表示部分 -->
                <ul id="api_container">
                </ul>

                <div class="more_container" hidden>
                    <p class="more" hidden>More...</p>
                </div>
                
            </div>
                        
        </div>
        
    </main>
    <script src="/js/api.js"></script>
</body>
</html>
