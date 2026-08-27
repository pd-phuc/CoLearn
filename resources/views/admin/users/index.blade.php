@extends('admin.layouts.admin')

@section('admin-content')
    <div class="space-y-6">
        {{-- Header Action & Search Bar --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
            <form class="flex flex-wrap items-center justify-between gap-4" method="GET">
                <div class="flex flex-wrap items-center gap-3 flex-1 min-w-[280px]">
                    <div class="relative flex-1 min-w-[200px]">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="{{ __('admin.search_users_placeholder') }}"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50/80 border border-slate-200/80 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all"
                        />
                        <svg
                            class="w-4 h-4 text-slate-400 absolute left-3.5 top-3"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            />
                        </svg>
                    </div>
                    <select
                        name="role"
                        onchange="this.form.submit()"
                        class="px-4 py-2.5 bg-slate-50/80 border border-slate-200/80 rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all"
                    >
                        <option value="">{{ __('admin.all_roles') }}</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>
                            {{ __('messages.role_admin') }}
                        </option>
                        <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>
                            {{ __('messages.role_teacher') }}
                        </option>
                        <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>
                            {{ __('messages.role_student') }}
                        </option>
                    </select>
                    <button
                        type="submit"
                        class="btn-primary px-5 py-2.5 rounded-xl text-xs font-extrabold uppercase shadow-sm"
                    >
                        {{ __('admin.filter') }}
                    </button>
                </div>
                <span class="text-xs font-bold text-slate-500">
                    {{ __('admin.showing_users_count', ['count' => $users->count()]) }}
                </span>
            </form>
        </div>

        {{-- Users Data Table --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/80 border-b border-slate-200/80">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('admin.user_account') }}
                            </th>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('admin.role') }}
                            </th>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('admin.wallet_balance') }}
                            </th>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('admin.joined_date') }}
                            </th>
                            <th
                                class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider text-right"
                            >
                                {{ __('admin.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($user->avatar)
                                            <img
                                                src="{{ $user->avatar }}"
                                                class="w-9 h-9 rounded-full object-cover ring-2 ring-orange-500/20"
                                            />
                                        @else
                                            <div
                                                class="w-9 h-9 rounded-full bg-gradient-to-tr from-orange-500 to-amber-500 flex items-center justify-center text-white font-black text-xs shadow-xs"
                                            >
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-extrabold text-slate-900">{{ $user->name }}</p>
                                            <p class="text-xs text-slate-400 font-medium">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @foreach ($user->roles as $role)
                                        @php
                                            $roleColors = ['admin' => 'bg-purple-50 text-purple-700 border-purple-200/60', 'teacher' => 'bg-blue-50 text-blue-700 border-blue-200/60', 'student' => 'bg-orange-50 text-orange-700 border-orange-200/60'];
                                        @endphp

                                        <span
                                            class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider border {{ $roleColors[$role->name] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}"
                                        >
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 text-sm font-black text-emerald-600">
                                    {{ number_format($user->balance, 0, ',', '.') }} đ
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-500">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a
                                        href="{{ route('admin.users.show', $user) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-orange-50 hover:text-orange-600 text-slate-700 rounded-xl text-xs font-bold transition-colors"
                                    >
                                        <svg
                                            class="w-3.5 h-3.5 text-slate-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                        {{ __('admin.view_profile') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-xs font-bold text-slate-400">
                                    {{ __('admin.no_users_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $users->links() }}</div>
    </div>
@endsection
