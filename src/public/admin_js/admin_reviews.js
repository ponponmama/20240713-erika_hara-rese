document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('review-modal');
    const closeButton = document.querySelector('.close-modal-button');
    const detailButtons = document.querySelectorAll('.detail-button');
    const deleteForm = document.getElementById('delete-form');

    // 詳細ボタンクリック時
    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const reviewId = this.getAttribute('data-review-id');

            // レビュー詳細を取得して表示
            fetch(`/admin/reviews/${reviewId}/details`)
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Received data:', data);

                    // 各要素にデータを設定
                    document.getElementById('modal-review-created-at').textContent = data.created_at;
                    document.getElementById('modal-review-shop-name').textContent = data.shop_name;
                    document.getElementById('modal-review-user-name').textContent = data.user_name;
                    document.getElementById('modal-review-comment').textContent = data.comment;

                    // 評価を星で表示
                    let starsHtml = '';
                    for (let i = 1; i <= 5; i++) {
                        if (i <= data.rating) {
                            starsHtml += '<i class="fas fa-star review-star"></i>';
                        } else {
                            starsHtml += '<i class="far fa-star review-star-empty"></i>';
                        }
                    }
                    document.getElementById('modal-review-rating').innerHTML = starsHtml;

                    // 削除フォームのアクションを設定
                    deleteForm.action = `/admin/reviews/${reviewId}`;
                    deleteForm.setAttribute('data-review-id', reviewId);

                    // モーダルを表示
                    modal.style.display = 'flex';
                })
                .catch(error => {
                    console.error('Error fetching review details:', error);
                });
        });
    });

    // モーダルを閉じる
    if (closeButton) {
        closeButton.onclick = function () {
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
