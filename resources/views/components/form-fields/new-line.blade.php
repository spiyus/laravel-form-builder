@props([
    'alpine' => false,
    'cssClass' => ''
])

<div class="{{ $alpine ? '' : $cssClass }} py-3">
    <div class="w-full border-t border-gray-200"></div>
    @if($alpine)
        <div class="mt-1 flex justify-between items-center text-xs text-gray-400 select-none">
            <span>New Line Divider</span>
            <span x-show="field.cssClass" x-text="'Class: ' + field.cssClass" class="font-mono"></span>
        </div>
    @endif
</div>
