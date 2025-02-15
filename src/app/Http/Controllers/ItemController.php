<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\CategoryItem;
use App\Http\Requests\ItemRequest;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request) {
        $tab = $request->query('tab', 'recommend');
        $search = $request->query('search');
        $query = Item::query();
        //itemsテーブルのデータを操作するためのクエリの準備をしている（まだ実行はしない）
        $query->where('user_id', '<>', Auth::id());
        //<>等しくないという意味、自分の商品を除外

        if($tab === 'mylist') {
        //タブがマイリストの時、ログイン中ユーザーがいいねした商品に絞る

            $query->whereIn('id', function($query) {
            //whereInとは、指定したカラムの値が複数の値のいずれかに一致するレコードを取得するためのクエリビルダーメソッド
            //ここでは、itemsのidを指定して一致するレコードを取得する 

                $query->select('item_id')
                //item_idカラムのみを取得
                    ->from('likes')
                    //likesテーブルにitem_idが存在する
                    ->where('user_id', auth()->id());
                    //ログイン中ユーザーである
            });
        }
        if($search) {  //もし$searchが指定されていたら
            $query->where('name', 'like', "%{$search}%");
            //$queryは上で定義した変数
            //itemsテーブルのnameカラムに検索ワードが含まれる商品を取得
        }
        $items = $query->get();
        //上記の条件を適用したクエリでitemsテーブルからデータを取得

        return view('index', compact('items', 'tab', 'search'));
        //商品一覧、現在のタブ、検索キーワードをビューに渡す
    }

    public function detail(Item $item) { //モデル結合ルート
        return view('detail', compact('item'));
    }

    public function sellView() {
        $categories = Category::all();
        $conditions = Condition::all();
        return view('sell', compact('categories', 'conditions'));
    }

    public function sellCreate(ItemRequest $request) {
        $img = $request->file('img_url');
        $img_url = Storage::disk('local')->put('public/img', $img);
        //Storage::disc('local')はstorage/appという意味
        //storage/app/public/imgに$imgを保存

        $item = Item::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'img_url' => $img_url,
            'condition_id' => $request->condition_id,
            'user_id' => Auth::id() 
        ]);

        // カテゴリーはitemsテーブルではなく中間テーブルなのでforeachで別で保存
        foreach($request->categories as $category_id) {
            CategoryItem::create([
                'item_id' => $item->id,
                'category_id' => $category_id
            ]);
        }
        return redirect()->route('item.detail', ['item'=>$item->id]);
    }
}
