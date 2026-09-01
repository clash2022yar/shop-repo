{{--
    Admin invoice — deliberately the very same printable document the customer
    receives, so nothing can drift between the two. See resources/views/account/invoice.blade.php
--}}
@include('account.invoice', ['order' => $order])
