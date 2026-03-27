<?php

namespace Database\Seeders;

use App\Models\IssueCategory;
use Illuminate\Database\Seeder;

class IssueCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'room_facilities', 'label' => 'Room & Facilities'],
            ['name' => 'food_beverage', 'label' => 'Food & Beverage'],
            ['name' => 'service_staff', 'label' => 'Service & Staff'],
            ['name' => 'health_safety_security', 'label' => 'Health, Safety & Security'],
            ['name' => 'guest_experience', 'label' => 'Guest Experience'],
        ];

        foreach ($categories as $category) {
            IssueCategory::firstOrCreate(
                ['name' => $category['name']],
                ['label' => $category['label']]
            );
        }
    }
}
