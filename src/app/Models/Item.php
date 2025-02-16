<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'img_url',
        'user_id',
        'condition_id'
    ];

    // リレーション定義
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function likes() {
        return $this->hasMany(Like::class);
    }
    public function condition() {
        return $this->belongsTo(Condition::class);
    }    
    public function categoryItem() {
        return $this->hasMany(CategoryItem::class);
    }
    public function comments() {
        return $this->hasMany(Comment::class);
    }

    // メソッド定義
    public function categories() {
        $categories = $this->categoryItem->map(function($item) {
        //現在のitemに関連する全てのcategoryItemレコードにアクセス
        //mapメソッドを使って全てのcategoryItemレコードのコレクションを1つずつループしてcategoryを取り出している
            return $item->category;
        });
        return $categories;
    }
    public function sold() {
        return SoldItem::where('item_id', $this->id)->exists();
        //クエリの結果が存在するかを確認するメソッド
    }
    public function likeCount() {
        return Like::where('item_id', $this->id)->count();
    }
    public function liked() {
        return Like::where(['item_id'=>$this->id, 'user_id'=>Auth::id()])->exists();
    }
    public function getComments() {
        $comments = Comment::where('item_id', $this->id)->get();
        return $comments;
    }
    public function mine() {
        return $this->user_id == Auth::id();
        //$this->user_id：この商品を出品したユーザーのID
        //Auth::id()：現在ログインしているユーザーのID
    }
}
