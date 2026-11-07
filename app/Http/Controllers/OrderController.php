<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        $order->load('items.menuItem');

        return view('orders.show', compact('order'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*' => ['required', 'integer', 'min:0', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $selected = [];
        foreach ($data['items'] as $id => $quantity) {
            if (! ctype_digit((string) $id) || (int) $id < 1) {
                throw ValidationException::withMessages(['items' => 'Invalid menu item.']);
            }

            if ((int) $quantity > 0) {
                $selected[(int) $id] = (int) $quantity;
            }
        }

        if ($selected === []) {
            throw ValidationException::withMessages(['items' => 'Select at least one item.']);
        }

        $order = DB::transaction(function () use ($request, $data, $selected) {
            $menuItems = MenuItem::query()
                ->whereIn('id', array_keys($selected))
                ->where('is_available', true)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($menuItems->count() !== count($selected)) {
                throw ValidationException::withMessages([
                    'items' => 'An item is no longer available. Please refresh the menu.',
                ]);
            }

            $totalCents = 0;
            $lines = [];
            foreach ($selected as $itemId => $quantity) {
                $menuItem = $menuItems->get($itemId);
                $totalCents += $this->toCents($menuItem->price) * $quantity;
                $lines[] = [
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $quantity,
                    'unit_price' => $menuItem->price, // snapshot, not live menu price
                ];
            }

            $order = Order::create([
                'user_id' => $request->user()->id,
                'status' => 'pending',
                'total_amount' => sprintf('%d.%02d', intdiv($totalCents, 100), $totalCents % 100),
                'notes' => $data['notes'] ?? null,
            ]);
            $order->items()->createMany($lines);

            return $order;
        });

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully.');
    }

    private function toCents(string $amount): int
    {
        [$whole, $fraction] = array_pad(explode('.', $amount, 2), 2, '0');
        return ((int) $whole * 100) + (int) str_pad(substr($fraction, 0, 2), 2, '0');
    }
}
