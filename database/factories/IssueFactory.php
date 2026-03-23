<?php

namespace Database\Factories;

use App\Models\Issue;
use App\Models\User;
use App\Models\IssueType;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class IssueFactory extends Factory
{
    protected $model = Issue::class;

    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();
        $nationalities = ['Indonesian', 'Malaysian', 'Singaporean', 'Australian', 'American', 'British', 'Japanese', 'Korean', 'Chinese', 'German', 'French', 'Dutch'];

        $checkinDate = fake()->dateTimeBetween('-6 months', 'now');
        $checkoutDate = fake()->dateTimeBetween($checkinDate, '+7 days');
        $issueDate = fake()->dateTimeBetween($checkinDate, $checkoutDate);

        return [
            'title' => fake()->randomElement([
                'Air conditioning not working in room',
                'Water leaking from bathroom faucet',
                'No hot water in shower',
                'TV remote control broken',
                'Room not cleaned properly',
                'Missing towels in bathroom',
                'Noisy neighbors late at night',
                'Wi-Fi connection very slow',
                'Safe in room not working',
                'Fridge making loud noise',
                'Cockroach found in room',
                'Bed sheets have stains',
                'Elevator out of service',
                'Key card not working',
                'Window won\'t lock properly',
                'Light bulb flickering',
                'Toilet not flushing properly',
                'Dust everywhere in room',
                'Hair dryer not working',
                'Mirror cracked in bathroom',
                'Broken chair in room',
                'Mold in bathroom',
                'Shower drain clogged',
                'Room key not provided',
                'Minibar not restocked',
                'Coffee maker not working',
                'Curtains torn',
                'Carpet stained',
                'Phone not working',
                'Power outlet not functioning',
            ]),
            'description' => fake()->randomElement([
                'Guest reported the issue immediately upon arrival. Staff notified.',
                'Issue discovered during morning housekeeping. Maintenance team alerted.',
                'Guest called front desk expressing frustration. Urgent attention needed.',
                'Guest mentioned this is the second time they\'re reporting this issue.',
                'Housekeeping noted the issue during routine cleaning.',
                'Guest requested room change due to this issue.',
                'Issue occurred during the night, guest tired and upset.',
                'Guest found the issue upon returning from dinner.',
                'Daily housekeeping missed this problem.',
                'Guest\'s child discovered the issue first.',
            ]),
            'location' => fake()->randomElement([
                'Room 101', 'Room 102', 'Room 103', 'Room 104', 'Room 105',
                'Room 201', 'Room 202', 'Room 203', 'Room 204', 'Room 205',
                'Room 301', 'Room 302', 'Room 303', 'Room 304', 'Room 305',
                'Room 401', 'Room 402', 'Room 403', 'Room 404', 'Room 405',
                'Room 501', 'Room 502', 'Room 503', 'Room 504', 'Room 505',
                'Lobby', 'Restaurant', 'Pool Area', 'Gym', 'Spa',
                'Conference Room A', 'Conference Room B', 'Business Center',
                'Parking Area', 'Garden', 'Rooftop Bar', 'Kids Club',
            ]),
            'name' => "{$firstName} {$lastName}",
            'room_number' => fake()->numberBetween(101, 599),
            'checkin_date' => $checkinDate->format('Y-m-d'),
            'checkout_date' => $checkoutDate->format('Y-m-d'),
            'issue_date' => $issueDate->format('Y-m-d'),
            'source' => fake()->randomElement(['In-person', 'Phone', 'Email', 'Website', 'Mobile App', 'Guest App']),
            'nationality' => fake()->randomElement($nationalities),
            'contact' => fake()->phoneNumber(),
            'recovery' => fake()->randomElement([
                null,
                'Apologized to guest and offered complimentary breakfast.',
                'Maintenance fixed the issue immediately. Guest satisfied.',
                'Room upgraded as compensation.',
                'Guest refunded for one night stay.',
                'Offered free spa service as apology.',
                'Issue resolved, guest thanked staff.',
                'Resolved with temporary fix, permanent repair scheduled.',
                'Guest accepted explanation and solution.',
            ]),
            'recovery_cost' => fake()->randomElement([null, 0, 25000, 50000, 75000, 100000, 150000, 200000, 500000]),
            'training' => fake()->randomElement([
                null,
                'Staff training scheduled for next week.',
                'Refresher course recommended for housekeeping team.',
                'Maintenance team already trained on this issue.',
                'No additional training needed.',
                'Training completed last month.',
                'New training materials being prepared.',
            ]),
            'priority' => fake()->randomElement(['low', 'low', 'medium', 'medium', 'high', 'urgent']),
            'status' => fake()->randomElement(['open', 'open', 'open', 'open', 'closed']), // 80% open
            'created_by' => User::inRandomOrder()->first()?->id,
            'assigned_to_user_id' => fake()->optional(0.7)->randomElement([User::inRandomOrder()->first()?->id]),
            'closed_at' => fake()->optional(0.2)->dateTimeBetween($issueDate, 'now'),
            'closed_by_user_id' => fake()->optional(0.2)->randomElement([User::inRandomOrder()->first()?->id]),
        ];
    }

    /**
     * Configure the issue with departments and types.
     */
    public function configure(): IssueFactory
    {
        return $this->afterCreating(function (Issue $issue) {
            // Attach random departments (1-3), create if none exist
            $departmentCount = Department::count();
            if ($departmentCount === 0) {
                $departments = Department::factory(fake()->numberBetween(1, 3))->create()->pluck('id');
            } else {
                $departments = Department::inRandomOrder()->limit(fake()->numberBetween(1, 3))->pluck('id');
            }
            $issue->departments()->attach($departments);

            // Attach random issue types (1-2), create if none exist
            $typeCount = IssueType::count();
            if ($typeCount === 0) {
                $types = IssueType::factory(fake()->numberBetween(1, 2))->create()->pluck('id');
            } else {
                $types = IssueType::inRandomOrder()->limit(fake()->numberBetween(1, 2))->pluck('id');
            }
            $issue->issueTypes()->attach($types);

            // Add random comments (0-5 per issue)
            $commentCount = fake()->numberBetween(0, 5);
            if ($commentCount > 0 && User::count() > 0) {
                for ($i = 0; $i < $commentCount; $i++) {
                    \App\Models\IssueComment::factory()->create([
                        'issue_id' => $issue->id,
                    ]);
                }
            }
        });
    }

    /**
     * Create an open issue.
     */
    public function open(): IssueFactory
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
            'closed_at' => null,
            'closed_by_user_id' => null,
        ]);
    }

    /**
     * Create a closed issue.
     */
    public function closed(): IssueFactory
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
            'closed_at' => fake()->dateTime(),
        ]);
    }

    /**
     * Create an urgent issue.
     */
    public function urgent(): IssueFactory
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
        ]);
    }
}
