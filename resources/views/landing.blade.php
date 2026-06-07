<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InvoiceX — Professional Invoice Management</title>
    <meta name="description" content="Create, send, and track invoices in multiple currencies. Built on Laravel with a full REST API.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Syne', sans-serif; }
        .hero-grid {
            background-image:
                linear-gradient(rgba(251,191,36,.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(251,191,36,.06) 1px, transparent 1px);
            background-size: 48px 48px;
        }
        .glow {
            background: radial-gradient(ellipse 60% 40% at 50% 0%, rgba(251,191,36,.18) 0%, transparent 70%);
        }
        .feature-card:hover { transform: translateY(-3px); }
        .feature-card { transition: transform .2s ease, box-shadow .2s ease; }
    </style>
</head>
<body class="bg-[#0a1628] text-white antialiased">

{{-- ─── NAVBAR ──────────────────────────────────────────────────────────── --}}
<header class="sticky top-0 z-50 border-b border-white/5 bg-[#0a1628]/90 backdrop-blur-md">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">

        <div class="flex items-center gap-2.5">
            <img src="{{ asset('favicon.ico') }}" alt="" class="w-7 h-7">
            <span class="font-display text-xl font-bold tracking-tight text-white">InvoiceX</span>
        </div>

        <nav class="hidden md:flex items-center gap-8 text-sm text-slate-400">
            <a href="#features" class="hover:text-white transition-colors">Features</a>
            <a href="#preview"  class="hover:text-white transition-colors">Preview</a>
            <a href="#api"      class="hover:text-white transition-colors">API</a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}"
               class="text-sm text-slate-400 hover:text-white transition-colors px-4 py-2">
                Sign In
            </a>
            <a href="{{ route('register') }}"
               class="text-sm font-semibold bg-amber-400 hover:bg-amber-300 text-slate-900 px-4 py-2 rounded-lg transition-colors">
                Get Started
            </a>
        </div>
    </div>
</header>


{{-- ─── HERO ────────────────────────────────────────────────────────────── --}}
<section class="relative hero-grid overflow-hidden">
    <div class="glow absolute inset-0 pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-6 pt-24 pb-16 relative">

        {{-- Badge --}}
        <div class="flex justify-center mb-8">
            <span class="inline-flex items-center gap-2 border border-amber-400/30 bg-amber-400/10 text-amber-300 text-xs font-semibold px-4 py-1.5 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                Built with Laravel 12 · REST API · Multi-currency
            </span>
        </div>

        {{-- Headline --}}
        <h1 class="font-display text-5xl md:text-6xl lg:text-7xl font-bold text-center leading-[1.08] tracking-tight">
            Invoice management<br>
            <span class="text-amber-400">done right.</span>
        </h1>

        <p class="mt-6 text-center text-slate-400 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
            Create professional invoices, track clients, export PDFs, and automate billing
            through a versioned REST API — all in one clean dashboard.
        </p>

        {{-- CTAs --}}
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold text-base px-7 py-3.5 rounded-xl transition-colors shadow-lg shadow-amber-400/20">
                Get Started Free
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 border border-white/10 hover:border-white/25 text-slate-300 hover:text-white font-medium text-base px-7 py-3.5 rounded-xl transition-colors">
                Sign In
            </a>
        </div>

        {{-- Social proof chips --}}
        <div class="mt-10 flex flex-wrap justify-center gap-6 text-xs text-slate-500">
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                No credit card required
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                11 currencies supported
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                REST API included
            </span>
        </div>

        {{-- ── APP MOCKUP ──────────────────────────────────────────────────── --}}
        <div id="preview" class="mt-16 relative">
            {{-- Glow under mockup --}}
            <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 w-3/4 h-16 bg-amber-400/10 blur-3xl rounded-full pointer-events-none"></div>

            {{-- Browser chrome --}}
            <div class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl shadow-black/60">

                {{-- Browser bar --}}
                <div class="bg-[#1a2740] px-4 py-3 flex items-center gap-3 border-b border-white/5">
                    <div class="flex gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-red-400/70"></span>
                        <span class="w-3 h-3 rounded-full bg-amber-400/70"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-400/70"></span>
                    </div>
                    <div class="flex-1 mx-4">
                        <div class="bg-[#0f1c2e] rounded-md px-3 py-1 text-xs text-slate-500 flex items-center gap-2 max-w-sm mx-auto">
                            <svg class="w-3 h-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            invoicex.app/dashboard
                        </div>
                    </div>
                </div>

                {{-- App shell --}}
                <div class="bg-slate-50 flex" style="min-height:360px">

                    {{-- Sidebar --}}
                    <div class="w-48 bg-slate-900 flex-shrink-0 flex flex-col py-4">
                        <div class="px-4 pb-3 border-b border-slate-700 mb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-amber-400 flex items-center justify-center">
                                    <span class="text-slate-900 font-bold text-xs">IX</span>
                                </div>
                                <span class="font-display text-sm font-bold text-white">InvoiceX</span>
                            </div>
                        </div>
                        <div class="px-3 space-y-0.5 text-xs">
                            <div class="flex items-center gap-2 px-2 py-2 rounded-lg bg-amber-400/15 border-l-2 border-amber-400 text-amber-400 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Dashboard
                            </div>
                            <div class="flex items-center gap-2 px-2 py-2 rounded-lg text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Clients
                            </div>
                            <div class="flex items-center gap-2 px-2 py-2 rounded-lg text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Invoices
                            </div>
                        </div>
                    </div>

                    {{-- Main content --}}
                    <div class="flex-1 p-5">
                        {{-- Stat cards --}}
                        <div class="grid grid-cols-4 gap-3 mb-4">
                            <div class="bg-white rounded-lg p-3 border border-slate-200">
                                <p class="text-xs text-slate-400 mb-1">Clients</p>
                                <p class="font-display text-xl font-bold text-slate-800">12</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-slate-200">
                                <p class="text-xs text-slate-400 mb-1">Invoices</p>
                                <p class="font-display text-xl font-bold text-slate-800">47</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-slate-200">
                                <p class="text-xs text-slate-400 mb-1">Revenue</p>
                                <p class="font-display text-xl font-bold text-slate-800">$8,240</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-slate-200">
                                <p class="text-xs text-slate-400 mb-1">Pending</p>
                                <p class="font-display text-xl font-bold text-amber-500">$1,350</p>
                            </div>
                        </div>

                        {{-- Mini table --}}
                        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                            <div class="px-4 py-2.5 border-b border-slate-100">
                                <p class="text-xs font-semibold text-slate-600">Recent Invoices</p>
                            </div>
                            <table class="w-full text-xs">
                                <thead class="bg-slate-50">
                                    <tr class="text-slate-400">
                                        <th class="px-4 py-2 text-left font-medium">Invoice</th>
                                        <th class="px-4 py-2 text-left font-medium">Client</th>
                                        <th class="px-4 py-2 text-left font-medium">Status</th>
                                        <th class="px-4 py-2 text-right font-medium">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <tr>
                                        <td class="px-4 py-2.5 font-medium text-slate-700">INV-047</td>
                                        <td class="px-4 py-2.5 text-slate-500">Acme Corp</td>
                                        <td class="px-4 py-2.5"><span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-600 font-medium">Paid</span></td>
                                        <td class="px-4 py-2.5 text-right font-semibold text-slate-700">$2,400.00</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2.5 font-medium text-slate-700">INV-046</td>
                                        <td class="px-4 py-2.5 text-slate-500">Stark Ltd</td>
                                        <td class="px-4 py-2.5"><span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 font-medium">Sent</span></td>
                                        <td class="px-4 py-2.5 text-right font-semibold text-slate-700">€950.00</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2.5 font-medium text-slate-700">INV-045</td>
                                        <td class="px-4 py-2.5 text-slate-500">Global LLC</td>
                                        <td class="px-4 py-2.5"><span class="px-2 py-0.5 rounded-full bg-red-100 text-red-600 font-medium">Overdue</span></td>
                                        <td class="px-4 py-2.5 text-right font-semibold text-slate-700">£400.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>


{{-- ─── FEATURES ────────────────────────────────────────────────────────── --}}
<section id="features" class="bg-slate-50 py-24">
    <div class="max-w-6xl mx-auto px-6">

        <div class="text-center mb-14">
            <p class="text-amber-500 text-sm font-semibold uppercase tracking-widest mb-3">Features</p>
            <h2 class="font-display text-4xl font-bold text-slate-900">Everything you need to get paid</h2>
            <p class="mt-4 text-slate-500 text-lg max-w-xl mx-auto">A focused tool that handles the full invoicing workflow — from first draft to final payment.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Invoice Management --}}
            <div class="feature-card bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-slate-900 font-semibold text-lg mb-2">Invoice Management</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-5">
                    Create itemized invoices with auto-generated numbers (INV-001, INV-002…). Track status through the full lifecycle: <strong class="text-slate-700">Draft → Sent → Paid → Overdue</strong>.
                </p>
                <ul class="space-y-2 text-sm text-slate-500">
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span>Auto-incrementing invoice numbers per account</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span>Line items with quantity × unit price</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span>Issue date, due date, and notes</li>
                </ul>
            </div>

            {{-- Client Tracking --}}
            <div class="feature-card bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-slate-900 font-semibold text-lg mb-2">Client Tracking</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-5">
                    Maintain a searchable client directory. Each client has a full invoice history so you can see total billed and outstanding at a glance.
                </p>
                <ul class="space-y-2 text-sm text-slate-500">
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>Name, email, phone, and address</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>Per-client invoice count on the index</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>Search and filter across all clients</li>
                </ul>
            </div>

            {{-- PDF Export --}}
            <div class="feature-card bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-slate-900 font-semibold text-lg mb-2">PDF Export</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-5">
                    Download any invoice as a clean, branded PDF — ready to attach to an email or share with a client. Powered by DomPDF with a custom template.
                </p>
                <ul class="space-y-2 text-sm text-slate-500">
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Branded header with InvoiceX logo</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Full line items table + totals</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>One-click download, named by invoice number</li>
                </ul>
            </div>

            {{-- REST API --}}
            <div class="feature-card bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </div>
                <h3 class="text-slate-900 font-semibold text-lg mb-2">REST API</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-5">
                    A complete versioned API secured with Laravel Sanctum tokens. Full CRUD on clients and invoices, plus live currency conversion — build integrations or automate billing.
                </p>
                <ul class="space-y-2 text-sm text-slate-500">
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Versioned endpoints at <code class="bg-slate-100 px-1 rounded">/api/v1/</code></li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Sanctum token authentication</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>JSON:API-style resource responses</li>
                </ul>
            </div>

        </div>

        {{-- Multi-currency callout --}}
        <div class="mt-6 bg-gradient-to-r from-slate-900 to-[#0f1c2e] rounded-2xl p-8 flex flex-col md:flex-row items-center gap-6">
            <div class="w-14 h-14 bg-amber-400/15 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                </svg>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-white font-semibold text-lg mb-1">Multi-currency with live exchange rates</h3>
                <p class="text-slate-400 text-sm">Invoice in USD, EUR, GBP, MAD, SAR, AED, DZD, TND, MRU, CAD, or AUD. Exchange rates are fetched live from FreeCurrencyAPI and cached hourly — always accurate.</p>
            </div>
            <div class="flex gap-2 flex-wrap justify-center">
                @foreach(['USD','EUR','GBP','MAD','SAR','AED'] as $c)
                <span class="text-xs font-semibold bg-white/5 border border-white/10 text-slate-300 px-3 py-1.5 rounded-lg">{{ $c }}</span>
                @endforeach
            </div>
        </div>

    </div>
</section>


{{-- ─── API SECTION ─────────────────────────────────────────────────────── --}}
<section id="api" class="bg-[#0a1628] py-24">
    <div class="max-w-6xl mx-auto px-6">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            {{-- Left: copy --}}
            <div>
                <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-4">REST API</p>
                <h2 class="font-display text-4xl font-bold text-white leading-tight mb-5">
                    Build on top of<br>your billing data
                </h2>
                <p class="text-slate-400 text-base leading-relaxed mb-8">
                    Every resource in InvoiceX is available through a versioned JSON API authenticated with Sanctum bearer tokens. Use it to power integrations, dashboards, or automation scripts.
                </p>

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-400/15 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm">Token authentication</p>
                            <p class="text-slate-500 text-xs mt-0.5">Sanctum personal access tokens — issue, revoke, and scope per client.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-400/15 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm">Consistent JSON responses</p>
                            <p class="text-slate-500 text-xs mt-0.5">All endpoints return structured <code class="text-amber-400 text-xs">{"data": {...}}</code> responses via Laravel API Resources.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-400/15 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm">Live currency conversion</p>
                            <p class="text-slate-500 text-xs mt-0.5">Hit <code class="text-amber-400 text-xs">/api/v1/currencies/convert</code> to convert between any two supported currencies in real time.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: code block --}}
            <div class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl shadow-black/50 text-sm font-mono">

                {{-- Tab bar --}}
                <div class="bg-[#1a2740] px-4 py-3 flex items-center gap-4 border-b border-white/5">
                    <span class="text-xs text-amber-400 font-semibold border-b-2 border-amber-400 pb-1">Request</span>
                    <span class="text-xs text-slate-500">Response</span>
                </div>

                {{-- Request --}}
                <div class="bg-[#0f1c2e] p-5 space-y-3">
                    <div>
                        <span class="text-slate-500 text-xs">// Authenticate</span><br>
                        <span class="text-violet-400">POST</span>
                        <span class="text-white"> /api/v1/auth/login</span>
                    </div>
                    <div class="bg-black/30 rounded-lg p-4 space-y-1">
                        <div><span class="text-slate-500">{</span></div>
                        <div class="pl-4"><span class="text-blue-300">"email"</span><span class="text-slate-400">: </span><span class="text-emerald-300">"you@example.com"</span><span class="text-slate-400">,</span></div>
                        <div class="pl-4"><span class="text-blue-300">"password"</span><span class="text-slate-400">: </span><span class="text-emerald-300">"••••••••"</span></div>
                        <div><span class="text-slate-500">}</span></div>
                    </div>
                    <div class="border-t border-white/5 pt-3">
                        <span class="text-slate-500 text-xs">// List invoices</span><br>
                        <span class="text-emerald-400">GET</span>
                        <span class="text-white"> /api/v1/invoices</span>
                    </div>
                    <div class="bg-black/30 rounded-lg p-4 space-y-1">
                        <div><span class="text-slate-500 text-xs">Authorization:</span> <span class="text-amber-300 text-xs">Bearer {token}</span></div>
                    </div>
                    <div class="bg-black/30 rounded-lg p-4 space-y-1">
                        <div><span class="text-slate-500">{</span></div>
                        <div class="pl-4"><span class="text-blue-300">"data"</span><span class="text-slate-400">: [</span></div>
                        <div class="pl-8"><span class="text-slate-500">{ </span><span class="text-blue-300">"invoice_number"</span><span class="text-slate-400">: </span><span class="text-emerald-300">"INV-047"</span><span class="text-slate-400">, </span><span class="text-blue-300">"total"</span><span class="text-slate-400">: </span><span class="text-amber-300">2400</span><span class="text-slate-500"> },</span></div>
                        <div class="pl-8"><span class="text-slate-500 text-xs">...</span></div>
                        <div class="pl-4"><span class="text-slate-400">]</span></div>
                        <div><span class="text-slate-500">}</span></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ─── TECH STACK ──────────────────────────────────────────────────────── --}}
<section class="bg-[#0d1f35] py-14 border-y border-white/5">
    <div class="max-w-6xl mx-auto px-6">
        <p class="text-center text-slate-500 text-xs uppercase tracking-widest mb-8">Built with</p>
        <div class="flex flex-wrap justify-center gap-8 items-center">
            @foreach([
                ['Laravel 12', 'bg-red-400/10 text-red-300 border-red-400/20'],
                ['PHP 8.3',    'bg-violet-400/10 text-violet-300 border-violet-400/20'],
                ['Sanctum',    'bg-amber-400/10 text-amber-300 border-amber-400/20'],
                ['PestPHP',    'bg-emerald-400/10 text-emerald-300 border-emerald-400/20'],
                ['Tailwind CSS','bg-cyan-400/10 text-cyan-300 border-cyan-400/20'],
                ['DomPDF',     'bg-pink-400/10 text-pink-300 border-pink-400/20'],
                ['MySQL',      'bg-blue-400/10 text-blue-300 border-blue-400/20'],
                ['Railway',    'bg-slate-400/10 text-slate-300 border-slate-400/20'],
            ] as [$label, $cls])
            <span class="text-sm font-semibold border px-4 py-2 rounded-xl {{ $cls }}">{{ $label }}</span>
            @endforeach
        </div>
    </div>
</section>


{{-- ─── CTA BANNER ─────────────────────────────────────────────────────── --}}
<section class="bg-amber-400 py-20">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <h2 class="font-display text-4xl md:text-5xl font-bold text-slate-900 leading-tight mb-5">
            Ready to streamline<br>your invoicing?
        </h2>
        <p class="text-slate-700 text-lg mb-10">
            Create your free account in seconds. No credit card, no setup fee.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-base px-8 py-4 rounded-xl transition-colors shadow-lg shadow-slate-900/30">
                Get Started Free
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 border-2 border-slate-900/20 hover:border-slate-900/40 text-slate-700 hover:text-slate-900 font-semibold text-base px-8 py-4 rounded-xl transition-colors">
                Sign In
            </a>
        </div>
    </div>
</section>


{{-- ─── FOOTER ──────────────────────────────────────────────────────────── --}}
<footer class="bg-[#0a1628] border-t border-white/5 py-10">
    <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2.5">
            <img src="{{ asset('favicon.ico') }}" alt="" class="w-5 h-5 opacity-60">
            <span class="font-display text-base font-bold text-white/60">InvoiceX</span>
        </div>
        <p class="text-xs text-slate-600 text-center">
            A portfolio project demonstrating Laravel 12 best practices — REST API, Policies, Service layer, PestPHP tests.
        </p>
        <div class="flex items-center gap-5 text-xs text-slate-500">
            <a href="{{ route('login') }}"    class="hover:text-white transition-colors">Sign In</a>
            <a href="{{ route('register') }}" class="hover:text-white transition-colors">Register</a>
        </div>
    </div>
</footer>

{{-- Smooth scroll --}}
<script>
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const target = document.querySelector(a.getAttribute('href'));
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
        });
    });
</script>

</body>
</html>
