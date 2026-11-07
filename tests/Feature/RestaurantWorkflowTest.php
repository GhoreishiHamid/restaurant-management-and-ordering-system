<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function menuItem(string $price = '9.99', bool $available = true): MenuItem
    {
        $category = Category::create(['name' => 'Pizza']);
        return MenuItem::create([
            'category_id' => $category->id,
            'name' => 'Margherita Pizza',
            'price' => $price,
            'is_available' => $available,
        ]);
    }

    public function test_guests_can_view_available_menu_items(): void
    {
        $this->menuItem();
        $this->get('/')->assertOk()->assertSee('Margherita Pizza');
    }

    public function test_guests_cannot_submit_orders(): void
    {
        $item = $this->menuItem();
        $this->post('/orders', ['items' => [$item->id => 1]])->assertRedirect(route('login'));
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_order_total_and_price_snapshot_are_calculated_on_server(): void
    {
        $item = $this->menuItem('9.99');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/orders', [
            'items' => [$item->id => 2],
            'notes' => 'Test order',
        ]);

        $order = Order::firstOrFail();
        $response->assertRedirect(route('orders.show', $order));
        $this->assertSame('19.98', $order->total_amount);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'menu_item_id' => $item->id,
            'quantity' => 2,
        ]);
        $this->assertSame('9.99', $order->items()->firstOrFail()->unit_price);
    }

    public function test_unavailable_menu_items_cannot_be_ordered(): void
    {
        $item = $this->menuItem('9.99', false);
        $this->actingAs(User::factory()->create())
            ->from('/')
            ->post('/orders', ['items' => [$item->id => 1]])
            ->assertRedirect('/')
            ->assertSessionHasErrors('items');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_customers_cannot_view_other_customers_orders(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $order = Order::create([
            'user_id' => $owner->id,
            'status' => 'pending',
            'total_amount' => '10.00',
        ]);
        $this->actingAs($other)->get(route('orders.show', $order))->assertNotFound();
    }
}
