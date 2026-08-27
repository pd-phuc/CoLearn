@extends('admin.layouts.admin')

@section('admin-content')
    <div class="space-y-6">
        {{-- Header Action Bar --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span
                    class="px-3 py-1 bg-white border border-slate-200/80 rounded-xl text-xs font-extrabold text-slate-700 shadow-2xs"
                >
                    {{ __('admin.total_categories', ['count' => $categories->count()]) }}
                </span>
            </div>
            <a
                href="{{ route('admin.categories.create') }}"
                class="btn-primary px-4 py-2.5 rounded-xl text-xs font-extrabold shadow-sm flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('admin.add_new_category') }}
            </a>
        </div>

        {{-- Categories Data Table --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/80 border-b border-slate-200/80">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('admin.category_name') }}
                            </th>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('admin.url_slug') }}
                            </th>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('admin.courses_count') }}
                            </th>
                            <th
                                class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider text-right"
                            >
                                {{ __('admin.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse ($categories as $cat)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-xl bg-orange-50 border border-orange-200/60 flex items-center justify-center text-orange-600 font-black text-xs"
                                        >
                                            {{ strtoupper(substr($cat->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-extrabold text-slate-900">{{ $cat->name }}</p>
                                            @if ($cat->description)
                                                <p class="text-xs text-slate-400 truncate max-w-xs">
                                                    {{ $cat->description }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 bg-slate-100 text-slate-600 font-mono text-xs font-semibold rounded-lg border border-slate-200/60"
                                    >
                                        {{ $cat->slug }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 bg-orange-50 text-orange-700 font-extrabold text-xs rounded-full border border-orange-200/60"
                                    >
                                        {{ $cat->courses_count }} courses
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route('admin.categories.edit', $cat) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-orange-50 hover:text-orange-600 text-slate-700 rounded-xl text-xs font-bold transition-colors"
                                        >
                                            <svg
                                                class="w-3.5 h-3.5 text-slate-400 group-hover:text-orange-500"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                />
                                            </svg>
                                            {{ __('admin.edit') }}
                                        </a>
                                        @if ($cat->courses_count === 0)
                                            <form
                                                action="{{ route('admin.categories.destroy', $cat) }}"
                                                method="POST"
                                                onsubmit="return confirm('Delete category {{ $cat->name }}?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition-colors cursor-pointer"
                                                >
                                                    <svg
                                                        class="w-3.5 h-3.5 text-rose-500"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                        />
                                                    </svg>
                                                    {{ __('admin.delete') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-xs font-bold text-slate-400">
                                    {{ __('messages.no_categories') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
