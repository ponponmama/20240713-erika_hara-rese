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

       return redirect()->route('verification.notice');
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
                    'email' => 'メールアドレスが認証されていません。',
                ]);
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => '指定された認証情報が記録と一致しません。',
        ]);
    }

    // メール認証処理
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        // メール認証後に thanks ページへリダイレクト
        return redirect()->route('thanks');
    }

    // Thanksページを表示
    public function showThanksPage()
    {
        return view('auth.thanks');
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}