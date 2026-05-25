<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="flex w-full max-w-3xl rounded-2xl overflow-hidden shadow-lg">

            {{-- Left brand panel --}}
            <div class="w-56 flex-shrink-0 bg-[#0f1c2e] flex flex-col justify-center px-8 py-12">
                <span class="text-xs uppercase tracking-widest text-[#8a9bb4] mb-6">Login</span>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('favicon.ico') }}" alt="IX" class="w-9 h-9">
                    <h1 class="font-display text-xl font-bold text-white tracking-tight">InvoiceX</h1>
                </div>
            </div>

            {{-- Right form panel --}}
            <div class="flex-1 bg-white px-10 py-10">
                <h2 class="text-xl font-semibold text-gray-900 mb-1">Forgot your password?</h2>
                <p class="text-sm text-gray-500 mb-6">
                    No problem. Enter your email and we'll send you a reset link.
                </p>

                {{-- Session Status (shows "link sent" confirmation) --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" novalidate>
                    @csrf

                    {{-- Email --}}
                    <div class="mb-6">
                        <x-input-label for="email" :value="__('Email')"
                            class="text-sm font-medium text-gray-700 mb-1" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm focus:outline-none focus:border-[#f5a623] focus:bg-white" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 bg-[#f5a623] hover:bg-[#e09510] text-gray-900 font-semibold text-sm rounded-lg transition-colors duration-150">
                        Email Password Reset Link
                    </button>

                    <p class="text-center text-sm text-gray-500 mt-4">
                        Remembered your password?
                        <a href="{{ route('login') }}" class="text-[#f5a623] hover:underline">Back to login →</a>
                    </p>

                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
