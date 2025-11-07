<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ClearSessionAfterReturn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 次のミドルウェアまたはアプリケーションへリクエストを進める
        $response = $next($request);

        Log::info('ClearSessionAfterReturn middleware executed');
        Log::info('clear_session_on_leave flag: ' . ($request->session()->has('clear_session_on_leave') ? 'true' : 'false'));

        // ユーザーが他のページに遷移する際にセッションデータをクリアするためのフラグをチェック
        if ($request->session()->pull('clear_session_on_leave', false)) {
            // 予約関連のセッションデータをクリア
            $request->session()->forget([
                'reservation_details',
                'selected_date',
                'last_visited_shop_id'
            ]);
            Log::info('Reservation session data cleared');
        }

        Log::info('After clearing session: ' . json_encode($request->session()->all()));

        return $response;
    }
}