document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('shop-modal');
    const span = document.querySelector('.close-modal-button');
    const detailButtons = document.querySelectorAll('.detail-button');
    const editShopLink = document.getElementById('edit-shop-link');

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
                    const shopGuide = areaTags + genreTags;

                    // 営業時間をフォーマット（HH:mm:ss形式からHH:mm形式に変換）
                    const formatTime = (timeString) => {
                        if (!timeString) return '';
                        const time = timeString.split(':');
                        return `${time[0]}:${time[1]}`;
                    };
                    const businessHours = `営業時間:${formatTime(data.open_time)}～${formatTime(data.close_time)}`;

                    // モーダルにデータを表示
                    document.getElementById('modal-shop-name-header').textContent = data.shop_name;
                    document.getElementById('modal-shop-guide').textContent = shopGuide;
                    document.getElementById('modal-detail-shop-guide').textContent = shopGuide;
                    document.getElementById('modal-description').textContent = data.description;
                    document.getElementById('modal-business-hours').textContent = businessHours;

                    // 画像がある場合のみ表示
                    if (data.image) {
                        document.getElementById('modal-shop-image').src =
                            `/storage/${data.image}`;
                        document.getElementById('modal-shop-image').alt = data.shop_name;
                        document.getElementById('modal-shop-image-container').style
                            .display = 'block';
                    } else {
                        document.getElementById('modal-shop-image-container').style
                            .display = 'none';
                    }

                    // 修正ボタンのリンクを設定（店舗管理者のページへ）
                    editShopLink.href = `/shop-manager/manage-shop`;

                    // モーダルを表示
                    modal.style.display = 'flex';
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
            modal.style.display = 'none';
        }
    }

    // モーダル外をクリックして閉じる
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});
