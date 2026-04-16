<?php

namespace App\Services;

use App\Models\IssueType;
use App\Models\Department;
use App\Exceptions\AIException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IssueAIService
{
    public function analyzeAndEnhanceIssue(
        string $rawDescription,
        string $title = ''
    ): array {
        try {
            $result = $this->callDeepSeekAPI($title, $rawDescription);

            return [
                'enhanced_description' => $result['enhanced_description'],
                'issue_type_ids' => $result['issue_type_ids'],
                'department_ids' => $result['department_ids'],
                'confidence' => $result['confidence'],
            ];
        } catch (\Exception $e) {
            Log::error('AI Service Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    private function callDeepSeekAPI(string $title, string $description): array
    {
        $apiKey = config('services.deepseek.api_key');
        $apiUrl = config('services.deepseek.api_url');
        $model = config('services.deepseek.model', 'deepseek-chat');
        $timeout = config('services.deepseek.timeout', 30);

        if (empty($apiKey)) {
            throw new AIException(
                'DeepSeek API key not configured',
                AIErrorType::AUTH
            );
        }

        $issueTypes = IssueType::orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        $departments = Department::orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        $issueTypeList = [];
        foreach ($issueTypes as $id => $name) {
            $issueTypeList[] = "{$id}:{$name}";
        }

        $deptList = [];
        foreach ($departments as $id => $name) {
            $deptList[] = "{$id}:{$name}";
        }

        $prompt = $this->buildPrompt($title, $description, $issueTypeList, $deptList);

        try {
            $response = Http::timeout($timeout)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                ])
                ->post($apiUrl, [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a hotel issue documentation expert. Always respond with valid JSON. Use EXACT IDs from the provided lists.'
                        ],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.0,
                    'response_format' => ['type' => 'json_object']
                ]);

            if ($response->failed()) {
                $statusCode = $response->status();

                if ($statusCode === 401 || $statusCode === 403) {
                    throw new AIException(
                        'Invalid API credentials',
                        AIErrorType::AUTH
                    );
                }

                if ($statusCode === 429) {
                    throw new AIException(
                        'Rate limit exceeded',
                        AIErrorType::RATE_LIMIT
                    );
                }

                if ($statusCode >= 500) {
                    throw new AIException(
                        "API server error: HTTP {$statusCode}",
                        AIErrorType::SERVER
                    );
                }

                throw new AIException(
                    "API returned HTTP {$statusCode}",
                    AIErrorType::SERVER
                );
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? '{}';

            $parsed = json_decode($content, true);

            if (!$parsed || !isset($parsed['enhanced_description'])) {
                throw new AIException(
                    'Invalid JSON response from API',
                    AIErrorType::INVALID_RESPONSE
                );
            }

            // Validate IDs
            $typeIds = array_intersect(
                (array) $parsed['issue_type_ids'],
                array_keys($issueTypes)
            );

            $deptIds = array_intersect(
                (array) $parsed['department_ids'],
                array_keys($departments)
            );

            // Ensure at least one type and department if available
            if (empty($typeIds) && !empty($issueTypes)) {
                // If AI returned no valid types, pick the most common one
                $typeIds = [array_key_first($issueTypes)];
            }

            if (empty($deptIds) && !empty($departments)) {
                // If AI returned no valid departments, pick the most common one
                $deptIds = [array_key_first($departments)];
            }

            return [
                'enhanced_description' => $parsed['enhanced_description'],
                'issue_type_ids' => array_values($typeIds),
                'department_ids' => array_values($deptIds),
                'confidence' => $parsed['confidence'] ?? 0.8,
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw new AIException(
                'Network connection failed',
                AIErrorType::NETWORK
            );
        } catch (\Illuminate\Http\Client\TimeoutException $e) {
            throw new AIException(
                'Request timeout',
                AIErrorType::NETWORK
            );
        }
    }

    private function buildPrompt(
        string $title,
        string $description,
        array $issueTypeList,
        array $deptList
    ): string {
        return "You are a hotel issue documentation expert. Rewrite this issue description and classify it.

ORIGINAL TITLE: {$title}
ORIGINAL DESCRIPTION: {$description}

TASK:
1. Rewrite the description to be:
   - In English only (translate if input is in other language)
   - Professional and clear
   - Well-structured with proper grammar
   - Concise but complete
   - Include relevant details from both title and description
   - Use hotel industry terminology appropriately

2. Classify by selecting:
   - 1-3 ISSUE_TYPES (be specific: use exact names like \"TV Problem\" not generic terms)
   - 1-2 DEPARTMENTS most relevant to handle this issue

ISSUE TYPES (ID:Name):
" . implode(', ', $issueTypeList) . "

DEPARTMENTS (ID:Name):
" . implode(', ', $deptList) . "

CLASSIFICATION RULES:
- For noise: Distinguish Noise From Guest vs Noise From Outside vs Noise From Event
- For pests: Be specific - Mouse/Rat vs Ants vs Cockroach vs Other Insects
- For AC issues: Use AC Not Cold, AC Too Cold, AC Noisy, AC Leaking specifically
- Multiple issues? List all relevant types (up to 3)
- Use EXACT IDs from the lists above

Return ONLY this JSON format:
{\"enhanced_description\":\"professional rewritten description in English...\",\"issue_type_ids\":[22,37],\"department_ids\":[10],\"confidence\":0.95}";
    }
}
