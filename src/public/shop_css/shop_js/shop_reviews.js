document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('review-modal');
    const closeButton = document.querySelector('.close-modal-button');
    const detailButtons = document.querySelectorAll('.detail-button');

    // 詳細ボタンクリック時
    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const reviewId = this.getAttribute('data-review-id');

            // レビュー詳細を取得して表示
            fetch(`/shop-manager/reviews/${reviewId}/details`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // 各要素にデータを設定
                    document.getElementById('modal-review-created-at').textContent = data.created_at;
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

                    // モーダルを表示
                    modal.classList.remove('hide');
                    modal.classList.add('show');
                })
                .catch(error => {
                    console.error('Error fetching review details:', error);
                    alert('レビュー詳細の取得に失敗しました。');
                });
        });
    });

    // モーダルを閉じる
    if (closeButton) {
        closeButton.onclick = function () {
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
