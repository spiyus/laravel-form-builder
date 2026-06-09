@props([
    'alpine' => false,
    'name' => '',
    'label' => 'File Upload',
    'required' => false,
    'cssClass' => ''
])

<div class="{{ $alpine ? '' : $cssClass }} mb-4">
    @if($alpine)
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            <span x-text="field.label || 'File Upload'"></span>
            <span x-show="field.required" class="text-red-500">*</span>
        </label>
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50 text-center flex flex-col items-center justify-center cursor-default">
            <i class="fa fa-cloud-upload text-3xl text-gray-400 mb-2"></i>
            <p class="text-sm font-medium text-gray-600">Drag & drop your file here, or <span class="text-blue-500">browse</span></p>
            <p class="text-xs text-gray-400 mt-1">Supports files up to 10MB</p>
        </div>
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
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 bg-white hover:bg-gray-50 text-center flex flex-col items-center justify-center cursor-pointer transition-all duration-200">
            <i class="fa fa-cloud-upload text-3xl text-gray-400 mb-2"></i>
            <p class="text-sm font-medium text-gray-600">Drag & drop your file here, or <span class="text-blue-500">browse</span></p>
            <p class="text-xs text-gray-400 mt-1">Supports files up to 10MB</p>
            <input type="file" name="{{ $name }}" class="hidden" {{ $required ? 'required' : '' }} />
        </div>
    @endif
</div>
