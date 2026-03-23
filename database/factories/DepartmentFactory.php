<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        $departments = [
            'Front Office',
            'Housekeeping',
            'Maintenance',
            'Food & Beverage',
            'Kitchen',
            'Security',
            'IT Support',
            'Human Resources',
            'Sales & Marketing',
            'Accounting',
            'Spa & Wellness',
            'Concierge',
            'Bell Service',
            'Laundry',
            'Gardening',
        ];

        return [
            'name' => fake()->unique()->randomElement($departments),
            'code' => fake()->optional(0.7)->unique()->regexify('[A-Z]{3,4}'),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
