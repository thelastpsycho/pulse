<?php

namespace Database\Factories;

use App\Models\IssueType;
use App\Models\IssueCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class IssueTypeFactory extends Factory
{
    protected $model = IssueType::class;

    private static $issueTypes = [
        ['name' => 'Guest Complaint', 'category' => 'service'],
        ['name' => 'Maintenance Request', 'category' => 'facility'],
        ['name' => 'Equipment Failure', 'category' => 'facility'],
        ['name' => 'Cleanliness Issue', 'category' => 'service'],
        ['name' => 'Service Quality', 'category' => 'service'],
        ['name' => 'Staff Behavior', 'category' => 'staff'],
        ['name' => 'Food Quality', 'category' => 'product'],
        ['name' => 'Billing Issue', 'category' => 'policy'],
        ['name' => 'Safety Concern', 'category' => 'general'],
        ['name' => 'Security Issue', 'category' => 'general'],
        ['name' => 'IT Problem', 'category' => 'facility'],
        ['name' => 'Facility Issue', 'category' => 'facility'],
        ['name' => 'Amenity Request', 'category' => 'service'],
        ['name' => 'Lost & Found', 'category' => 'general'],
        ['name' => 'Transportation', 'category' => 'service'],
        ['name' => 'Reservation Issue', 'category' => 'policy'],
        ['name' => 'Noise Complaint', 'category' => 'general'],
        ['name' => 'Housekeeping Issue', 'category' => 'service'],
        ['name' => 'HVAC Problem', 'category' => 'facility'],
        ['name' => 'Plumbing Issue', 'category' => 'facility'],
        ['name' => 'Electrical Issue', 'category' => 'facility'],
        ['name' => 'Furniture Damage', 'category' => 'facility'],
        ['name' => 'Pool & Spa Issue', 'category' => 'facility'],
        ['name' => 'Gym Equipment', 'category' => 'facility'],
        ['name' => 'Internet & Wi-Fi', 'category' => 'facility'],
        ['name' => 'Parking Issue', 'category' => 'facility'],
        ['name' => 'Check-in/Check-out', 'category' => 'service'],
        ['name' => 'Room Change Request', 'category' => 'policy'],
    ];

    private static $index = 0;

    public function definition(): array
    {
        $typeData = self::$issueTypes[self::$index % count(self::$issueTypes)];
        self::$index++;

        // Get category ID by name
        $category = IssueCategory::where('name', $typeData['category'])->first();

        return [
            'name' => $typeData['name'],
            'description' => fake()->optional(0.7)->sentence(),
            'default_severity' => fake()->randomElement(['urgent', 'high', 'medium', 'low']),
            'issue_category_id' => $category?->id ?? IssueCategory::inRandomOrder()->first()?->id ?? 1,
            'is_active' => true,
        ];
    }

    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'default_severity' => 'urgent',
        ]);
    }

    public function high(): static
    {
        return $this->state(fn (array $attributes) => [
            'default_severity' => 'high',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
