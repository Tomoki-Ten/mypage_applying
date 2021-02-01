'use strict'
{
    // サムネイルの画像、押された場合、大きく表示
    const display_img = document.getElementById('display_img');
    
    const thumbs= document.querySelectorAll('.thumb');

    thumbs.forEach(function(item, index) {
        item.onclick = function() {
            display_img.src = `./storage_images/${this.dataset.image}`;
        }
    });
}