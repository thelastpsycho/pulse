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
            ['name' => 'product', 'label' => 'Product'],
            ['name' => 'service', 'label' => 'Service'],
            ['name' => 'general', 'label' => 'General'],
        ];

        foreach ($categories as $category) {
            IssueCategory::firstOrCreate(
                ['name' => $category['name']],
                ['label' => $category['label']]
            );
        }
    }
}
