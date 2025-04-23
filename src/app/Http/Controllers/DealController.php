<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Message;
use App\Models\User;
use App\Http\Requests\MessageRequest;
use App\Models\SoldItem;
use App\Models\Review;

class DealController extends Controller
{
    public function index($item_id) {
        $item = Item::find($item_id);
        $formattedPrice = number_format($item->price);
        $user = Auth::user();
        $buyer = SoldItem::where('item_id', $item->id)->first();

        $messages = Message::where('item_id', $item->id)
                            ->orderBy('created_at', 'asc')
                            ->get();
        
        $partner = null;

        if($messages->isNotEmpty()) {
            foreach ($messages as $message) {
                if ($message->myself_id === $user->id) {
                    $partner = $message->partner;
                    break;
                } elseif ($message->partner_id === $user->id) {
                    $partner = $message->myself;
                    break;
                }
            }
        }else {
            if($item->user_id === $user->id && $buyer) {
            //その商品の出品者と自分が等しい　かつ　商品が買われてる
                $partner = $buyer->user;
                //パートナーは購入者
            }elseif($buyer && $buyer->user_id === $user->id) {
            //商品が買われてる　かつ　購入者と自分が等しい
                $partner = $item->user;
                //パートナーは商品の出品者
            }
        }

        $otherDeals = SoldItem::where('user_id', $user->id)
                            ->where('transaction_completed', false)
                            ->where('item_id', '!=', $item->id)
                            ->get();

        return view('deal', compact('item', 'formattedPrice', 'user', 'messages', 'partner', 'buyer', 'otherDeals'));
    }

    public function send(MessageRequest $request, $item_id, $myself_id, $partner_id) {
        $myself = User::find($myself_id);
        $partner = User::find($partner_id);

        $img = $request->file('img_url');
        if(isset($img)) {
            $img_url = Storage::disk('local')->put('public/img', $img);
        }else {
            $img_url = '';
        }

        Message::create([
            'item_id' => $item_id,
            'myself_id' => $myself->id,
            'partner_id' => $partner->id,
            'message' => $request->message,
            'img_url' => $img_url,
        ]);

        return back();
    }

    public function buyerCreateReview(Request $request, $partner_id, $user_id, $item_id) {
        Review::create([
            'reviewer_id' => $user_id,
            'reviewee_id' => $partner_id,
            'item_id' => $item_id,
            'rating' => $request->rating,
        ]);
        $soldItem = SoldItem::where('item_id', $item_id)->first();
        $soldItem->update([
            'buyer_reviewed' => true,
        ]);
        
        return redirect('/')->with('flashSuccess', '出品者を評価しました！');
    }
    public function sellerCreateReview(Request $request, $partner_id, $user_id, $item_id)
    {
        Review::create([
            'reviewer_id' => $user_id,
            'reviewee_id' => $partner_id,
            'item_id' => $item_id,
            'rating' => $request->rating,
        ]);
        $soldItem = SoldItem::where('item_id', $item_id)->first();
        $soldItem->update([
            'seller_reviewed' => true,
            'transaction_completed' => true,
        ]);

        return redirect('/')->with('flashSuccess', '購入者を評価して取引が完了しました！');
    }

    public function destory($message_id) {
        $message = Message::find($message_id);
        $message->delete();
        return back();
    }
    public function update(Request $request, $message_id) {
        $message = Message::find($message_id);
        $message->update([
            'message' => $request->message,
        ]);
        return back();
    }
}
