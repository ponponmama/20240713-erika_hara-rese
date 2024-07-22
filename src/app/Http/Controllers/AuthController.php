<?php

namespace App\Http\Controllers;

   use App\Models\User;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\Hash;
   use Illuminate\Foundation\Auth\EmailVerificationRequest;
   use App\Http\Requests\RegisterRequest;

   class AuthController extends Controller
{
    //register表示
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
       
    // ユーザー登録処理
    public function register(RegisterRequest $request)
    {
           
        $user = User::create([
            'user_name' => $request->user_name,  
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new \Illuminate\Auth\Events\Registered($user));

        Auth::login($user);

       return redirect()->route('verification.notice');
    }

        // Thanksページを表示
    public function showThanksPage()
    {
        return view('auth.thanks');
    }

    // メール認証処理
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        // メール認証後に thanks ページへリダイレクト
        return redirect()->route('thanks');
    }


    //login表示
    public function showLoginForm()
    {
        return view('auth.login');
    }


    // ログイン処理
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (!Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'メールアドレスが認証されていません。認証メールを確認してください。',
                ]);
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => '指定された認証情報が記録と一致しません。',
        ]);
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function mypage()
    {
        $user = auth()->user(); // 現在認証されているユーザーを取得
        $reservations = $user->reservations; // ユーザーモデルに定義されたリレーションを使用して予約情報を取得

        return view('mypage', ['reservations' => $reservations]);
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard'); // 管理者ダッシュボードへリダイレクト
        } elseif ($user->role === 'shop_manager') {
            return redirect()->route('shop_manager.dashboard'); // ショップマネージャーダッシュボードへリダイレクト
        } else {
            return redirect()->route('index'); // 一般ユーザーはホームページへリダイレクト
        }
    }
}