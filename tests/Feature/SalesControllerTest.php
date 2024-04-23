<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Coffee;
use App\Models\User;
use Tests\TestCase;

class SalesControllerTest extends TestCase
{
    /**
     * Test listing sales requires authorization
     */
    public function test_listing_sales_requires_authorozation(): void
    {
        $response = $this->get('/sales');
        $response->assertRedirect('/login');
    }

    /**
     * Test selling price endpoint requires valid parameters
     */
    public function test_selling_price_endpoint_requires_valid_parameters(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sales/get-selling-price', []);
        $response->assertSessionHasErrors([
            'quantity',
            'product',
            'unit_cost',
        ]);
    }

    /**
     * Test return valid selling price
     */
    public function test_returns_valid_selling_price(): void
    {
        $user = User::factory()->create();
        $coffee = Coffee::factory()->create();

        $response = $this->actingAs($user)->post('/sales/get-selling-price', [
            'product' => $coffee->id,
            'quantity' => 1,
            'unit_cost' => 10
        ]);
        $response->assertExactJson(
        [
            'data' => [
                'selling_price' => 23.33
            ]
        ]);
    }
}
