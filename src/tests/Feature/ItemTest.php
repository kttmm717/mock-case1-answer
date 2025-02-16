<?php

namespace Tests\Feature;


use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Item;
use App\Models\User;

class ItemTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testEnvironmentIsTesting() {
        $this->assertEquals('testing', config('app.env'));
    }

    //商品一覧取得機能
    public function test_get_items() {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewHas('items', Item::all());
        //①ビューにitemsというキーが渡されているか
        //②itemsというキーの中身がItem::all()と一致するか
    }

    //マイリスト取得機能
    public function test_get_mylist(){
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertSee('腕時計');
    }

    //商品検索機能
    public function test_search_item() {
        $response = $this->get('/?search=ノートPC');

        $response->assertStatus(200);
        $response->assertSee('ノートPC');
    }

    //いいね機能
    public function test_like_item() {
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->post('item/like/4');
        $response->assertStatus(302);
        $this->assertDatabaseHas('likes', [
            'user_id' => 1,
            'item_id' => 4
        ]);
    }

    //コメント送信機能
    public function test_add_comment() {
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->post('/item/comment/1', [
            'comment' => 'テストコメント'
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('comments', [
            'user_id' => 1,
            'item_id' => 1,
            'comment' => 'テストコメント'
        ]);
    }
}
