document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('shop-confirm-modal');
    const span = document.querySelector('.close-modal-button');
    const imageInput = document.getElementById('image');
    const editButton = document.querySelector('.edit-button');
    const form = document.querySelector('.manage_form');

    // ファイル名の更新と画像プレビュー機能
    function updateFileName(input) {
        const fileName = input.files[0]?.name || '写真を選択';
        document.getElementById('file-name').textContent = fileName;

        // 画像プレビュー機能の追加
        const preview = document.querySelector('.preview-image-container');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.innerHTML = `<img src="${e.target.result}" class="preview-image">`;
                preview.style.display = 'inline-block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.innerHTML = '';
            preview.style.display = 'none';
        }
    }

    // フォーム部分へのスクロール
    function scrollToForm() {
        document.querySelector('.manage_form').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
        // モーダルを閉じる
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // モーダル内のデータを更新
    function updateModalData() {
        const shopName = document.querySelector('.shop-name-entry')?.textContent || '';
        const description = document.getElementById('description')?.value || '';
        const openTime = document.getElementById('open_time')?.value || '';
        const closeTime = document.getElementById('close_time')?.value || '';
        const shopImage = document.getElementById('modal-shop-image');

        // 店舗名と説明を更新
        document.getElementById('modal-shop-name').textContent = shopName;
        document.getElementById('modal-shop-description').textContent = description;

        // 営業時間を更新（フォームの値があればそれを使用、なければBladeで表示されている値を使用）
        if (openTime && closeTime) {
            document.getElementById('modal-shop-hours').textContent = `${openTime} - ${closeTime}`;
        } else {
            // フォームの値がない場合は、Bladeで表示されている値をそのまま使用（既に設定されている）
        }

        // 画像プレビューがあれば更新、なければBladeで表示されている画像を使用
        const preview = document.querySelector('.preview-image-container img');
        if (preview && preview.src) {
            shopImage.src = preview.src;
        }
        // プレビューがない場合は、Bladeで設定された画像がそのまま表示される
    }

    // 「更新を確認」ボタンのイベントリスナー
    const confirmButton = document.getElementById('confirm-button');
    if (confirmButton) {
        confirmButton.addEventListener('click', function () {
            // モーダル内のデータを更新して表示
            updateModalData();
            if (modal) {
                modal.style.display = 'flex';
            }
        });
    }

    // 画像入力フィールドのイベントリスナー
    if (imageInput) {
        imageInput.addEventListener('change', function () {
            updateFileName(this);
        });
    }

    // 修正ボタンのイベントリスナー
    if (editButton) {
        editButton.addEventListener('click', function () {
            scrollToForm();
        });
    }

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

    // フォーム送信時にモーダル内のデータを更新
    if (form) {
        form.addEventListener('submit', function () {
            // フォーム送信前にモーダル内のデータを更新
            updateModalData();
        });
    }
});
