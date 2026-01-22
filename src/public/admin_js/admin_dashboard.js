// モーダルを閉じる
function closeRegistrationModal() {
    const modal = document.querySelector('.registration-modal');
    if (modal) {
        modal.classList.add('hide');
        modal.classList.remove('show');
    }
}

// ×ボタンで閉じる
document.addEventListener('DOMContentLoaded', function () {
    // 店舗登録成功時にモーダルを表示
    const registrationModal = document.querySelector('.registration-modal');
    if (registrationModal) {
        // モーダルが存在する場合、表示する（Bladeでshowクラスが設定されている場合）
        registrationModal.classList.remove('hide');
        registrationModal.classList.add('show');
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
        const modal = document.querySelector('.registration-modal');
        if (event.target == modal) {
            closeRegistrationModal();
        }
    }
});
