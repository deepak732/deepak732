<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Amenity;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $amenities = collect(['WiFi','Breakfast','Pool','Gym','Parking','Air Conditioning'])
            ->map(fn ($name) => Amenity::firstOrCreate(['name' => $name]));

        $hotelsData = [
            [
                'name' => 'Seaside Resort', 'city' => 'Miami', 'rating' => 4.5,
                'address' => '123 Ocean Drive, Miami, FL',
                'description' => 'Beachfront resort with pool and spa.',
                'image_url' => 'https://picsum.photos/seed/h1/800/400',
                'rooms' => [
                    ['name' => 'Standard King', 'capacity' => 2, 'price' => 150.00],
                    ['name' => 'Family Suite', 'capacity' => 4, 'price' => 260.00],
                ],
            ],
            [
                'name' => 'Mountain Lodge', 'city' => 'Denver', 'rating' => 4.2,
                'address' => '77 Peak Road, Denver, CO',
                'description' => 'Cozy lodge near hiking trails.',
                'image_url' => 'https://picsum.photos/seed/h2/800/400',
                'rooms' => [
                    ['name' => 'Queen Room', 'capacity' => 2, 'price' => 120.00],
                    ['name' => 'Deluxe Suite', 'capacity' => 3, 'price' => 220.00],
                ],
            ],
            [
                'name' => 'City Central Hotel', 'city' => 'New York', 'rating' => 4.7,
                'address' => '500 5th Ave, New York, NY',
                'description' => 'Downtown hotel near attractions.',
                'image_url' => 'https://picsum.photos/seed/h3/800/400',
                'rooms' => [
                    ['name' => 'Double Room', 'capacity' => 2, 'price' => 200.00],
                    ['name' => 'Executive Suite', 'capacity' => 3, 'price' => 350.00],
                ],
            ],
        ];

        foreach ($hotelsData as $h) {
            $hotel = Hotel::create([
                'name' => $h['name'],
                'city' => $h['city'],
                'address' => $h['address'],
                'description' => $h['description'],
                'rating' => $h['rating'],
                'image_url' => $h['image_url'],
            ]);

            foreach ($h['rooms'] as $r) {
                $room = Room::create([
                    'hotel_id' => $hotel->id,
                    'name' => $r['name'],
                    'capacity' => $r['capacity'],
                    'price_per_night' => $r['price'],
                    'image_url' => 'https://picsum.photos/seed/'.md5($hotel->id.$r['name']).'/800/400',
                ]);
                $room->amenities()->sync($amenities->random(3)->pluck('id'));
            }
        }
    }
}
