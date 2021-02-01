<?php

    require_once '/var/www/html/vendor/autoload.php';
    
    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable('/var/www/html');
    $dotenv->load();

    // 環境変数、取得
    function env(string $env_param_name)
    {
        $param_name = $_ENV[$env_param_name];
        return $param_name;
    }

    // DB Class 内でPDOを扱う為の関数
    function DB_set()
    {
        $dsn = 'mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_NAME');
        $user = env('DB_USER');
        $password = env('DB_PASSWORD');
        $dbh = new PDO($dsn,$user,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        return $dbh;
    }

    
    // エスケープ
    function sanitizer($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    // エスケープ、配列
    function sanitizer_loop(array $array)
    {
        foreach ($array as $key => $value) {
            $sanitized_value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            $array[$key] = $sanitized_value;
        }
        return $array;
    }

    // 配列の空白削除,ループ
    function trim_loop(array $array)
    {
        foreach ($array as $key => $value) {
            $replaced_value = str_replace("\xe3\x80\x80", "", $value);
            $trimed_value = trim($replaced_value);
            $array[$key] = $trimed_value;
        }
        return $array;
    }

    // トークン、作成
    // セッションにいれる
    function token_creator()
    {
        $bin = random_bytes(16);
        $changed_bin = bin2hex($bin);
        $_SESSION['token'] = $changed_bin;

        return $changed_bin;
    }


    function filename_maker()
    {
        // ランダムの文字列生成
        $bin = random_bytes(30);
        $encoded_filename = bin2hex($bin);

        return $encoded_filename;
    }

    // 画像タイプ、JPEG、PNG のみ有効
    function ext_checker($file_type)
    {
        if ($file_type === 'image/jpeg') {
            $ext = '.jpeg';
            return $ext;
        } elseif ($file_type === 'image/png') {
            $ext = '.png';
            return $ext;
        } else {
            return false;
        }
    }
    

    // 拡張子だけ、切り取る
    function slash_remover($mime_type)
    {
        $five_char = mb_substr($mime_type, -5);

        $slash_char = '/' . $five_char;
        $without_slash = mb_strrchr($slash_char, '/');

        $almost_mime = mb_substr($without_slash, 1);

        $mime = mb_strrchr($almost_mime, '.');
        
        // .jpeg, .jpg, .png を返す
        return $mime;
    }

    function like_basename($file_path)
    {
        $modified_file_path = '/'.$file_path;
        $strrchr_result = mb_strrchr($modified_file_path, '/');
        $substr_result = mb_substr($strrchr_result, 1);
        return $substr_result;
    }