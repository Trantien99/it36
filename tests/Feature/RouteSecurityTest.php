<?php

namespace Tests\Feature;

use Tests\TestCase;

class RouteSecurityTest extends TestCase
{
    public function testGuestIsRedirectedFromProtectedIncomeRoute()
    {
        $this->get(route('product.order.income'))
            ->assertRedirect(route('login.form'));
    }

    public function testGuestIsRedirectedWhenTrackingOrder()
    {
        $this->post(route('product.track.order'), [
            'order_number' => 'ORD-123',
        ])->assertRedirect(route('login.form'));
    }

    public function testGuestIsRedirectedWhenPostingCartAndWishlistMutations()
    {
        $this->post(route('add-to-cart', 'demo-product'))
            ->assertRedirect(route('login.form'));

        $this->post(route('add-to-wishlist', 'demo-product'))
            ->assertRedirect(route('login.form'));
    }

    public function testStateChangingRoutesRejectLegacyGetRequests()
    {
        $this->get(route('user.logout'))->assertStatus(405);
        $this->get(route('add-to-cart', 'demo-product'))->assertStatus(405);
        $this->get(route('cart-delete', 1))->assertStatus(405);
        $this->get(route('wishlist-delete', 1))->assertStatus(405);
    }

}
