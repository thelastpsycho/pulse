<?php

namespace App\Livewire\Reports;

use App\Models\IssueCategory;
use App\Services\ReportService;
use Livewire\Component;

class Monthly extends Component
{
    public int $selectedYear;
    public int $selectedMonth;
    public ?int $selectedCategoryId = null;
    public array $availableYears = [];
    public array $availableMonths = [];
    public array $availableCategories = [];

    public array $reportData = [];

    public function mount(): void
    {
        $this->selectedYear = now()->year;
        $this->selectedMonth = now()->month;

        // Generate available years (current year and 4 years back)
        for ($i = 0; $i < 5; $i++) {
            $year = now()->subYears($i)->year;
            $this->availableYears[$year] = $year;
        }

        // Generate available months
        for ($i = 1; $i <= 12; $i++) {
            $this->availableMonths[$i] = now()->setDateTime($this->selectedYear, $i, 1, 0, 0)->format('F');
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
        $this->reportData = $reportService->monthlyReport(
            $this->selectedYear,
            $this->selectedMonth,
            $this->selectedCategoryId
        );
    }

    public function updatedSelectedYear(): void
    {
        $this->loadReport();
    }

    public function updatedSelectedMonth(): void
    {
        $this->loadReport();
    }

    public function updatedSelectedCategoryId(): void
    {
        $this->loadReport();
    }

    public function render()
    {
        return view('livewire.reports.monthly', [
            'report' => $this->reportData,
            'availableYears' => $this->availableYears,
            'availableMonths' => $this->availableMonths,
            'availableCategories' => $this->availableCategories,
        ])->layout('layouts.app')->title('Monthly Report');
    }
}
