@props([
    'alpine' => false,
    'name' => '',
    'label' => 'Dropdown',
    'required' => false,
    'cssClass' => '',
    'options' => [],
    'defaultValue' => ''
])

<div class="{{ $alpine ? '' : $cssClass }} mb-4">
    @if($alpine)
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            <span x-text="field.label || 'Dropdown'"></span>
            <span x-show="field.required" class="text-red-500">*</span>
        </label>
        <select 
            x-bind:required="field.required"
            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
            disabled
        >
            <option value="" x-text="field.placeholder || 'Select an option...'"></option>
            <template x-for="(opt, idx) in field.options" :key="idx">
                <option :value="opt" x-text="opt" :selected="opt === field.defaultValue"></option>
            </template>
        </select>
        <div class="mt-1 flex items-center justify-between text-xs text-gray-400">
            <span x-text="(field.options ? field.options.length : 0) + ' options configured'"></span>
            <span x-show="field.cssClass" x-text="'Class: ' + field.cssClass" class="font-mono"></span>
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
            <option value="">Select an option...</option>
            @foreach($options as $opt)
                <option value="{{ $opt }}" {{ $defaultValue == $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
        </select>
    @endif
</div>
