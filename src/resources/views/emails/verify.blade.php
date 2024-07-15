<table width="100%" cellspacing="0" cellpadding="0" style="max-width: 768px; margin: auto;">
    <tr>
        <td style="text-align: center; padding: 20px;">
            <h1 style="font-size: 24px; color: #333;">メールアドレスの確認</h1>
            <p style="font-size: 16px; color: #555;">{{ $user->user_name }} さん、</p>
            <p style="font-size: 16px; color: #555;">ご登録ありがとうございます！以下のボタンをクリックしてメールアドレスを確認してください。</p>
            <a href="{{ $verificationUrl }}" style="padding: 10px 20px; background-color: #0056b3; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; margin-top: 20px;">メールアドレスを確認する</a>
            <p style="font-size: 16px; color: #555;">もしこのメールに心当たりがない場合は、このメッセージを無視してください。</p>
            <p style="font-size: 16px; color: #555;">よろしくお願いいたします。<br>{{ config('app.name') }}</p>
        </td>
    </tr>
</table>