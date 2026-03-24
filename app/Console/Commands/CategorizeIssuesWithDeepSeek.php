<?php

namespace App\Console\Commands;

use App\Models\Department;
use App\Models\Issue;
use App\Models\IssueType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CategorizeIssuesWithDeepSeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'issues:categorize-deepseek
                            {--limit= : Limit number of issues to categorize}
                            {--api-key= : DeepSeek API key (or set DEEPSEEK_API_KEY env var)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Categorize issues using DeepSeek AI API';

    private string $apiKey;
    private string $apiUrl = 'https://api.deepseek.com/chat/completions';

    private array $issueTypes;
    private array $departments;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->apiKey = $this->option('api-key') ?: env('DEEPSEEK_API_KEY', 'sk-c25fcc0bfe8444c0bd363b42cb1962f4');

        if (empty($this->apiKey) || $this->apiKey === 'your-api-key-here') {
            $this->error('DeepSeek API key is required. Set DEEPSEEK_API_KEY in .env or use --api-key option');
            return Command::FAILURE;
        }

        $this->info("Loading issue types and departments...");

        // Build ID:Name mappings
        $this->issueTypes = IssueType::orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        $this->departments = Department::orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        $this->info("Loaded " . count($this->issueTypes) . " issue types");
        $this->info("Loaded " . count($this->departments) . " departments");

        // Get uncategorized issues
        $query = Issue::whereNull('issue_type_id')
            ->select('id', 'title', 'description');

        $limit = $this->option('limit');
        if ($limit) {
            $query->limit((int) $limit);
            $this->info("Processing first {$limit} uncategorized issues");
        }

        $issues = $query->get();
        $total = $issues->count();

        if ($total === 0) {
            $this->info("No uncategorized issues found.");
            return Command::SUCCESS;
        }

        $this->info("Found {$total} issues to categorize");
        $this->newLine();

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $stats = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($issues as $index => $issue) {
            try {
                $result = $this->categorizeIssue($issue);

                if ($result) {
                    $stats['success']++;

                    // Show brief progress
                    if ($stats['success'] % 10 === 0) {
                        $typeNames = [];
                        foreach ($result['issue_type_ids'] as $tid) {
                            $typeNames[] = $this->issueTypes[$tid] ?? "ID:{$tid}";
                        }
                        $this->newLine();
                        $this->line("#{$issue->id}: " . implode(', ', array_slice($typeNames, 0, 2)));
                    }
                } else {
                    $stats['failed']++;
                }
            } catch (\Exception $e) {
                $stats['failed']++;
                $stats['errors'][] = "Issue #{$issue->id}: " . $e->getMessage();
            }

            $bar->advance();

            // Brief pause every 50 requests to avoid rate limits
            if (($index + 1) % 50 === 0) {
                sleep(1);
            }
        }

        $bar->finish();
        $this->newLine();
        $this->newLine();

        // Display statistics
        $this->info("Categorization Summary:");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Successfully Categorized', $stats['success']],
                ['Failed', $stats['failed']],
            ]
        );

        if (!empty($stats['errors'])) {
            $this->warn("Errors encountered:");
            foreach (array_slice($stats['errors'], 0, 10) as $error) {
                $this->line("  - {$error}");
            }
        }

        // Show distribution
        $this->newLine();
        $this->displayDistribution();

        return Command::SUCCESS;
    }

    /**
     * Categorize a single issue using DeepSeek API
     */
    protected function categorizeIssue(Issue $issue): ?array
    {
        $issueText = trim(($issue->title ?: '') . ' ' . ($issue->description ?: ''));

        if (strlen($issueText) < 5) {
            return null;
        }

        // Build compact lists for the prompt
        $issueTypeList = [];
        foreach ($this->issueTypes as $id => $name) {
            $issueTypeList[] = "{$id}:{$name}";
        }
        $typeListStr = implode(', ', $issueTypeList);

        $deptList = [];
        foreach ($this->departments as $id => $name) {
            $deptList[] = "{$id}:{$name}";
        }
        $deptListStr = implode(', ', $deptList);

        $prompt = "You are a hotel issue categorization expert. Analyze this issue and assign categories.

ISSUE TYPES (ID:Name) - Choose 1-3 most relevant:
{$typeListStr}

DEPARTMENTS (ID:Name) - Choose 1-2 most relevant:
{$deptListStr}

Issue: {$issueText}

IMPORTANT RULES:
- Be specific: Use exact category names like \"TV Problem\" not generic terms
- For noise: Distinguish Noise From Guest vs Noise From Outside vs Noise From Event vs Noise From Chiller
- For pests: Be specific - Mouse/Rat vs Ants vs Cockroach vs Other Insects vs Mosquito
- For AC issues: Use AC Not Cold, AC Too Cold, AC Noisy, AC Leaking specifically
- Multiple issues? List all relevant types (up to 3)
- Use EXACT IDs from the lists above

Return ONLY this JSON format:
{\"issue_type_ids\":[22,37],\"department_ids\":[10]}";

        $data = [
            'model' => 'deepseek-chat',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a hotel issue categorization expert. Always respond with valid JSON containing numeric arrays. Use EXACT IDs from the provided lists.'
                ],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.0,
        ];

        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            $this->warn("API error for issue #{$issue->id}: HTTP {$httpCode}");
            return null;
        }

        $result = json_decode($response, true);
        $content = $result['choices'][0]['message']['content'] ?? '{}';

        // Clean and parse JSON
        $content = preg_replace('/\s+/', ' ', $content);
        if (preg_match('/\{[^}]+\}/', $content, $matches)) {
            $content = $matches[0];
        }

        $categories = json_decode($content, true);

        if (!$categories || !isset($categories['issue_type_ids']) || !isset($categories['department_ids'])) {
            $this->warn("Invalid JSON for issue #{$issue->id}: {$content}");
            return null;
        }

        // Validate and filter IDs
        $typeIds = array_intersect(
            (array) $categories['issue_type_ids'],
            array_keys($this->issueTypes)
        );

        $deptIds = array_intersect(
            (array) $categories['department_ids'],
            array_keys($this->departments)
        );

        if (empty($typeIds)) {
            return null;
        }

        // Apply categorization
        $issue->issue_type_id = $typeIds[0]; // Primary type
        $issue->save();

        // Sync pivot tables
        $issue->issueTypes()->sync($typeIds);

        if (!empty($deptIds)) {
            $issue->departments()->sync($deptIds);
        }

        return [
            'issue_type_ids' => $typeIds,
            'department_ids' => $deptIds,
        ];
    }

    /**
     * Display distribution of categorized issues
     */
    protected function displayDistribution(): void
    {
        $this->info("Top Issue Types:");

        $distribution = DB::table('issue_issue_type')
            ->select('issue_type_id', DB::raw('COUNT(*) as count'))
            ->groupBy('issue_type_id')
            ->orderBy('count', 'desc')
            ->limit(15)
            ->get();

        foreach ($distribution as $row) {
            $name = $this->issueTypes[$row->issue_type_id] ?? "Unknown";
            $this->line("  {$name}: {$row->count}");
        }

        $this->newLine();
        $this->info("Department Distribution:");

        $deptDist = DB::table('department_issue')
            ->select('department_id', DB::raw('COUNT(*) as count'))
            ->groupBy('department_id')
            ->orderBy('count', 'desc')
            ->get();

        foreach ($deptDist as $row) {
            $name = $this->departments[$row->department_id] ?? "Unknown";
            $this->line("  {$name}: {$row->count}");
        }

        $this->newLine();
        $this->info("Total categorized: " . Issue::whereNotNull('issue_type_id')->count());
        $this->info("Remaining uncategorized: " . Issue::whereNull('issue_type_id')->count());
    }
}
