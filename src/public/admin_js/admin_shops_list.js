document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('shop-modal');
    const span = document.querySelector('.close-modal-button');
    const detailButtons = document.querySelectorAll('.detail-button');
    const deleteForm = document.getElementById('delete-form');

    // 詳細ボタンクリック時
    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const shopId = this.getAttribute('data-shop-id');

            // 店舗詳細を取得
            fetch(`/admin/shops/${shopId}/details`)
                .then(response => response.json())
                .then(data => {
                    // モーダルにデータを表示
                    document.getElementById('modal-shop-name').textContent = data
                        .shop_name;
                    document.getElementById('modal-shop-description').textContent = data
                        .description;

                    // エリアとジャンルは配列なので結合
                    const areaNames = data.areas.map(area => area.area_name).join(', ');
                    const genreNames = data.genres.map(genre => genre.genre_name).join(
                        ', ');

                    document.getElementById('modal-shop-area').textContent = areaNames;
                    document.getElementById('modal-shop-genre').textContent =
                        genreNames;
                    document.getElementById('modal-shop-hours').textContent =
                        `${data.open_time} - ${data.close_time}`;

                    // 画像がある場合のみ表示
                    if (data.image) {
                        document.getElementById('modal-shop-image').src =
                            `/storage/${data.image}`;
                        document.getElementById('modal-shop-image').alt = data
                            .shop_name;
                        document.getElementById('modal-shop-image-container').style
                            .display = 'block';
                    } else {
                        document.getElementById('modal-shop-image-container').style
                            .display = 'none';
                    }

                    // 削除フォームのアクションを設定
                    deleteForm.action = `/admin/shops/${shopId}`;
                    deleteForm.setAttribute('data-shop-id', shopId);

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
