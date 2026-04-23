<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pulse API Documentation</title>
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
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="sidebar w-80 bg-white border-r border-gray-200 fixed">
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">Pulse API</h1>
                <p class="text-sm text-gray-600 mt-1">RESTful API v1.0</p>
            </div>

            <div class="p-4">
                <input type="text" id="search" placeholder="Search endpoints..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <nav class="px-4">
                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Overview</h3>
                    <a href="#introduction" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Introduction</a>
                    <a href="#authentication" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Authentication</a>
                    <a href="#errors" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Errors</a>
                </div>

                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Issues</h3>
                    <a href="#issues-list" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">List Issues</a>
                    <a href="#issues-create" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Create Issue</a>
                    <a href="#issues-show" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Get Issue</a>
                    <a href="#issues-update" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Update Issue</a>
                    <a href="#issues-delete" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Delete Issue</a>
                    <a href="#issues-close" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Close Issue</a>
                    <a href="#issues-reopen" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Reopen Issue</a>
                    <a href="#issues-comments" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Get Comments</a>
                </div>

                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Bulk Operations</h3>
                    <a href="#bulk-create" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Bulk Create</a>
                    <a href="#bulk-update" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Bulk Update</a>
                    <a href="#bulk-delete" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Bulk Delete</a>
                    <a href="#bulk-close" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Bulk Close</a>
                    <a href="#bulk-reopen" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Bulk Reopen</a>
                </div>

                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Statistics</h3>
                    <a href="#stats-dashboard" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Dashboard</a>
                    <a href="#stats-department" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">By Department</a>
                    <a href="#stats-user" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">By User</a>
                    <a href="#stats-trends" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Trends</a>
                </div>

                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Exports</h3>
                    <a href="#export-csv" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Export CSV</a>
                    <a href="#export-excel" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Export Excel</a>
                    <a href="#export-pdf" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Export PDF</a>
                    <a href="#export-reports" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Export Reports</a>
                </div>

                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">User Management</h3>
                    <a href="#users-roles" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Assign Roles</a>
                    <a href="#users-remove-roles" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Remove Roles</a>
                    <a href="#users-password" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Change Password</a>
                    <a href="#users-activate" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Activate User</a>
                    <a href="#users-deactivate" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Deactivate User</a>
                    <a href="#users-permissions" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Get Permissions</a>
                    <a href="#users-issues" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">User Issues</a>
                </div>

                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Additional Queries</h3>
                    <a href="#dept-issues" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Department Issues</a>
                    <a href="#category-types" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Category Types</a>
                    <a href="#activity-logs" class="block py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Activity Logs</a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="content ml-80 flex-1 p-8">
            <div class="max-w-4xl mx-auto">

                <!-- Introduction -->
                <section id="introduction" class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Introduction</h2>
                    <p class="text-gray-700 mb-4">
                        The Pulse API provides RESTful endpoints to manage hotel/DM operations issues, users, departments, and more.
                        This API allows you to integrate Pulse functionality into your applications.
                    </p>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 mb-2">Base URL</h4>
                        <code class="text-blue-800">/api</code>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mt-4">
                        <h4 class="font-semibold text-green-900 mb-2">API Version</h4>
                        <code class="text-green-800">v1.0</code>
                    </div>
                </section>

                <!-- Authentication -->
                <section id="authentication" class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Authentication</h2>
                    <p class="text-gray-700 mb-4">
                        All API endpoints require authentication using Laravel Sanctum bearer tokens.
                        Use the login endpoint to obtain your access token.
                    </p>

                    <!-- Login Endpoint -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                        <div class="flex items-center mb-4">
                            <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded mr-3">POST</span>
                            <code class="text-lg font-mono text-gray-900">/api/login</code>
                            <span class="ml-3 px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Public</span>
                        </div>
                        <p class="text-gray-700 mb-4">Authenticate with email and password credentials to receive an API bearer token.</p>

                        <h4 class="font-semibold text-gray-900 mb-3">Request Body</h4>
                        <div class="bg-gray-900 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">Example Request</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-json">{
  "email": "user@example.com",
  "password": "your_password",
  "device_name": "iPhone App"
}</code></pre>
                        </div>

                        <h4 class="font-semibold text-gray-900 mb-3">Response (200 OK)</h4>
                        <div class="bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">Example Response</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-json">{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "is_active": true
  },
  "token": "20|XzAbC123DefG456HijK789MnoP012Qrs",
  "token_type": "Bearer"
}</code></pre>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Using Your Token</h4>
                        <p class="text-gray-700 mb-3">Include the bearer token in the Authorization header for all authenticated requests:</p>
                        <div class="bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">Authorization Header</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-bash">Authorization: Bearer 20|XzAbC123DefG456HijK789MnoP012Qrs
Accept: application/json</code></pre>
                        </div>
                    </div>
                </section>

                <!-- Errors -->
                <section id="errors" class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Errors</h2>
                    <p class="text-gray-700 mb-4">The API uses standard HTTP status codes and returns detailed error messages.</p>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-4 py-3"><code class="bg-green-100 text-green-800 px-2 py-1 rounded">200</code></td>
                                    <td class="px-4 py-3 text-gray-700">Success</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3"><code class="bg-green-100 text-green-800 px-2 py-1 rounded">201</code></td>
                                    <td class="px-4 py-3 text-gray-700">Created</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3"><code class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">400</code></td>
                                    <td class="px-4 py-3 text-gray-700">Bad Request - Validation errors</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3"><code class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">401</code></td>
                                    <td class="px-4 py-3 text-gray-700">Unauthorized - Authentication required</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3"><code class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">403</code></td>
                                    <td class="px-4 py-3 text-gray-700">Forbidden - Insufficient permissions</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3"><code class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">404</code></td>
                                    <td class="px-4 py-3 text-gray-700">Not Found - Resource doesn't exist</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3"><code class="bg-red-100 text-red-800 px-2 py-1 rounded">500</code></td>
                                    <td class="px-4 py-3 text-gray-700">Server Error</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Issues Endpoints -->
                <section id="issues-list" class="mb-12">
                    <div class="flex items-center mb-4">
                        <span class="method-get text-white text-xs font-bold px-2 py-1 rounded mr-3">GET</span>
                        <h2 class="text-2xl font-bold text-gray-900">List Issues</h2>
                    </div>
                    <code class="text-lg text-gray-800">/api/issues</code>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Query Parameters</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parameter</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-3"><code class="text-gray-800">status</code></td>
                                        <td class="px-4 py-3 text-gray-700">string</td>
                                        <td class="px-4 py-3 text-gray-700">Filter by status: open, in_progress, closed, cancelled</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3"><code class="text-gray-800">priority</code></td>
                                        <td class="px-4 py-3 text-gray-700">string</td>
                                        <td class="px-4 py-3 text-gray-700">Filter by priority: urgent, high, medium, low</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3"><code class="text-gray-800">department_id</code></td>
                                        <td class="px-4 py-3 text-gray-700">integer</td>
                                        <td class="px-4 py-3 text-gray-700">Filter by department ID</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3"><code class="text-gray-800">search</code></td>
                                        <td class="px-4 py-3 text-gray-700">string</td>
                                        <td class="px-4 py-3 text-gray-700">Search in title and description</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3"><code class="text-gray-800">per_page</code></td>
                                        <td class="px-4 py-3 text-gray-700">integer</td>
                                        <td class="px-4 py-3 text-gray-700">Items per page (default: 15)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Response</h4>
                        <div class="bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">200 OK</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-json">{
  "data": [
    {
      "id": 1,
      "title": "Room 301 AC not working",
      "description": "Guest reported AC not cooling properly",
      "status": "open",
      "priority": "high",
      "issue_date": "2026-04-14",
      "departments": [
        {"id": 1, "name": "Housekeeping"}
      ],
      "issue_types": [
        {"id": 1, "name": "Maintenance"}
      ],
      "assigned_to": {
        "id": 5,
        "name": "John Doe"
      },
      "created_by": {
        "id": 3,
        "name": "Jane Smith"
      },
      "created_at": "2026-04-14T10:30:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 42
  }
}</code></pre>
                        </div>
                    </div>
                </section>

                <!-- Create Issue -->
                <section id="issues-create" class="mb-12">
                    <div class="flex items-center mb-4">
                        <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                        <h2 class="text-2xl font-bold text-gray-900">Create Issue</h2>
                    </div>
                    <code class="text-lg text-gray-800">/api/issues</code>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Request Body</h4>
                        <div class="bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">JSON</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-json">{
  "title": "Room 301 AC not working",
  "description": "Guest reported AC not cooling properly",
  "priority": "high",
  "status": "open",
  "issue_date": "2026-04-14",
  "assigned_to_user_id": 5,
  "department_ids": [1, 2],
  "issue_type_ids": [1]
}</code></pre>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Response</h4>
                        <div class="bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">201 Created</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-json">{
  "data": {
    "id": 43,
    "title": "Room 301 AC not working",
    "status": "open",
    "priority": "high",
    "created_at": "2026-04-14T10:30:00.000000Z"
  }
}</code></pre>
                        </div>
                    </div>
                </section>

                <!-- Bulk Operations -->
                <section id="bulk-create" class="mb-12">
                    <div class="flex items-center mb-4">
                        <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                        <h2 class="text-2xl font-bold text-gray-900">Bulk Create Issues</h2>
                    </div>
                    <code class="text-lg text-gray-800">/api/issues/bulk/create</code>

                    <div class="mt-6">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <h4 class="font-semibold text-yellow-900 mb-2">⚠️ Limit</h4>
                            <p class="text-yellow-800">Maximum 100 issues per bulk operation</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Request Body</h4>
                        <div class="bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">JSON</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-json">{
  "issues": [
    {
      "title": "Room 101 TV not working",
      "priority": "medium",
      "department_ids": [1],
      "issue_type_ids": [1]
    },
    {
      "title": "Room 102 plumbing issue",
      "priority": "high",
      "assigned_to_user_id": 5,
      "department_ids": [2],
      "issue_type_ids": [2]
    }
  ]
}</code></pre>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Response</h4>
                        <div class="bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">201 Created</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-json">{
  "message": "Bulk issues created successfully",
  "count": 2,
  "issues": [
    {"id": 44, "title": "Room 101 TV not working"},
    {"id": 45, "title": "Room 102 plumbing issue"}
  ]
}</code></pre>
                        </div>
                    </div>
                </section>

                <!-- Statistics Dashboard -->
                <section id="stats-dashboard" class="mb-12">
                    <div class="flex items-center mb-4">
                        <span class="method-get text-white text-xs font-bold px-2 py-1 rounded mr-3">GET</span>
                        <h2 class="text-2xl font-bold text-gray-900">Dashboard Statistics</h2>
                    </div>
                    <code class="text-lg text-gray-800">/api/statistics/dashboard</code>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Response</h4>
                        <div class="bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">200 OK</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-json">{
  "summary": {
    "total_issues": 142,
    "open_issues": 58,
    "closed_issues": 84,
    "urgent_issues": 8,
    "avg_resolution_hours": 24.5
  },
  "by_priority": {
    "urgent": 8,
    "high": 23,
    "medium": 45,
    "low": 22
  },
  "by_status": {
    "open": 58,
    "in_progress": 32,
    "closed": 84,
    "cancelled": 2
  },
  "recent_activity": [
    {
      "id": 43,
      "title": "Room 301 AC not working",
      "updated_at": "2026-04-14T10:30:00.000000Z"
    }
  ]
}</code></pre>
                        </div>
                    </div>
                </section>

                <!-- Export CSV -->
                <section id="export-csv" class="mb-12">
                    <div class="flex items-center mb-4">
                        <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                        <h2 class="text-2xl font-bold text-gray-900">Export Issues to CSV</h2>
                    </div>
                    <code class="text-lg text-gray-800">/api/exports/issues/csv</code>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Request Body</h4>
                        <div class="bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">JSON (Optional filters)</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-json">{
  "status": "closed",
  "priority": "high",
  "department_id": 1,
  "date_from": "2026-04-01",
  "date_to": "2026-04-30"
}</code></pre>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Response</h4>
                        <div class="bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">200 OK</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-json">{
  "message": "CSV export generated successfully",
  "filename": "issues_2026_04_14_103000.csv",
  "url": "https://pulse.test/storage/exports/issues_2026_04_14_103000.csv",
  "count": 42
}</code></pre>
                        </div>
                    </div>
                </section>

                <!-- User Management -->
                <section id="users-roles" class="mb-12">
                    <div class="flex items-center mb-4">
                        <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                        <h2 class="text-2xl font-bold text-gray-900">Assign Role to User</h2>
                    </div>
                    <code class="text-lg text-gray-800">/api/users/{user}/roles</code>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Request Body</h4>
                        <div class="bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">JSON</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-json">{
  "role_id": 2
}</code></pre>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Response</h4>
                        <div class="bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">200 OK</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-json">{
  "data": {
    "id": 5,
    "name": "John Doe",
    "email": "john@example.com",
    "roles": [
      {"id": 1, "name": "Admin"},
      {"id": 2, "name": "Manager"}
    ]
  }
}</code></pre>
                        </div>
                    </div>
                </section>

                <!-- Change Password -->
                <section id="users-password" class="mb-12">
                    <div class="flex items-center mb-4">
                        <span class="method-post text-white text-xs font-bold px-2 py-1 rounded mr-3">POST</span>
                        <h2 class="text-2xl font-bold text-gray-900">Change User Password</h2>
                    </div>
                    <code class="text-lg text-gray-800">/api/users/{user}/password</code>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Request Body</h4>
                        <div class="bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">JSON</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-json">{
  "password": "new_secure_password",
  "password_confirmation": "new_secure_password"
}</code></pre>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Response</h4>
                        <div class="bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">200 OK</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-json">{
  "message": "Password changed successfully"
}</code></pre>
                        </div>
                    </div>
                </section>

                <!-- Department Issues -->
                <section id="dept-issues" class="mb-12">
                    <div class="flex items-center mb-4">
                        <span class="method-get text-white text-xs font-bold px-2 py-1 rounded mr-3">GET</span>
                        <h2 class="text-2xl font-bold text-gray-900">Get Issues by Department</h2>
                    </div>
                    <code class="text-lg text-gray-800">/api/departments/{department}/issues</code>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Query Parameters</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parameter</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-3"><code class="text-gray-800">status</code></td>
                                        <td class="px-4 py-3 text-gray-700">string</td>
                                        <td class="px-4 py-3 text-gray-700">Filter by status</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3"><code class="text-gray-800">priority</code></td>
                                        <td class="px-4 py-3 text-gray-700">string</td>
                                        <td class="px-4 py-3 text-gray-700">Filter by priority</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Response</h4>
                        <div class="bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 text-sm font-mono">200 OK</span>
                                <button onclick="copyToClipboard(this)" class="text-gray-400 hover:text-white text-sm">Copy</button>
                            </div>
                            <pre><code class="language-json">{
  "data": [
    {
      "id": 1,
      "title": "Room 301 AC not working",
      "status": "open",
      "priority": "high"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 28
  }
}</code></pre>
                        </div>
                    </div>
                </section>

            </div>

            <!-- Footer -->
            <footer class="mt-16 pt-8 border-t border-gray-200">
                <div class="max-w-4xl mx-auto text-center text-gray-600">
                    <p class="mb-2">Pulse API Documentation v1.0</p>
                    <p class="text-sm">© 2026 Pulse Issue Tracking System. All rights reserved.</p>
                </div>
            </footer>
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

            links.forEach(link => {
                const text = link.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    link.style.display = 'block';
                } else {
                    link.style.display = 'none';
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
    </script>
</body>
</html>
