<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function create($item_id) {
    // ルートパラメータに{item_id}があるので、引数に$item_idを渡すことができる
        Like::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id
        ]);
        return back();
    }
    public function destory($item_id) {
        Like::where(['user_id'=>Auth::id(), 'item_id'=>$item_id])->delete();
        return back();
    }
}
