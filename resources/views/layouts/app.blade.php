<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'InvoiceX') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Syne:wght@700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-display {
            font-family: 'Syne', sans-serif;
        }

        .sidebar-link.active {
            background: rgba(251, 191, 36, 0.12);
            border-left: 3px solid #fbbf24;
            color: #fbbf24;
        }

        .sidebar-link {
            border-left: 3px solid transparent;
        }

        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">
    @if ($errors->any())
        <div class="mx-8 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            <p class="font-semibold mb-1">Please fix these errors:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        <aside class="w-64 bg-slate-900 text-white flex flex-col fixed inset-y-0 left-0 z-50">

            {{-- Logo --}}
            <div class="px-6 py-6 border-b border-slate-700">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('favicon.ico') }}" alt="IX" class="w-9 h-9">
                    <h1 class="font-display text-2xl font-bold text-white tracking-tight">InvoiceX</h1>
                </div>
            </div>

            {{-- User --}}
            <div class="px-6 py-4 border-b border-slate-700 flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-full bg-amber-400 flex items-center justify-center text-slate-900 font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate w-36">{{ auth()->user()->email }}</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-1">
                <p class="text-xs uppercase tracking-widest text-slate-500 mb-3 px-2">Main Menu</p>

                <a href="{{ route('dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('clients.index') }}"
                    class="sidebar-link {{ request()->routeIs('clients.*') ? 'active' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Clients
                </a>

                <a href="{{ route('invoices.index') }}"
                    class="sidebar-link {{ request()->routeIs('invoices.*') ? 'active' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Invoices
                </a>
            </nav>

            {{-- Logout --}}
            <div class="px-4 py-4 border-t border-slate-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:text-red-400 hover:bg-slate-800 rounded-lg text-sm font-medium transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 ml-64">

            {{-- Top Bar --}}
            <header
                class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between sticky top-0 z-40">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">@yield('title', 'Dashboard')</h2>
                    <p class="text-xs text-slate-400">@yield('subtitle', '')</p>
                </div>
                <div class="flex items-center gap-3">
                    @yield('header-actions')
                </div>
            </header>

            {{-- Flash Message --}}
            {{-- Toast Notifications --}}
            @if (session('success') || session('error'))
                <div id="toast-container" class="fixed top-5 right-5 z-50 space-y-3">

                    @if (session('success'))
                        <div id="toast-success"
                            class="flex items-center gap-3 bg-white border border-emerald-200 shadow-lg rounded-xl px-5 py-4 min-w-72 transform transition-all duration-500">
                            {{-- Icon --}}
                            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            {{-- Message --}}
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-slate-700">Success</p>
                                <p class="text-xs text-slate-500">{{ session('success') }}</p>
                            </div>
                            {{-- Close button --}}
                            <button onclick="dismissToast('toast-success')"
                                class="text-slate-300 hover:text-slate-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div id="toast-error"
                            class="flex items-center gap-3 bg-white border border-red-200 shadow-lg rounded-xl px-5 py-4 min-w-72 transform transition-all duration-500">
                            {{-- Icon --}}
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            {{-- Message --}}
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-slate-700">Error</p>
                                <p class="text-xs text-slate-500">{{ session('error') }}</p>
                            </div>
                            {{-- Close button --}}
                            <button onclick="dismissToast('toast-error')"
                                class="text-slate-300 hover:text-slate-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif

                </div>

                {{-- Toast JavaScript --}}
                <script>
                    // Auto dismiss after 4 seconds
                    setTimeout(() => {
                        dismissToast('toast-success');
                        dismissToast('toast-error');
                    }, 4000);

                    function dismissToast(id) {
                        const toast = document.getElementById(id);
                        if (toast) {
                            // Fade out animation
                            toast.style.opacity = '0';
                            toast.style.transform = 'translateX(100%)';
                            setTimeout(() => toast.remove(), 500);
                        }
                    }
                </script>
            @endif

            {{-- Page Content --}}
            <div class="px-8 py-6">
                @yield('content')
            </div>
        </main>
    </div>
    {{-- Delete Confirmation Modal --}}
    <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

        {{-- Modal --}}
        <div class="relative bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4 transform transition-all">
            {{-- Icon --}}
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>

            <h3 class="text-lg font-semibold text-slate-800 text-center mb-2">
                Are you sure?
            </h3>
            <p class="text-sm text-slate-500 text-center mb-6">
                This action cannot be undone.
            </p>

            <div class="flex gap-3">
                <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button id="confirm-delete-btn"
                    class="flex-1 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-medium transition-colors">
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>

    <script>
        let deleteForm = null;

        function confirmDelete(form) {
            deleteForm = form;
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            deleteForm = null;
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            if (deleteForm) deleteForm.submit();
        });
    </script>
</body>

</html>
