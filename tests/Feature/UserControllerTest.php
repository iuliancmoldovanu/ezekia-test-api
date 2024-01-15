<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest   extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_list_users()
    {
        User::factory(3)->create();

        $response = $this->get('/api/users');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_show_user()
    {
        $user = User::factory()->create();

        $response = $this->get('/api/users/' . $user->id);

        $response->assertStatus(200)
            ->assertJson([
                'name' => $user->name,
                'hourly_rate' => $user->hourly_rate,
                'currency' => $user->currency,
            ]);
    }

    public function test_can_create_user()
    {
        $userData = [
            'name' => $this->faker->name,
            'hourly_rate' => 25.5,
            'currency' => 'USD',
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJson([
                'name' => $userData['name'],
                'hourly_rate' => $userData['hourly_rate'],
                'currency' => $userData['currency'],
            ]);

        $this->assertDatabaseHas('users', $userData);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create();
        $newUserData = [
            'name' => 'Updated Name',
            'hourly_rate' => 30.0,
            'currency' => 'EUR',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $newUserData);

        $response->assertStatus(200)
            ->assertJson([
                'name' => $newUserData['name'],
                'hourly_rate' => $newUserData['hourly_rate'],
                'currency' => $newUserData['currency'],
            ]);

        $this->assertDatabaseHas('users', $newUserData);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->delete("/api/users/{$user->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_invalid_hourly_rate_validation()
    {
        $userData = [
            'name' => $this->faker->name,
            'hourly_rate' => -5, // Invalid hourly rate
            'currency' => 'USD',
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['hourly_rate']);
    }
}