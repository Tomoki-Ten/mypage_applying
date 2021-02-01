<?php
    use Classes\DB;
    use Classes\BackTo;

    try{
        $db = new DB('posts');

        $record = $db->findId($post_id)
                    ->exec();

        if ($record == false) {

            BackTo::indexMsg('表示、失敗。');
        }

    } catch(PDOException $e) {

        BackTo::indexMsg('表示、失敗。');

        // echo 'ERROR : ' . $e->getMessage();
        // exit();
    }
    
    // エスケープ
    $record = sanitizer_loop($record);
