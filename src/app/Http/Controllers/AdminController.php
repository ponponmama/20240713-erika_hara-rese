<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\CreateShopManagerRequest;

class AdminController extends Controller
{
    // 管理ダッシュボード表示
    public function index()
    {
        $shops = Shop::all();
        return view('admin.dashboard',compact('shops'));
    }

    public function __construct()
    {
        $this->middleware('auth');  // ログインしていることを確認
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 1) {
                abort(403);  // admin でなければアクセス禁止
            }
            return $next($request);
        });
    }

    // shop_manager の管理画面
    public function manageShopManagers()
    {
        $managers = User::where('role', '2')->get();
        return view('admin.manage-shop-managers', ['managers' => $managers]);
    }

    //店舗代表者を作成
    public function createShopManager(CreateShopManagerRequest $request)
    {
        $validated = $request->validated();

        try {
            $user = User::create([
                'user_name' => $validated['user_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 2,
                'email_verified_at' => now(),
            ]);

            $shop = Shop::find($validated['shop_id']);
            $shop->user_id = $user->id;
            $shop->save();

            return redirect()->route('admin.dashboard')->with('admin_success', '新しいShopManagerが正常に登録されました');
        } catch (\Exception $e) {
            Log::error('Shop manager creation error: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('admin_error', '店舗代表者の登録に失敗しました');
        }
    }

    //新しい店舗作成
    public function createShop(StoreShopRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // 地域とジャンルをデータベースに保存または取得
            $area = Area::firstOrCreate(['area_name' => $validated['area_name']]);
            $genre = Genre::firstOrCreate(['genre_name' => $validated['genre_name']]);

            $shop = new Shop([
                'shop_name' => $validated['shop_name'],
                'description' => $validated['description'],
                'open_time' => $validated['open_time'],
                'close_time' => $validated['close_time'],
            ]);

            if ($request->hasFile('image')) {
                $shop->image = $request->file('image')->store('images', 'public');
            }

            $shop->save();

            // 中間テーブルに関連付け
            $shop->areas()->attach($area->id);
            $shop->genres()->attach($genre->id);

            DB::commit();
            return redirect()->route('admin.dashboard')
                ->with('shop_success', '新規店舗が正常に登録されました。')
                ->with('new_shop_id', $shop->id);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in createShop', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('admin.dashboard')->with('shop_error', '店舗の登録に失敗しました');
        }
    }

    // 管理者権限を確認してshop管理者を削除するメソッド
    public function destroy(User $user)
    {
        if (auth()->user()->role !== 1) {
            abort(403);
        }

        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'ユーザーを正常に削除しました。');
    }

    // レビュー詳細を取得するメソッド
    public function getReviewDetails($id)
    {
        try {
            $review = \App\Models\Review::with(['shop', 'user'])->findOrFail($id);

            return response()->json([
                'created_at' => $review->created_at->format('Y/m/d H:i'),
                'shop_name' => $review->shop->shop_name,
                'user_name' => $review->user->user_name,
                'rating' => $review->rating,
                'comment' => $review->comment
            ]);
        } catch (\Exception $e) {
            Log::error('Review details error: ' . $e->getMessage());
            return response()->json(['error' => 'レビュー詳細の取得に失敗しました'], 500);
        }
    }

    // レビューを削除するメソッド
    public function destroyReview(\App\Models\Review $review)
    {
        try {
            $review->delete();
            return redirect()->route('admin.reviews')->with('success', 'レビューを削除しました');
        } catch (\Exception $e) {
            Log::error('Review delete error: ' . $e->getMessage());
            return redirect()->route('admin.reviews')->with('error', 'レビューの削除に失敗しました');
        }
    }

    // 店舗一覧を表示するメソッド
    public function shopsList()
    {
        /** @var \Illuminate\Pagination\LengthAwarePaginator $shops */
        $shops = Shop::with(['areas', 'genres'])->paginate(10);
        $shops->onEachSide(0);
        return view('admin.shops_list', compact('shops'));
    }

    // 店舗詳細を取得するメソッド
    public function getShopDetails($id)
    {
        try {
            $shop = Shop::with(['areas', 'genres'])->findOrFail($id);

            return response()->json([
                'shop_name' => $shop->shop_name,
                'description' => $shop->description,
                'open_time' => $shop->open_time,
                'close_time' => $shop->close_time,
                'image' => $shop->image,
                'areas' => $shop->areas->map(function($area) {
                    return ['area_name' => $area->area_name];
                }),
                'genres' => $shop->genres->map(function($genre) {
                    return ['genre_name' => $genre->genre_name];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Shop details error: ' . $e->getMessage());
            return response()->json(['error' => '店舗詳細の取得に失敗しました'], 500);
        }
    }

    // 店舗を削除するメソッド
    public function destroyShop(Shop $shop)
    {
        try {
            DB::beginTransaction();

            // 画像ファイルの削除
            if ($shop->image && Storage::disk('public')->exists($shop->image)) {
                Storage::disk('public')->delete($shop->image);
            }

            // 店舗に関連付けられている店舗代表者（ユーザー）を取得
            $shopManager = User::where('id', $shop->user_id)->where('role', 2)->first();

            // 店舗の削除（中間テーブルの関連付けも自動的に削除される）
            $shop->delete();

            // 店舗代表者が存在する場合は削除
            if ($shopManager) {
                $shopManager->delete();
            }

            DB::commit();
            return redirect()->route('admin.shops.list')->with('success', '店舗と店舗代表者を削除しました');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Shop delete error: ' . $e->getMessage());
            return redirect()->route('admin.shops.list')->with('error', '店舗の削除に失敗しました');
        }
    }
}