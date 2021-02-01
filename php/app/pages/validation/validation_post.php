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
    if (isset($_POST['token']) && $_SESSION['token'] === $_POST['token'] ) {

        unset($_SESSION['token']);

    } else {

        BackTo::home();
    }

    // 管理者ID、取り出し
    $manager_id = $_SESSION['manager'];

    // タイトル、文章、処理
    // 空白削除
    $_POST = trim_loop($_POST);

    $title = $_POST['post_title'];
    $text = $_POST['post_text'];

    // タイトル、文章が空だった場合
    if ($title == '' || $text == '') {

        BackTo::post('タイトル,又は文章が入力されていません。');
    }

    // ファイル、処理
    $files = $_FILES['post_images'];
    $count = count($files['name']);
    $file_keys = array_keys($files);

    // $key と $i を入れ替えた後に値をいれる配列
    $file_array = [];

    // 画像の名前群、データベースに複数のファイルの名前を保存するための
    $file_names = [];

    
    // ファイルがある場合
    if ($files['name'][0] != '') {

        for ($i = 0; $i < $count; $i++) {

            // name,tmp_name,size などの $key を $i と入れ替える
            // $i から $key を呼び出せるように、
            foreach ($file_keys as $key) {
                $file_array[$i][$key] = $files[$key][$i];
            }

            // アップロードされたデータかチェック
            $is_uploaded = is_uploaded_file($file_array[$i]['tmp_name']);

            if ($is_uploaded === false) {

                BackTo::post('画像処理、失敗。');
            }

            // 画像、サイズチェック、移動
            if ($file_array[$i]['size'] > 5000000) {

                BackTo::post('画像サイズが大き過ぎます。');
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

                BackTo::post('画像処理、失敗。');
            }

            // ランダムなファイル名、作成
            $bytes = filename_maker($file_array[$i]['name']);

            // ファイル名と拡張子、繋ぐ
            $name = $bytes . $ext;
            
            $from = $file_array[$i]['tmp_name'];

            $uploads_dir = '/var/www/html/storage_images';

            $is_moved = move_uploaded_file($from, "$uploads_dir/$name");

            if ($is_moved === false) {

                BackTo::post('画像処理、失敗。');
            }

            // ファイルの名前、配列にいれる
            $file_names[] = $name;
        }

        // 配列、文字列化
        $file_string = implode(',', $file_names);
        
    } else {
        // 文字列、なし
        $file_string = '';
    }

    try {
        $db = new DB('posts');

        $params = [
            'manager_id' => $manager_id,
            'title' => $title,
            'post' => $text,
            'image_name' => $file_string
        ];

        $db->insert($params)
            ->exec();

        BackTo::post('投稿されました。');

    } catch(PDOException $e) {

        BackTo::post('投稿、失敗a。');

        // echo $e->getMessage();
        // exit();
    }
