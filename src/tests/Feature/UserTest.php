<?php

namespace Tests\Feature;


use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testEnvironmentIsTesting() {
    $this->assertEquals('testing', config('app.env'));
    }

    //会員登録機能
    public function test_register_user() {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $response->assertRedirect('/email/verify');
        $this->assertDatabaseHas(User::class, [
            'name' => 'テストユーザー',
            'email' => 'test@gmail.com'
        ]);
    }
    //ログイン機能
    public function test_login_user() {
        $user = User::find(2);

        $response = $this->post('login', [
            'email' => 'general2@gmail.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }
    //ログアウト機能
    public function test_logout_user() {
        $user = User::find(1);
        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }
    //ユーザー情報取得
    public function test_get_profile() {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertSeeInOrder([
            '一般ユーザー1',
            '1080014',
            '東京都港区芝5丁目29-20610',
            'クロスオフィス三田'
        ]);
    }
    //ユーザー情報変更
    public function test_change_profile() {
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->post('/mypage/profile', [
            'name' => "変更後ネーム",
            'postcode' => "1110032",
            'address' => "東京都台東区浅草2-3-1",
            'building' => "浅草寺",
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas(Profile::class, [
            'user_id' => 1,
            'postcode' => "1110032",
            'address' => "東京都台東区浅草2-3-1",
            'building' => "浅草寺",
        ]);
    }
    //出品情報登録
    public function test_listing_item() {
        $user = User::find(1);
        $this->actingAs($user);

        Storage::fake('local'); //仮のストレージ作成
        $image = UploadedFile::fake()->create('test_item.png', 150); //仮の画像作成

        $response = $this->post('/sell', [
            'name' => 'テストアイテム',
            'price' => 5000,
            'description' => 'これはテストアイテムです',
            'img_url' => $image,
            'condition_id' => 3,
            'categories' => [2,3,4]
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas(Item::class, [
            'name' => 'テストアイテム',
            'price' => 5000,
            'description' => 'これはテストアイテムです',
            'user_id' => 1,
            'condition_id' => 3,
        ]);
        Storage::disk('local')->assertExists('public/img/'.$image->hashName());
    }
}
