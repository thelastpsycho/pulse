<?php

namespace Database\Factories;

use App\Models\IssueComment;
use App\Models\Issue;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IssueCommentFactory extends Factory
{
    protected $model = IssueComment::class;

    public function definition(): array
    {
        $comments = [
            'Issue acknowledged. Team dispatched.',
            'Guest contacted. Waiting for response.',
            'Maintenance team is working on it.',
            'Resolved. Guest satisfied.',
            'Follow-up required.',
            'Parts ordered. Will fix tomorrow.',
            'Need additional resources.',
            'Escalated to management.',
            'Training scheduled for staff.',
            'Documentation updated.',
            'Guest compensation approved.',
            'Issue resolved. No further action needed.',
            'Monitoring situation.',
            'Waiting for vendor response.',
            'Temporary fix applied.',
        ];

        return [
            'issue_id' => Issue::inRandomOrder()->first()?->id ?? Issue::factory(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'body' => fake()->randomElement($comments),
        ];
    }
}
