@extends('admin.layouts.admin')
@section('page-title', $category ? 'Edit Category: ' . $category->name : 'Create New Category')
@section('page-description', 'Manage course category details, slug, and display hierarchy')

@section('admin-content')
<div class="space-y-6">
    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-orange-600 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Back to Categories
    </a>

    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-xs">
        <form action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" class="space-y-6">
            @csrf
            @if($category) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">Category Name</label>
                    <input type="text" name="name" value="{{ old('name', $category?->name) }}" required placeholder="e.g. Lập Trình Web" class="w-full px-4 py-2.5 text-sm bg-slate-50/80 border border-slate-200/80 rounded-xl focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all font-medium">
                    @error('name') <p class="text-xs text-rose-600 font-bold mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">URL Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $category?->slug) }}" required placeholder="lap-trinh-web" class="w-full px-4 py-2.5 text-sm bg-slate-50/80 border border-slate-200/80 rounded-xl focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all font-medium">
                    @error('slug') <p class="text-xs text-rose-600 font-bold mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">Description</label>
                    <textarea name="description" rows="3" placeholder="Category summary and target skills..." class="w-full px-4 py-2.5 text-sm bg-slate-50/80 border border-slate-200/80 rounded-xl focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all font-medium">{{ old('description', $category?->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">Display Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category?->sort_order ?? 0) }}" class="w-full px-4 py-2.5 text-sm bg-slate-50/80 border border-slate-200/80 rounded-xl focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all font-medium">
                </div>
            </div>

            <div class="pt-5 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm rounded-xl transition-colors shadow-sm shadow-orange-500/20">
                    {{ $category ? 'Update Category' : 'Create Category' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
