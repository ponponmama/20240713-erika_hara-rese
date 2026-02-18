(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('reserve-form');
        const dateInput = document.getElementById('date');
        if (!form || !dateInput) return;

        const baseUrl = form.getAttribute('data-update-url');
        if (!baseUrl) return;

        dateInput.addEventListener('change', function () {
            const timeValue = document.getElementById('time').value;
            const numberValue = document.getElementById('number').value;
            let url = baseUrl + '?date=' + encodeURIComponent(this.value);
            if (timeValue) url += '&time=' + encodeURIComponent(timeValue);
            if (numberValue) url += '&number=' + encodeURIComponent(numberValue);
            window.location.href = url;
        });
    });
})();
