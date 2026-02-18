<table width="100%" style="max-width: 768px; margin: auto; border-spacing: 0; border-collapse: collapse;">
    <tr>
        <td style="text-align: center; padding: 20px;">
            <h1 style="font-size: 24px; color: rgb(0 0 0 / 0.8);">メールアドレスの確認</h1>
            <p style="font-size: 16px; color: rgb(0 0 0 / 0.6);">{{ $user->user_name }} さん、</p>
            <p style="font-size: 16px; color: rgb(0 0 0 / 0.6);">ご登録ありがとうございます！以下のボタンをクリックしてメールアドレスを確認してください。</p>
            <a href="{{ $verificationUrl }}" style="padding: 10px 20px; background-color: rgb(0 0 255 / 1); color: rgb(255 255 255 / 1); text-decoration: none; border-radius: 5px; font-weight: 700; display: inline-block; margin-top: 20px; cursor: pointer;">メールアドレスを確認する</a>
            <p style="font-size: 16px; color: rgb(0 0 0 / 0.8);">もしこのメールに心当たりがない場合は、このメッセージを無視してください。</p>
            <p style="font-size: 16px; color: #555;">よろしくお願いいたします。<br>{{ config('app.name') }}</p>
        </td>
    </tr>
</table>
