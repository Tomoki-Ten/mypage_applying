'use strict'
{
    // API KEY
    const key = 'AIzaSyDLXsIIuqnbr2-ftVDqg2gaBvCMZXDaUTc';
    
    // Search ボタン
    const search_btn = document.getElementById('search_btn');
    // 入力フォーム
    const input = document.getElementById('search_input');
    // 検索結果を表示する、ul
    const api_container = document.getElementById('api_container');
    // nextPageTokenを保留する為の input, type="hidden"
    const token_keeper = document.getElementById('token_keeper');
    // 検索した文字を保留する為の input, type="hidden"
    const key_keeper = document.getElementById('key_keeper');
    // More...ボタン
    const more_container = document.querySelector('.more_container');
    const more = document.querySelector('.more');


    // １回目、API、リクエスト処理
    async function api_call(q)
    {
        const url = 'https://www.googleapis.com/youtube/v3/search?';

        const part = 'part=snippet&';

        const maxResults = 'maxResults=10&';


        const data = await fetch(url + 'key=' + key + '&' + part + maxResults + 'q=' + q);
        
        const response = await data.json();

        
        for (let i = 0; i < response.items.length; i++) {
            const list = document.createElement('li');

            const img = document.createElement('img');

            const div = document.createElement('div');
            div.classList.add('api_text');

            const div_text = document.createElement('div');
            const h3 = document.createElement('h3');
            const p = document.createElement('p');
            const div_pub = document.createElement('div');
            div_pub.classList.add('published_at');


            img.src = response.items[i].snippet.thumbnails.medium.url;
            h3.innerHTML = response.items[i].snippet.title;
            p.innerHTML = response.items[i].snippet.description;

            div_pub.innerHTML = timeFormat(response.items[i].snippet.publishedAt);

            
            div_text.appendChild(h3);
            div_text.appendChild(p);

            div.appendChild(div_text);
            div.appendChild(div_pub);
            
            list.appendChild(img);
            list.appendChild(div);
            
            api_container.appendChild(list);
        }

        // YoutubeAPIの nextPageToken、保留
        token_keeper.value = response.nextPageToken;
    }


    // ２回目以降、API、リクエスト処理
    async function next_call(q, token)
    {
        const url = 'https://www.googleapis.com/youtube/v3/search?';

        const part = 'part=snippet&';

        const maxResults = 'maxResults=10&';

        const pageToken = 'pageToken=';


        const data = await fetch(url + 'key=' + key + '&' + part + maxResults + pageToken + token + '&q=' + q);
        
        const response = await data.json();

        
        for (let i = 0; i < response.items.length; i++) {
            const list = document.createElement('li');

            const img = document.createElement('img');

            const div = document.createElement('div');
            div.classList.add('api_text');

            const div_text = document.createElement('div');
            const h3 = document.createElement('h3');
            const p = document.createElement('p');
            const div_pub = document.createElement('div');
            div_pub.classList.add('published_at');


            img.src = response.items[i].snippet.thumbnails.medium.url;
            h3.innerHTML = response.items[i].snippet.title;
            p.innerHTML = response.items[i].snippet.description;

            div_pub.innerHTML = timeFormat(response.items[i].snippet.publishedAt);


            div_text.appendChild(h3);
            div_text.appendChild(p);

            div.appendChild(div_text);
            div.appendChild(div_pub);
            
            list.appendChild(img);
            list.appendChild(div);
            
            api_container.appendChild(list);
        }

        // YoutubeAPIの nextPageToken、保留
        token_keeper.value = response.nextPageToken;
    }



    // More...ボタン、表示
    function appear()
    {
        more_container.hidden = false;
        more.hidden = false;
    }


    // 時間の表示、調整
    function timeFormat(time)
    {
        const pub_at = new Date(time);
        const y = pub_at.getFullYear();
        const m = pub_at.getMonth() + 1;
        const d = pub_at.getDate();
        const h = pub_at.getHours();
        const min = pub_at.getMinutes();

        return y + '/' + m + '/' + d;
        // return y + '/' + m + '/' + d + ', ' + h + ':' + min;
    }
    
    
    // Search、押された時の動作
    search_btn.addEventListener('click', () => {

        if (input.value != '') {

            // 既に表示されている検索結果、削除
            while (api_container.firstChild) {
                api_container.removeChild(api_container.firstChild);
            }
            
            const q = input.value;
    
            // 検索した、文字を保留
            key_keeper.value = input.value;
            // フォームを空にする
            input.value = '';
            
            // fetch、処理
            api_call(q);
            // More...ボタン、表示
            appear();
        }
    });


    // More...、押された時の動作
    more.addEventListener('click', () => {

        // 保留されている文字
        const q = key_keeper.value;

        // Youtube API 
        const token = token_keeper.value;

        // fetch、処理
        next_call(q, token);
    });
}