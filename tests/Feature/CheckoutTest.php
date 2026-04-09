<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\UserRole;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use App\Services\CheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function createBuyerWithCart(float $price = 10.00, int $stock = 5, int $quantity = 1): array
    {
        $buyer  = User::factory()->create(['role' => UserRole::BUYER]);
        $cart   = Cart::factory()->create(['user_id' => $buyer->id]);
        $vendor = Vendor::factory()->create();

        $product = Product::factory()->create([
            'vendor_id' => $vendor->id,
            'price'     => $price,
            'stock'     => $stock,
        ]);

        CartItem::factory()->create([
            'cart_id'    => $cart->id,
            'product_id' => $product->id,
            'quantity'   => $quantity,
        ]);

        return [$buyer, $cart->load('items.product'), $product];
    }

    // Test 1 — Successful checkout decrements stock and clears cart
    public function test_successful_checkout_decrements_stock_and_clears_cart(): void
    {
        [$buyer, $cart, $product] = $this->createBuyerWithCart(price: 50.00, stock: 10, quantity: 2);

        $service = app(CheckoutService::class);
        $order   = $service->checkout($cart, PaymentMethod::CREDIT_CARD);

        $this->assertEquals(OrderStatus::PAID, $order->status);
        $this->assertCount(1, $order->items);
        $this->assertEquals(8, $product->fresh()->stock); // 10 - 2
        $this->assertCount(0, $cart->fresh()->items);
    }

    // Test 2 — Checkout fails when total exceeds $999
    public function test_checkout_fails_when_total_exceeds_999(): void
    {
        [$buyer, $cart, $product] = $this->createBuyerWithCart(price: 1000.00, stock: 5, quantity: 1);

        $service = app(CheckoutService::class);

        $this->expectException(ValidationException::class);
        $service->checkout($cart, PaymentMethod::CREDIT_CARD);

        $this->assertCount(1, $cart->fresh()->items);
        $this->assertEquals(0, Order::count());
    }

    // Test 3 — Checkout fails when product has insufficient stock
    public function test_checkout_fails_when_stock_is_insufficient(): void
    {
        [$buyer, $cart, $product] = $this->createBuyerWithCart(price: 10.00, stock: 1, quantity: 3);

        $service = app(CheckoutService::class);

        $this->expectException(ValidationException::class);
        $service->checkout($cart, PaymentMethod::WALLET);

        $this->assertEquals(0, Order::count());
    }

    // Test 4 — Vendor route is denied for buyers
    public function test_buyer_cannot_access_vendor_routes(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);

        $this->actingAs($buyer)
            ->get(route('vendor.products.index'))
            ->assertStatus(403);
    }

    // Test 5 — Order status transitions are enforced
    public function test_order_status_can_only_move_forward(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::PAID]);

        // Forward: paid → shipped ✅
        $this->assertTrue($order->status->canTransitionTo(OrderStatus::SHIPPED));

        // Backward: paid → pending ❌
        $this->assertFalse($order->status->canTransitionTo(OrderStatus::PENDING));

        // Skip forward: paid → delivered ✅
        $this->assertTrue($order->status->canTransitionTo(OrderStatus::DELIVERED));
    }
}
