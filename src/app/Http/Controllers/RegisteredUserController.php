<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Actions\Fortify\CreateNewUser;

class RegisteredUserController extends Controller
{
    public function store(Request $request, CreateNewUser $creator) {

        event(new Registered($user = $creator->create($request->all())));
        //ユーザーが正常に作成された後、Registeredイベントが発火
        //Registeredイベントは登録完了の通知が行われる

        session()->put('unauthenticated_user', $user);
        //ユーザー情報をunauthenticated_userというセッションに保存
        
        return redirect()->route('verification.notice');
    }
}
