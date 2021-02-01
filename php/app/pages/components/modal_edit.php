<!-- モーダルウィンドウ -->
<!-- 背景 -->
<div id="mask" hidden></div>

<!-- 投稿フォーム -->
<div id="modal_body" hidden>

    <!-- クローズ -->
    <div class="close_container">
        <span id="close">X</span>
    </div>
    
    <form action="/pages/validation/validation_edit.php" method="POST" enctype="multipart/form-data">
        <!-- タイトル -->
        <div>

            <label for="post_title" class="post_title_label">Title : </label>

            <input type="text" name="post_title" id="post_title" value="<?= $record['title'] ?>" required>

        </div>

        <!-- 文章 -->
        <div>

            <label for="post_text" class="post_text_label">Text : </label>

        </div>

        <div class="text_container">

            <textarea name="post_text" id="post_text" cols="73" rows="7" wrap="hard" required><?= $record['post'] ?></textarea>

        </div>
        
        <!-- ファイルを選択 -->
        <div class="btn_upload_container">

            <label for="file_selector" class="file_selector_label">

                Upload-Image

                <input type="file" name="post_images[]" id="file_selector" accept="image/png,image/jpeg" multiple>
                
            </label>

            <!-- イメージ、キャンセル -->
            <span id="btn_cancel">Cancel</span>

        </div>

        <!-- イメージ、表示部分 -->
        <?php
            // イメージ、ある場合、ない場合
            if ($record['image_name'] != '') {

                $image_name = explode(',', $record['image_name']);

                // プレビュー、表示
                foreach ($image_name as $image) {
                    // 1枚以上ある場合、最初の1枚、表示
                    if (file_exists('/var/www/html/storage_images/' . $image)) {

                        echo <<< _HAS_IMAGE
        
                            <div id="preview_holder">
                            
                                <img id="preview" src="./storage_images/{$image}" alt="preview_image">
        
                                <input type="checkbox" name="check_image" value="exist" id="image_checker" checked>
                                
                            </div>
                        _HAS_IMAGE;

                        break;
                    }
                }
            } else {
                echo <<< _DOESNT_HAVE_IMAGE

                    <div id="preview_holder" hidden>

                        <img id="preview" src="" alt="preview_image" hidden>

                        <input type="checkbox" name="check_image" id="image_checker" value="">

                    </div>
                _DOESNT_HAVE_IMAGE;
            }
        ?>

        <input type="hidden" name="post_id" value="<?= $record['id']; ?>">
        
        <input type="hidden" name="token" value="<?= $token; ?>">

        <!-- 送信 -->
        <label id="submit_label">
            Edit
            <input type="submit" id="submit_post">
        </label>

    </form>
</div>