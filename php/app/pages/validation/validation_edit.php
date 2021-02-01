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
    if (isset($_POST['token']) && $_SESSION['token'] === $_POST['token']) {

        unset($_SESSION['token']);

    } else {

        BackTo::home();
    }


    if (isset($_POST['post_id']) == false) {

        BackTo::home();
    }


    // 空白削除
    $_POST = trim_loop($_POST);

    $post_id = $_POST['post_id'];
    $title = $_POST['post_title'];
    $text = $_POST['post_text'];
    $check_image = $_POST['check_image'] ?? '';


    // タイトル、文章が空だった場合
    if ($title == '' || $text == '') {

        BackTo::eachPost($post_id, 'タイトル,又は文章が入力されていません。');
    }

    // データ、取得
    // 既に画像がアップデートされているか、確認するため
    try{
        $db = new DB('posts');

        $record = $db->findId($post_id)
                    ->exec();

        if ($record == false) {

            BackTo::eachPost($post_id, '編集、失敗。');
        }

    } catch(PDOException $e) {

        BackTo::eachPost($post_id, '編集、失敗。');
        
        // echo $e->getMessage();
        // exit();
    }


    // 画像の名前
    $image_string = $record['image_name'];

    // sqlの指定
    $pattern = 0;

    // ファイル、処理
    $files = $_FILES['post_images'];

    $count = count($files['name']);
    $file_keys = array_keys($files);


    // アップデートファイルあり
    // サイズチェック、移動、削除
    if ($files['name'][0] != '') {
        
        for ($i = 0; $i < $count; $i++) {

            // name,tmp_name,size などの $key を $i と入れ替える
            // $i から $key を呼び出せるように、
            foreach ($file_keys as $key) {
                $file_array[$i][$key] = $files[$key][$i];
            }

            // アップロードされたデータがチェック
            $is_uploaded = is_uploaded_file($file_array[$i]['tmp_name']);

            if ($is_uploaded === false) {

                BackTo::eachPost($post_id, '画像処理、失敗。');
            }

            // 画像、サイズチェック、移動
            if ($file_array[$i]['size'] > 5000000) {

                BackTo::eachPost($post_id, '画像サイズが大き過ぎます。');
            }

            // アップロードされたファイル、移動
            // ディレクトリがない場合は、作成
            $exists = file_exists('/var/www/html/storage_images');

            if ($exists === false) {
                mkdir('/var/www/html/storage_images');
            }


            // 拡張子、チェック
            $ext = ext_checker($file_array[$i]['type']);

            if ($ext === false) {

                BackTo::eachPost($post_id, '画像処理、失敗。');
            }

            // ランダムなファイル名、作成
            $bytes = filename_maker($file_array[$i]['name']);


            // ファイル名と拡張子、繋ぐ
            $name = $bytes . $ext;

            $from = $file_array[$i]['tmp_name'];

            $uploads_dir = '/var/www/html/storage_images';
            
            
            $is_moved = move_uploaded_file($from, "$uploads_dir/$name");
            
            if ($is_moved === false) {

                BackTo::eachPost($post_id, '画像処理、失敗。');
            }

            // ファイルの名前、配列に入れる
            $file_names[] = $name;
        }

        // 配列、文字列化
        $file_string = implode(',', $file_names);

        // 既存のファイル、削除
        if ($image_string != '') {
            $image_name_array = explode(',', $image_string);

            foreach($image_name_array as $old_name) {

                if (file_exists('/var/www/html/storage_images/' . $old_name)) {

                    unlink('/var/www/html/storage_images/' . $old_name);
                }
            }
        }

        // SQL、指定
        $pattern = 1;

    } elseif ($files['name'][0] == '' && $image_string != '' && $check_image == '') {
        // アップデートファイルなし、既に画像が投稿されている、かつ
        // 変更あり（画像を削除）

        // 既存のファイル、削除
        $image_name_array = explode(',', $image_string);

        foreach($image_name_array as $old_name) {

            if (file_exists('/var/www/html/storage_images/' . $old_name)) {

                unlink('/var/www/html/storage_images/' . $old_name);
            }
        }
        // 保存する画像の名前、なし
        $file_string = '';

        // SQL、指定
        $pattern = 1;
    }

    // 実行する、SQL
    try {
        if ($pattern === 1) {

            $db_update = new DB('posts');

            $params = [
                'title' => $title,
                'post' => $text,
                'image_name' => $file_string
            ];

            $db_update->update($params)
                        ->where('id', '=', $post_id)
                        ->exec();

        } else {

            $db_update = new DB('posts');

            $params = [
                'title' => $title,
                'post' => $text
            ];

            $db_update->update($params)
                        ->where('id', '=', $post_id)
                        ->exec();
        }

        // each_post.php に移動
        BackTo::eachPost($post_id, '編集が完了しました。');

    } catch(PDOException $e) {

        BackTo::eachPost($post_id, '編集、失敗。');

        // echo $e->getMessage();
        // exit();
    }
