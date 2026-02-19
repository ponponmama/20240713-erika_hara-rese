(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('reserve-form');
        const dateInput = document.getElementById('date');
        if (!form || !dateInput) return;

        const ack = form.querySelector('input[name="date_acknowledged"]');
        const baseUrl = form.getAttribute('data-update-url');
        if (!baseUrl) return;

        // カレンダーで日付に触れたら「選択した」とみなす（今日を選び直しただけでも change が飛ばないため）
        function setDateAcknowledged() {
            if (ack) ack.value = '1';
        }
        dateInput.addEventListener('click', setDateAcknowledged);
        dateInput.addEventListener('change', setDateAcknowledged);

        dateInput.addEventListener('change', function () {
            if (!this.value) return; // 日付が空のときは何もしない（updateDate に空で飛ばさない）
            const timeValue = document.getElementById('time').value;
            const numberValue = document.getElementById('number').value;
            let url = baseUrl + '?date=' + encodeURIComponent(this.value);
            if (timeValue) url += '&time=' + encodeURIComponent(timeValue);
            if (numberValue) url += '&number=' + encodeURIComponent(numberValue);
            window.location.href = url;
        });
    });
})();
