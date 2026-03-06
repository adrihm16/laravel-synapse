@props(['message' => null, 'type' => 'success'])

<!-- Alpine.js Toast Container -->
<div x-data="{ 
        show: false, 
        message: '{{ $message ?? session('success') ?? session('error') ?? '' }}',
        type: '{{ session('error') ? 'error' : $type }}',
        init() {
            if (this.message) {
                this.showToast();
            }
            
            // Listen for custom events to show toast dynamically
            window.addEventListener('notify', (e) => {
                this.message = e.detail.message;
                this.type = e.detail.type || 'success';
                this.showToast();
            });
        },
        showToast() {
            this.show = true;
            setTimeout(() => {
                this.show = false;
            }, 4000);
        }
    }" 
    x-show="show" 
    x-transition:enter="transition ease-out duration-300" 
    x-transition:enter-start="opacity-0 transform translate-y-10 scale-95" 
    x-transition:enter-end="opacity-100 transform translate-y-0 scale-100" 
    x-transition:leave="transition ease-in duration-200" 
    x-transition:leave-start="opacity-100 transform translate-y-0 scale-100" 
    x-transition:leave-end="opacity-0 transform translate-y-10 scale-95" 
    class="fixed bottom-6 right-6 z-[100] flex items-center gap-3 px-6 py-4 rounded-2xl shadow-2xl border"
    :class="{
        'bg-white border-[#004689]/20 text-gray-800': type === 'success',
        'bg-red-50 border-red-200 text-red-800': type === 'error'
    }"
    style="display: none;">
    
    <!-- Icon -->
    <div class="flex-shrink-0" :class="{'text-[#004689]': type === 'success', 'text-red-500': type === 'error'}">
        <template x-if="type === 'success'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </template>
        <template x-if="type === 'error'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </template>
    </div>

    <!-- Message -->
    <div class="font-medium text-sm tracking-wide" x-text="message"></div>

    <!-- Close Button -->
    <button @click="show = false" class="ml-4 opacity-50 hover:opacity-100 transition focus:outline-none" :class="{'text-gray-400': type === 'success', 'text-red-400': type === 'error'}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>
