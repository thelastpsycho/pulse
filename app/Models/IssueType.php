<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IssueType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'default_severity',
        'description',
        'is_active',
        'issue_category_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the issues that belong to the issue type.
     */
    public function issues(): BelongsToMany
    {
        return $this->belongsToMany(Issue::class, 'issue_issue_type')
            ->withTimestamps();
    }

    /**
     * Get the category that this issue type belongs to.
     */
    public function issueCategory(): BelongsTo
    {
        return $this->belongsTo(IssueCategory::class);
    }
}
