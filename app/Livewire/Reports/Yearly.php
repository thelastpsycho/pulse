<?php

namespace App\Livewire\Reports;

use App\Models\IssueCategory;
use App\Services\ReportService;
use Livewire\Component;

class Yearly extends Component
{
    public int $selectedYear;
    public ?int $selectedCategoryId = null;
    public array $availableYears = [];
    public array $availableCategories = [];

    public array $reportData = [];

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

        $this->loadReport();
    }

    public function loadReport(): void
    {
        $reportService = app(ReportService::class);
        $this->reportData = $reportService->yearlyReport(
            $this->selectedYear,
            $this->selectedCategoryId
        );
    }

    public function updatedSelectedYear(): void
    {
        $this->loadReport();
    }

    public function updatedSelectedCategoryId(): void
    {
        $this->loadReport();
    }

    public function render()
    {
        return view('livewire.reports.yearly', [
            'report' => $this->reportData,
            'availableYears' => $this->availableYears,
            'availableCategories' => $this->availableCategories,
        ])->layout('layouts.app')->title('Yearly Report');
    }
}
