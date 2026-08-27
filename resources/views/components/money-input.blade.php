@props([
    'name',
    'id' => null,
    'value' => '',
    'label' => null,
    'placeholder' => '0',
    'required' => false,
    'min' => 0,
    'max' => 500000000,
    'hint' => null,
    'suffix' => 'đ',
    'error' => null,
])

@php
    $inputId = $id ?? $name . '_' . uniqid();
    $errorKey = $error ?? $name;
    $initialValue = old($name, $value);
    $initialFormatted = $initialValue !== '' && $initialValue !== null && is_numeric($initialValue) ? number_format((int) $initialValue, 0, ',', '.') : '';
    $initialRaw = $initialValue !== '' && $initialValue !== null && is_numeric($initialValue) ? (int) $initialValue : '';
@endphp

<div
    x-data="{
        rawValue: '{{ $initialRaw }}',
        display: '{{ $initialFormatted }}',
        formatNum(num) {
            if (num === '' || num === null || isNaN(num)) return ''
            return num.toString().replace(/\B(?=(\d{3})+(?! \d))/g, '.')
        },
        onInput(e) {
            let digits = e.target.value.replace(/\D/g, '')
            if (digits === '') {
                this.rawValue = ''
                this.display = ''
                return
            }
            let num = parseInt(digits, 10)
            if (num > {{ $max }}) num = {{ $max }}
            this.rawValue = num
            this.display = this.formatNum(num)
            this.$nextTick(() => {
                e.target.setSelectionRange(this.display.length, this.display.length)
            })
        },
    }"
    class="w-full"
>
    @if ($label)
        <label for="{{ $inputId }}" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
            {{ $label }}
            @if ($required)
                <span class="text-rose-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <input
            type="text"
            id="{{ $inputId }}"
            x-model="display"
            @input="onInput($event)"
            @focus="$event.target.select()"
            inputmode="numeric"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-bold pr-12 transition-colors']) }}
        />
        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 select-none">
            {{ $suffix }}
        </span>
        <input type="hidden" name="{{ $name }}" x-model="rawValue" />
    </div>

    @if ($hint)
        <p class="text-[11px] text-slate-400 mt-1 font-medium">{{ $hint }}</p>
    @endif

    @error($errorKey)
        <p class="text-xs text-rose-600 font-bold mt-1.5">{{ $message }}</p>
    @enderror
</div>
