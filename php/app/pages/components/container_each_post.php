<?php

    // 画像、あった場合
    if ($record['image_name'] != '') {

        $images_name = explode(',', $record['image_name']);
        $images_count = count($images_name);

        // ディスプレイ、表示
        foreach ($images_name as $image) {
            // 1枚以上ある場合、最初の1枚、表示
            if (file_exists('/var/www/html/storage_images/' . $image)) {
                
                echo '<div id="display"><img id="display_img" src="./storage_images/'. $image .'"></div>';

                break;
            }
        }
        
        // 画像、何枚存在するか、チェック
        $is_exist = 0;
        foreach ($images_name as $each_name) {
            if (file_exists('/var/www/html/storage_images/' . $each_name)) {
                $is_exist++;
            }
        }

        // 2枚以上の場合、表示
        if ($is_exist > 1) {
            
            echo '<div id="image_container">';
            
                for ($i = 0; $i < $images_count; $i++) {
    
                    if (file_exists('/var/www/html/storage_images/' . $images_name[$i])) {
                        
                        list($width, $height) = getimagesize('./storage_images/' . $images_name[$i]);
                        
                        $image_top =  <<< _IMAGE_TOP
                
                            <div class="image_case">
                            
                                <img class="{class} thumb" src="./storage_images/{$images_name[$i]}" alt="posted_image" data-image="{$images_name[$i]}">
                
                            </div>
                        
                        _IMAGE_TOP;
                
                        if ($width >= $height) {
        
                            // 画像が横長の場合
                            $css_class = 'each_image_wider';
                            $image_top = str_replace('{class}', $css_class, $image_top);
        
                        } elseif ($width < $height) {
        
                            // 画像が縦長の場合
                            $css_class = 'each_image_taller';
                            $image_top =  str_replace('{class}', $css_class, $image_top);
                        }
                        
                        // CSSクラスを適応させた状態で、表示
                        echo $image_top;
                    }
                }
            echo '</div>';
        }

    }

        // 文章、表示部分
        echo <<< _EACH_TEXT
            <div class="each_text_container">

                <div class="each_title">

                    <span>
                        {$record['title']}
                    </span>

                </div>

                <div class="long_text_container">

                    <p class="each_text">
                        {$record['post']}
                    </p>

                </div>

            </div>
        _EACH_TEXT;
