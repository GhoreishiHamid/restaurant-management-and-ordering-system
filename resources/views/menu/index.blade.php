@extends('layouts.restaurant')
@section('title', 'Menu')
@section('content')
<section class="hero">
    <div class="eyebrow">RESTAURANT MANAGEMENT · DEMO</div>
    <h1>Good food. Simple ordering.</h1>
    <p>Explore the menu and place an order. This is a research and software-engineering demonstration, not a real restaurant.</p>
    @guest <a class="button" href="{{ route('register') }}">Create an account to order</a> @endguest
</section>

@if($categories->isEmpty())
    <div class="empty-state">No menu items yet. Run the sample-data seeder to populate the menu.</div>
@else
    @auth
    <form method="post" action="{{ route('orders.store') }}">
        @csrf
    @endauth
    @foreach($categories as $category)
        <section class="menu-section">
            <h2>{{ $category->name }}</h2>
            <div class="menu-grid">
                @foreach($category->menuItems as $item)
                    <article class="menu-card">
                        <div class="card-top"><div class="item-mark">{{ strtoupper(substr($item->name, 0, 1)) }}</div><span class="price">{{ number_format((float) $item->price, 2) }}</span></div>
                        <h3>{{ $item->name }}</h3>
                        <p>{{ $item->description }}</p>
                        @auth
                        <label class="quantity">Quantity
                            <input aria-label="Quantity for {{ $item->name }}" type="number" name="items[{{ $item->id }}]" min="0" max="20" value="{{ old('items.'.$item->id, 0) }}" required>
                        </label>
                        @endauth
                    </article>
                @endforeach
            </div>
        </section>
    @endforeach
    @auth
        <section class="checkout">
            <div><h2>Ready to order?</h2><p>Choose quantities above; totals are calculated securely on the server.</p></div>
            <div class="checkout-actions"><label for="notes">Notes (optional)</label><textarea id="notes" name="notes" maxlength="500" rows="2" placeholder="Special request">{{ old('notes') }}</textarea><button class="button" type="submit">Place order</button></div>
        </section>
    </form>
    @endauth
@endif
@endsection
