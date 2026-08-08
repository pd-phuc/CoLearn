<!-- Free Preview Video Modal Component (Alpine.js) -->
<div x-data="{
        isOpen: false,
        videoTitle: '',
        videoUrl: '',
        openModal(detail) {
            this.videoTitle = detail?.title || '{{ __('messages.free_preview_modal_title') }}';
            this.videoUrl = detail?.videoUrl || '';
            this.isOpen = true;
        },
        closeModal() {
            this.isOpen = false;
            this.videoUrl = '';
        }
     }"
     @open-preview-modal.window="openModal($event.detail)"
     @keydown.escape.window="closeModal()"
     x-show="isOpen"
     class="relative z-50"
     style="display: none;">

    <!-- Backdrop Blur -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-md"></div>

    <!-- Modal Dialog -->
    <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 lg:p-8 flex items-center justify-center">
        <div x-show="isOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             @click.outside="closeModal()"
             class="w-full max-w-4xl bg-slate-900 rounded-3xl shadow-2xl overflow-hidden border border-slate-800 text-white">

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 bg-orange-500/20 text-orange-400 border border-orange-500/30 rounded-md font-bold text-[10px] uppercase tracking-wider">
                        {{ __('messages.free_preview_modal_title') }}
                    </span>
                    <h3 class="font-extrabold text-sm sm:text-base text-white truncate max-w-md" x-text="videoTitle"></h3>
                </div>
                <button @click="closeModal()" class="text-slate-400 hover:text-white font-bold text-2xl cursor-pointer">&times;</button>
            </div>

            <!-- Video Player Area -->
            <div class="aspect-video bg-black relative flex items-center justify-center">
                <template x-if="videoUrl">
                    <iframe :src="videoUrl"
                            class="w-full h-full border-0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </template>
                <template x-if="!videoUrl">
                    <div class="text-center p-8 space-y-3">
                        <div class="w-16 h-16 rounded-full bg-orange-500/20 text-orange-500 flex items-center justify-center mx-auto">
                            <svg class="w-7 h-7 fill-current text-orange-500 ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                        <p class="text-sm font-extrabold text-white">{{ __('messages.free_preview_modal_title') }}</p>
                        <p class="text-xs text-slate-400">{{ __('messages.free_preview_modal_sub') }}</p>
                    </div>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 bg-slate-950 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span>{{ __('messages.video_protected_notice') }}</span>
                </div>
                <a href="{{ route('register') }}" class="btn-primary py-2 px-4 text-xs font-bold">
                    {{ __('messages.enroll_full_course') }}
                </a>
            </div>

        </div>
    </div>
</div>
