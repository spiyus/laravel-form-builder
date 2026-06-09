@props([
    'alpine' => false,
    'name' => '',
    'label' => 'State Selector',
    'required' => false,
    'cssClass' => '',
    'defaultValue' => ''
])

<div class="{{ $alpine ? '' : $cssClass }} mb-4">
    @if($alpine)
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            <span x-text="field.label || 'State Selector'"></span>
            <span x-show="field.required" class="text-red-500">*</span>
        </label>
        <select 
            x-bind:required="field.required"
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
            disabled
        >
            <option value="" x-text="field.placeholder || 'Select State...'"></option>
            <option>California</option>
            <option>New York</option>
            <option>Texas</option>
            <option>Maharashtra</option>
            <option>Karnataka</option>
        </select>
        <div x-show="field.cssClass" class="mt-1 text-xs text-gray-400 font-mono text-right">
            Class: <span x-text="field.cssClass"></span>
        </div>
    @else
        @if($label)
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                {{ $label }}
                @if($required)<span class="text-red-500">*</span>@endif
            </label>
        @endif
        <select 
            name="{{ $name }}"
            {{ $required ? 'required' : '' }}
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
        >
            <option value="">Select State...</option>
            <option value="California" {{ $defaultValue == 'California' ? 'selected' : '' }}>California</option>
            <option value="New York" {{ $defaultValue == 'New York' ? 'selected' : '' }}>New York</option>
            <option value="Texas" {{ $defaultValue == 'Texas' ? 'selected' : '' }}>Texas</option>
            <option value="Maharashtra" {{ $defaultValue == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
            <option value="Karnataka" {{ $defaultValue == 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
        </select>
    @endif
</div>
