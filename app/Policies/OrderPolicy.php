<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /** Admins may inspect anything; customers only their own orders. */
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function view(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }

    public function cancel(User $user, Order $order): bool
    {
        return $order->user_id === $user->id && $order->is_cancellable;
    }

    public function pay(User $user, Order $order): bool
    {
        return $order->user_id === $user->id && $order->is_payable;
    }
}
