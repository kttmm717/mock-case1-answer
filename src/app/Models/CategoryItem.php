<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    use HasFactory;

    protected $table = 'category_items';
    //テーブル名がモデル名の複数形と異なる場合は、手動でテーブル名を指定する
    //仮に指定しない場合、category_itemsではなくcategory_itemというテーブルを探してしまう

    protected $primaryKey = ['item_id', 'category_id'];

    public $incrementing = false;

    protected $fillable = [
        'item_id',
        'category_id'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }
}
