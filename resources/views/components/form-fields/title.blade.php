@props([
    'alpine' => false,
    'label' => 'Section Title',
    'cssClass' => ''
])

<div class="{{ $alpine ? '' : $cssClass }} mb-2 mt-4">
    @if($alpine)
        <h3 class="text-xl font-bold text-gray-800" x-text="field.label || 'Section Title'"></h3>
        <div x-show="field.cssClass" class="mt-1 text-xs text-gray-400 font-mono">
            Class: <span x-text="field.cssClass"></span>
        </div>
    @else
        <h3 class="text-xl font-bold text-gray-800">{{ $label }}</h3>
    @endif
</div>
