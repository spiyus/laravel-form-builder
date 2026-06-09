@props([
    'alpine' => false,
    'name' => '',
    'label' => 'Text Area',
    'placeholder' => 'Enter long text...',
    'required' => false,
    'cssClass' => '',
    'defaultValue' => '',
    'min' => null,
    'max' => null
])

<div class="{{ $alpine ? '' : $cssClass }} mb-4">
    @if($alpine)
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            <span x-text="field.label || 'Text Area'"></span>
            <span x-show="field.required" class="text-red-500">*</span>
        </label>
        <textarea 
            rows="3"
            :placeholder="field.placeholder || 'Enter long text...'" 
            x-text="field.defaultValue || ''"
            x-bind:required="field.required"
            x-bind:minlength="field.min"
            x-bind:maxlength="field.max"
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 resize-y"
            disabled
        ></textarea>
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
        <textarea 
            name="{{ $name }}" 
            placeholder="{{ $placeholder }}" 
            {{ $required ? 'required' : '' }}
            @if($min) minlength="{{ $min }}" @endif
            @if($max) maxlength="{{ $max }}" @endif
            rows="3"
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 resize-y"
        >{{ $defaultValue }}</textarea>
    @endif
</div>
