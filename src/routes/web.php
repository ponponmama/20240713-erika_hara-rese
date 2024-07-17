<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request; 
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\FavoriteController;

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

// メール認証ルート

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
// メール認証リンクの再送信
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', '認証メールを再送信しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');


// Thanksページ
Route::get('thanks', [AuthController::class, 'showThanksPage'])->name('thanks');

// ログアウト
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// その他の認証済みルート
Route::middleware('auth')->group(function () {
    Route::get('/', [ShopController::class, 'shop_list'])->name('index');

    Route::get('/mypage', [AuthController::class, 'mypage'])->name('mypage');

    Route::get('/shop/{id}', [ShopController::class, 'show'])->name('shop.detail');
//お気に入り
    Route::post('/favorite/add/{id}', [FavoriteController::class, 'add'])->name('favorite.add');
    
});


//require __DIR__.'/auth.php';
