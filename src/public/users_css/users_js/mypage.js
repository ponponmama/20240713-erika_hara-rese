function toggleEditForm(reservationId) {
    const viewElement = document.getElementById(`view-${reservationId}`);
    const editElements = document.querySelectorAll(`#edit-${reservationId}`);
    const updateButton = document.getElementById(`update-button-${reservationId}`);
    const editButton = document.querySelector(`button[onclick="toggleEditForm(${reservationId})"]`);
    const cancelButton = document.getElementById(`cancel-button-${reservationId}`);
    // 削除ボタンを取得
    const deleteButton = document.querySelector(`button[form="delete-form-${reservationId}"]`);

    if (!viewElement.classList.contains('hide')) {
        // 表示モードから編集モードへ
        viewElement.classList.add('hide');
        editElements.forEach(element => {
            element.classList.add('show');
        });
        updateButton.classList.add('show');
        cancelButton.classList.add('show');
        editButton.classList.add('hide');
        // 削除ボタンを非表示にする
        deleteButton.classList.add('hide');
    } else {
        // 編集モードから表示モードへ
        viewElement.classList.remove('hide');
        editElements.forEach(element => {
            element.classList.remove('show');
        });
        updateButton.classList.remove('show');
        cancelButton.classList.remove('show');
        editButton.classList.remove('hide');
        // 削除ボタンを再表示する
        deleteButton.classList.remove('hide');
    }
}
