<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;
use Stripe\StripeClient;
use App\Models\SoldItem;

class PurchaseController extends Controller
{
    public function index($item_id, Request $request) {
        $item = Item::find($item_id);
        $user = User::find(Auth::id());
        return view('purchase', compact('item', 'user'));
    }

    public function purchase($item_id, Request $request) {
        $item = Item::find($item_id);
        $stripe = new StripeClient(config('stripe.stripe_secret_key'));

        [
            $user_id,
            $amount,
            $sending_postcode,
            $sending_address,
            $sending_building
        ] = [
            Auth::id(),
            $item->price,
            $request->destination_postcode,
            urlencode($request->destination_address),
            urlencode($request->destination_building) ?? null
            //外部サービス（今回はstripe）に送るので日本語をエンコードする必要がある
        ];

        //Stripeで決済を行うには、Checkoutセッションを作成する必要がある！
        $checkout_session = $stripe->checkout->sessions->create([
            'payment_method_types' => [$request->payment_method],
            'payment_method_options' => [
                'konbini' => [
                    'expires_after_days' => 7,
                ],
            ],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => ['name' => $item->name],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => "http://localhost/purchase/{$item_id}/success?user_id={$user_id}&amount={$amount}&sending_postcode={$sending_postcode}&sending_address={$sending_address}&sending_building={$sending_building}",
            //クエリパラメータに購入データを付けて送っている　　　　　　　　　　→ここからクエリパラメータ
        ]);

        return redirect($checkout_session->url);
    }

    public function success($item_id, Request $request) {
        if(!$request->user_id || !$request->amount || !$request->sending_postcode || !$request->sending_address) {
            throw new Exception("You need all Query Parameters (user_id, amount, sending_postcode, sending_address)");
        }

        $stripe = new StripeClient(config('stripe.stripe_secret_key'));

        //クレジットカード決済を行う処理、以下3つの情報が必須
        $stripe->charges->create([
            'amount' => $request->amount, //支払い金額
            'currency' => 'jpy',          //使用する通貨（日本円はjpy）
            'source' => 'tok_visa'        //支払い方法（テスト環境はtok_visa、本番環境は$request->token等）
        ]);

        SoldItem::create([
            'user_id' => $request->user_id,
            'item_id' => $item_id,
            'sending_postcode' => $request->sending_postcode,
            'sending_address' => $request->sending_address,
            'sending_building' => $request->sending_building
        ]);

        return redirect('/')->with('flashSuccess', '決済が完了しました！');
    }
}
