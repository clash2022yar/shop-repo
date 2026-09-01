{{-- Cart line items, re-rendered by ajax.cart.show --}}
<div class="divide-y divide-ink-100">
    @foreach($items as $item)
        @include('partials.cart-line', ['item' => $item])
    @endforeach
</div>
