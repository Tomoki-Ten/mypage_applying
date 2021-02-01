<?php
    require_once '/var/www/html/pages/functions/functions.php';

    use Classes\DB;
    use Classes\BackTo;

    session_start();
    session_regenerate_id();

    
    // トークン、確認
    if (isset($_POST['token']) && $_SESSION['token'] === $_POST['token'] ) {
        
        unset($_SESSION['token']);

    } else {

        BackTo::indexMsg('入力に誤りがあります。');
    }

    $email = $_POST['manager_email'];
    $pass = $_POST['manager_pass'];
    $pass_confirm = $_POST['confirm_pass'];

    // 入力チェック
    if ( $email == '' || $pass == '' || $pass_confirm == '') {

        BackTo::indexMsg('入力に誤りがあります。');

    } elseif ($pass != $pass_confirm) {

        BackTo::indexMsg('入力に誤りがあります。');
    }

    try {
        $db = new DB('managers');

        $manager_data = $db->selectOne()
                            ->where('email', '=', $email)
                            ->exec();


        // 該当、管理者の有無
        if ($manager_data == false) {

            BackTo::indexMsg('入力に誤りがあります。');
        }

        // メールでの登録完了が済んでいるか確認
        // if ($manager_data['verification'] == 0) {

        //     BackTo::loginMsg('登録が完了していません。');
        // }
        
        // パスワード確認
        if (password_verify($pass, $manager_data['password'])) {
            $_SESSION['login'] = 1;
            $_SESSION['manager'] = $manager_data['id'];

            BackTo::post('ログイン、OK');

        } else {

            BackTo::indexMsg('入力に誤りがあります。');
        }

    } catch(PDOException $e) {

        BackTo::indexMsg('入力に誤りがあります。');

        // print $e->getMessage();
        // exit();
    }
