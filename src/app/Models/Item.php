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
    public function messages() {
        return $this->hasMany(Message::class);
    }
    public function latestMessage() {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    // メソッド定義
    public function categories() {
        $categories = $this->categoryItem->map(function($item) {
            return $item->category;
        });
        return $categories;
    }
    public function sold() {
        return SoldItem::where('item_id', $this->id)->exists();
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
    }
    
}
