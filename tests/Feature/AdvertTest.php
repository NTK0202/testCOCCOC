<?php

namespace Tests\Feature;

use App\Models\Ad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdvertTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testGetAdvertList()
    {
        Ad::factory(20)->create();

        $response = $this->getJson('/api/ads');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'main_image', 'price']
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page', 'last_page', 'from', 'to', 'path', 'per_page', 'total'
                ]
            ]);
    }

    public function testGetSingleAdvert()
    {
        $advert = Ad::factory()->create();

        $response = $this->getJson('/api/ads/' . $advert->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $advert->id,
                'title' => $advert->title,
                'price' => $advert->price,
                'main_image' => $advert->main_image,
            ]);
    }

    public function testCreateAdvert()
    {
        Storage::fake('public');

        $title = $this->faker->sentence(5);
        $price = $this->faker->numberBetween(10, 500);
        $description = $this->faker->sentence(50);
        $images = [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.jpg'),
            UploadedFile::fake()->image('image3.jpg'),
        ];

        $response = $this->postJson('/api/ads', [
            'title' => $title,
            'price' => $price,
            'description' => $description,
            'images' => $images,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'title' => $title,
                'price' => $price,
            ]);

        $this->assertDatabaseHas('ads', [
            'title' => $title,
            'price' => $price,
        ]);

        $advert = Ad::where('title', $title)->first();

        Storage::disk('public')->assertExists($advert->main_image);
        foreach ($advert->images as $image) {
            Storage::disk('public')->assertExists($image);
        }
    }

    public function testCreateAdvertWithValidationErrors()
    {
        $response = $this->postJson('/api/ads', [
            'title' => '',
            'price' => 'invalid',
            'description' => $this->faker->sentence(1000),
            'images' => [
                'invalid_image_url',
                'invalid_image_url',
                'invalid_image_url',
                'invalid_image_url',
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'title', 'price', 'description', 'images'
            ]);
    }

}
