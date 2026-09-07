<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_storefront_pages_render(): void
    {
        $product = Product::first();
        $this->get('/')->assertOk()->assertSee('دیجینو');
        $this->get('/products')->assertOk()->assertSee($product->name);
        $this->get('/category/mobile')->assertOk();
        $this->get('/product/'.$product->slug)->assertOk()->assertSee($product->name);
        $this->get('/about')->assertOk()->assertSee('یارمحمدی');
    }

    public function test_customer_can_add_to_cart_and_place_order(): void
    {
        $product = Product::first();
        $user = User::where('email', 'user@digino.ir')->firstOrFail();
        $this->postJson('/cart/'.$product->id, ['quantity' => 2])->assertOk()->assertJsonPath('count', 2);
        $this->actingAs($user)->postJson('/checkout', [
            'recipient' => $user->name,
            'phone' => $user->phone,
            'city' => 'تهران',
            'address' => 'خیابان ولیعصر، پلاک ۱۲۳',
            'postal_code' => '1599912345',
            'payment_method' => 'cod',
        ])->assertOk()->assertJsonStructure(['message', 'redirect']);
        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'status' => 'processing']);
    }

    public function test_admin_middleware_and_inventory_update(): void
    {
        $customer = User::where('email', 'user@digino.ir')->firstOrFail();
        $admin = User::where('email', 'admin@digino.ir')->firstOrFail();
        $product = Product::first();
        $this->actingAs($customer)->get('/admin')->assertForbidden();
        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->patchJson('/admin/inventory/'.$product->id, ['stock' => 7])->assertOk();
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 7]);
    }
}
