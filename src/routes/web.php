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
// 登録ページ
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


// ログインページ
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// ログアウト
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// メール認証ルート
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// メール認証の確認
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

    // メール認証リンクの再送信
Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証リンクを再送信しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// 登録完了後の感謝ページ
Route::get('/thanks', [AuthController::class, 'showThanksPage'])->name('thanks');

// その他の認証済みルート
Route::middleware('auth')->group(function () {
    Route::get('/', [ShopController::class, 'shop_list'])->name('index');
    Route::get('/shop/{id}', [ShopController::class, 'show'])->name('shop.detail');
});


require __DIR__.'/auth.php';
