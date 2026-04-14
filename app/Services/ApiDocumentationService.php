<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Route as RouteInstance;
use ReflectionClass;
use ReflectionMethod;

class ApiDocumentationService
{
    protected array $excludePatterns = [
        'sanctum',
        'verification',
        'login',
        'forgot-password',
        'reset-password',
        'register',
        'profile',
        'dashboard',
        'docs',
        'ignition',
        'telescope',
        'horizon',
    ];

    protected array $categoryMapping = [
        'issues' => [
            'name' => 'Issues',
            'icon' => '',
            'description' => 'Issue management and lifecycle operations',
            'color' => 'blue',
        ],
        'issue-comments' => [
            'name' => 'Issue Comments',
            'icon' => '',
            'description' => 'Comment and discussion management',
            'color' => 'purple',
        ],
        'issue-types' => [
            'name' => 'Issue Types',
            'icon' => '',
            'description' => 'Issue type categorization',
            'color' => 'indigo',
        ],
        'issue-categories' => [
            'name' => 'Issue Categories',
            'icon' => '',
            'description' => 'Category management and organization',
            'color' => 'violet',
        ],
        'departments' => [
            'name' => 'Departments',
            'icon' => '',
            'description' => 'Department structure management',
            'color' => 'cyan',
        ],
        'users' => [
            'name' => 'Users',
            'icon' => '',
            'description' => 'User account and permission management',
            'color' => 'emerald',
        ],
        'roles' => [
            'name' => 'Roles',
            'icon' => '',
            'description' => 'Role-based access control',
            'color' => 'amber',
        ],
        'permissions' => [
            'name' => 'Permissions',
            'icon' => '',
            'description' => 'Permission management system',
            'color' => 'rose',
        ],
        'activity-logs' => [
            'name' => 'Activity Logs',
            'icon' => '',
            'description' => 'Audit trail and activity tracking',
            'color' => 'slate',
        ],
        'statistics' => [
            'name' => 'Statistics',
            'icon' => '',
            'description' => 'Analytics and reporting statistics',
            'color' => 'chart',
        ],
        'reports' => [
            'name' => 'Reports',
            'icon' => '',
            'description' => 'Business intelligence reports',
            'color' => 'green',
        ],
        'exports' => [
            'name' => 'Exports',
            'icon' => '',
            'description' => 'Data export and download functionality',
            'color' => 'teal',
        ],
        'saved-filters' => [
            'name' => 'Saved Filters',
            'icon' => '',
            'description' => 'Custom search filters and views',
            'color' => 'orange',
        ],
        'logbook' => [
            'name' => 'DM Log Book',
            'icon' => '',
            'description' => 'Daily management log entries',
            'color' => 'brown',
        ],
        'bulk' => [
            'name' => 'Bulk Operations',
            'icon' => '',
            'description' => 'Bulk data processing operations',
            'color' => 'yellow',
        ],
    ];

    public function getDocumentation(): array
    {
        $routes = collect(Route::getRoutes());
        $apiRoutes = collect();

        foreach ($routes as $route) {
            if ($this->shouldIncludeRoute($route)) {
                $apiRoutes->push($this->formatRoute($route));
            }
        }

        return [
            'categories' => $this->categorizeRoutes($apiRoutes),
            'stats' => $this->calculateStats($apiRoutes),
            'endpoints' => $apiRoutes->toArray(),
        ];
    }

    protected function shouldIncludeRoute(RouteInstance $route): bool
    {
        $uri = $route->uri();

        // Only include API routes
        if (!str_starts_with($uri, 'api/')) {
            return false;
        }

        // Exclude certain patterns
        foreach ($this->excludePatterns as $pattern) {
            if (str_contains($uri, $pattern)) {
                return false;
            }
        }

        return true;
    }

    protected function formatRoute(RouteInstance $route): array
    {
        $methods = collect($route->methods())->filter(fn($m) => $m !== 'HEAD')->toArray();
        $uri = $route->uri();
        $action = $route->getActionName();

        return [
            'uri' => $uri,
            'methods' => $methods,
            'name' => $route->getName(),
            'action' => $action,
            'middleware' => $route->middleware(),
            'category' => $this->getCategoryFromUri($uri),
            'parameters' => $this->extractParameters($uri),
            'summary' => $this->generateSummary($uri, $methods),
            'description' => $this->generateDescription($uri, $methods),
        ];
    }

    protected function getCategoryFromUri(string $uri): string
    {
        // Remove 'api/' prefix
        $path = str_replace('api/', '', $uri);

        // Get the first segment
        $segments = explode('/', $path);
        $category = $segments[0] ?? 'other';

        // Handle special cases
        if (str_contains($category, 'issue-')) {
            return 'issues';
        }

        if (str_contains($uri, 'bulk')) {
            return 'bulk';
        }

        return $category;
    }

    protected function extractParameters(string $uri): array
    {
        preg_match_all('/\{([^}]+)\}/', $uri, $matches);
        return $matches[1] ?? [];
    }

    protected function generateSummary(string $uri, array $methods): string
    {
        $category = $this->getCategoryFromUri($uri);
        $action = $this->getActionFromMethods($methods);
        $resource = $this->getResourceName($uri);

        return "{$action} {$resource}";
    }

    protected function generateDescription(string $uri, array $methods): string
    {
        $descriptions = [
            'list' => "Retrieve a paginated list of resources with optional filtering and sorting capabilities",
            'show' => "Retrieve detailed information for a specific resource including all relationships",
            'store' => "Create a new resource with the provided data. Returns the created resource on success",
            'update' => "Update an existing resource with new data. Only provided fields will be updated",
            'destroy' => "Permanently delete a resource. This action cannot be undone",
            'close' => "Mark the resource as closed and record the closure timestamp",
            'reopen' => "Reopen a previously closed resource and reset its status",
            'export' => "Generate and download data exports in various formats including CSV, Excel, and PDF",
            'bulk' => "Perform operations on multiple resources simultaneously (maximum 100 items per request)",
            'dashboard' => "Retrieve aggregated statistics and metrics for dashboard display",
            'trends' => "Access trend analysis and historical data patterns",
        ];

        $methodKey = strtolower($methods[0] ?? 'get');

        // Special handling for specific endpoints
        if (str_contains($uri, 'bulk/create')) {
            return "Create multiple resources in a single request. All items will be created atomically";
        }
        if (str_contains($uri, 'bulk/update')) {
            return "Update multiple resources with the same data values in a single request";
        }
        if (str_contains($uri, 'bulk/delete')) {
            return "Delete multiple resources in a single request. All deletions are permanent";
        }
        if (str_contains($uri, 'bulk/close')) {
            return "Close multiple open resources simultaneously and record closure timestamps";
        }
        if (str_contains($uri, 'bulk/reopen')) {
            return "Reopen multiple closed resources and reset their status to open";
        }

        // Method-based descriptions
        if ($methodKey === 'get' && !str_contains($uri, '{')) {
            return $descriptions['list'] ?? "Retrieve a list of resources";
        }

        if ($methodKey === 'get' && str_contains($uri, '{')) {
            return $descriptions['show'] ?? "Retrieve resource details";
        }

        if ($methodKey === 'post') {
            return $descriptions['store'] ?? "Create a new resource";
        }

        if ($methodKey === 'put' || $methodKey === 'patch') {
            return $descriptions['update'] ?? "Update resource data";
        }

        if ($methodKey === 'delete') {
            return $descriptions['destroy'] ?? "Delete resource";
        }

        // Fallback description
        return "API endpoint for {$this->getResourceName($uri)} operations";
    }

    protected function getActionFromMethods(array $methods): string
    {
        $method = strtolower($methods[0] ?? 'get');

        return match($method) {
            'get' => 'Retrieve',
            'post' => 'Create',
            'put', 'patch' => 'Update',
            'delete' => 'Delete',
            default => 'Handle'
        };
    }

    protected function getResourceName(string $uri): string
    {
        $category = $this->getCategoryFromUri($uri);
        return $this->categoryMapping[$category]['name'] ?? 'Resource';
    }

    protected function categorizeRoutes($routes): array
    {
        return $routes->groupBy('category')->map(function ($routes, $category) {
            $config = $this->categoryMapping[$category] ?? [
                'name' => ucfirst($category),
                'icon' => '📄',
                'description' => 'API endpoints',
                'color' => 'gray',
            ];

            return [
                'config' => $config,
                'routes' => $routes->sortBy('uri')->values()->toArray(),
                'count' => $routes->count(),
            ];
        })->sortByDesc('count')->toArray();
    }

    protected function calculateStats($routes): array
    {
        $methodCounts = [];
        foreach ($routes as $route) {
            foreach ($route['methods'] as $method) {
                $methodCounts[$method] = ($methodCounts[$method] ?? 0) + 1;
            }
        }

        return [
            'total' => $routes->count(),
            'categories' => $routes->groupBy('category')->count(),
            'methods' => $methodCounts,
        ];
    }
}
