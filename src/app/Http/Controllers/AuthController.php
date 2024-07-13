   <?php

   namespace App\Http\Controllers;

   use App\Models\User;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\Hash;
   use Illuminate\Foundation\Auth\EmailVerificationRequest;

   class AuthController extends Controller
   {
       public function register(Request $request)
       {
           $request->validate([
               'name' => 'required|string|max:255',
               'email' => 'required|string|email|max:255|unique:users',
               'password' => 'required|string|min:8|confirmed',
           ]);

           $user = User::create([
               'name' => $request->name,
               'email' => $request->email,
               'password' => Hash::make($request->password),
           ]);

           Auth::login($user);

           return redirect()->route('index');
       }

       public function login(Request $request)
       {
           $request->validate([
               'email' => 'required|string|email',
               'password' => 'required|string',
           ]);

           if (Auth::attempt($request->only('email', 'password'))) {
               return redirect()->route('index');
           }

           return back()->withErrors([
               'email' => 'The provided credentials do not match our records.',
           ]);
    
       }

       public function verify(EmailVerificationRequest $request)
        {
            $request->fulfill();
            return redirect()->route('thanks');
        }

       public function showThanksPage()
        {
            return view('auth.thanks');
        }
   }