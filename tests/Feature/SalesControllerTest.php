<?php

namespace Tests\Feature;

use App\Models\Coffee;
use App\Models\User;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesControllerTest extends TestCase
{
    use RefreshDatabase;

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

    /**
     * Test saving sale endpoint requires valid parameters
     */
    public function test_saving_sale_requires_valid_parameters(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sales', []);
        $response->assertSessionHasErrors([
            'quantity',
            'product',
            'unit_cost',
            'selling_price'
        ]);
    }

    /**
     * Test you can save the sale to DB
     */
    public function test_sale_can_be_saved_to_db(): void
    {
        $user = User::factory()->create();
        $coffee = Coffee::factory()->create();

        $response = $this->actingAs($user)->post('/sales', [
            'product' => $coffee->id,
            'quantity' => 1,
            'unit_cost' => 10,
            'selling_price' => 23.33
        ]);
        $response->assertOk();
        $data = $response->getOriginalContent();
        $sale_id = $data['data']['sale_id'];

        $this->assertDatabaseHas('sales', [
            'id' => $sale_id,
            'quantity' => 1,
            'unit_cost' => 10,
            'coffee_id' => $coffee->id,
            'selling_price' => 23.33
        ]);
    }

    /**
     * Test all sales are returned from DB
     */
    public function test_all_sales_returned_from_db(): void
    {
        $user = User::factory()->create();
        Sale::factory(5)->create();

        $response = $this->actingAs($user)->get('/get-sales');
        $response->assertOk();

        $data = $response->getOriginalContent();
        $salesFromDbCount = count($data['data']['sales']);
        $this->assertSame(5, $salesFromDbCount);
    }
}
