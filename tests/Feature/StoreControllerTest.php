<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Store;

class StoreControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_stores()
    {
        $response = $this->get('/api/stores');

        $response->assertStatus(200);
    }

    public function test_can_search_stores_by_name_and_description()
    {
        Store::factory()->create(['name' => 'Store A', 'description' => 'Description of Store A']);
        Store::factory()->create(['name' => 'Store B', 'description' => 'Description of Store B']);
        Store::factory()->create(['name' => 'Store C', 'description' => 'Description of Store C']);

        $response = $this->get('/api/stores?search=Store A');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'Store A']);
    }

    public function test_can_get_single_store()
    {
        $store = Store::factory()->create();

        $response = $this->get('/api/stores/' . $store->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'user_id',
                'name',
                'description',
                'created_at',
                'updated_at',
            ]);
    }

    public function test_can_create_store()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/stores', [
                'name' => 'New Store',
                'description' => 'Description of the new store',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'New Store',
                'description' => 'Description of the new store',
            ]);

        $this->assertDatabaseHas('stores', [
            'name' => 'New Store',
            'description' => 'Description of the new store',
        ]);
    }

    public function test_can_update_store()
    {
        $user = User::factory()->create();
        $store = Store::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->putJson('/api/stores/' . $store->id, [
                'name' => 'Updated Store Name',
                'description' => 'Updated store description',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Updated Store Name',
                'description' => 'Updated store description',
            ]);

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'name' => 'Updated Store Name',
            'description' => 'Updated store description',
        ]);
    }

    public function test_can_soft_delete_store()
    {
        $user = User::factory()->create();
        $store = Store::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->delete('/api/stores/' . $store->id);

        $response->assertStatus(204);

        $this->assertSoftDeleted('stores', [
            'id' => $store->id,
        ]);
    }
}
