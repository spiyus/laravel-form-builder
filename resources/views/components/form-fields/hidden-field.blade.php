@props([
    'alpine' => false,
    'name' => '',
    'label' => 'Hidden Field',
    'defaultValue' => '',
    'cssClass' => ''
])

<div class="{{ $alpine ? '' : $cssClass }}">
    @if($alpine)
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3.5 flex items-center justify-between shadow-sm select-none">
            <div class="flex items-center space-x-3">
                <div class="h-8 w-8 rounded-lg bg-gray-200 flex items-center justify-center text-gray-500">
                    <i class="fa fa-eye-slash"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-700" x-text="field.label || 'Hidden Field'"></h4>
                    <p class="text-xs text-gray-400">Value: <span x-text="field.defaultValue || '[None]'" class="font-mono bg-gray-150 px-1 rounded"></span></p>
                </div>
            </div>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-200 text-gray-600 uppercase tracking-wider">Hidden Input</span>
        </div>
    @else
        <input type="hidden" name="{{ $name }}" value="{{ $defaultValue }}" />
    @endif
</div>
