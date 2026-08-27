@extends('admin.layouts.admin')

@section('admin-content')
    <div x-data="{ activeTab: '{{ array_key_first($settingGroups) }}' }" class="space-y-6">
        {{-- Tabs Navigation Bar --}}
        <div
            class="bg-white border border-slate-200/80 rounded-2xl p-2 shadow-xs flex items-center gap-1 overflow-x-auto"
        >
            @php
                $tabLabels = [
                    'platform' => __('admin.tab_platform'),
                    'sepay' => __('admin.tab_sepay'),
                    'stripe' => __('admin.tab_stripe'),
                    'email' => __('admin.tab_email'),
                    'google' => __('admin.tab_google'),
                    'facebook' => __('admin.tab_facebook'),
                    's3' => __('admin.tab_s3'),
                ];
            @endphp

            @foreach ($settingGroups as $groupKey => $group)
                <button
                    type="button"
                    @click="activeTab = '{{ $groupKey }}'"
                    :class="activeTab === '{{ $groupKey }}' ? 'bg-orange-50 text-orange-600 border-orange-200/60 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-transparent'"
                    class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all border whitespace-nowrap cursor-pointer"
                >
                    {{ $tabLabels[$groupKey] ?? $group['title'] }}
                </button>
            @endforeach
        </div>

        {{-- Settings Form --}}
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            @foreach ($settingGroups as $groupKey => $group)
                <div
                    x-show="activeTab === '{{ $groupKey }}'"
                    x-transition
                    class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-xs space-y-6"
                >
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="text-base font-extrabold text-slate-900">
                            {{ $tabLabels[$groupKey] ?? $group['title'] }}
                        </h3>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $group['description'] }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($group['settings'] as $key => $setting)
                            @php
                                $fieldType = $setting['type'] ?? 'text';
                                $fieldLabel = $setting['label'] ?? $key;
                                $fieldValue = $setting['value'] ?? '';
                                $fieldOptions = $setting['options'] ?? [];
                            @endphp

                            @if ($fieldType === 'secret' || $fieldType === 'password')
                                @include('admin.settings._secret', ['name' => "settings[{$key}]", 'label' => $fieldLabel, 'value' => $fieldValue])
                            @elseif ($fieldType === 'select')
                                @include('admin.settings._select', ['name' => "settings[{$key}]", 'label' => $fieldLabel, 'value' => $fieldValue, 'options' => $fieldOptions])
                            @else
                                @include('admin.settings._field', ['name' => "settings[{$key}]", 'label' => $fieldLabel, 'value' => $fieldValue, 'type' => $fieldType])
                            @endif
                        @endforeach
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end">
                        <button
                            type="submit"
                            class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-sm rounded-xl transition-all shadow-sm shadow-orange-500/20 cursor-pointer"
                        >
                            {{ __('admin.save_settings') }}
                        </button>
                    </div>
                </div>
            @endforeach
        </form>
    </div>
@endsection
