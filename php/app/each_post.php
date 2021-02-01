<?php
    require_once '/var/www/html/pages/functions/functions.php';

    use Classes\BackTo;
    
    session_start();
    session_regenerate_id();

    
    // トークン作成
    $token = token_creator();

    $post_id = $_GET['post_id'] ?? '';
    $message = $_GET['message'] ?? '';

    // メッセージ、エスケープ
    if (isset($message)) {
        $message = sanitizer($message);
    }

    if ($post_id == '') {

        BackTo::home();
    }

    // データ処理、読み込み
    require_once '/var/www/html/pages/components/action_each_post.php';
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POST</title>
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

            <?php
                // ログアウト、ログイン中のみ、表示
                if (isset($_SESSION['login'])) {
                    echo '<li>
                            <a href="/pages/manager/manager_logout.php">
                                Logout
                            </a>
                        </li>';
                }
            ?>
            
        </ul>
        
    </nav>

    <?php
        // 修正、削除ボタン
        // ログイン中のみ、表示
        if (isset($_SESSION['login'])) {
            echo <<< _EDIT_DELETE_BTN
            <div class="controller">

                <span id="btn_edit">
                    Edit
                </span>

                <form action="/pages/validation/validation_delete.php" method="POST" id="form_delete">

                    <input type="hidden" name="post_id" value="{$record['id']}">

                    <input type="hidden" name="token" value="{$token}">

                    <input type="button" id="btn_delete" value="Delete">

                </form>
                
                <p class="message">Message: {$message}</p>

            </div>
            _EDIT_DELETE_BTN;
            }
    ?>

    <!-- メイン、投稿内容 -->
    <main class="main">
        
        <!-- 投稿内容、表示 -->
        <div class="each_post_container">

            <?php 
                // 投稿内容、表示
                require_once '/var/www/html/pages/components/container_each_post.php';
            ?>

        </div>
        
    </main>

    <?php
        // モーダルウィンドウ
        require_once '/var/www/html/pages/components/modal_edit.php';
    ?>
    
    <?php
        // Javascrip、読み込み
        // ログイン中のみ
        if(isset($_SESSION['login'])) {
            echo '<script src="/js/modal_edit_delete.js"></script>';
        }
    ?>
    
    <!-- Javascript、サムネイル   -->
    <script src="/js/thumbnail_each_post.js"></script>

</body>
</html>