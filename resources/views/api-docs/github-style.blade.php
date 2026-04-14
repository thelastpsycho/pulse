<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pulse API Documentation - Reference</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=-apple-system,BlinkMacSystemFont,"Segoe+UI",Helvetica,Arial,sans-serif,"Apple+Color+Emoji","Segoe+UI+Emoji","Segoe+UI+Symbol"&family=SF+Mono+Regular,SF+Mono,Menlo,Consolas,Liberation+Mono,monospace&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-coy.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-json.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-bash.min.js"></script>
    <style>
        * {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
        }

        code, pre, .mono {
            font-family: "SF Mono Regular", SF Mono, Menlo, Consolas, "Liberation Mono", monospace;
        }

        body {
            background-color: #ffffff;
            color: #24292f;
            line-height: 1.5;
            font-size: 14px;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header {
            background-color: #24292f;
            color: #ffffff;
            padding: 16px 0;
            border-bottom: 1px solid #30363d;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            font-weight: 600;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .main-content {
            display: flex;
            min-height: calc(100vh - 60px);
        }

        .sidebar {
            width: 280px;
            background-color: #f6f8fa;
            border-right: 1px solid #d0d7de;
            padding: 24px 16px;
            overflow-y: auto;
            height: calc(100vh - 60px);
            position: sticky;
            top: 0;
        }

        .content {
            flex:1;
            padding: 32px 48px;
            max-width: 980px;
        }

        h1, h2, h3, h4 {
            font-weight: 600;
            margin-top: 24px;
            margin-bottom: 16px;
        }

        h1 {
            font-size: 32px;
            border-bottom: 1px solid #d0d7de;
            padding-bottom: 16px;
        }

        h2 {
            font-size: 24px;
            border-bottom: 1px solid #d0d7de;
            padding-bottom: 8px;
            margin-top: 48px;
        }

        h3 {
            font-size: 16px;
            margin-top: 32px;
        }

        h4 {
            font-size: 14px;
            margin-top: 24px;
        }

        .sidebar-section {
            margin-bottom: 24px;
        }

        .sidebar-title {
            font-size: 12px;
            font-weight: 600;
            color: #57606a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .sidebar-link {
            display: block;
            padding: 6px 8px;
            color: #57606a;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 2px;
            font-size: 13px;
        }

        .sidebar-link:hover {
            background-color: #d0d7de;
        }

        .sidebar-link.active {
            background-color: #ddf4ff;
            color: #0969da;
            font-weight: 500;
        }

        .endpoint {
            background-color: #ffffff;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            margin-bottom: 24px;
            overflow: hidden;
        }

        .endpoint-header {
            padding: 16px;
            border-bottom: 1px solid #d0d7de;
            background-color: #f6f8fa;
        }

        .endpoint-method {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-right: 8px;
        }

        .method-get { background-color: #1a7f37; color: #ffffff; }
        .method-post { background-color: #0969da; color: #ffffff; }
        .method-put { background-color: #9e6a03; color: #ffffff; }
        .method-patch { background-color: #8250df; color: #ffffff; }
        .method-delete { background-color: #cf222e; color: #ffffff; }

        .endpoint-url {
            font-family: "SF Mono Regular", SF Mono, Menlo, Consolas, "Liberation Mono", monospace;
            font-size: 13px;
            color: #24292f;
        }

        .endpoint-body {
            padding: 16px;
        }

        .endpoint-description {
            margin-bottom: 16px;
            color: #57606a;
        }

        .code-block {
            background-color: #f6f8fa;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            padding: 16px;
            margin: 16px 0;
            position: relative;
        }

        .code-block pre {
            margin: 0;
            padding: 0;
            background: none;
            font-size: 12px;
            line-height: 1.45;
        }

        .copy-button {
            position: absolute;
            top: 8px;
            right: 8px;
            background-color: #f6f8fa;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 12px;
            cursor: pointer;
            color: #57606a;
        }

        .copy-button:hover {
            background-color: #d0d7de;
        }

        .parameter-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
        }

        .parameter-table th,
        .parameter-table td {
            border: 1px solid #d0d7de;
            padding: 8px 12px;
            text-align: left;
        }

        .parameter-table th {
            background-color: #f6f8fa;
            font-weight: 600;
        }

        .parameter-table code {
            background-color: #f6f8fa;
            padding: 2px 4px;
            border-radius: 3px;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
        }

        .badge-required {
            background-color: #cf222e;
            color: #ffffff;
        }

        .badge-optional {
            background-color: #d0d7de;
            color: #24292f;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }

        .stat-card {
            background-color: #f6f8fa;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            padding: 16px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 600;
            color: #24292f;
        }

        .stat-label {
            font-size: 13px;
            color: #57606a;
            margin-top: 4px;
        }

        .search-box {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .search-box:focus {
            outline: none;
            border-color: #0969da;
            box-shadow: 0 0 0 3px rgba(9, 105, 218, 0.1);
        }

        .alert {
            padding: 16px;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            margin-bottom: 16px;
        }

        .alert-info {
            background-color: #ddf4ff;
            border-color: #ddf4ff;
        }

        .alert-warning {
            background-color: #fff8c5;
            border-color: #d0d7de;
        }

        .alert-title {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .method-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .method-list li {
            padding: 6px 8px;
            border-radius: 6px;
            margin-bottom: 2px;
            font-size: 13px;
        }

        .method-list li:hover {
            background-color: #d0d7de;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f6f8fa;
        }

        ::-webkit-scrollbar-thumb {
            background: #d0d7de;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #adb5bd;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <svg height="32" viewBox="0 0 16 16" version="1.1" width="32" aria-hidden="true" fill="currentColor">
                        <path d="M8 0c4.42 0 8 3.58 8 8a8.013 8.013 0 0 1-5.45 7.59c-.4.08-.55-.17-.55-.38 0-.27.01-1.13.01-2.2 0-.75-.25-1.23-.54-1.48 1.78-.2 3.65-.88 3.65-3.95 0-.88-.31-1.59-.82-2.15.08-.2.36-1.02-.08-2.12 0 0-.67-.22-2.2.82-.64-.18-1.32-.27-2-.27-.68 0-1.36.09-2 .27-1.53-1.03-2.2-.82-2.2-.82-.44 1.1-.16 1.92-.08 2.12-.51.56-.82 1.28-.82 2.15 0 3.06 1.86 3.75 3.64 3.95-.23.2-.44.55-.51 1.07-.46.21-1.61.55-2.33-.66-.15-.24-.6-.83-1.23-.82-.67.01-.27.38.01.53.34.19.73.9.82 1.13.16.45.68 1.31 2.69.94 0 .67.01 1.3.01 1.49 0 .21-.15.45-.55.38A7.995 7.995 0 0 1 0 8c0-4.42 3.58-8 8-8Z"></path>
                    </svg>
                    <span>Pulse API Documentation</span>
                </div>
                <div style="font-size: 13px;">
                    Version 1.0
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Sidebar -->
        <div class="sidebar">
            <input type="text" id="search" class="search-box" placeholder="Search endpoints...">

            <div class="sidebar-section">
                <div class="sidebar-title">Getting Started</div>
                <a href="#overview" class="sidebar-link">Overview</a>
                <a href="#authentication" class="sidebar-link">Authentication</a>
                <a href="#pagination" class="sidebar-link">Pagination</a>
                <a href="#errors" class="sidebar-link">Errors</a>
            </div>

            @foreach($categories as $key => $category)
                <div class="sidebar-section">
                    <div class="sidebar-title">{{ $category['config']['name'] }}</div>
                    @foreach($category['routes'] as $route)
                        <a href="#endpoint-{{ str_replace(['/', '{', '}', '-', ' '], ['', '', '', '', ''], $route['uri']) }}" class="sidebar-link">
                            {{ $route['summary'] }}
                        </a>
                    @endforeach
                </div>
            @endforeach
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Overview Section -->
            <section id="overview">
                <h1>API Reference</h1>
                <p style="font-size: 16px; color: #57606a; margin-bottom: 24px;">
                    Welcome to the Pulse API documentation. This API provides comprehensive endpoints for managing hotel/DM operations,
                    including issues, users, departments, statistics, and more.
                </p>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value">{{ $stats['total'] }}+</div>
                        <div class="stat-label">Total Endpoints</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $stats['categories'] }}</div>
                        <div class="stat-label">Categories</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $stats['methods']['GET'] ?? 0 }}</div>
                        <div class="stat-label">GET Requests</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">
                            {{ ($stats['methods']['POST'] ?? 0) + ($stats['methods']['PUT'] ?? 0) + ($stats['methods']['DELETE'] ?? 0) }}
                        </div>
                        <div class="stat-label">Write Operations</div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <div class="alert-title">Base URL</div>
                    <div style="font-family: monospace;">https://your-domain.com/api</div>
                </div>
            </section>

            <!-- Authentication Section -->
            <section id="authentication">
                <h2>Authentication</h2>
                <p>
                    All API endpoints require authentication using Laravel Sanctum tokens or session-based authentication.
                    Include your authentication token in the request headers.
                </p>

                <div class="code-block">
                    <button class="copy-button" onclick="copyCode(this)">Copy</button>
                    <pre><code class="language-json">{
  "Authorization": "Bearer {your_token_here}",
  "Accept": "application/json",
  "Content-Type": "application/json"
}</code></pre>
                </div>

                <h3>Token Management</h3>
                <p>
                    Tokens can be generated through the user interface or API. Each token has specific permissions
                    and can be revoked at any time through the user settings.
                </p>
            </section>

            <!-- Pagination Section -->
            <section id="pagination">
                <h2>Pagination</h2>
                <p>
                    List endpoints support pagination with consistent response structure. Use the <code>per_page</code>
                    parameter to control page size (default: 15, maximum: 100).
                </p>

                <div class="code-block">
                    <button class="copy-button" onclick="copyCode(this)">Copy</button>
                    <pre><code class="language-json">{
  "data": [...],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 142,
    "last_page": 10
  },
  "links": {
    "first": "https://api.example.com/issues?page=1",
    "last": "https://api.example.com/issues?page=10",
    "prev": null,
    "next": "https://api.example.com/issues?page=2"
  }
}</code></pre>
                </div>
            </section>

            <!-- Errors Section -->
            <section id="errors">
                <h2>Error Handling</h2>
                <p>
                    The API uses standard HTTP status codes and returns detailed error messages in JSON format.
                </p>

                <table class="parameter-table">
                    <thead>
                        <tr>
                            <th>Status Code</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>200 OK</strong></td>
                            <td>Request succeeded</td>
                        </tr>
                        <tr>
                            <td><strong>201 Created</strong></td>
                            <td>Resource created successfully</td>
                        </tr>
                        <tr>
                            <td><strong>400 Bad Request</strong></td>
                            <td>Invalid request parameters</td>
                        </tr>
                        <tr>
                            <td><strong>401 Unauthorized</strong></td>
                            <td>Authentication required</td>
                        </tr>
                        <tr>
                            <td><strong>403 Forbidden</strong></td>
                            <td>Insufficient permissions</td>
                        </tr>
                        <tr>
                            <td><strong>404 Not Found</strong></td>
                            <td>Resource not found</td>
                        </tr>
                        <tr>
                            <td><strong>422 Unprocessable Entity</strong></td>
                            <td>Validation error</td>
                        </tr>
                        <tr>
                            <td><strong>500 Server Error</strong></td>
                            <td>Internal server error</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- API Endpoints -->
            @foreach($categories as $key => $category)
                <section id="category-{{ $key }}">
                    <h2>{{ $category['config']['name'] }}</h2>
                    <p style="margin-bottom: 24px;">{{ $category['config']['description'] }}</p>

                    @foreach($category['routes'] as $route)
                        <div id="endpoint-{{ str_replace(['/', '{', '}', '-', ' '], ['', '', '', '', ''], $route['uri']) }}" class="endpoint">
                            <div class="endpoint-header">
                                @foreach($route['methods'] as $method)
                                    <span class="endpoint-method method-{{ strtolower($method) }}">{{ $method }}</span>
                                @endforeach
                                <code class="endpoint-url">/{{ $route['uri'] }}</code>
                            </div>
                            <div class="endpoint-body">
                                <h4 style="margin-top: 0;">{{ $route['summary'] }}</h4>
                                <p class="endpoint-description">{{ $route['description'] }}</p>

                                @if(!empty($route['parameters']))
                                    <h4>Parameters</h4>
                                    <table class="parameter-table">
                                        <thead>
                                            <tr>
                                                <th>Parameter</th>
                                                <th>Type</th>
                                                <th>Required</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($route['parameters'] as $param)
                                                <tr>
                                                    <td><code>{{ $param }}</code></td>
                                                    <td>integer</td>
                                                    <td><span class="badge badge-required">required</span></td>
                                                    <td>The {{ $param }} identifier</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif

                                @if(strtolower($route['methods'][0]) === 'get')
                                    <h4>Query Parameters</h4>
                                    <table class="parameter-table">
                                        <thead>
                                            <tr>
                                                <th>Parameter</th>
                                                <th>Type</th>
                                                <th>Required</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><code>per_page</code></td>
                                                <td>integer</td>
                                                <td><span class="badge badge-optional">optional</span></td>
                                                <td>Items per page (default: 15, max: 100)</td>
                                            </tr>
                                            <tr>
                                                <td><code>page</code></td>
                                                <td>integer</td>
                                                <td><span class="badge badge-optional">optional</span></td>
                                                <td>Page number (default: 1)</td>
                                            </tr>
                                            @if(str_contains($route['uri'], 'issues'))
                                            <tr>
                                                <td><code>status</code></td>
                                                <td>string</td>
                                                <td><span class="badge badge-optional">optional</span></td>
                                                <td>Filter by status: open, in_progress, closed, cancelled</td>
                                            </tr>
                                            <tr>
                                                <td><code>priority</code></td>
                                                <td>string</td>
                                                <td><span class="badge badge-optional">optional</span></td>
                                                <td>Filter by priority: urgent, high, medium, low</td>
                                            </tr>
                                            <tr>
                                                <td><code>search</code></td>
                                                <td>string</td>
                                                <td><span class="badge badge-optional">optional</span></td>
                                                <td>Search in title and description</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                @endif

                                @if(strtolower($route['methods'][0]) === 'get')
                                    <h4>Response</h4>
                                    <div class="code-block">
                                        <button class="copy-button" onclick="copyCode(this)">Copy</button>
                                        <pre><code class="language-json">{
  "data": [
    {
      "id": 1,
      @if(str_contains($route['uri'], 'issues'))
      "title": "Room 301 AC Issue",
      "description": "Air conditioning not working properly",
      "status": "open",
      "priority": "high",
      "issue_date": "2026-04-14",
      "created_at": "2026-04-14T10:30:00.000000Z",
      "updated_at": "2026-04-14T10:30:00.000000Z"
      @elseif(str_contains($route['uri'], 'departments'))
      "name": "Housekeeping",
      "code": "HK",
      "is_active": true,
      "created_at": "2026-04-14T10:30:00.000000Z"
      @elseif(str_contains($route['uri'], 'users'))
      "name": "John Doe",
      "email": "john@example.com",
      "is_active": true,
      "created_at": "2026-04-14T10:30:00.000000Z"
      @else
      "name": "Resource Name",
      "created_at": "2026-04-14T10:30:00.000000Z"
      @endif
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 42
  }
}</code></pre>
                                    </div>
                                @endif

                                @if(strtolower($route['methods'][0]) === 'post')
                                    <h4>Request Body</h4>
                                    <div class="code-block">
                                        <button class="copy-button" onclick="copyCode(this)">Copy</button>
                                        <pre><code class="language-json">@if(str_contains($route['uri'], 'issues'))
{
  "title": "Room 301 AC Issue",
  "description": "Air conditioning not working properly",
  "priority": "high",
  "assigned_to_user_id": 5,
  "department_ids": [1, 2],
  "issue_type_ids": [1]
}
@elseif(str_contains($route['uri'], 'users'))
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "secure_password",
  "password_confirmation": "secure_password"
}
@elseif(str_contains($route['uri'], 'roles'))
{
  "name": "Manager",
  "permission_ids": [1, 2, 3]
}
@else
{
  "name": "Resource Name",
  "description": "Resource description"
}
@endif</code></pre>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </section>
            @endforeach

            <!-- Footer -->
            <footer style="margin-top: 64px; padding-top: 24px; border-top: 1px solid #d0d7de; color: #57606a; font-size: 13px;">
                <div style="text-align: center;">
                    <p style="margin-bottom: 8px;">Pulse API Documentation v1.0</p>
                    <p>Complete RESTful API for Hotel/DM Issue Tracking System</p>
                    <p style="margin-top: 16px;">&copy; 2026 Pulse. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Copy code to clipboard
        function copyCode(button) {
            const codeBlock = button.nextElementSibling.querySelector('code');
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
            const endpoints = document.querySelectorAll('.endpoint');
            const sidebarLinks = document.querySelectorAll('.sidebar-link');

            // Filter endpoints
            endpoints.forEach(endpoint => {
                const text = endpoint.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    endpoint.style.display = 'block';
                } else {
                    endpoint.style.display = 'none';
                }
            });

            // Filter sidebar links
            sidebarLinks.forEach(link => {
                const text = link.textContent.toLowerCase();
                if (text.includes(searchTerm) || link.getAttribute('href') === '#overview' || link.getAttribute('href') === '#authentication' || link.getAttribute('href') === '#pagination' || link.getAttribute('href') === '#errors') {
                    link.style.display = 'block';
                } else {
                    link.style.display = 'none';
                }
            });
        });

        // Smooth scrolling
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

        // Active sidebar link on scroll
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const sidebarLinks = document.querySelectorAll('.sidebar-link');

            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (window.pageYOffset >= sectionTop - 100) {
                    current = section.getAttribute('id');
                }
            });

            sidebarLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
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
