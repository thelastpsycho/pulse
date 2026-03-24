<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IssueTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'default_severity' => $this->default_severity,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'issue_category_id' => $this->issue_category_id,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'category' => IssueCategoryResource::make($this->whenLoaded('issueCategory')),
            'issues_count' => $this->whenCounted('issues'),
        ];
    }
}
