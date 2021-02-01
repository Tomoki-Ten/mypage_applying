<?php
    require_once '/var/www/html/pages/functions/functions.php';

    use Classes\DB;
    use Classes\BackTo;


    session_start();
    session_regenerate_id();


    if (isset($_GET['code']) === false) {

        BackTo::home();
    }

    $code = $_GET['code'];

    $code_array = explode('.', $code);


    // 16進数以外が送信されてきた場合
    foreach ($code_array as $each_code) {
        
        if (preg_match("/[^0-9a-f]/", $each_code) === 1) {

            BackTo::home();
        }
    }


    $email = hex2bin($code_array[0]);
    $hex = $code_array[1];


    try {
        $db = new DB('managers');

        // 登録されたメールアドレスと一致するか、確認
        $record = $db->selectOne()
                    ->where('email', '=', $email)
                    ->exec();

        if ($record == false) {

            BackTo::registerMsg('登録、失敗。');
        }

        if ($hex !== $record['code_to_verify']) {

            BackTo::registerMsg('登録、失敗。');
        }


        $db_update = new DB('managers');

        // 確認サイン、有効化
        $params = [
            'verification' => 1
        ];

        $db_update->update($params)
                ->where('email', '=', $email)
                ->exec();

        BackTo::loginMsg('登録が完了しました。');

    } catch(PDOException $e) {

        BackTo::registerMsg('登録、失敗。');
        
        // echo $e->getMessage();
        // exit();
    }

