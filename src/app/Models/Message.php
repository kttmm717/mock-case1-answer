<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function item() {
        return $this->belongsTo(Item::class);
    }
    public function myself() {
        return $this->belongsTo(User::class, 'myself_id');
    }
    public function partner() {
        return $this->belongsTo(User::class, 'partner_id');
    }
}
