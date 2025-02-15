<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class PurchaseMiddleware
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
        $param = $request->route()->parameter('item_id');
        //$request->route()で現在のリクエストに対するルート情報を取得できる
        //parameter('item_id')でルートパラメータを取得できる

        $item = Item::find($param);
        if($item->user_id == Auth::id()) {
            return redirect()->route('item.detail', ['item'=>$request->item_id])->with('flesh_alert', '出品者が購入することはできません');
        }
        return $next($request);
        //出品者でなければ次の処理(通常のルート)が実行される
    }
}

