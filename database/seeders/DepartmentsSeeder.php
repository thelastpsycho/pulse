<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Front Office'],
            ['name' => 'Housekeeping'],
            ['name' => 'F&B Service'],
            ['name' => 'F&B Production'],
            ['name' => 'Engineering'],
            ['name' => 'Security'],
            ['name' => 'Sales & Marketing'],
            ['name' => 'Human Resources'],
            ['name' => 'Finance'],
            ['name' => 'IT Department'],
            ['name' => 'Spa & Wellness'],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(['name' => $department['name']]);
        }
    }
}
