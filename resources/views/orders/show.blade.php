@extends('layouts.restaurant')
@section('title', 'Order details')
@section('content')
<section class="page-heading"><div class="eyebrow">ORDER DETAILS</div><h1>Order #{{ $order->id }}</h1><p>Placed {{ $order->created_at->format('M d, Y · H:i') }} · <span class="status">{{ ucfirst($order->status) }}</span></p></section>
<div class="receipt">
    @foreach($order->items as $line)
        <div class="receipt-row"><span>{{ $line->menuItem?->name ?? 'Removed menu item' }} <small>× {{ $line->quantity }}</small></span><strong>{{ number_format((float)$line->unit_price * $line->quantity, 2) }}</strong></div>
    @endforeach
    <div class="receipt-row total"><span>Total</span><strong>{{ number_format((float)$order->total_amount, 2) }}</strong></div>
    @if($order->notes)<p><strong>Notes:</strong> {{ $order->notes }}</p>@endif
</div>
<p><a href="{{ route('orders.index') }}">← Back to orders</a></p>
@endsection
