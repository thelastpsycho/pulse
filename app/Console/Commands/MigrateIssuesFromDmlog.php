<?php

namespace App\Console\Commands;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateIssuesFromDmlog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dmlog:migrate-issues
                            {--dry-run : Show what would be migrated without actually migrating}
                            {--limit= : Limit number of records to migrate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate issues from dmlog database to dm-log (without categorization - use DeepSeek AI instead)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $limit = $this->option('limit');

        $this->info("Migrating issues from dmlog database...");
        if ($isDryRun) {
            $this->warn("DRY RUN MODE - No data will be inserted");
        }

        $this->info("Note: Categorization will be done by DeepSeek AI after import");

        // Connect to dmlog database
        $query = DB::connection('mysql')->table('dmlog.issues')
            ->select([
                'id',
                'name',
                'room_number',
                'checkin_date',
                'checkout_date',
                'issue_date',
                'source',
                'nationality',
                'contact',
                'issue',
                'description',
                'recovery',
                'recovery_cost',
                'training',
                'created_by',
                'updated_by',
                'status',
                'created_at',
                'updated_at',
            ]);

        if ($limit) {
            $query->limit((int) $limit);
            $this->info("Processing first {$limit} records");
        }

        $oldIssues = $query->get();
        $this->info("Found " . $oldIssues->count() . " issues to migrate");

        $bar = $this->output->createProgressBar($oldIssues->count());
        $bar->start();

        $stats = [
            'migrated' => 0,
            'errors' => 0,
        ];

        // Get default admin user for created_by
        $adminUser = User::where('email', 'admin@example.com')->first();

        // Get valid user IDs for foreign key validation
        $validUserIds = User::pluck('id')->toArray();

        foreach ($oldIssues as $oldIssue) {
            try {
                // Prepare data (issue_type_id will be set by DeepSeek AI later)
                $data = [
                    'title' => $oldIssue->issue ?: 'General Issue',
                    'description' => $oldIssue->description,
                    'location' => $oldIssue->room_number,
                    'name' => $oldIssue->name,
                    'room_number' => $oldIssue->room_number,
                    'checkin_date' => $this->fixDate($oldIssue->checkin_date),
                    'checkout_date' => $this->fixDate($oldIssue->checkout_date),
                    'issue_date' => $this->fixDate($oldIssue->issue_date),
                    'source' => $oldIssue->source,
                    'nationality' => $oldIssue->nationality,
                    'contact' => $oldIssue->contact,
                    'recovery' => $oldIssue->recovery,
                    'recovery_cost' => $oldIssue->recovery_cost ?: null,
                    'training' => $oldIssue->training,
                    'priority' => $this->determinePriority($oldIssue),
                    'status' => $this->mapStatus($oldIssue->status),
                    'created_by' => $oldIssue->created_by,
                    'updated_by' => ($oldIssue->updated_by && in_array($oldIssue->updated_by, $validUserIds)) ? $oldIssue->updated_by : null,
                    'issue_type_id' => null, // Will be set by DeepSeek AI
                    'created_at' => $this->fixDateTime($oldIssue->created_at),
                    'updated_at' => $this->fixDateTime($oldIssue->updated_at),
                ];

                if (!$isDryRun) {
                    Issue::create($data);
                }

                $stats['migrated']++;

            } catch (\Exception $e) {
                $stats['errors']++;
                $this->newLine();
                $this->error("Error migrating issue ID {$oldIssue->id}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->newLine();

        // Display statistics
        $this->info("Migration Summary:");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Processed', $stats['migrated']],
                ['Errors', $stats['errors']],
            ]
        );

        $this->info("Run 'php artisan issues:categorize-deepseek' to categorize issues with DeepSeek AI");

        return Command::SUCCESS;
    }

    /**
     * Fix date values (handle 0000-00-00)
     */
    protected function fixDate($date): ?string
    {
        if (!$date || $date === '0000-00-00') {
            return null;
        }
        return $date;
    }

    /**
     * Fix datetime values (handle 0000-00-00 00:00:00)
     */
    protected function fixDateTime($dateTime): ?string
    {
        if (!$dateTime || str_starts_with($dateTime, '0000-00-00')) {
            return now()->toDateTimeString();
        }
        return $dateTime;
    }

    /**
     * Determine priority based on content (issue type categorization moved to DeepSeek AI)
     */
    protected function determinePriority($issue): string
    {
        // Check for urgent keywords
        $urgentKeywords = ['emergency', 'urgent', 'police', 'ambulance', 'fire', 'medical', 'doctor'];
        $searchText = strtolower($issue->issue . ' ' . $issue->description);

        foreach ($urgentKeywords as $keyword) {
            if (str_contains($searchText, $keyword)) {
                return 'urgent';
            }
        }

        return 'medium';
    }

    /**
     * Map old status values to new status
     */
    protected function mapStatus($oldStatus): string
    {
        $statusMap = [
            'Open' => 'open',
            'open' => 'open',
            'Closed' => 'closed',
            'closed' => 'closed',
            'In Progress' => 'in_progress',
            'in progress' => 'in_progress',
            'Pending' => 'pending',
            'pending' => 'pending',
        ];

        return $statusMap[$oldStatus] ?? 'open';
    }
}
