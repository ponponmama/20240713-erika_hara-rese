document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('shop-modal');
    const span = document.querySelector('.close-modal-button');
    const detailButtons = document.querySelectorAll('.detail-button');

    // 詳細ボタンクリック時
    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const shopId = this.getAttribute('data-shop-id');

            // 店舗詳細を取得
            fetch(`/admin/shops/${shopId}/details`)
                .then(response => response.json())
                .then(data => {
            // エリアとジャンルを#形式で結合
            const areaTags = data.areas.map(area => `＃${area.area_name}`).join('');
            const genreTags = data.genres.map(genre => `＃${genre.genre_name}`).join('');

            // 営業時間をフォーマット（HH:mm:ss形式からHH:mm形式に変換）
            const formatTime = (timeString) => {
                if (!timeString) return '';
                const time = timeString.split(':');
                return `${time[0]}:${time[1]}`;
            };
            const businessHours = `${formatTime(data.open_time)}～${formatTime(data.close_time)}`;

            // モーダルにデータを表示
            document.getElementById('modal-shop-name').textContent = data.shop_name;
            document.getElementById('modal-shop-description').textContent = data.description;
            document.getElementById('modal-shop-area').textContent = areaTags;
            document.getElementById('modal-shop-genre').textContent = genreTags;
            document.getElementById('modal-shop-hours').textContent = businessHours;

                    // 画像がある場合のみ表示
                    const imageContainer = document.getElementById('modal-shop-image-container');
                    if (data.image) {
                        document.getElementById('modal-shop-image').src =
                            `/storage/${data.image}`;
                        document.getElementById('modal-shop-image').alt = data.shop_name;
                        imageContainer.classList.remove('hide');
                        imageContainer.classList.add('show');
                    } else {
                        imageContainer.classList.add('hide');
                        imageContainer.classList.remove('show');
                    }

                    // モーダルを表示
                    modal.classList.remove('hide');
                    modal.classList.add('show');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('店舗情報の取得に失敗しました');
                });
        });
    });

    // モーダルを閉じる
    if (span) {
        span.onclick = function () {
            modal.classList.add('hide');
            modal.classList.remove('show');
        }
    }

    // モーダル外をクリックして閉じる
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.classList.add('hide');
            modal.classList.remove('show');
        }
    }
});
