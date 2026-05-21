<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_is_created_with_valid_data(): void
    {
        $user = User::factory()->create();

        $bookData = [
            'title' => 'Le Petit Prince',
            'author' => 'Antoine de Saint-Exupéry',
            'summary' => 'Un pilote rencontre un petit prince venu d\'une autre planète.',
            'isbn' => '9782070612758',
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/books', $bookData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('books', $bookData);
    }

    public function test_book_is_not_created_with_invalid_data(): void
    {
        $user = User::factory()->create();

        $invalidBookData = [
            'title' => 'AB',
            'author' => 'Antoine de Saint-Exupéry',
            'summary' => 'Un pilote rencontre un petit prince venu d\'une autre planète.',
            'isbn' => '9782070612758',
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/books', $invalidBookData);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('books', $invalidBookData);
    }

    public function test_book_is_not_created_when_user_is_not_authenticated(): void
    {
        $bookData = [
            'title' => 'Le Petit Prince',
            'author' => 'Antoine de Saint-Exupéry',
            'summary' => 'Un pilote rencontre un petit prince venu d\'une autre planète.',
            'isbn' => '9782070406330',
        ];

        $response = $this->postJson('/api/v1/books', $bookData);

        $response->assertStatus(401);
        $this->assertDatabaseMissing('books', $bookData);
    }
}
