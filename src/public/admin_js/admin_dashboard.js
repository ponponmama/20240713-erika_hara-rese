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
            if (this.files && this.files[0]) {
                var fileName = this.files[0].name;
                var fileLabel = document.getElementById('file-name');
                if (fileLabel) {
                    fileLabel.textContent = fileName;
                }
            }
        });
    }

    // 店舗登録フォームのAjax送信
    const createShopForm = document.querySelector('.create-shop-form');
    if (createShopForm) {
        createShopForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton ? submitButton.textContent : '';

            // ボタンを無効化
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = '送信中...';
            }

            // Ajaxはfetchを使用して送信
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })

            .then(response => {
                if (response.status === 422) {
                    // バリデーションエラー
                    return response.json().then(data => {
                        throw { validation: true, errors: data.errors };
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // 成功時はリダイレクト
                    window.location.href = data.redirect;
                }
            })
            .catch(error => {
                // ボタンを再有効化
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = originalButtonText;
                }

                if (error.validation) {
                    // バリデーションエラーを表示
                    const errorMessages = error.errors;

                    // 既存のエラーメッセージをクリア
                    document.querySelectorAll('.form__error').forEach(el => {
                        el.textContent = '';
                    });

                    // 各フィールドのエラーを表示
                    Object.keys(errorMessages).forEach(field => {
                        const input = document.querySelector(`[name="${field}"]`);
                        if (input) {
                            const formGroup = input.closest('.form-group') || input.closest('.form-group-time');
                            if (formGroup) {
                                const errorElement = formGroup.nextElementSibling;
                                if (errorElement && errorElement.classList.contains('form__error')) {
                                    errorElement.textContent = errorMessages[field][0];
                                }
                            }
                        }
                    });
                } else {
                    alert('エラーが発生しました。もう一度お試しください。');
                }
            });
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
