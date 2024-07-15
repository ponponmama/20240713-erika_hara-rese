 <h1>メールアドレスの確認</h1>
 <p>{{ $user->user_name }} さん、</p>

<p>ご登録ありがとうございます！以下のボタンをクリックしてメールアドレスを確認してください。</p>

<a href="{{ $verificationUrl }}" style="padding: 10px; background-color: blue; color: white; text-decoration: none;">メールアドレスを確認する</a>

<p>もしこのメールに心当たりがない場合は、このメッセージを無視してください。</p>

<p>よろしくお願いいたします。<br>{{ config('app.name') }}</p>