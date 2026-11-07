@extends('layouts.restaurant')
@section('title', 'My orders')
@section('content')
<section class="page-heading"><div class="eyebrow">YOUR ACCOUNT</div><h1>Order history</h1><p>See your previous orders and their current status.</p></section>
@forelse($orders as $order)
    <a class="order-row" href="{{ route('orders.show', $order) }}">
        <div><strong>Order #{{ $order->id }}</strong><small>{{ $order->created_at->format('M d, Y · H:i') }} · {{ $order->items_count }} item line(s)</small></div>
        <div class="order-right"><span class="status">{{ ucfirst($order->status) }}</span><strong>{{ number_format((float)$order->total_amount, 2) }}</strong></div>
    </a>
@empty
    <div class="empty-state">You have no orders yet. <a href="{{ route('menu.index') }}">Explore the menu</a>.</div>
@endforelse
<div class="pagination">{{ $orders->links() }}</div>
@endsection
