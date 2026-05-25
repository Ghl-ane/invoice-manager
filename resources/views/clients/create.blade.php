{{-- resources/views/clients/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Add New Client')
@section('subtitle', 'Fill in the client details below')

@section('content')

    <div class="max-w-xl">
        <div class="bg-white rounded-xl border border-slate-200 p-6">

            <form action="{{ route('clients.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Full Name <span
                            class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition @error('name') border-red-400 @enderror"
                        placeholder="John Smith">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Email Address <span
                            class="text-red-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition @error('email') border-red-400 @enderror"
                        placeholder="john@example.com">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Phone <span
                            class="text-slate-400 font-normal">(optional)</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                        placeholder="+1 234 567 890">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Address <span
                            class="text-slate-400 font-normal">(optional)</span></label>
                    <textarea name="address" rows="3"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition resize-none"
                        placeholder="123 Main St, New York, USA">{{ old('address') }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="bg-amber-400 hover:bg-amber-500 text-slate-900 font-semibold text-sm px-6 py-2.5 rounded-lg transition-colors">
                        Save Client
                    </button>
                    <a href="{{ route('clients.index') }}"
                        class="text-sm text-slate-500 hover:text-slate-700 px-4 py-2.5 rounded-lg hover:bg-slate-100 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>

        </div>
    </div>

@endsection
