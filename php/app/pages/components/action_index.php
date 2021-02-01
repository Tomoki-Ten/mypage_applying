<?php
    require_once '/var/www/html/vendor/autoload.php';

    use Classes\DB;
    use Classes\BackTo;

    try {
        $db = new DB('posts');

        // 全投稿数、取得
        $num_of_rows = $db->count('id')->exec();

        // print_r($num_of_rows);

        if ($num_of_rows == false) {
            echo '投稿がありません。投稿してください。';
            // exit();
        }

        // 全投稿数を１ページあたりの表示件数で割る
        $float_page = $num_of_rows / 1; 
        // 表示する、最大ページ数
        $num_of_pages = ceil($float_page);

        // page_num、代入
        $next_page = ($page_num - 1) * 1;
        
        // <
        $page_minus = $page_num - 1;
        // >
        $page_plus = $page_num + 1;


        $db = new DB('posts');
        
        $records = $db->selectPlural()
                    ->orderBy('created_at', 'DESC')
                    ->limit($next_page, 1)
                    ->exec();

    } catch(PDOException $e) {

        echo '表示、失敗';
        exit();

        // echo 'ERROR : ' . $e->getMessage();
        // exit();
    }


    echo '<div class="post_container">';

    
        // 投稿、表示部分
        foreach ($records as $record) {

            // エスケープ処理
            $record = sanitizer_loop($record);

            
            echo '<div class="post">';

                // 画像があったら、表示
                if ($record['image_name'] != '') {

                    // 画像、最初にある１枚だけ、表示
                    $images = explode(',', $record['image_name']);

                    foreach ($images as $image) {

                        if (file_exists('/var/www/html/storage_images/' . $image)) {
    
                            echo <<< _IMAGE
                                <a href="/each_post.php?post_id={$record['id']}">
        
                                    <img class="post_image" src="./storage_images/{$image}">
        
                                </a>
                            _IMAGE;
                            break;
                        }
                    }
                }

                // 文章、長さ、調整
                // 画像サイズと同じ高さになるように調整
                if (mb_strlen($record['post'], 'utf8') > 250) {
                    $record['post'] = mb_strimwidth($record['post'], 0, 345, "...", 'utf8');
                }

                // 時間、調整
                $created_at = new DateTime($record['created_at']);
                $modified_time = $created_at->format('d, M, Y');
                
                
                // 文章、表示
                    echo <<< _TEXT
                        <div class="post_text_container">

                            <div class="post_title">

                                <a href="/each_post.php?post_id={$record['id']}">{$record['title']}</a>

                            </div>
                            
                            <div class="text_part">
                            
                                <p class="post_text">{$record['post']}</p>
                                
                                <p class="post_time">$modified_time</p>
                            
                            </div>
                            
                        </div>
                    _TEXT;

            echo '</div>';
        }


            // ぺージネーション
            if ($num_of_rows > 1) {

            echo '<div class="pagination_container">';

                // ' < ' マーク
                echo '<div class="page_num">';
                    if ($page_minus == 0) {
                        echo '<a class="disable_page_num"><</a>';

                    } else {
                        echo '<a href="post.php?page_num='. $page_minus .'" class="able_page_num"><</a>';
                    }
                echo '</div>';

                // ページ番号
                for ($i = 1; $i <= $num_of_pages; $i++ ) {
                    echo '<div class="page_num">';

                        if ($i == $page_num) {
                            echo '<a class="disable_page_num">' . $i . "</a>";

                        } else {
                            echo '<a href="post.php?page_num='. $i .'" class="able_page_num">' . $i . "</a>";
                        }
                    echo '</div>';
                }

                // ' > ' マーク
                echo '<div class="page_num">';

                    if ($page_plus > $num_of_pages) {
                        echo '<a class="disable_page_num">></a>';

                    } else {
                        echo '<a href="post.php?page_num='. $page_plus . '" class="able_page_num">></a>';
                    }
                echo '</div>';

            echo '</div>';

            }

    echo '</div>';
