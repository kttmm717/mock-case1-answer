<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function sold() {
        return SoldItem::where('item_id', $this->id)->exists();
        //クエリの結果が存在するかを確認するメソッド
    }
}
