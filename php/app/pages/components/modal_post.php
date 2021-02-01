<!-- モーダルウィンドウ -->
<!-- 黒い背景 -->
<div id="mask" hidden></div>

<!-- 投稿フォーム -->
<div id="modal_body" hidden>

    <!-- クローズ -->
    <div class="close_container">
        <span id="close">X</span>
    </div>
    
    <form action="/pages/validation/validation_post.php" method="POST" enctype="multipart/form-data">
        <!-- タイトル -->
        <div>

            <label for="post_title" class="post_title_label">Title : </label>

            <input type="text" name="post_title" id="post_title" required>

        </div>

        <!-- 文章 -->
        <div>
            <label for="post_text" class="post_text_label">Text : </label>
        </div>

        <div class="text_container">

            <textarea name="post_text" id="post_text" cols="73" rows="7" wrap="hard" required></textarea>

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
        <div id="preview_holder" hidden>

            <img id="preview" src="" alt="preview_image" hidden>

        </div>

        <input type="hidden" name="token" value="<?= $token?>">

        <!-- 送信 -->
        <label id="submit_label">
            POST
            <input type="submit" id="submit_post">
        </label>

    </form>
</div>
