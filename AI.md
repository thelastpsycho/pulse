# AI-Powered Issue Creation Enhancement

## Overview
Add an AI assistant to the issue creation form that automatically enhances issue descriptions and classifies issues by type and department using DeepSeek AI API.

## User Requirements

### Trigger Mechanism
- **Manual button click only** - User clicks an AI button to trigger analysis
- More controlled, no accidental API calls
- Button positioned next to description field

### AI Actions
- **Single action** - One button that rewrites description AND auto-fills issue types/departments together
- Streamlined user experience
- All AI enhancements happen in one API call

### User Interaction
- **Pre-filled with edit ability** - AI suggestions are automatically filled in form fields
- User can edit suggestions before submitting
- No approval workflow required

### Permissions
- **All authenticated users** - Any staff member can use AI assistance
- Rate limiting to prevent abuse
- No role restrictions

---

## Feature Specification

### What AI Does

1. **Rewrite Description**
   - Transform rough notes into professional, clear language
   - Fix grammar and spelling
   - Improve structure and readability
   - Preserve all important details

2. **Classify Issue**
   - Select 1-3 relevant issue types (e.g., "TV Problem", "AC Not Cold")
   - Select 1-2 relevant departments (e.g., "Maintenance", "Housekeeping")
   - Based on semantic analysis of the description

3. **Return Format**
   ```json
   {
     "enhanced_description": "The television in Room 302 is malfunctioning...",
     "issue_type_ids": [22, 37],
     "department_ids": [10],
     "confidence": 0.95
   }
   ```

---

## API Failure Handling

### Failure Behavior

When the DeepSeek API fails:
1. **Show error message** via toast notification
2. **Disable AI button** - Button becomes inactive
3. **Display reason** - Button shows "⚠️ AI Unavailable" with hover tooltip
4. **Preserve data** - Original description remains intact
5. **Form functional** - User can continue manually

### Failure Scenarios

| Scenario | Message | Action |
|----------|---------|--------|
| Network timeout | "⚠️ Unable to reach AI service. Please check your connection." | Disable button (5 min cooldown) |
| Invalid API key | "⚠️ AI service not configured. Please contact administrator." | Disable button (persistent) |
| Rate limit exceeded | "⚠️ Daily AI limit reached. Please try again tomorrow." | Disable button (until reset) |
| API server error | "⚠️ AI service temporarily unavailable. Please try again later." | Disable button (5 min cooldown) |
| Invalid response | "⚠️ AI service returned unexpected data. Please try again." | Disable button (5 min cooldown) |

### Button States

```html
<!-- Normal State -->
<button class="btn-primary">
  ✨ Enhance with AI
</button>

<!-- Loading State -->
<button class="btn-primary" disabled>
  <span class="spinner"></span>
  Enhancing...
</button>

<!-- Failed State -->
<button class="btn-disabled" disabled title="⚠️ Network error - AI unavailable">
  ⚠️ AI Unavailable
</button>
```

### Reactivation Logic

- **Cooldown period**: 5 minutes (configurable)
- **Auto-reactivation**: Button becomes active after cooldown
- **Manual retry**: "Retry Now" link shown during cooldown
- **Session-based**: Failure state stored in session

---

## Technical Implementation

### Backend Components

#### 1. IssueAIService

**File**: `app/Services/IssueAIService.php`

**Responsibilities**:
- Call DeepSeek API with structured prompt
- Parse and validate API response
- Return enhanced description + classifications
- Handle API errors with specific exception types
- Log API usage for analytics

**Method Signature**:
```php
public function analyzeAndEnhanceIssue(
    string $rawDescription,
    string $title = ''
): array
```

**Returns**:
```php
[
    'enhanced_description' => string,  // Professional rewritten description
    'issue_type_ids' => [int],         // 1-3 issue type IDs
    'department_ids' => [int],         // 1-2 department IDs
    'confidence' => float,              // 0.0 to 1.0
]
```

**Error Types**:
```php
enum AIErrorType: string
{
    case NETWORK = 'network';
    case AUTH = 'auth';
    case RATE_LIMIT = 'rate_limit';
    case SERVER = 'server';
    case INVALID_RESPONSE = 'invalid_response';
}
```

#### 2. Livewire Component Update

**File**: `app/Livewire/Issues/Form.php`

**New Properties**:
```php
public bool $aiAvailable = true;
public ?string $aiError = null;
public bool $aiLoading = false;
```

**New Method**:
```php
public function assistWithAI(): void
{
    $this->aiLoading = true;

    try {
        // Store original values for rollback
        $originalDescription = $this->description;
        $originalIssueTypes = $this->issue_type_ids;
        $originalDepartments = $this->department_ids;

        // Call AI service
        $result = app(IssueAIService::class)->analyzeAndEnhanceIssue(
            $this->description,
            $this->title
        );

        // Update form fields
        $this->description = $result['enhanced_description'];
        $this->issue_type_ids = $result['issue_type_ids'];
        $this->department_ids = $result['department_ids'];

        // Clear failure state
        session()->forget(['ai_failure_time', 'ai_error']);

        session()->flash('ai_success', '✨ Enhanced with AI!');

    } catch (AIException $e) {
        // Rollback to original values
        $this->description = $originalDescription;
        $this->issue_type_ids = $originalIssueTypes;
        $this->department_ids = $originalDepartments;

        // Mark AI as unavailable
        session()->put('ai_failure_time', now());
        session()->put('ai_error', $e->getMessage());

        session()->flash('ai_error', '⚠️ ' . $e->getUserMessage());

        // Log for admin
        Log::warning('AI Enhancement Failed', [
            'user_id' => auth()->id(),
            'error' => $e->getMessage(),
            'type' => $e->getType(),
        ]);

    } finally {
        $this->aiLoading = false;
    }
}

public function retryAI(): void
{
    session()->forget(['ai_failure_time', 'ai_error']);
    $this->assistWithAI();
}

public function getAiAvailableProperty(): bool
{
    $lastFailure = session()->get('ai_failure_time');
    if (!$lastFailure) return true;

    $cooldownMinutes = config('services.ai.failure_cooldown_minutes', 5);
    $minutesSinceFailure = now()->diffInMinutes($lastFailure);

    $available = $minutesSinceFailure > $cooldownMinutes;

    // Auto-clear if cooldown passed
    if ($available) {
        session()->forget(['ai_failure_time', 'ai_error']);
    }

    return $available;
}

public function getAiRetryAfterProperty(): ?string
{
    $lastFailure = session()->get('ai_failure_time');
    if (!$lastFailure) return null;

    $cooldownMinutes = config('services.ai.failure_cooldown_minutes', 5);
    $retryAt = $lastFailure->addMinutes($cooldownMinutes);

    if (now()->gte($retryAt)) {
        session()->forget('ai_failure_time');
        return null;
    }

    return $retryAt->diffForHumans();
}
```

---

### Frontend Components

#### 1. Update Form Template

**File**: `resources/views/livewire/issues/form.blade.php`

**Add AI Button to Description Field**:
```blade
<!-- Description -->
<div class="space-y-1.5">
    <label class="text-sm font-medium text-text">Description</label>

    <div class="relative">
        <textarea
            wire:model="description"
            rows="3"
            class="w-full pr-24 bg-surface border border-border text-text placeholder:text-muted/60 rounded-lg px-3 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-none"
            placeholder="Detailed description of the issue..."
        ></textarea>

        <!-- AI Button -->
        @if($aiAvailable && !$aiLoading)
            <button
                wire:click="assistWithAI"
                class="absolute top-2 right-2 inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled=" !$description || $description.length < 10 "
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <span>Enhance</span>
            </button>
        @elseif($aiLoading)
            <button
                disabled
                class="absolute top-2 right-2 inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-primary rounded-lg opacity-75"
            >
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Enhancing...</span>
            </button>
        @else
            <button
                disabled
                class="absolute top-2 right-2 inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-muted bg-surface-2 border border-border rounded-lg cursor-not-allowed"
                title="{{ session('ai_error', 'AI service unavailable') }}"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Unavailable</span>
            </button>

            <!-- Retry countdown -->
            @if($aiRetryAfter)
                <button
                    wire:click="retryAI"
                    class="absolute top-10 right-2 text-xs text-primary hover:underline"
                >
                    Retry in: {{ $aiRetryAfter }}
                </button>
            @endif
        @endif
    </div>

    <x-input-error :messages="$errors->get('description')" />

    <!-- Flash messages -->
    @session('ai_success')
        <div class="mt-2 text-sm text-success flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ $value }}
        </div>
    @endsession

    @session('ai_error')
        <div class="mt-2 text-sm text-danger flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $value }}
        </div>
    @endsession
</div>
```

#### 2. Add AI Status Badge (Optional)

Add to form header to show AI-assisted status:
```blade
<div class="flex items-center justify-between">
    <h2>{{ $isEditing ? 'Edit Issue' : 'Create Issue' }}</h2>

    @if(session()->has('ai_used'))
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-primary bg-primary/10 rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
            AI-assisted
        </span>
    @endif
</div>
```

---

## API Prompt Engineering

### DeepSeek API Request

```php
private function callDeepSeekAPI(string $title, string $description): array
{
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

    $prompt = "You are a hotel issue documentation expert. Rewrite this issue description and classify it.

ORIGINAL TITLE: {$title}
ORIGINAL DESCRIPTION: {$description}

TASK:
1. Rewrite the description to be:
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
{\"enhanced_description\":\"professional rewritten description...\",\"issue_type_ids\":[22,37],\"department_ids\":[10],\"confidence\":0.95}";

    $data = [
        'model' => 'deepseek-chat',
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are a hotel issue documentation expert. Always respond with valid JSON. Use EXACT IDs from the provided lists.'
            ],
            ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.0,
        'response_format' => ['type' => 'json_object']
    ];

    $ch = curl_init('https://api.deepseek.com/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . config('services.deepseek.api_key'),
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        throw new AIException(
            "API returned HTTP {$httpCode}",
            AIErrorType::SERVER
        );
    }

    $result = json_decode($response, true);
    $content = $result['choices'][0]['message']['content'] ?? '{}';

    $parsed = json_decode($content, true);

    if (!$parsed || !isset($parsed['enhanced_description'])) {
        throw new AIException(
            "Invalid JSON response from API",
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

    return [
        'enhanced_description' => $parsed['enhanced_description'],
        'issue_type_ids' => array_values($typeIds),
        'department_ids' => array_values($deptIds),
        'confidence' => $parsed['confidence'] ?? 0.8,
    ];
}
```

---

## Configuration

### Environment Variables

```env
# .env
DEEPSEEK_API_KEY=sk-c25fcc0bfe8444c0bd363b42cb1962f4

# Optional: AI service configuration
AI_ENABLED=true
AI_FAILURE_COOLDOWN_MINUTES=5
AI_MAX_REQUESTS_PER_USER_PER_DAY=100
```

### Config File

**File**: `config/services.php`

```php
return [
    // ... existing services ...

    'deepseek' => [
        'api_key' => env('DEEPSEEK_API_KEY'),
        'api_url' => 'https://api.deepseek.com/chat/completions',
        'model' => 'deepseek-chat',
        'timeout' => 30,
    ],

    'ai' => [
        'enabled' => env('AI_ENABLED', true),
        'failure_cooldown_minutes' => env('AI_FAILURE_COOLDOWN_MINUTES', 5),
        'max_requests_per_user_per_day' => env('AI_MAX_REQUESTS_PER_USER_PER_DAY', 100),
    ],
];
```

---

## Files to Create/Modify

### New Files

1. **`app/Services/IssueAIService.php`**
   - Main AI service class
   - API communication logic
   - Error handling

2. **`app/Exceptions/AIException.php`**
   - Custom exception for AI errors
   - Error type enum
   - User-friendly messages

3. **`database/migrations/xxxx_add_ai_usage_tracking.php`** (Optional)
   - Track AI requests per user
   - Monitor success/failure rates
   - Usage analytics

### Modified Files

1. **`app/Livewire/Issues/Form.php`**
   - Add `assistWithAI()` method
   - Add `retryAI()` method
   - Add computed properties for AI state
   - Handle AI errors

2. **`resources/views/livewire/issues/form.blade.php`**
   - Add AI button to description field
   - Add loading/failure states
   - Add flash messages for AI feedback
   - Optional: AI status badge

---

## UX Flow Example

### Before AI
```
User types: "tv broke in room 302, guest angry, no picture"

[Description field]
┌─────────────────────────────────────────────┐
│ tv broke in room 302, guest angry, no       │
│ picture                                      │
│                                              │
└─────────────────────────────────────────────┘

[✨ Enhance with AI] button active
```

### Click AI Button
```
[Description field]
┌─────────────────────────────────────────────┐
│ tv broke in room 302, guest angry, no       │
│ picture                                      │
│                                              │
└─────────────────────────────────────────────┘

[✨ Enhancing...] button shows spinner
```

### After AI Success
```
[Description field]
┌─────────────────────────────────────────────┐
│ The television in Room 302 is malfunctioning.│
│ The guest reports that the TV is not        │
│ displaying any picture, causing frustration │
│ during their stay.                          │
└─────────────────────────────────────────────┘

[Issue Types] ✓ TV Problem selected
[Departments] ✓ Maintenance selected

✨ Enhanced with AI!
```

### After AI Failure
```
[Description field]
┌─────────────────────────────────────────────┐
│ tv broke in room 302, guest angry, no       │
│ picture [original preserved]                │
└─────────────────────────────────────────────┘

[⚠️ Unavailable] button disabled (hover: "Network error")

⚠️ Unable to reach AI service. Please check your connection.
[Retry in: 4 minutes]
```

---

## Error Handling Summary

### Data Safety Guarantees

✅ **Original data preserved** - Rollback on any error
✅ **Form always functional** - Never blocked by AI failure
✅ **Manual editing** - Always available as fallback
✅ **No data loss** - Session-based failure state clears itself

### User Communication

✅ **Clear error messages** - Specific to failure type
✅ **Visual feedback** - Button state changes
✅ **Recovery path** - Retry option with countdown
✅ **No jarring alerts** - Toast/inline messages only

### Admin Visibility

✅ **Error logging** - All failures logged with context
✅ **Usage tracking** - Optional analytics table
✅ **Health monitoring** - Can check API status dashboard
✅ **Rate limiting** - Prevent abuse

---

## Optional Enhancements (Future)

1. **Confidence-based warnings** - Warn if AI confidence < 70%
2. **Multi-language support** - Detect and preserve guest's language
3. **Template learning** - Learn from past successful categorizations
4. **Batch enhancement** - Apply AI to multiple uncategorized issues
5. **Usage dashboard** - Show AI statistics to admins
6. **Cost tracking** - Monitor DeepSeek API costs per user/month
7. **Feedback loop** - Users can correct AI, improving future accuracy
8. **Auto-assignee** - Suggest best person to assign based on history

---

## Testing Checklist

- [ ] AI button enables when description has 10+ characters
- [ ] Loading state shows during API call
- [ ] Success message displays and fields update
- [ ] Network failure disables button correctly
- [ ] Cooldown period re-enables button
- [ ] Manual retry works during cooldown
- [ ] Invalid API key shows admin error
- [ ] Rate limit shows daily limit message
- [ ] Original data preserved on error
- [ ] Form submission works regardless of AI state
- [ ] Multiple users can use AI simultaneously
- [ ] Session-based failure clears appropriately
- [ ] Error logging captures all failure types

---

## Cost Estimates

### DeepSeek API Pricing (as of 2025)
- **Input**: ~$0.14 per million tokens
- **Output**: ~$0.28 per million tokens

### Per Request Cost
- **Average issue**: ~300 tokens (prompt + response)
- **Estimated cost**: ~$0.0001 per request
- **100 requests/day**: ~$0.01 per day
- **3,000 requests/month**: ~$0.30 per month

### Budget Planning
- **Small hotel (50 issues/month)**: ~$0.005/month
- **Medium hotel (200 issues/month)**: ~$0.02/month
- **Large hotel (1000 issues/month)**: ~$0.10/month

**Very cost-effective** compared to time saved on data entry and improved classification accuracy.

---

## Success Metrics

Track these to measure impact:

1. **Adoption rate**: % of issues created with AI assistance
2. **Time saved**: Average form completion time with/without AI
3. **Data quality**: % reduction in issues needing reclassification
4. **User satisfaction**: Feedback surveys after 30 days
5. **Error rate**: % of AI requests that fail
6. **Cost per issue**: Total API cost / number of AI-assisted issues

---

## Implementation Timeline

1. **Backend (2-3 hours)**
   - Create IssueAIService
   - Add AI exception types
   - Update Form Livewire component

2. **Frontend (1-2 hours)**
   - Add AI button with states
   - Implement loading/failure UI
   - Add flash messages

3. **Configuration (30 minutes)**
   - Set environment variables
   - Create config entries
   - Test API connection

4. **Testing (1-2 hours)**
   - Test all failure scenarios
   - Verify rollback behavior
   - Check rate limiting

**Total**: 4.5-7.5 hours

---

## Migration Notes

### No Database Changes Required
- Uses existing `issues` table
- Optional: Add `ai_assisted` boolean column for tracking

### No Breaking Changes
- Feature is additive only
- Forms work without AI
- No existing code affected

### Rollback Plan
If issues arise:
1. Set `AI_ENABLED=false` in `.env`
2. AI button hides automatically
3 All forms continue working normally

---

## Support & Troubleshooting

### Common Issues

**Q: AI button not appearing**
- Check `AI_ENABLED=true` in `.env`
- Verify DEEPSEEK_API_KEY is set
- Clear browser cache

**Q: "AI service not configured" error**
- Verify API key in `.env`
- Run `php artisan config:clear`
- Check API key hasn't expired

**Q: Button stays disabled after 5 minutes**
- Clear session: `php artisan session:clear`
- Check browser cookies/local storage
- Verify cooldown config

**Q: Poor AI categorization**
- Check issue types and departments are seeded
- Review prompt engineering in IssueAIService
- Consider adding examples to prompt

---

## Conclusion

This AI enhancement will:
- ✅ Save time on issue entry
- ✅ Improve data quality and consistency
- ✅ Reduce classification errors
- ✅ Provide professional, well-documented issues
- ✅ Scale seamlessly with usage
- ✅ Fail gracefully when API unavailable

**Ready to implement!** 🚀
