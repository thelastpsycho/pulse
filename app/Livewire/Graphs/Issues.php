<?php

namespace App\Livewire\Graphs;

use App\Models\Issue;
use App\Models\IssueCategory;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Issues extends Component
{
    public int $selectedYear;
    public ?int $selectedCategoryId = null;
    public array $availableYears = [];
    public array $availableCategories = [];
    public array $chartData = [];

    public function mount(): void
    {
        $this->selectedYear = now()->year;

        // Generate available years (current year and 4 years back)
        for ($i = 0; $i < 5; $i++) {
            $year = now()->subYears($i)->year;
            $this->availableYears[$year] = $year;
        }

        // Load available categories
        $this->availableCategories = IssueCategory::orderBy('name')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        $this->loadChartData();
    }

    public function loadChartData(): void
    {
        // Monthly trend data
        $monthlyQuery = DB::table('issues')
            ->whereYear('issues.created_at', $this->selectedYear)
            ->whereNull('issues.deleted_at')
            ->selectRaw('MONTH(issues.created_at) as month, COUNT(*) as count');

        if ($this->selectedCategoryId) {
            $monthlyQuery
                ->join('issue_issue_type as iit', 'issues.id', '=', 'iit.issue_id')
                ->join('issue_types as it', 'iit.issue_type_id', '=', 'it.id')
                ->where('it.issue_category_id', $this->selectedCategoryId);
        }

        $monthlyData = $monthlyQuery
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        for ($i = 1; $i <= 12; $i++) {
            $this->chartData['monthly'][] = [
                'month' => $i,
                'label' => now()->setDateTime($this->selectedYear, $i, 1, 0, 0)->format('M'),
                'count' => $monthlyData[$i] ?? 0,
            ];
        }

        // Status breakdown
        $openQuery = DB::table('issues')
            ->whereYear('issues.created_at', $this->selectedYear)
            ->whereNull('issues.deleted_at')
            ->whereNull('issues.closed_at');

        $closedQuery = DB::table('issues')
            ->whereYear('issues.created_at', $this->selectedYear)
            ->whereNull('issues.deleted_at')
            ->whereNotNull('issues.closed_at');

        if ($this->selectedCategoryId) {
            $openQuery
                ->join('issue_issue_type as iit', 'issues.id', '=', 'iit.issue_id')
                ->join('issue_types as it', 'iit.issue_type_id', '=', 'it.id')
                ->where('it.issue_category_id', $this->selectedCategoryId);

            $closedQuery
                ->join('issue_issue_type as iit', 'issues.id', '=', 'iit.issue_id')
                ->join('issue_types as it', 'iit.issue_type_id', '=', 'it.id')
                ->where('it.issue_category_id', $this->selectedCategoryId);
        }

        $openCount = $openQuery->count();
        $closedCount = $closedQuery->count();

        $this->chartData['status'] = [
            'open' => $openCount,
            'closed' => $closedCount,
        ];

        // Department breakdown
        $deptQuery = DB::table('issues')
            ->whereYear('issues.created_at', $this->selectedYear)
            ->whereNull('issues.deleted_at')
            ->selectRaw('d.name, COUNT(*) as count')
            ->join('department_issue as di', 'issues.id', '=', 'di.issue_id')
            ->join('departments as d', 'di.department_id', '=', 'd.id');

        if ($this->selectedCategoryId) {
            $deptQuery
                ->join('issue_issue_type as iit2', 'issues.id', '=', 'iit2.issue_id')
                ->join('issue_types as it2', 'iit2.issue_type_id', '=', 'it2.id')
                ->where('it2.issue_category_id', $this->selectedCategoryId);
        }

        $deptData = $deptQuery
            ->groupBy('d.id', 'd.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $this->chartData['departments'] = $deptData->map(fn($item) => [
            'name' => $item->name,
            'count' => $item->count,
        ])->toArray();

        // Issue type breakdown
        $typeQuery = DB::table('issues')
            ->whereYear('issues.created_at', $this->selectedYear)
            ->whereNull('issues.deleted_at')
            ->selectRaw('it.name, COUNT(*) as count')
            ->join('issue_issue_type as iit', 'issues.id', '=', 'iit.issue_id')
            ->join('issue_types as it', 'iit.issue_type_id', '=', 'it.id');

        if ($this->selectedCategoryId) {
            $typeQuery->where('it.issue_category_id', $this->selectedCategoryId);
        }

        $typeData = $typeQuery
            ->groupBy('it.id', 'it.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $this->chartData['issueTypes'] = $typeData->map(fn($item) => [
            'name' => $item->name,
            'count' => $item->count,
        ])->toArray();
    }

    public function updatedSelectedYear(): void
    {
        $this->loadChartData();
    }

    public function updatedSelectedCategoryId(): void
    {
        $this->loadChartData();
    }

    public function render()
    {
        return view('livewire.graphs.issues', [
            'chartData' => $this->chartData,
            'availableYears' => $this->availableYears,
            'availableCategories' => $this->availableCategories,
        ])->layout('layouts.app')->title('Issues Graphs');
    }
}
