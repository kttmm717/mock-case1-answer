<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Message;
use App\Models\User;
use App\Http\Requests\MessageRequest;

class DealController extends Controller
{
    public function index($item_id) {
        $item = Item::find($item_id);
        $formattedPrice = number_format($item->price);
        $user = Auth::user();

        $messages = Message::where('item_id', $item->id)
                            ->orderBy('created_at', 'asc')
                            ->get();
        
        $partner = null;
        foreach($messages as $message) {
            if($message->myself_id === $user->id) {
                $partner = $message->partner;
                break;
            }elseif($message->partner_id === $user->id) {
                $partner = $message->myself;
                break;
            }
        }

        return view('deal', compact('item', 'formattedPrice', 'user', 'messages', 'partner'));
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
}
