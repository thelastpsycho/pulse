<?php

namespace Database\Factories;

use App\Models\IssueCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class IssueCategoryFactory extends Factory
{
    protected $model = IssueCategory::class;

    private static $categories = [
        ['name' => 'product', 'label' => 'Product'],
        ['name' => 'service', 'label' => 'Service'],
        ['name' => 'general', 'label' => 'General'],
        ['name' => 'facility', 'label' => 'Facility'],
        ['name' => 'staff', 'label' => 'Staff'],
        ['name' => 'policy', 'label' => 'Policy'],
    ];

    private static $index = 0;

    public function definition(): array
    {
        $category = self::$categories[self::$index % count(self::$categories)];
        self::$index++;

        return [
            'name' => $category['name'],
            'label' => $category['label'],
        ];
    }
}
