<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Requests\EmailVerificationRequest;
use App\Http\Controllers\DealController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ItemController::class, 'index'])->name('items.list');
Route::get('/item/{item}', [ItemController::class, 'detail'])->name('item.detail');

//ログイン済み＆メール認証済みユーザーだけがアクセスできる
Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/sell', [ItemController::class, 'sellView']);
    Route::post('/sell', [ItemController::class, 'sellCreate']);
    Route::post('item/like/{item_id}', [LikeController::class, 'create']);
    Route::post('/item/unlike/{item_id}', [LikeController::class, 'destory']);
    Route::post('/item/comment/{item_id}', [CommentController::class, 'create']);
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'index'])->middleware('purchase')->name('purchase.index');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'purchase'])->middleware('purchase');
    Route::get('/purchase/{item_id}/success', [PurchaseController::class, 'success']);
    Route::get('/mypage', [UserController::class, 'mypage']);
    Route::get('/mypage/profile', [UserController::class, 'profile']);
    Route::post('/mypage/profile', [UserController::class, 'updateProfile']);
    Route::get('deal/{item_id}', [DealController::class, 'index'])->name('deal.index');
    Route::post('send/{item_id}/{myself_id}/{partner_id}', [DealController::class, 'send']);
    Route::post('buyer/review/{partner_id}/{user_id}/{item_id}', [DealController::class, 'buyerCreateReview']);
    Route::post('seller/review/{partner_id}/{user_id}/{item_id}', [DealController::class, 'sellerCreateReview']);
    Route::patch('message/update/{message_id}', [DealController::class, 'update']);
    Route::delete('message/delete/{message_id}', [DealController::class, 'destory']);
    Route::post('/messages/read/{message_id}', [DealController::class, 'markAsRead']);
});

Route::post('login', [AuthenticatedSessionController::class, 'store'])->middleware('email');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');

Route::post('/email/verification-notification', function () {
    session()->get('unauthenticated_user')->sendEmailVerificationNotification();
    session()->put('resent', true);
    return back()->with('message', 'Verification link sent!');
})->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    session()->forget('unauthenticated_user');
    return redirect('/mypage/profile');
})->name('verification.verify');

