'use strict'
{
    // Editボタン
    const btn_edit = document.getElementById('btn_edit');
    // Deleteボタン、フォーム
    const btn_delete = document.getElementById('btn_delete');
    const form_delete = document.getElementById('form_delete');
    // 黒い背景、投稿用フォーム
    const mask = document.getElementById('mask');
    const modal_body = document.getElementById('modal_body');
    // イメージ選択、表示、要素
    const file_selector = document.getElementById('file_selector');
    const preview_holder = document.getElementById('preview_holder');
    const preview = document.getElementById('preview');
    // キャンセルボタン
    const btn_cancel = document.getElementById('btn_cancel');
    // X、ボタン
    const close = document.getElementById('close');
    // イメージチェッカー
    const image_checker = document.getElementById('image_checker');
    

    // モーダルウィンドウ、黒い背景、表示
    function openModal() {
        mask.hidden = false;
        modal_body.hidden = false;
    }

    function closeModal() {
        mask.hidden = true;
        modal_body.hidden = true;
    }

    // イメージ、読み込み、表示
    function previewImage() {
        const file = file_selector.files[0];
        const reader = new FileReader();

        reader.addEventListener('load', () => {
            preview.src = reader.result;
        });
        if(file) {
            reader.readAsDataURL(file);
        }
    }

    // イメージ、キャンセル
    function cancelImage() {
        if (preview.src) {
        // if (file_selector.value) {
            file_selector.value = '';
            preview.src = '';
            image_checker.checked = false;
            closePreview();
        }
    }

    // <img>要素、表示
    function displayPreview() {
        preview_holder.hidden = false;
        preview.hidden = false;
    }

    // <img>要素、非表示
    function closePreview() {
        preview_holder.hidden = true;
        preview.hidden = true;
    }


    // モーダルウィンドウ、表示
    btn_edit.addEventListener('click', () => {
        openModal();
    });

    // モーダルウィンドウ、非表示
    close.addEventListener('click', () => {
        closeModal();
    });
    mask.addEventListener('click', () => {
        closeModal();
    });
    
    // プレビューイメージ、読み込み時の動作
    file_selector.addEventListener('change', () => {
        previewImage();
        displayPreview();
    });

    // プレビューイメージ、非表示、実行
    btn_cancel.addEventListener('click', () => {
        cancelImage();
    })

    // Deleteボタン、サブミット確認
    btn_delete.addEventListener('click', () => {
        if (window.confirm('この投稿を削除します。よろしいですか？')) {
            form_delete.submit();
        }
    });


    
}