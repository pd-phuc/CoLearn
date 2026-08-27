{{-- Flash message toasts. Bottom-right, stacked, auto-dismissing. --}}
@php
    $toasts = collect([
        'success' => 'success',
        'status' => 'success',
        'error' => 'error',
        'warning' => 'warning',
        'info' => 'info',
    ])
        ->filter(fn ($type, $key) => filled(session($key)))
        ->map(fn ($type, $key) => ['type' => $type, 'message' => (string) session($key)])
        ->values()
        ->all();
@endphp

@if (! empty($toasts))
    <div
        x-data="{
            toasts: @js($toasts).map((toast, i) => ({ ...toast, id: i, leaving: false })),
            init() {
                this.toasts.forEach((toast) =>
                    setTimeout(() => this.dismiss(toast.id), 5000 + toast.id * 70),
                )
            },
            dismiss(id) {
                const toast = this.toasts.find((t) => t.id === id)
                if (! toast || toast.leaving) return
                toast.leaving = true
                setTimeout(
                    () => (this.toasts = this.toasts.filter((t) => t.id !== id)),
                    180,
                )
            },
        }"
        class="pointer-events-none fixed inset-x-0 bottom-0 z-[100] flex flex-col gap-2 p-4 sm:inset-x-auto sm:right-0 sm:w-[400px]"
        aria-live="polite"
        aria-atomic="true"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div
                class="toast-item pointer-events-auto flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-lg shadow-slate-900/5"
                :data-leaving="toast.leaving ? 'true' : 'false'"
                :style="`animation-delay: ${toast.id * 70}ms`"
                @click="dismiss(toast.id)"
            >
                {{-- success --}}
                <svg
                    x-show="toast.type === 'success'"
                    class="mt-px h-4 w-4 shrink-0 text-emerald-600"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"
                    />
                </svg>

                {{-- error --}}
                <svg
                    x-show="toast.type === 'error'"
                    class="mt-px h-4 w-4 shrink-0 text-rose-600"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"
                    />
                </svg>

                {{-- warning --}}
                <svg
                    x-show="toast.type === 'warning'"
                    class="mt-px h-4 w-4 shrink-0 text-amber-500"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"
                    />
                </svg>

                {{-- info --}}
                <svg
                    x-show="toast.type === 'info'"
                    class="mt-px h-4 w-4 shrink-0 text-sky-600"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd"
                    />
                </svg>

                <p class="flex-1 text-[13px] font-semibold leading-5 text-slate-900" x-text="toast.message"></p>

                <button
                    type="button"
                    @click.stop="dismiss(toast.id)"
                    class="-m-1 shrink-0 rounded-md p-1 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600"
                    aria-label="{{ __('messages.dismiss_notification') }}"
                >
                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </div>
        </template>
    </div>
@endif
