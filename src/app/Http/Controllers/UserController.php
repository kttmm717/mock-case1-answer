<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\User;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\SoldItem;
use App\Models\Item;
use App\Models\Review;
use App\Models\Message;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function profile() {
    //現在ログイン中ユーザーのプロフィール情報を1件取得し、ビューに渡す
        $profile = Profile::where('user_id', Auth::id())->first();
        return view('profile', compact('profile'));
    }
    public function updateProfile(ProfileRequest $request) {
        $img = $request->file('img_url');
        if(isset($img)) {
        //$imgの結果がnullの可能性があるため、isset()使用（nullの場合put()に渡すとエラーになる）
            $img_url = Storage::disk('local')->put('public/img', $img);
            //storage/app/public/imgに$imgを保存
        }else {
            $img_url = '';
        }

        $profile = Profile::where('user_id', Auth::id())->first();
        if($profile) {
        //$profileの結果はprofileインスタンスかnullのどちらかしかないのでif文だけでOK
            $profile->update([
                'user_id' => Auth::id(),
                'img_url' => $img_url,
                'postcode' => $request->postcode,
                'address' => $request->address,
                'building' => $request->building
            ]);
        }else {
            Profile::create([
                'user_id' => Auth::id(),
                'img_url' => $img_url,
                'postcode' => $request->postcode,
                'address' => $request->address,
                'building' => $request->building
            ]);
        }

        //名前はprofilesテーブルではなくusersテーブルなので、別で処理
        User::find(Auth::id())->update([
            'name' => $request->name
        ]);

        return redirect('/');
    }

    public function mypage(Request $request) {
        $user = User::find(Auth::id());
        if($request->page == 'buy') {
            $items = SoldItem::where('user_id', $user->id)->get()->map(function ($sold_item) {
                return $sold_item->item;
            });
        }elseif($request->page == 'deal') {
            $buyerItems = SoldItem::where('user_id', $user->id)
                            ->where('buyer_reviewed', false)
                            ->with('item.user')
                            ->get()
                            ->map(function ($sold_item) {
                                return $sold_item->item;
                            });
                            
            $sellerItems = SoldItem::whereHas('item.user', function ($query) use ($user) {
                $query->where('id', $user->id);
            })
                ->where('transaction_completed', false)
                ->with('item.user')
                ->get()
                ->map(function ($soldItem) {
                    return $soldItem->item;
                });

            $items = $buyerItems->merge($sellerItems)
                ->sortByDesc(function($item) {
                    return optional($item->messages->sortByDesc('created')->first())->created_at;
                });
        }else {
            $items = Item::where('user_id', $user->id)->get();
        }

        $averageRating = Review::where('reviewee_id', $user->id)->avg('rating');
        $averageRatingRound = round($averageRating);

        $unreadCounts = Message::select('item_id', DB::raw('count(*) as unread_count'))
                            ->where('partner_id', $user->id)
                            ->where('is_read', false)
                            ->groupBy('item_id')
                            ->get();
        $totalUnreadCount = Message::where('partner_id', $user->id)
                                ->where('is_read', false)
                                ->count();

        return view('mypage', compact('user', 'items', 'averageRatingRound', 'unreadCounts', 'totalUnreadCount'));
    }
}


