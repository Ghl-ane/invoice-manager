<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="flex w-full max-w-3xl rounded-2xl overflow-hidden shadow-lg">

            {{-- Left brand panel --}}
            <div class="w-56 flex-shrink-0 bg-[#0f1c2e] flex flex-col justify-center px-8 py-12">
                <span class="text-xs uppercase tracking-widest text-[#8a9bb4] mb-6">Reset Password</span>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('favicon.ico') }}" alt="IX" class="w-9 h-9">
                    <h1 class="font-display text-xl font-bold text-white tracking-tight">InvoiceX</h1>
                </div>
            </div>

            {{-- Right form panel --}}
            <div class="flex-1 bg-white px-10 py-10">
                <h2 class="text-xl font-semibold text-gray-900 mb-1">Set new password</h2>
                <p class="text-sm text-gray-500 mb-6">Choose a strong password for your account</p>

                <form method="POST" action="{{ route('password.store') }}" novalidate>
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- Email --}}
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')"
                            class="text-sm font-medium text-gray-700 mb-1" />
                        <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus
                            autocomplete="username"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm focus:outline-none focus:border-[#f5a623] focus:bg-white" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <x-input-label for="password" :value="__('Password')"
                            class="text-sm font-medium text-gray-700 mb-1" />
                        <div class="relative">
                            <x-text-input id="password" type="password" name="password" required
                                autocomplete="new-password"
                                class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg bg-gray-50 text-sm focus:outline-none focus:border-[#f5a623] focus:bg-white" />
                            <button type="button" onclick="togglePassword('password', 'eye-password')"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600">
                                <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-6">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')"
                            class="text-sm font-medium text-gray-700 mb-1" />
                        <div class="relative">
                            <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                                required autocomplete="new-password"
                                class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg bg-gray-50 text-sm focus:outline-none focus:border-[#f5a623] focus:bg-white" />
                            <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm')"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600">
                                <svg id="eye-confirm" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full py-2.5 bg-[#f5a623] hover:bg-[#e09510] text-gray-900 font-semibold text-sm rounded-lg transition-colors duration-150">
                        Reset Password
                    </button>

                    <p class="text-center text-sm text-gray-500 mt-4">
                        Remembered your password?
                        <a href="{{ route('login') }}" class="text-[#f5a623] hover:underline">Back to login →</a>
                    </p>

                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.innerHTML = isPassword ?
                `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21" />` :
                `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
        }
    </script>
</x-guest-layout>
