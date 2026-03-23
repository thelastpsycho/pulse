<?php

namespace App\Livewire\Reports;

use App\Models\IssueCategory;
use App\Services\ReportService;
use Livewire\Component;

class Index extends Component
{
    public string $dateRangePreset = 'last_month';
    public string $dateFrom;
    public string $dateTo;
    public ?int $selectedCategoryId = null;
    public array $availableCategories = [];
    public array $reportData = [];

    public array $presetOptions = [
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        'this_week' => 'This Week',
        'last_week' => 'Last Week',
        'mtd' => 'Month to Date (MTD)',
        'last_month' => 'Last Month',
        'last_3_months' => 'Last 3 Months',
        'last_6_months' => 'Last 6 Months',
        'ytd' => 'Year to Date (YTD)',
        'last_year' => 'Last Year',
        'custom' => 'Custom Range',
    ];

    public function mount(): void
    {
        $this->applyDatePreset();

        // Load available categories
        $this->availableCategories = IssueCategory::orderBy('name')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        $this->loadReport();
    }

    public function applyDatePreset(): void
    {
        switch ($this->dateRangePreset) {
            case 'today':
                $this->dateFrom = now()->format('Y-m-d');
                $this->dateTo = now()->format('Y-m-d');
                break;
            case 'yesterday':
                $this->dateFrom = now()->subDay()->format('Y-m-d');
                $this->dateTo = now()->subDay()->format('Y-m-d');
                break;
            case 'this_week':
                $this->dateFrom = now()->startOfWeek()->format('Y-m-d');
                $this->dateTo = now()->format('Y-m-d');
                break;
            case 'last_week':
                $this->dateFrom = now()->subWeek()->startOfWeek()->format('Y-m-d');
                $this->dateTo = now()->subWeek()->endOfWeek()->format('Y-m-d');
                break;
            case 'mtd':
                $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
                $this->dateTo = now()->format('Y-m-d');
                break;
            case 'last_month':
                $this->dateFrom = now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->dateTo = now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_3_months':
                $this->dateFrom = now()->subMonths(2)->startOfMonth()->format('Y-m-d');
                $this->dateTo = now()->format('Y-m-d');
                break;
            case 'last_6_months':
                $this->dateFrom = now()->subMonths(5)->startOfMonth()->format('Y-m-d');
                $this->dateTo = now()->format('Y-m-d');
                break;
            case 'ytd':
                $this->dateFrom = now()->startOfYear()->format('Y-m-d');
                $this->dateTo = now()->format('Y-m-d');
                break;
            case 'last_year':
                $this->dateFrom = now()->subYear()->startOfYear()->format('Y-m-d');
                $this->dateTo = now()->subYear()->endOfYear()->format('Y-m-d');
                break;
            case 'custom':
                // Don't change dates when switching to custom
                break;
        }
    }

    public function updatedDateRangePreset(): void
    {
        $this->applyDatePreset();
        $this->loadReport();
    }

    public function updatedDateFrom(): void
    {
        // When manually changing dates, switch to custom preset
        if ($this->dateRangePreset !== 'custom') {
            $this->dateRangePreset = 'custom';
        }
        $this->loadReport();
    }

    public function updatedDateTo(): void
    {
        // When manually changing dates, switch to custom preset
        if ($this->dateRangePreset !== 'custom') {
            $this->dateRangePreset = 'custom';
        }
        $this->loadReport();
    }

    public function updatedSelectedCategoryId(): void
    {
        $this->loadReport();
    }

    public function loadReport(): void
    {
        $reportService = app(ReportService::class);
        $this->reportData = $reportService->dateRangeReport(
            $this->dateFrom,
            $this->dateTo,
            $this->selectedCategoryId
        );
    }

    public function render()
    {
        return view('livewire.reports.index', [
            'report' => $this->reportData,
            'availableCategories' => $this->availableCategories,
            'presetOptions' => $this->presetOptions,
        ])->layout('layouts.app')->title('Reports');
    }
}
