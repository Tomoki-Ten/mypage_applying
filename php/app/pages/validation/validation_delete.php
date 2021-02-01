<?php
    require_once '/var/www/html/pages/functions/functions.php';

    use Classes\DB;
    use Classes\BackTo;

    session_start();
    session_regenerate_id();

    // ログイン、確認
    if (!isset($_SESSION['login']) || !isset($_SESSION['manager'])) {

        BackTo::home();
    }

    // トークン、確認
    if (isset($_POST['token']) || $_SESSION['token'] === $_POST['token']) {

        unset($_SESSION['token']);

    } else {

        BackTo::home();
    }

    $post_id = $_POST['post_id'];

    
    try {
        $db = new DB('posts');

        $record = $db->findId($post_id)
                    ->exec();

        if ($record == false) {

            BackTo::eachPost($post_id, '削除、失敗。');
        }


        $db_del = new DB('posts');

        $db_del->delete()
                ->where('id', '=', $post_id)
                ->exec();

        // ファイル名があった場合、削除
        if ($record['image_name'] != '') {
    
            $image_string = explode(',', $record['image_name']);
    
            foreach ($image_string as $filename) {
    
                if (file_exists('/var/www/html/storage_images/' . $filename)) {
                    
                    unlink('/var/www/html/storage_images/' . $filename);
                }
            }
        }

        BackTo::post('削除しました。');

    } catch(PDOException $e) {

        BackTo::eachPost($post_id, 'a削除、失敗');

        // echo $e->getMessage();
        // exit();
    }
