@props([
    'alpine' => false,
    'label' => 'Enter descriptive text or instructions for the user here...',
    'cssClass' => ''
])

<div class="{{ $alpine ? '' : $cssClass }} mb-4">
    @if($alpine)
        <p class="text-sm text-gray-600 leading-relaxed" x-text="field.label || 'Enter descriptive text or instructions for the user here...'"></p>
        <div x-show="field.cssClass" class="mt-1 text-xs text-gray-400 font-mono">
            Class: <span x-text="field.cssClass"></span>
        </div>
    @else
        <p class="text-sm text-gray-600 leading-relaxed">{{ $label }}</p>
    @endif
</div>
