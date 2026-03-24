<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IssueType;
use App\Models\IssueCategory;
use Illuminate\Support\Facades\DB;

class IssueTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get category IDs
        $productCategory = IssueCategory::where('name', 'product')->first();
        $serviceCategory = IssueCategory::where('name', 'service')->first();
        $generalCategory = IssueCategory::where('name', 'general')->first();

        if (!$productCategory || !$serviceCategory || !$generalCategory) {
            $this->command->error('Issue categories not found. Run IssueCategorySeeder first.');
            return;
        }

        $issueTypes = [
            // === PRODUCT ===
            // Room Condition
            ['name' => 'Room Not Ready', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Room Cleanliness', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Room Size Issue', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Room Configuration', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Room View Issue', 'category_id' => $productCategory->id, 'default_severity' => 'low', 'is_active' => true],
            ['name' => 'Room Odor', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Room Moldy', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Room Facility Broken', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],

            // Air Conditioning
            ['name' => 'AC Not Cold', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'AC Too Cold', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'AC Leaking', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'AC Noisy', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],

            // Bathroom & Plumbing
            ['name' => 'No Hot Water', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Water Leaking', 'category_id' => $productCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Drain Blocked', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Toilet Issue', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Bathroom Door', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Jetspray Problem', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],

            // Electrical & Lighting
            ['name' => 'Electricity Off', 'category_id' => $productCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Power Socket', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Light Problem', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'TV Problem', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'TV Remote', 'category_id' => $productCategory->id, 'default_severity' => 'low', 'is_active' => true],

            // Doors, Locks & Keys
            ['name' => 'Room Key Problem', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Door Can\'t Lock', 'category_id' => $productCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Door Can\'t Close', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Ventaza Problem', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Balcony Door', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Door Handle Broken', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],

            // Appliances & Amenities
            ['name' => 'Fridge/Minibar', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Safe Problem', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Hair Dryer', 'category_id' => $productCategory->id, 'default_severity' => 'low', 'is_active' => true],
            ['name' => 'Iron', 'category_id' => $productCategory->id, 'default_severity' => 'low', 'is_active' => true],
            ['name' => 'Coffee Maker', 'category_id' => $productCategory->id, 'default_severity' => 'low', 'is_active' => true],
            ['name' => 'Kettle', 'category_id' => $productCategory->id, 'default_severity' => 'low', 'is_active' => true],
            ['name' => 'Curtain', 'category_id' => $productCategory->id, 'default_severity' => 'low', 'is_active' => true],

            // Internet & Phone
            ['name' => 'WiFi Issue', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Telephone Problem', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'TV Internet', 'category_id' => $productCategory->id, 'default_severity' => 'low', 'is_active' => true],

            // Pool & Beach
            ['name' => 'Pool Water', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Pool Facility', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Beach Cleanliness', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],

            // Pest Control
            ['name' => 'Cockroach', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Ants', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Mouse/Rat', 'category_id' => $productCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Lizard/Gecko', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Mosquito', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Other Insects', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Bedbugs', 'category_id' => $productCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Snake', 'category_id' => $productCategory->id, 'default_severity' => 'urgent', 'is_active' => true],

            // Building & Structure
            ['name' => 'Ceiling Leaking', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Ceiling Damage', 'category_id' => $productCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Floor Wet', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Wall Damage', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Window Issue', 'category_id' => $productCategory->id, 'default_severity' => 'medium', 'is_active' => true],

            // === SERVICE ===
            // Staff Behavior
            ['name' => 'Staff Attitude', 'category_id' => $serviceCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Staff Not Acknowledge', 'category_id' => $serviceCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Staff Communication', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Staff Entry Without Permission', 'category_id' => $serviceCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Security Service', 'category_id' => $serviceCategory->id, 'default_severity' => 'high', 'is_active' => true],

            // Service Quality
            ['name' => 'Delayed Service', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Failed Wake Up Call', 'category_id' => $serviceCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Delayed Housekeeping', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Delayed Luggage', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Delayed Laundry', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Delayed Room Service', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Inconsistent Service', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Service Below Expectation', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Wrong Service Delivered', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],

            // Check-in & Reservation
            ['name' => 'Reservation Not Found', 'category_id' => $serviceCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Late Check-in', 'category_id' => $serviceCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Room Not Available', 'category_id' => $serviceCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Room Assignment Wrong', 'category_id' => $serviceCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Reservation Dispute', 'category_id' => $serviceCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Double Booking', 'category_id' => $serviceCategory->id, 'default_severity' => 'urgent', 'is_active' => true],

            // Airport Transfer & Transport
            ['name' => 'Missed Airport Pickup', 'category_id' => $serviceCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Transport Issue', 'category_id' => $serviceCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Luggage Service', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],

            // Food & Beverage
            ['name' => 'Food Quality', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Food Variety', 'category_id' => $serviceCategory->id, 'default_severity' => 'low', 'is_active' => true],
            ['name' => 'Restaurant Service', 'category_id' => $serviceCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Breakfast Issue', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Hygiene Issue', 'category_id' => $serviceCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Food Allergy', 'category_id' => $serviceCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Food Poisoning', 'category_id' => $serviceCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Restaurant Policy', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],

            // Room Setup & Decoration
            ['name' => 'Extra Bed Issue', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Honeymoon Setup', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Birthday Setup', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Baby Cot', 'category_id' => $serviceCategory->id, 'default_severity' => 'low', 'is_active' => true],

            // Housekeeping Request
            ['name' => 'Make Up Room', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Extra Towel', 'category_id' => $serviceCategory->id, 'default_severity' => 'low', 'is_active' => true],
            ['name' => 'Extra Pillow/Blanket', 'category_id' => $serviceCategory->id, 'default_severity' => 'low', 'is_active' => true],
            ['name' => 'Amenities Request', 'category_id' => $serviceCategory->id, 'default_severity' => 'low', 'is_active' => true],

            // Maintenance Request
            ['name' => 'AC Maintenance', 'category_id' => $serviceCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Plumbing Maintenance', 'category_id' => $serviceCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Electrical Maintenance', 'category_id' => $serviceCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'General Maintenance', 'category_id' => $serviceCategory->id, 'default_severity' => 'medium', 'is_active' => true],

            // === GENERAL ===
            // Health & Safety
            ['name' => 'Medical Emergency', 'category_id' => $generalCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Guest Injury', 'category_id' => $generalCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Guest Unwell', 'category_id' => $generalCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Guest Drunk', 'category_id' => $generalCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Guest Vomiting', 'category_id' => $generalCategory->id, 'default_severity' => 'urgent', 'is_active' => true],

            // Security & Lost Items
            ['name' => 'Lost Item', 'category_id' => $generalCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Room Intrusion', 'category_id' => $generalCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Stolen Item', 'category_id' => $generalCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Theft/Fraud', 'category_id' => $generalCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Room Not Secure', 'category_id' => $generalCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Privacy Issue', 'category_id' => $generalCategory->id, 'default_severity' => 'high', 'is_active' => true],

            // Noise
            ['name' => 'Noise From Guest', 'category_id' => $generalCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Noise From Chiller', 'category_id' => $generalCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Noise From Corridor', 'category_id' => $generalCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Noise From Event', 'category_id' => $generalCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Noise From Outside', 'category_id' => $generalCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Noise From Above', 'category_id' => $generalCategory->id, 'default_severity' => 'medium', 'is_active' => true],

            // Billing & Payment
            ['name' => 'Billing Dispute', 'category_id' => $generalCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Deposit Issue', 'category_id' => $generalCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Credit Card Issue', 'category_id' => $generalCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Refund Request', 'category_id' => $generalCategory->id, 'default_severity' => 'high', 'is_active' => true],

            // Guest Expectation
            ['name' => 'Room Not As Expected', 'category_id' => $generalCategory->id, 'default_severity' => 'high', 'is_active' => true],
            ['name' => 'Facility Not Available', 'category_id' => $generalCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Breakfast Location', 'category_id' => $generalCategory->id, 'default_severity' => 'low', 'is_active' => true],
            ['name' => 'Package Inclusion', 'category_id' => $generalCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Hotel Policy', 'category_id' => $generalCategory->id, 'default_severity' => 'low', 'is_active' => true],

            // External Issue
            ['name' => 'Money Changer Fraud', 'category_id' => $generalCategory->id, 'default_severity' => 'urgent', 'is_active' => true],
            ['name' => 'Beach Condition', 'category_id' => $generalCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Weather', 'category_id' => $generalCategory->id, 'default_severity' => 'low', 'is_active' => true],

            // Other
            ['name' => 'Various', 'category_id' => $generalCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'General Complaint', 'category_id' => $generalCategory->id, 'default_severity' => 'medium', 'is_active' => true],
            ['name' => 'Feedback', 'category_id' => $generalCategory->id, 'default_severity' => 'low', 'is_active' => true],
        ];

        foreach ($issueTypes as $type) {
            IssueType::firstOrCreate(
                ['name' => $type['name']],
                [
                    'issue_category_id' => $type['category_id'],
                    'default_severity' => $type['default_severity'],
                    'is_active' => $type['is_active'],
                ]
            );
        }

        $this->command->info('Seeded ' . count($issueTypes) . ' issue types.');
    }
}
