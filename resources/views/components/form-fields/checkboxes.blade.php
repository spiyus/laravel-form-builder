@props([
    'alpine' => false,
    'name' => '',
    'label' => 'Checkboxes',
    'required' => false,
    'cssClass' => '',
    'options' => [],
    'defaultValue' => [] // Array for checkboxes
])

<div class="{{ $alpine ? '' : $cssClass }} mb-4">
    @if($alpine)
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            <span x-text="field.label || 'Checkboxes'"></span>
            <span x-show="field.required" class="text-red-500">*</span>
        </label>
        <div class="mt-2 space-y-2">
            <template x-for="(opt, idx) in field.options" :key="idx">
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        :name="'checkbox_' + field.id + '[]'" 
                        :value="opt"
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        disabled
                    />
                    <label class="ms-2 text-sm text-gray-700" x-text="opt"></label>
                </div>
            </template>
            <div x-show="!field.options || field.options.length === 0" class="text-xs text-gray-400 italic">
                No options configured. Add options in Field Options.
            </div>
        </div>
        <div x-show="field.cssClass" class="mt-2 text-xs text-gray-400 font-mono">
            Class: <span x-text="field.cssClass"></span>
        </div>
    @else
        @if($label)
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                {{ $label }}
                @if($required)<span class="text-red-500">*</span>@endif
            </label>
        @endif
        <div class="mt-2 space-y-2">
            @foreach($options as $opt)
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="{{ $name }}[]" 
                        value="{{ $opt }}"
                        {{ is_array($defaultValue) && in_array($opt, $defaultValue) ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    />
                    <label class="ms-2 text-sm text-gray-700">{{ $opt }}</label>
                </div>
            @endforeach
        </div>
    @endif
</div>
