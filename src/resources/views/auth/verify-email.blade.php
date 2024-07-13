<!-- resources/views/auth/verify-email.blade.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body>
    <p>メールを確認して、リンクをクリックしてください。メールが届いていない場合は、以下のボタンを押して再送信してください。</p>
    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit">認証リンクを再送信</button>
    </form>
</body>
</html>