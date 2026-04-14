<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pulse API Documentation | Elegant Reference</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-json.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-bash.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        code, pre { font-family: 'JetBrains Mono', monospace; }

        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .method-badge {
            @apply text-xs font-bold px-2.5 py-1 rounded-lg uppercase tracking-wide;
        }

        .method-get { @apply bg-emerald-500 text-white; }
        .method-post { @apply bg-blue-500 text-white; }
        .method-put { @apply bg-amber-500 text-white; }
        .method-patch { @apply bg-purple-500 text-white; }
        .method-delete { @apply bg-red-500 text-white; }

        .sidebar-link {
            @apply px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition-all duration-200;
        }

        .sidebar-link.active {
            @apply bg-primary-50 text-primary-700 font-medium;
        }

        .endpoint-card {
            @apply bg-white rounded-xl border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:border-primary-300;
        }

        .code-block {
            @apply bg-gray-900 rounded-lg overflow-hidden;
        }

        .code-block pre {
            @apply !bg-gray-900 !m-0 !rounded-t-lg;
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .sidebar::-webkit-scrollbar,
        .main-content::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track,
        .main-content::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .sidebar::-webkit-scrollbar-thumb,
        .main-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover,
        .main-content::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .stat-card {
            @apply bg-white rounded-xl border border-gray-200 p-6 transition-all duration-300 hover:shadow-lg hover:-translate-y-1;
        }

        .category-card {
            @apply bg-white rounded-xl border border-gray-200 p-5 transition-all duration-300 hover:shadow-md;
        }

        .copy-button {
            @apply absolute top-3 right-3 bg-gray-700 hover:bg-gray-600 text-white text-xs px-2 py-1 rounded transition-colors duration-200;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 glass-effect border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
                        <span class="text-white text-xl">⚡</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Pulse API</h1>
                        <p class="text-xs text-gray-500">Documentation v1.0</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden md:flex items-center space-x-2 text-sm">
                        <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full font-medium">
                            {{ $stats['total'] }} Endpoints
                        </span>
                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full font-medium">
                            {{ $stats['categories'] }} Categories
                        </span>
                    </div>
                    <div class="relative">
                        <input type="text" id="search" placeholder="Search endpoints..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm">
                        <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex pt-16">
        <!-- Sidebar -->
        <aside class="sidebar w-72 bg-white border-r border-gray-200 fixed h-[calc(100vh-4rem)] overflow-y-auto">
            <div class="p-6">
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Categories</h2>
                <nav class="space-y-1">
                    @foreach($categories as $key => $category)
                        <a href="#category-{{ $key }}" class="sidebar-link flex items-center justify-between">
                            <span class="flex items-center">
                                <span class="mr-2">{{ $category['config']['icon'] }}</span>
                                <span>{{ $category['config']['name'] }}</span>
                            </span>
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">{{ $category['count'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Quick Stats</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">GET</span>
                            <span class="font-medium text-emerald-600">{{ $stats['methods']['GET'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">POST</span>
                            <span class="font-medium text-blue-600">{{ $stats['methods']['POST'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">PUT/PATCH</span>
                            <span class="font-medium text-amber-600">34</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">DELETE</span>
                            <span class="font-medium text-red-600">{{ $stats['methods']['DELETE'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content flex-1 ml-72 p-8 overflow-y-auto">
            <div class="max-w-5xl mx-auto">
                <!-- Hero Section -->
                <section class="mb-12 animate-fade-in">
                    <div class="gradient-bg rounded-2xl p-8 text-white mb-8">
                        <h1 class="text-4xl font-bold mb-3">🚀 Pulse API Documentation</h1>
                        <p class="text-lg text-white/90 mb-6 max-w-2xl">
                            Complete RESTful API reference for Hotel/DM Issue Tracking System. Explore {{ $stats['total'] }}+ endpoints across {{ $stats['categories'] }} categories.
                        </p>
                        <div class="flex items-center space-x-4">
                            <div class="bg-white/20 backdrop-blur rounded-lg px-4 py-2">
                                <div class="text-2xl font-bold">{{ $stats['total'] }}+</div>
                                <div class="text-xs text-white/80">Endpoints</div>
                            </div>
                            <div class="bg-white/20 backdrop-blur rounded-lg px-4 py-2">
                                <div class="text-2xl font-bold">{{ $stats['categories'] }}</div>
                                <div class="text-xs text-white/80">Categories</div>
                            </div>
                            <div class="bg-white/20 backdrop-blur rounded-lg px-4 py-2">
                                <div class="text-2xl font-bold">v1.0</div>
                                <div class="text-xs text-white/80">Version</div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <div class="stat-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Total Endpoints</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}+</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl">🔌</span>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Categories</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['categories'] }}</p>
                                </div>
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl">📁</span>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">GET Requests</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['methods']['GET'] ?? 0 }}</p>
                                </div>
                                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl">📥</span>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">POST/PUT/DELETE</p>
                                    <p class="text-2xl font-bold text-gray-900">
                                        {{ ($stats['methods']['POST'] ?? 0) + ($stats['methods']['PUT'] ?? 0) + ($stats['methods']['DELETE'] ?? 0) }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl">⚡</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- API Endpoints by Category -->
                @foreach($categories as $key => $category)
                    <section id="category-{{ $key }}" class="mb-12 animate-fade-in">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <span class="text-3xl">{{ $category['config']['icon'] }}</span>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">{{ $category['config']['name'] }}</h2>
                                    <p class="text-sm text-gray-600">{{ $category['config']['description'] }}</p>
                                </div>
                            </div>
                            <span class="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full font-medium">
                                {{ $category['count'] }} endpoints
                            </span>
                        </div>

                        <div class="space-y-4">
                            @foreach($category['routes'] as $route)
                                <div class="endpoint-card">
                                    <div class="p-5">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex items-center space-x-3">
                                                @foreach($route['methods'] as $method)
                                                    <span class="method-badge method-{{ strtolower($method) }}">{{ $method }}</span>
                                                @endforeach
                                                <code class="text-gray-900 font-medium">{{ $route['uri'] }}</code>
                                            </div>
                                        </div>

                                        <p class="text-gray-700 mb-4">{{ $route['description'] }}</p>

                                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                                            @if(!empty($route['parameters']))
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-medium">Parameters:</span>
                                                    @foreach($route['parameters'] as $param)
                                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">{!! $param !!}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        @if(strtolower($route['methods'][0]) === 'get' && str_contains($route['uri'], '{'))
                                            <div class="mt-4 pt-4 border-t border-gray-200">
                                                <button onclick="toggleExample('{{ $route['uri'] }}')"
                                                    class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center space-x-1">
                                                    <span>Show Example Response</span>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </button>
                                                <div id="example-{{ str_replace(['/', '{', '}'], ['', '', ''], $route['uri']) }}" class="hidden mt-4 code-block">
                                                    <button onclick="copyCode(this)" class="copy-button">Copy</button>
                                                    <pre><code class="language-json">{
  "data": {
    @if(str_contains($route['uri'], 'departments'))
    "id": 1,
    "name": "Housekeeping",
    "code": "HK",
    "is_active": true
    @elseif(str_contains($route['uri'], 'users'))
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "is_active": true
    @elseif(str_contains($route['uri'], 'issues'))
    "id": 1,
    "title": "Room 301 AC Issue",
    "status": "open",
    "priority": "high",
    "created_at": "2026-04-14T10:30:00.000000Z"
    @else
    "id": 1,
    "name": "Resource Name",
    "created_at": "2026-04-14T10:30:00.000000Z"
    @endif
  },
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 42
  }
}</code></pre>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>

            <!-- Footer -->
            <footer class="mt-16 pt-8 border-t border-gray-200">
                <div class="max-w-5xl mx-auto text-center">
                    <p class="text-gray-900 font-semibold mb-2">Pulse API Documentation</p>
                    <p class="text-sm text-gray-600 mb-4">Complete RESTful API for Hotel/DM Issue Tracking System</p>
                    <div class="flex justify-center space-x-4 text-sm text-gray-500">
                        <span>Version 1.0</span>
                        <span>•</span>
                        <span>{{ $stats['total'] }}+ Endpoints</span>
                        <span>•</span>
                        <span>© 2026 Pulse</span>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    <script>
        // Toggle example visibility
        function toggleExample(uri) {
            const elementId = 'example-' + uri.replace(/[\/{}]/g, '');
            const element = document.getElementById(elementId);
            if (element) {
                element.classList.toggle('hidden');
            }
        }

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
            const cards = document.querySelectorAll('.endpoint-card');

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    card.style.display = 'block';
                    card.classList.add('animate-fade-in');
                } else {
                    card.style.display = 'none';
                }
            });

            // Also filter sidebar links
            const sidebarLinks = document.querySelectorAll('.sidebar-link');
            sidebarLinks.forEach(link => {
                const text = link.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    link.style.display = 'flex';
                } else {
                    link.style.display = 'none';
                }
            });
        });

        // Smooth scrolling and active state
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

        // Active sidebar link on scroll
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id^="category-"]');
            const sidebarLinks = document.querySelectorAll('.sidebar-link');

            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (window.pageYOffset >= sectionTop - 200) {
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
    </script>
</body>
</html>
