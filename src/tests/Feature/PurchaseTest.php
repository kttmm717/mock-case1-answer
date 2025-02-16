<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use Stripe\Stripe;

class PurchaseTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testEnvironmentIsTesting() {
        $this->assertEquals('testing', config('app.env'));
    }

    public function test_purchase_item() {
        $user = User::find(1);
        $this->actingAs($user);

        Stripe::setApiKey(config('stripe.stripe_secret_key'));
        $response = $this->post('/purchase/5', [
            'destination_postcode' => $user->profile->postcode,
            'destination_address' => $user->profile->address,
            'destination_building' => $user->profile->building ?? ''
        ]);
        $response->assertSeeInOrder([
            'Test',
            'Powered by stripe'
        ]);
    }
}
