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

        BackTo::home();
    }


    // 空白削除
    $_POST = trim_loop($_POST);
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $confirm_pass = $_POST['confirm_pass'];

    // 入力チェック
    if ($name == '' || $email == '' || $pass == '' || $confirm_pass == '') {

        BackTo::registerMsg('入力に誤りがあります。');

    } elseif (filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) == false) {

        BackTo::registerMsg('入力に誤りがあります。');

    } elseif ($pass != $confirm_pass) {

        BackTo::registerMsg('パスワード度が一致しません。');
    }

    // パスワード、暗号化
    $pass = password_hash($pass, PASSWORD_DEFAULT);

    // 暗号、メールアドレス確認用
    $bin = random_bytes(40);
    $readable_bin = bin2hex($bin);

    $encoded_email = bin2hex($email);

    try {
        $db = new DB('managers');

        $params = [
            'name' => $name,
            'password' => $pass,
            'email' => $email,
            'code_to_verify' => $readable_bin
        ];
        
        $db->insert($params)
            ->exec();

        BackTo::indexMsg('登録されました。');
            
        // メール送信
        // $from = env('MAIL_ADDRESS');
        // $pass = env('MAIL_PASS');

        // $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
        //             ->setUsername($from)
        //             ->setPassword($pass);

        // $mailer = new Swift_Mailer($transport);

        // $registerd_email = $email;

        // 確認メール、内容
        // $html = <<< _MESSAGE
        //     <div style="background:rgb(249,246,240);height:100%;width100%;margin:0;">
        //         <div>
        //             <p style="text-align:center;">Tomoki Ten's Page への登録を確認しました。</p>
        //             <p style="text-align:center;">下記のボタンを押して、登録を完了してください。</p>
        //         </div>
        //         <form action="/var/www/html/pages/validation/validation_email.php?code={$encoded_email}.{$readable_bin}" method="GET" style="display:flex;justify-content:center;">
        //             <label for="sub" style="padding:5px 10px;border:solid;border-radius:5px;color:rgb(236,134,24);font-wight:bold;">登録を完了</label>
        //             <input type="submit" id="sub" style="display:none;">
        //         </form>
        //     </div>
        // _MESSAGE;

        // $message = (new Swift_Message('Tomoki Ten\'s Page'))
        //         ->setSubject('Tomoki Ten\'s Page')
        //         ->setFrom([$from => 'Tomoki Ten\'s Page'])
        //         ->setTo([$registerd_email])
        //         ->setBody($html, 'text/html');


        // if ($mailer->send($message)) {

        //     BackTo::registerMsg('登録したメールアドレスに登録完了用のメールを送信しました。メールにて登録を完了してください。');
        // } 
        
    } catch(PDOException $e) {
        BackTo::registerMsg('登録、失敗。');

        // echo $e->getMessage();
        // exit();
    }
