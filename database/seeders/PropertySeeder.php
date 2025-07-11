<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\User;

class PropertySeeder extends Seeder
{
    public function run(): void
    {

        Property::create([
            'title' => 'Modern City Apartment',
            'description' => 'A stylish apartment in the heart of the city.',
            'price_per_night' => 150,
            'location' => 'Downtown',
            'amenities' => ['WiFi', 'Air Conditioning', 'Kitchen', 'TV'],
            'images' => [
                'https://example.com/image1.jpg',
                'https://example.com/image2.jpg'
            ]
        ]);

        Property::create([
            'title' => 'Beachside Bungalow',
            'description' => 'A cozy bungalow by the beach.',
            'price_per_night' => 200,
            'location' => 'Gold Coast',
            'amenities' => ['WiFi', 'Pool', 'Kitchen', 'Balcony'],
            'images' => [
                'https://example.com/beach1.jpg',
                'https://example.com/beach2.jpg'
            ]
        ]);
    }
}
