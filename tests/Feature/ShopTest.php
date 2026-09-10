<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['shop.demo' => false]);
        $this->seed();
    }

    private function customer(): User
    {
        return User::factory()->create();
    }

    private function admin(): User
    {
        $u = $this->customer();
        $u->forceFill(['is_admin' => true])->save();

        return $u;
    }

    private function address(User $u)
    {
        return $u->addresses()->create(['name' => 'کاربر تست', 'phone' => '09123456789', 'city' => 'تهران', 'postal_code' => '1234567890', 'address' => 'تهران خیابان آزمایش پلاک یک']);
    }

    public function test_public_pages_render_and_assets_are_local(): void
    {
        foreach (['/', '/products', '/category/mobile', '/categories', '/search?q=Apple', '/product/galaxy-s24-ultra', '/cart', '/login', '/register', '/about', '/help/faq', '/help/privacy', '/journal', '/journal/macbook-air-guide'] as $url) {
            $this->get($url)->assertOk();
        }$this->assertSame(90, Product::count());
        $this->get('/')->assertSee('یارمحمدی')->assertDontSee('cdn.tailwindcss');
    }

    public function test_authentication_registration_and_admin_role_cannot_be_injected(): void
    {
        $this->postJson('/register', ['name' => 'کاربر', 'email' => 'new@example.test', 'password' => 'Testpass123', 'password_confirmation' => 'Testpass123', 'terms' => 1, 'is_admin' => true])->assertOk();
        $this->assertFalse(User::where('email', 'new@example.test')->first()->is_admin);
        $this->get('/account')->assertOk()->assertDontSee('site-footer');
        $this->get('/admin')->assertForbidden();
        $this->postJson('/logout')->assertOk();
        $this->postJson('/login', ['email' => 'new@example.test', 'password' => 'wrong'])->assertUnprocessable();
        $this->postJson('/login', ['email' => 'new@example.test', 'password' => 'Testpass123'])->assertOk();
    }

    public function test_cart_validates_quantity_and_server_prices(): void
    {
        $p = Product::first();
        $this->postJson('/cart/'.$p->id, ['quantity' => 2, 'price' => 1])->assertOk()->assertJsonPath('count', 2);
        $this->get('/cart')->assertOk()->assertSee($p->name);
        $this->patchJson('/cart/'.$p->id, ['quantity' => -1])->assertUnprocessable();
        $this->patchJson('/cart/'.$p->id, ['quantity' => $p->stock + 1])->assertUnprocessable();
        $this->patchJson('/cart/'.$p->id, ['quantity' => 0])->assertOk()->assertJsonPath('count', 0);
    }

    public function test_checkout_is_idempotent_and_cancellation_restores_stock_once(): void
    {
        $u = $this->customer();
        $a = $this->address($u);
        $p = Product::first();
        $token = (string) Str::uuid();
        $this->actingAs($u)->withSession(['cart' => [$p->id => 2], 'checkout_token' => $token]);
        $payload = ['address_id' => $a->id, 'checkout_token' => $token, 'payment_method' => 'cod', 'total' => 1];
        $this->get('/checkout')->assertOk();
        $this->postJson('/checkout', $payload)->assertOk();
        $order = Order::firstOrFail();
        $this->assertSame($p->price * 2, $order->total);
        $this->assertSame($p->stock - 2, $p->fresh()->stock);
        $this->postJson('/checkout', $payload)->assertOk();
        $this->assertSame(1, Order::count());
        $this->get('/account/orders/'.$order->id)->assertOk();
        $this->postJson('/account/orders/'.$order->id.'/cancel')->assertOk();
        $this->postJson('/account/orders/'.$order->id.'/cancel')->assertOk();
        $this->assertSame($p->stock, $p->fresh()->stock);
    }

    public function test_checkout_rejects_foreign_addresses_and_insufficient_stock(): void
    {
        $u = $this->customer();
        $other = $this->customer();
        $a = $this->address($other);
        $p = Product::first();
        $token = (string) Str::uuid();
        $this->actingAs($u)->withSession(['cart' => [$p->id => 99], 'checkout_token' => $token]);
        $data = ['address_id' => $a->id, 'checkout_token' => $token, 'payment_method' => 'cod'];
        $this->postJson('/checkout', $data)->assertNotFound();
        $data['address_id'] = $this->address($u)->id;
        $this->postJson('/checkout', $data)->assertUnprocessable();
        $this->assertSame(0, Order::count());
        $this->assertSame($p->stock, $p->fresh()->stock);
    }

    public function test_users_cannot_read_other_orders_or_addresses(): void
    {
        $u = $this->customer();
        $a = $this->address($u);
        $p = Product::first();
        $token = (string) Str::uuid();
        $this->actingAs($u)->withSession(['cart' => [$p->id => 1], 'checkout_token' => $token])->postJson('/checkout', ['address_id' => $a->id, 'checkout_token' => $token, 'payment_method' => 'cod'])->assertOk();
        $order = Order::first();
        $this->actingAs($this->customer())->get('/account/orders/'.$order->id)->assertForbidden();
        $this->postJson('/account/orders/'.$order->id.'/cancel')->assertForbidden();
        $this->deleteJson('/account/addresses/'.$a->id)->assertNotFound();
    }

    public function test_coupon_limits_and_expiry_are_enforced(): void
    {
        $p = Product::first();
        $this->withSession(['cart' => [$p->id => 1]]);
        $c = Coupon::create(['code' => 'TEST10', 'percent' => 10, 'min_total' => 0, 'usage_limit' => 1, 'used' => 0, 'expires_at' => now()->addDay(), 'active' => true]);
        $this->postJson('/cart/coupon', ['code' => 'TEST10'])->assertOk();
        $this->get('/cart')->assertOk();
        $c->update(['used' => 1]);
        $this->postJson('/cart/coupon', ['code' => 'TEST10'])->assertUnprocessable();
        $c->update(['used' => 0, 'expires_at' => now()->subDay()]);
        $this->postJson('/cart/coupon', ['code' => 'TEST10'])->assertUnprocessable();
    }

    public function test_admin_pages_and_crud_validation(): void
    {
        $u = $this->admin();
        $this->actingAs($u);
        foreach (['/admin', '/admin/products', '/admin/products/create', '/admin/products/1/edit', '/admin/orders', '/admin/settings', ...array_map(fn ($s) => '/admin/'.$s, array_keys(config('admin')))] as $url) {
            $this->get($url)->assertOk();
        }$this->postJson('/admin/categories', ['name' => 'تست', 'slug' => 'test-new', 'icon' => 'box'])->assertOk();
        $c = Category::where('slug', 'test-new')->firstOrFail();
        $this->putJson('/admin/categories/'.$c->id, ['name' => 'ویرایش', 'slug' => 'test-new', 'icon' => 'phone'])->assertOk();
        $this->deleteJson('/admin/categories/'.$c->id)->assertOk();
        $this->deleteJson('/admin/categories/1')->assertUnprocessable();
        $this->putJson('/admin/inventory/1', ['stock' => 7])->assertOk();
        $this->assertSame(7, Product::find(1)->stock);
        $this->putJson('/admin/inventory/1', ['stock' => -1])->assertUnprocessable();
        $this->postJson('/admin/banners', ['title' => 'تست', 'subtitle' => 'تست بنر', 'image' => '/assets/images/laptop.jpg', 'link' => 'https://evil.example', 'color' => '#ffffff', 'active' => true])->assertUnprocessable();
        $this->postJson('/admin/coupons', ['code' => 'NEW20', 'percent' => 20, 'min_total' => 0, 'usage_limit' => 5, 'expires_at' => now()->addDay()->format('Y-m-d'), 'active' => 1])->assertOk();
    }

    public function test_product_creation_and_archiving(): void
    {
        $this->actingAs($this->admin());
        $data = ['name' => 'محصول تست', 'slug' => 'new-test', 'sku' => 'TEST-001', 'brand' => 'Test', 'category_id' => 1, 'price' => 123000, 'old_price' => 150000, 'stock' => 5, 'description' => 'توضیحات محصول تست برای بررسی فروشگاه', 'image' => '/assets/images/laptop.jpg', 'color' => 'مشکی', 'active' => 1, 'featured' => 0, 'specs_json' => '{"test":"value"}'];
        $this->postJson('/admin/products', $data)->assertOk();
        $p = Product::where('slug', 'new-test')->firstOrFail();
        $this->get('/product/new-test')->assertOk();
        $data['price'] = 130000;
        $this->putJson('/admin/products/'.$p->id, $data)->assertOk();
        $this->deleteJson('/admin/products/'.$p->id)->assertOk();
        $this->get('/product/new-test')->assertNotFound();
    }

    public function test_ajax_filter_search_and_favorites(): void
    {
        $this->getJson('/products?brand[]=Apple&max=15000000')->assertOk()->assertJsonPath('count', 1);
        $this->getJson('/api/suggest?q=Galaxy')->assertOk()->assertJsonCount(6);
        $u = $this->customer();
        $this->actingAs($u)->postJson('/favorites/1')->assertOk()->assertJsonPath('active', true);
        $this->postJson('/favorites/1')->assertOk()->assertJsonPath('active', false);
        foreach (['favorites', 'addresses', 'profile', 'tickets', 'settings', 'recent', 'orders'] as $s) {
            $this->get('/account/'.$s)->assertOk();
        }
    }

    public function test_reviews_require_moderation_and_tickets_can_be_answered(): void
    {
        $u = $this->customer();
        $this->actingAs($u)->postJson('/reviews/1', ['rating' => 5, 'body' => 'دیدگاه آزمایشی درباره محصول'])->assertOk();
        $this->get('/product/galaxy-s24-ultra')->assertDontSee('دیدگاه آزمایشی درباره محصول');
        $this->postJson('/account/tickets', ['subject' => 'درخواست تست', 'body' => 'لطفاً سفارش من را بررسی کنید'])->assertOk();
        $t = Ticket::firstOrFail();
        $this->actingAs($this->admin())->putJson('/admin/tickets/'.$t->id, ['reply' => 'درخواست شما بررسی شد.', 'status' => 'answered'])->assertOk();
        $this->putJson('/admin/reviews/'.Review::first()->id, ['approved' => 1])->assertOk();
        $this->actingAs($u)->get('/account/tickets')->assertSee('درخواست شما بررسی شد.');
        $this->get('/product/galaxy-s24-ultra')->assertSee('دیدگاه آزمایشی درباره محصول');
    }

    public function test_newsletter_is_persisted_and_duplicate_safe(): void
    {
        $this->postJson('/newsletter', ['email' => 'reader@example.test'])->assertOk();
        $this->postJson('/newsletter',['email' => 'reader@example.test'])->assertOk();
        $this->assertDatabaseCount('subscribers',1);
        $this->postJson('/newsletter',['email' => 'invalid'])->assertUnprocessable();
    }
}
