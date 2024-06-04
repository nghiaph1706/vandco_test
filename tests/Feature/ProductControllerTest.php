<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Store;
use App\Models\Product;
use App\Models\User;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_products_for_store()
    {
        $store = Store::factory()->create();
        Product::factory(3)->create(['store_id' => $store->id]);

        $response = $this->get("/api/stores/{$store->id}/products");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_search_products_by_name_and_description()
    {
        $store = Store::factory()->create();
        Product::factory()->create(['store_id' => $store->id, 'name' => 'Product A', 'description' => 'Description of Product A']);
        Product::factory()->create(['store_id' => $store->id, 'name' => 'Product B', 'description' => 'Description of Product B']);
        Product::factory()->create(['store_id' => $store->id, 'name' => 'Product C', 'description' => 'Description of Product C']);

        $response = $this->get("/api/stores/{$store->id}/products?search=Product A");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'Product A']);
    }

    public function test_can_create_product()
    {
        $user = User::factory()->create();
        $store = Store::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->postJson("/api/stores/{$store->id}/products", [
                'name' => 'New Product',
                'description' => 'Description of new product',
                'price' => 10.99,
                'quantity' => 100,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'New Product',
                'description' => 'Description of new product',
                'price' => 10.99,
                'quantity' => 100,
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'New Product',
            'description' => 'Description of new product',
            'price' => 10.99,
            'quantity' => 100,
        ]);
    }


    public function test_can_get_single_product()
    {
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        $response = $this->get("/api/stores/{$store->id}/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
            ]);
    }

    public function test_can_update_product()
    {
        $user = User::factory()->create();
        $store = Store::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['store_id' => $store->id]);

        $response = $this->actingAs($user)
            ->putJson("/api/stores/{$store->id}/products/{$product->id}", [
                'name' => 'Updated Product Name',
                'description' => 'Updated product description',
                'price' => 19.99,
                'quantity' => 200,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Updated Product Name',
                'description' => 'Updated product description',
                'price' => 19.99,
                'quantity' => 200,
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'description' => 'Updated product description',
            'price' => 19.99,
            'quantity' => 200,
        ]);
    }


    public function test_can_delete_product()
    {
        $user = User::factory()->create();
        $store = Store::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['store_id' => $store->id]);

        $response = $this->actingAs($user)
            ->delete("/api/stores/{$store->id}/products/{$product->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}
