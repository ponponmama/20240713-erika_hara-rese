<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    // 管理ダッシュボード表示
    public function index()
    {
        return view('admin.dashboard');
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

    public function createShopManager(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'user_name' => $validated['user_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 2,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'ShopManagerが正常に作成されました');
    }

    public function destroy(User $user)
    {
        // 削除前に適切な権限があるか確認
        if (!auth()->user()->can('delete', $user)) {
           abort(403);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
