<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $primaryKey = ['user_id', 'item_id'];
    //通常idが主キーになるが、この記述によってuser_idとitem_idの組み合わせを主キーにしている
    
    public $incrementing = false;
    //複合主キーを使う場合は、オートインクリメントを無効にする必要がある

    protected $fillable = [
        'user_id',
        'item_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }
}
