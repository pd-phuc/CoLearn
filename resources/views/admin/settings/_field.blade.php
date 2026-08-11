<div>
    <label for="{{ $name }}" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">{{ $label }}</label>
    <input type="{{ $type ?? 'text' }}"
           name="{{ $name }}"
           id="{{ $name }}"
           value="{{ old($name, $value ?? '') }}"
           placeholder="{{ $placeholder ?? '' }}"
           class="w-full px-4 py-2.5 text-sm bg-slate-50/80 border border-slate-200/80 rounded-xl focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all font-medium">
    @error($name)
        <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>
    @enderror
</div>
