<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>支払いフォーム</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .form-row {
            width: 30%;
            margin-bottom: 20px;
            background-color: #f4f4f4;
        }

        /* ボタンのスタイルをカスタマイズ */
        button {
            background-color: #32325d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #5c67f2;
        }
    </style>
</head>
<body>
    <form action="{{ route('payment.process') }}" method="post" id="payment-form">
        @csrf
        <div class="form-row">
            <label for="card-number-element">
                カード番号
            </label>
            <div id="card-number-element">
                <!-- Stripeのカード番号要素がここに挿入されます -->
            </div>
        </div>
        <div class="form-row">
            <label for="card-expiry-element">
                有効期限
            </label>
            <div id="card-expiry-element">
                <!-- Stripeの有効期限要素がここに挿入されます -->
            </div>
        </div>
        <div class="form-row">
            <label for="card-cvc-element">
                CVC
            </label>
            <div id="card-cvc-element">
                <!-- StripeのCVC要素がここに挿入されます -->
            </div>
        </div>
        <div id="card-errors" role="alert"></div>
        <button type="submit">支払う</button>
    </form>

    <script>
        var stripe = Stripe('pk_test_51PmGJ2HFRbtTxjfgZZo9KG9kYXwmjCAAUn1cJRqV7apN8uhrU1RuOXlNGadgH0n16kTGJKPDtsKERO6N5l3QXSnm00FRhEumuA');
        var elements = stripe.elements();
        var style = {
            base: {
                color: "#32325d",
                fontSize: "16px",
            }
        };

        var cardNumber = elements.create('cardNumber', {style: style});
        cardNumber.mount('#card-number-element');

        var cardExpiry = elements.create('cardExpiry', {style: style});
        cardExpiry.mount('#card-expiry-element');

        var cardCvc = elements.create('cardCvc', {style: style});
        cardCvc.mount('#card-cvc-element');

        function setErrorMessage(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        }

        cardNumber.addEventListener('change', setErrorMessage);
        cardExpiry.addEventListener('change', setErrorMessage);
        cardCvc.addEventListener('change', setErrorMessage);

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(cardNumber).then(function(result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', result.token.id);
                    form.appendChild(hiddenInput);

                    form.submit();
                }
            });
        });
    </script>
</body>
</html>