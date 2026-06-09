@props([
    'alpine' => false,
    'cssClass' => ''
])

<div class="{{ $alpine ? '' : $cssClass }} py-4 relative flex items-center justify-center">
    <div class="absolute inset-0 flex items-center" aria-hidden="true">
        <div class="w-full border-t-2 border-dashed border-indigo-200"></div>
    </div>
    <span class="relative px-3 bg-white text-[10px] font-bold text-indigo-600 uppercase tracking-wider rounded-full border border-indigo-200 shadow-sm select-none">
        Page Break
    </span>
</div>
