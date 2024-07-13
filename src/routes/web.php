<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request; 


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// 登録
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register.form');
Route::post('register', [AuthController::class, 'register'])->name('register');

// ログイン
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('login', [AuthController::class, 'login'])->name('login');

// メール認証
Route::get('email/verify/action', [AuthController::class, 'verify'])->name('verification.verify');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証リンクを再送信しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Thanksページ
Route::get('thanks', [AuthController::class, 'showThanksPage'])->name('thanks');

// ログアウト
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// その他の認証済みルート
Route::middleware('auth')->group(function () {
    Route::get('/', [ShopController::class, 'shop_list'])->name('index');

    Route::get('/shop/{id}', [ShopController::class, 'show'])->name('shop.detail');
});


//require __DIR__.'/auth.php';
