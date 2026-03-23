<?php

namespace Database\Seeders;

use App\Models\IssueType;
use App\Models\IssueCategory;
use Illuminate\Database\Seeder;

class IssueTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all categories
        $categories = IssueCategory::all()->keyBy('name');

        if ($categories->isEmpty()) {
            $this->command->error('Issue categories not found. Make sure IssueCategorySeeder runs first.');
            return;
        }

        $issueTypes = [
            // Service category
            ['name' => 'Guest Complaint', 'category' => 'service', 'severity' => 'high'],
            ['name' => 'Cleanliness Issue', 'category' => 'service', 'severity' => 'medium'],
            ['name' => 'Service Quality', 'category' => 'service', 'severity' => 'medium'],
            ['name' => 'Staff Behavior', 'category' => 'service', 'severity' => 'high'],
            ['name' => 'Amenity Request', 'category' => 'service', 'severity' => 'low'],
            ['name' => 'Transportation', 'category' => 'service', 'severity' => 'low'],
            ['name' => 'Reservation Issue', 'category' => 'service', 'severity' => 'high'],
            ['name' => 'Check-in/Check-out', 'category' => 'service', 'severity' => 'medium'],
            ['name' => 'Housekeeping Issue', 'category' => 'service', 'severity' => 'medium'],

            // Facility category
            ['name' => 'Maintenance Request', 'category' => 'facility', 'severity' => 'medium'],
            ['name' => 'Equipment Failure', 'category' => 'facility', 'severity' => 'high'],
            ['name' => 'IT Problem', 'category' => 'facility', 'severity' => 'high'],
            ['name' => 'Facility Issue', 'category' => 'facility', 'severity' => 'medium'],
            ['name' => 'HVAC Problem', 'category' => 'facility', 'severity' => 'high'],
            ['name' => 'Plumbing Issue', 'category' => 'facility', 'severity' => 'high'],
            ['name' => 'Electrical Issue', 'category' => 'facility', 'severity' => 'urgent'],
            ['name' => 'Furniture Damage', 'category' => 'facility', 'severity' => 'low'],
            ['name' => 'Pool & Spa Issue', 'category' => 'facility', 'severity' => 'medium'],
            ['name' => 'Gym Equipment', 'category' => 'facility', 'severity' => 'low'],
            ['name' => 'Internet & Wi-Fi', 'category' => 'facility', 'severity' => 'high'],
            ['name' => 'Parking Issue', 'category' => 'facility', 'severity' => 'low'],

            // Product category
            ['name' => 'Food Quality', 'category' => 'product', 'severity' => 'high'],
            ['name' => 'Billing Issue', 'category' => 'product', 'severity' => 'medium'],

            // General category
            ['name' => 'Safety Concern', 'category' => 'general', 'severity' => 'urgent'],
            ['name' => 'Security Issue', 'category' => 'general', 'severity' => 'urgent'],
            ['name' => 'Lost & Found', 'category' => 'general', 'severity' => 'low'],
            ['name' => 'Noise Complaint', 'category' => 'general', 'severity' => 'medium'],

            // Staff category
            ['name' => 'Staff Performance', 'category' => 'staff', 'severity' => 'medium'],
            ['name' => 'Staff Availability', 'category' => 'staff', 'severity' => 'high'],

            // Policy category
            ['name' => 'Policy Inquiry', 'category' => 'policy', 'severity' => 'low'],
            ['name' => 'Room Change Request', 'category' => 'policy', 'severity' => 'medium'],
        ];

        foreach ($issueTypes as $issueType) {
            $category = $categories->get($issueType['category'], $categories->first());

            IssueType::firstOrCreate(
                ['name' => $issueType['name']],
                [
                    'issue_category_id' => $category->id,
                    'default_severity' => $issueType['severity'],
                    'is_active' => true,
                ]
            );
        }
    }
}
