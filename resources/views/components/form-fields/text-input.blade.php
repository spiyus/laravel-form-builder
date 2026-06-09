@props([
    'alpine' => false,
    'name' => '',
    'label' => 'Text Input',
    'placeholder' => 'Enter text...',
    'required' => false,
    'cssClass' => '',
    'defaultValue' => '',
    'min' => null,
    'max' => null
])

<div class="{{ $alpine ? '' : $cssClass }} mb-4">
    @if($alpine)
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            <span x-text="field.label || 'Text Input'"></span>
            <span x-show="field.required" class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            :placeholder="field.placeholder || 'Enter text...'" 
            :value="field.defaultValue || ''"
            x-bind:required="field.required"
            x-bind:minlength="field.min"
            x-bind:maxlength="field.max"
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
            disabled
        />
        <div class="mt-1 flex items-center justify-between text-xs text-gray-400">
            <span x-show="field.min || field.max" x-text="'Length: ' + (field.min || 0) + ' - ' + (field.max || 'any') + ' chars'"></span>
            <span x-show="field.cssClass" x-text="'Class: ' + field.cssClass" class="font-mono"></span>
        </div>
    @else
        @if($label)
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                {{ $label }}
                @if($required)<span class="text-red-500">*</span>@endif
            </label>
        @endif
        <input 
            type="text" 
            name="{{ $name }}" 
            placeholder="{{ $placeholder }}" 
            value="{{ $defaultValue }}"
            {{ $required ? 'required' : '' }}
            @if($min) minlength="{{ $min }}" @endif
            @if($max) maxlength="{{ $max }}" @endif
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
        />
    @endif
</div>
