<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pulse API Documentation - Complete Reference</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-json.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-bash.min.js"></script>
    <style>
        .sidebar { height: 100vh; overflow-y: auto; }
        .content { height: 100vh; overflow-y: auto; }
        .method-get { background-color: #10b981; }
        .method-post { background-color: #3b82f6; }
        .method-put { background-color: #f59e0b; }
        .method-patch { background-color: #8b5cf6; }
        .method-delete { background-color: #ef4444; }
        pre[class*="language-"] { margin: 0; border-radius: 0.5rem; }
        code[class*="language-"] { font-size: 0.875rem; }
        .endpoint-card { transition: all 0.2s ease; }
        .endpoint-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="sidebar w-80 bg-white border-r border-gray-200 fixed">
            <div class="p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
                <h1 class="text-2xl font-bold text-gray-900">🚀 Pulse API</h1>
                <p class="text-sm text-gray-600 mt-1">Complete API Reference</p>
                <div class="mt-3 flex items-center text-xs text-gray-500">
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">125+ Endpoints</span>
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded ml-2">v1.0</span>
                </div>
            </div>

            <div class="p-4 sticky top-24 bg-white z-10">
                <input type="text" id="search" placeholder="🔍 Search endpoints..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <nav class="px-4 pb-4">
                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Getting Started</h3>
                    <a href="#overview" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Overview</a>
                    <a href="#authentication" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Authentication</a>
                    <a href="#pagination" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Pagination</a>
                    <a href="#errors" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Error Handling</a>
                </div>

                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Core Resources</h3>
                    <a href="#issues" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Issues (9 endpoints)</a>
                    <a href="#issue-comments" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Issue Comments</a>
                    <a href="#departments" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Departments</a>
                    <a href="#issue-types" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Issue Types</a>
                    <a href="#issue-categories" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Issue Categories</a>
                </div>

                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">User Management</h3>
                    <a href="#users" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Users (8 endpoints)</a>
                    <a href="#roles" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Roles</a>
                    <a href="#permissions" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Permissions</a>
                </div>

                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">⚡ Bulk Operations</h3>
                    <a href="#bulk-operations" class="block py-1 px-2 text-sm text-blue-700 hover:bg-blue-50 rounded font-semibold">Bulk Operations (5 endpoints)</a>
                </div>

                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">📊 Statistics & Reports</h3>
                    <a href="#statistics" class="block py-1 px-2 text-sm text-blue-700 hover:bg-blue-50 rounded font-semibold">Statistics (4 endpoints)</a>
                    <a href="#reports" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Reports</a>
                </div>

                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">📤 Export</h3>
                    <a href="#exports" class="block py-1 px-2 text-sm text-blue-700 hover:bg-blue-50 rounded font-semibold">Export (4 endpoints)</a>
                </div>

                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Other Resources</h3>
                    <a href="#saved-filters" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Saved Filters</a>
                    <a href="#activity-logs" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Activity Logs</a>
                    <a href="#logbook" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">DM Log Book</a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="content ml-80 flex-1 p-8">
            <div class="max-w-4xl mx-auto">

                <!-- Overview -->
                <section id="overview" class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">🚀 Pulse API Overview</h2>
                    <p class="text-gray-700 mb-6">
                        Welcome to the Pulse API documentation. The Pulse API provides comprehensive RESTful endpoints to manage
                        hotel/DM operations including issues, users, departments, reports, and more. This API enables seamless
                        integration with your applications.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="text-2xl font-bold text-blue-600">125+</div>
                            <div class="text-sm text-gray-600">API Endpoints</div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="text-2xl font-bold text-green-600">11</div>
                            <div class="text-sm text-gray-600">Core Resources</div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="text-2xl font-bold text-purple-600">47</div>
                            <div class="text-sm text-gray-600">New Endpoints</div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 mb-2">📍 Base URL</h4>
                        <code class="text-blue-800 text-lg">/api</code>
                    </div>
                </section>

                <!-- Authentication -->
                <section id="authentication" class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">🔐 Authentication</h2>
                    <p class="text-gray-700 mb-4">
                        All API endpoints require authentication. Pulse API supports two authentication methods:
                    </p>

                    <div class="space-y-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h4 class="font-semibold text-gray-900 mb-3">1. Token Authentication (Sanctum)</h4>
                            <p class="text-gray-700 mb-3">Include your Bearer token in the Authorization header:</p>
                            <div class="bg-gray-900 rounded-lg p-4">
                                <pre><code class="language-bash">Authorization: Bearer {your_token_here}</code></pre>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h4 class="font-semibold text-gray-900 mb-3">2. Session Authentication</h4>
                            <p class="text-gray-700 mb-3">Use authenticated session cookies from web login.</p>
                        </div>
                    </div>
                </section>

                <!-- Pagination -->
                <section id="pagination" class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">📄 Pagination</h2>
                    <p class="text-gray-700 mb-4">
                        List endpoints support pagination with consistent response structure:
                    </p>

                    <div class="bg-gray-900 rounded-lg p-4">
                        <pre><code class="language-json">{
  "data": [...],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 142
  },
  "links": {
    "first": "...",
    "last": "...",
    "prev": "...",
    "next": "..."
  }
}</code></pre>
                    </div>
                </section>

                <!-- Issues Section -->
                <section id="issues" class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">📋 Issues API</h2>
                    <p class="text-gray-700 mb-6">Complete issue management with 9 endpoints including lifecycle operations.</p>

                    <div class="space-y-6">
                        <!-- List Issues -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-get text-white text-xs font-bold px-2 py-1 rounded mr-3">GET</span>
                                    <code class="text-lg">/api/issues</code>
                                </div>
                                <p class="text-gray-600 mt-2">List all issues with filtering and pagination</p>
                            </div>
                            <div class="p-4">
                                <div class="mb-4">
                                    <h5 class="font-semibold text-gray-900 mb-2">Query Parameters</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                        <div><code class="bg-gray-100 px-2 py-1 rounded">status</code> - open, in_progress, closed, cancelled</div>
                                        <div><code class="bg-gray-100 px-2 py-1 rounded">priority</code> - urgent, high, medium, low</div>
                                        <div><code class="bg-gray-100 px-2 py-1 rounded">department_id</code> - Filter by department</div>
                                        <div><code class="bg-gray-100 px-2 py-1 rounded">search</code> - Search in title/description</div>
                                        <div><code class="bg-gray-100 px-2 py-1 rounded">per_page</code> - Items per page (default: 15)</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Create Issue -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                                    <code class="text-lg">/api/issues</code>
                                </div>
                                <p class="text-gray-600 mt-2">Create a new issue</p>
                            </div>
                            <div class="p-4">
                                <div class="bg-gray-900 rounded-lg p-4">
                                    <pre><code class="language-json">{
  "title": "Room 301 AC not working",
  "description": "Guest reported AC issue",
  "priority": "high",
  "assigned_to_user_id": 5,
  "department_ids": [1, 2],
  "issue_type_ids": [1]
}</code></pre>
                                </div>
                            </div>
                        </div>

                        <!-- Close Issue -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                                    <code class="text-lg">/api/issues/{issue}/close</code>
                                </div>
                                <p class="text-gray-600 mt-2">Close an issue (with optional note)</p>
                            </div>
                        </div>

                        <!-- Reopen Issue -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                                    <code class="text-lg">/api/issues/{issue}/reopen</code>
                                </div>
                                <p class="text-gray-600 mt-2">Reopen a closed issue</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Bulk Operations -->
                <section id="bulk-operations" class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">⚡ Bulk Operations</h2>
                    <p class="text-gray-700 mb-6">Perform operations on multiple issues at once (max 100 items).</p>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <h4 class="font-semibold text-yellow-900 mb-2">⚠️ Bulk Operations Limit</h4>
                        <p class="text-yellow-800">Maximum 100 issues per bulk operation for performance and safety.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Bulk Create -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                                    <code class="text-lg">/api/issues/bulk/create</code>
                                </div>
                                <p class="text-gray-600 mt-2">Create multiple issues at once</p>
                            </div>
                            <div class="p-4">
                                <div class="bg-gray-900 rounded-lg p-4">
                                    <pre><code class="language-json">{
  "issues": [
    {
      "title": "Room 101 TV issue",
      "priority": "medium",
      "department_ids": [1]
    },
    {
      "title": "Room 102 plumbing",
      "priority": "high",
      "assigned_to_user_id": 5
    }
  ]
}</code></pre>
                                </div>
                            </div>
                        </div>

                        <!-- Bulk Update -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-put text-white text-xs font-bold px-2 py-1 rounded mr-3">PUT</span>
                                    <code class="text-lg">/api/issues/bulk/update</code>
                                </div>
                                <p class="text-gray-600 mt-2">Update multiple issues with same values</p>
                            </div>
                        </div>

                        <!-- Bulk Delete -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-delete text-white text-xs font-bold px-2 py-1 rounded mr-3">DELETE</span>
                                    <code class="text-lg">/api/issues/bulk/delete</code>
                                </div>
                                <p class="text-gray-600 mt-2">Delete multiple issues</p>
                            </div>
                        </div>

                        <!-- Bulk Close -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                                    <code class="text-lg">/api/issues/bulk/close</code>
                                </div>
                                <p class="text-gray-600 mt-2">Close multiple issues at once</p>
                            </div>
                        </div>

                        <!-- Bulk Reopen -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                                    <code class="text-lg">/api/issues/bulk/reopen</code>
                                </div>
                                <p class="text-gray-600 mt-2">Reopen multiple closed issues</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Statistics -->
                <section id="statistics" class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">📊 Statistics API</h2>
                    <p class="text-gray-700 mb-6">Comprehensive statistics and analytics for data-driven decisions.</p>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Dashboard -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-get text-white text-xs font-bold px-2 py-1 rounded mr-3">GET</span>
                                    <code class="text-lg">/api/statistics/dashboard</code>
                                </div>
                                <p class="text-gray-600 mt-2">Dashboard overview with key metrics</p>
                            </div>
                            <div class="p-4">
                                <div class="bg-gray-900 rounded-lg p-4">
                                    <pre><code class="language-json">{
  "summary": {
    "total_issues": 142,
    "open_issues": 58,
    "closed_issues": 84,
    "urgent_issues": 8,
    "avg_resolution_hours": 24.5
  },
  "by_priority": {
    "urgent": 8, "high": 23, "medium": 45, "low": 22
  },
  "by_status": {
    "open": 58, "in_progress": 32, "closed": 84
  },
  "recent_activity": [...]
}</code></pre>
                                </div>
                            </div>
                        </div>

                        <!-- By Department -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-get text-white text-xs font-bold px-2 py-1 rounded mr-3">GET</span>
                                    <code class="text-lg">/api/statistics/by-department</code>
                                </div>
                                <p class="text-gray-600 mt-2">Department-wise statistics with closure rates</p>
                            </div>
                        </div>

                        <!-- By User -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-get text-white text-xs font-bold px-2 py-1 rounded mr-3">GET</span>
                                    <code class="text-lg">/api/statistics/by-user</code>
                                </div>
                                <p class="text-gray-600 mt-2">User performance statistics with completion rates</p>
                            </div>
                        </div>

                        <!-- Trends -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-get text-white text-xs font-bold px-2 py-1 rounded mr-3">GET</span>
                                    <code class="text-lg">/api/statistics/trends</code>
                                </div>
                                <p class="text-gray-600 mt-2">Trend analysis with daily/weekly/monthly periods</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Exports -->
                <section id="exports" class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">📤 Export API</h2>
                    <p class="text-gray-700 mb-6">Export issues and reports in multiple formats.</p>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- CSV Export -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                                    <code class="text-lg">/api/exports/issues/csv</code>
                                </div>
                                <p class="text-gray-600 mt-2">Export issues to CSV format</p>
                            </div>
                            <div class="p-4">
                                <div class="bg-gray-900 rounded-lg p-4">
                                    <pre><code class="language-json">{
  "status": "closed",
  "priority": "high",
  "department_id": 1,
  "date_from": "2026-04-01",
  "date_to": "2026-04-30"
}</code></pre>
                                </div>
                            </div>
                        </div>

                        <!-- Excel Export -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                                    <code class="text-lg">/api/exports/issues/excel</code>
                                </div>
                                <p class="text-gray-600 mt-2">Export issues to Excel format with formatting</p>
                            </div>
                        </div>

                        <!-- PDF Export -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                                    <code class="text-lg">/api/exports/issues/pdf</code>
                                </div>
                                <p class="text-gray-600 mt-2">Export issues to professional PDF report</p>
                            </div>
                        </div>

                        <!-- Reports Export -->
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                                    <code class="text-lg">/api/exports/reports</code>
                                </div>
                                <p class="text-gray-600 mt-2">Export monthly/yearly reports to CSV or PDF</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- User Management -->
                <section id="users" class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">👥 User Management API</h2>
                    <p class="text-gray-700 mb-6">Complete user lifecycle management with 8 endpoints.</p>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                                    <code class="text-lg">/api/users/{user}/roles</code>
                                </div>
                                <p class="text-gray-600 mt-2">Assign role to user</p>
                            </div>
                        </div>

                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-delete text-white text-xs font-bold px-2 py-1 rounded mr-3">DELETE</span>
                                    <code class="text-lg">/api/users/{user}/roles/{role}</code>
                                </div>
                                <p class="text-gray-600 mt-2">Remove role from user</p>
                            </div>
                        </div>

                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                                    <code class="text-lg">/api/users/{user}/password</code>
                                </div>
                                <p class="text-gray-600 mt-2">Change user password</p>
                            </div>
                        </div>

                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                                    <code class="text-lg">/api/users/{user}/activate</code>
                                </div>
                                <p class="text-gray-600 mt-2">Activate user account</p>
                            </div>
                        </div>

                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                                    <code class="text-lg">/api/users/{user}/deactivate</code>
                                </div>
                                <p class="text-gray-600 mt-2">Deactivate user account</p>
                            </div>
                        </div>

                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-get text-white text-xs font-bold px-2 py-1 rounded mr-3">GET</span>
                                    <code class="text-lg">/api/users/{user}/permissions</code>
                                </div>
                                <p class="text-gray-600 mt-2">Get user permissions and roles</p>
                            </div>
                        </div>

                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-get text-white text-xs font-bold px-2 py-1 rounded mr-3">GET</span>
                                    <code class="text-lg">/api/users/{user}/issues</code>
                                </div>
                                <p class="text-gray-600 mt-2">Get issues assigned to user</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Additional Queries -->
                <section id="additional-queries" class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">🔍 Additional Query Endpoints</h2>
                    <p class="text-gray-700 mb-6">Specialized queries for common use cases.</p>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-get text-white text-xs font-bold px-2 py-1 rounded mr-3">GET</span>
                                    <code class="text-lg">/api/departments/{department}/issues</code>
                                </div>
                                <p class="text-gray-600 mt-2">Get issues by department</p>
                            </div>
                        </div>

                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-get text-white text-xs font-bold px-2 py-1 rounded mr-3">GET</span>
                                    <code class="text-lg">/api/issue-categories/{category}/types</code>
                                </div>
                                <p class="text-gray-600 mt-2">Get issue types by category</p>
                            </div>
                        </div>

                        <div class="endpoint-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="method-get text-white text-xs font-bold px-2 py-1 rounded mr-3">GET</span>
                                    <code class="text-lg">/api/activity-logs/subject/{type}/{id}</code>
                                </div>
                                <p class="text-gray-600 mt-2">Get activity logs for specific entity</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Footer -->
                <footer class="mt-16 pt-8 border-t border-gray-200">
                    <div class="max-w-4xl mx-auto text-center">
                        <p class="text-gray-900 font-semibold mb-2">Pulse API Documentation</p>
                        <p class="text-sm text-gray-600 mb-4">Complete RESTful API for Hotel/DM Issue Tracking System</p>
                        <div class="flex justify-center space-x-4 text-sm text-gray-500">
                            <span>Version: 1.0</span>
                            <span>•</span>
                            <span>125+ Endpoints</span>
                            <span>•</span>
                            <span>© 2026 Pulse</span>
                        </div>
                    </div>
                </footer>

            </div>
        </main>
    </div>

    <script>
        // Copy to clipboard functionality
        function copyToClipboard(button) {
            const codeBlock = button.closest('.bg-gray-900').querySelector('code');
            const text = codeBlock.textContent;

            navigator.clipboard.writeText(text).then(() => {
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                setTimeout(() => {
                    button.textContent = originalText;
                }, 2000);
            });
        }

        // Search functionality
        document.getElementById('search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const links = document.querySelectorAll('nav a');
            const sections = document.querySelectorAll('section[id]');

            // Filter sidebar links
            links.forEach(link => {
                const text = link.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    link.style.display = 'block';
                } else {
                    link.style.display = 'none';
                }
            });

            // Highlight matching sections
            sections.forEach(section => {
                const text = section.textContent.toLowerCase();
                if (searchTerm && text.includes(searchTerm)) {
                    section.classList.add('ring-2', 'ring-blue-500');
                } else {
                    section.classList.remove('ring-2', 'ring-blue-500');
                }
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Highlight code on page load
        document.addEventListener('DOMContentLoaded', function() {
            Prism.highlightAll();
        });

        // Add active state to sidebar links based on scroll position
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const links = document.querySelectorAll('nav a[href^="#"]');

            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (window.pageYOffset >= sectionTop - 100) {
                    current = section.getAttribute('id');
                }
            });

            links.forEach(link => {
                link.classList.remove('bg-blue-100', 'text-blue-700');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('bg-blue-100', 'text-blue-700');
                }
            });
        });
    </script>
</body>
</html>
