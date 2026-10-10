<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_require_authentication(): void
    {
        $this->getJson('/api/categories')->assertUnauthorized();
    }

    public function test_user_can_create_and_list_their_categories(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/categories', ['name' => 'Books'])
            ->assertCreated()
            ->assertJsonPath('category.name', 'Books');

        $this->getJson('/api/categories')
            ->assertOk()
            ->assertJsonCount(1, 'categories')
            ->assertJsonPath('categories.0.name', 'Books');

        $this->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonCount(1, 'categories');
    }

    public function test_user_cannot_view_another_users_category(): void
    {
        $owner = User::factory()->create();
        $category = $owner->categories()->create(['name' => 'Private']);
        Sanctum::actingAs(User::factory()->create());

        $this->getJson("/api/categories/{$category->id}")->assertNotFound();
    }
}