@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl text-sm font-medium flex items-center gap-3 mb-6" 
         x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 4000)" 
         x-transition>
        <x-icon name="check-circle" class="w-5 h-5 text-green-500 shrink-0" />
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl text-sm font-medium flex items-center gap-3 mb-6" 
         x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 4000)" 
         x-transition>
        <x-icon name="exclamation-circle" class="w-5 h-5 text-red-500 shrink-0" />
        {{ session('error') }}
    </div>
@endif
