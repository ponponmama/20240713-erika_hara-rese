// モーダルを閉じる
function closeRegistrationModal() {
    const modal = document.getElementById('shop-registration-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// ×ボタンで閉じる
document.addEventListener('DOMContentLoaded', function () {
    // 店舗登録成功時にモーダルを表示
    const registrationModal = document.getElementById('shop-registration-modal');
    if (registrationModal) {
        // モーダルが存在する場合、表示する（Bladeでstyle="display: flex;"が設定されている場合）
        registrationModal.style.display = 'flex';
    }

    // ファイル名表示
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function () {
            var fileName = this.files[0].name;
            var fileLabel = document.getElementById('file-name');
            if (fileLabel) {
                fileLabel.textContent = fileName;
            }
        });
    }

    const closeBtn = document.querySelector('.close-modal-button');
    if (closeBtn) {
        closeBtn.onclick = function () {
            closeRegistrationModal();
        }
    }

    // モーダル外をクリックして閉じる
    window.onclick = function (event) {
        const modal = document.getElementById('shop-registration-modal');
        if (event.target == modal) {
            closeRegistrationModal();
        }
    }
});
