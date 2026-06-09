@props([
    'alpine' => false,
    'name' => '',
    'label' => 'Number Input',
    'placeholder' => 'Enter number...',
    'required' => false,
    'cssClass' => '',
    'defaultValue' => ''
])

<div class="{{ $alpine ? '' : $cssClass }} mb-4">
    @if($alpine)
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            <span x-text="field.label || 'Number Input'"></span>
            <span x-show="field.required" class="text-red-500">*</span>
        </label>
        <input 
            type="number" 
            :placeholder="field.placeholder || 'Enter number...'" 
            :value="field.defaultValue || ''"
            x-bind:required="field.required"
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
            disabled
        />
        <div x-show="field.cssClass" class="mt-1 text-xs text-gray-400 text-right">
            <span x-text="'Class: ' + field.cssClass" class="font-mono"></span>
        </div>
    @else
        @if($label)
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                {{ $label }}
                @if($required)<span class="text-red-500">*</span>@endif
            </label>
        @endif
        <input 
            type="number" 
            name="{{ $name }}" 
            placeholder="{{ $placeholder }}" 
            value="{{ $defaultValue }}"
            {{ $required ? 'required' : '' }}
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
        />
    @endif
</div>
