@props([
    'alpine' => false,
    'name' => 'location',
    'label' => 'State & City Combined',
    'required' => false,
    'cssClass' => '',
    'stateDefault' => '',
    'cityDefault' => ''
])

<div class="{{ $alpine ? '' : $cssClass }} mb-4">
    @if($alpine)
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            <span x-text="field.label || 'State & City'"></span>
            <span x-show="field.required" class="text-red-500">*</span>
        </label>
        
        <!-- Inside editor/preview container -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1">State</label>
                <select 
                    x-model="field.stateValue"
                    x-bind:required="field.required"
                    class="w-full px-3 py-2 rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm transition-all duration-200"
                    :disabled="mode === 'edit'"
                >
                    <option value="">Select State...</option>
                    <option value="California">California</option>
                    <option value="New York">New York</option>
                    <option value="Texas">Texas</option>
                    <option value="Maharashtra">Maharashtra</option>
                    <option value="Karnataka">Karnataka</option>
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1">City</label>
                <select 
                    x-model="field.cityValue"
                    x-bind:required="field.required"
                    class="w-full px-3 py-2 rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm transition-all duration-200"
                    :disabled="mode === 'edit' || !field.stateValue"
                >
                    <option value="">Select City...</option>
                    <template x-if="field.stateValue === 'California'">
                        <optgroup label="California Cities">
                            <option value="Los Angeles">Los Angeles</option>
                            <option value="San Francisco">San Francisco</option>
                            <option value="San Diego">San Diego</option>
                        </optgroup>
                    </template>
                    <template x-if="field.stateValue === 'New York'">
                        <optgroup label="New York Cities">
                            <option value="New York City">New York City</option>
                            <option value="Buffalo">Buffalo</option>
                            <option value="Rochester">Rochester</option>
                        </optgroup>
                    </template>
                    <template x-if="field.stateValue === 'Texas'">
                        <optgroup label="Texas Cities">
                            <option value="Houston">Houston</option>
                            <option value="Austin">Austin</option>
                            <option value="Dallas">Dallas</option>
                        </optgroup>
                    </template>
                    <template x-if="field.stateValue === 'Maharashtra'">
                        <optgroup label="Maharashtra Cities">
                            <option value="Mumbai">Mumbai</option>
                            <option value="Pune">Pune</option>
                            <option value="Nagpur">Nagpur</option>
                        </optgroup>
                    </template>
                    <template x-if="field.stateValue === 'Karnataka'">
                        <optgroup label="Karnataka Cities">
                            <option value="Bangalore">Bangalore</option>
                            <option value="Mysore">Mysore</option>
                            <option value="Hubli">Hubli</option>
                        </optgroup>
                    </template>
                </select>
            </div>
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
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <select 
                    name="{{ $name }}_state"
                    {{ $required ? 'required' : '' }}
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
                >
                    <option value="">Select State...</option>
                    <option value="California" {{ $stateDefault == 'California' ? 'selected' : '' }}>California</option>
                    <option value="New York" {{ $stateDefault == 'New York' ? 'selected' : '' }}>New York</option>
                    <option value="Texas" {{ $stateDefault == 'Texas' ? 'selected' : '' }}>Texas</option>
                    <option value="Maharashtra" {{ $stateDefault == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                    <option value="Karnataka" {{ $stateDefault == 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
                </select>
            </div>
            <div>
                <select 
                    name="{{ $name }}_city"
                    {{ $required ? 'required' : '' }}
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
                >
                    <option value="">Select City...</option>
                    @if($stateDefault)
                        <!-- Render standard options based on stateDefault if known -->
                        @if($stateDefault == 'California')
                            <option value="Los Angeles" {{ $cityDefault == 'Los Angeles' ? 'selected' : '' }}>Los Angeles</option>
                            <option value="San Francisco" {{ $cityDefault == 'San Francisco' ? 'selected' : '' }}>San Francisco</option>
                            <option value="San Diego" {{ $cityDefault == 'San Diego' ? 'selected' : '' }}>San Diego</option>
                        @elseif($stateDefault == 'New York')
                            <option value="New York City" {{ $cityDefault == 'New York City' ? 'selected' : '' }}>New York City</option>
                            <option value="Buffalo" {{ $cityDefault == 'Buffalo' ? 'selected' : '' }}>Buffalo</option>
                            <option value="Rochester" {{ $cityDefault == 'Rochester' ? 'selected' : '' }}>Rochester</option>
                        @elseif($stateDefault == 'Texas')
                            <option value="Houston" {{ $cityDefault == 'Houston' ? 'selected' : '' }}>Houston</option>
                            <option value="Austin" {{ $cityDefault == 'Austin' ? 'selected' : '' }}>Austin</option>
                            <option value="Dallas" {{ $cityDefault == 'Dallas' ? 'selected' : '' }}>Dallas</option>
                        @elseif($stateDefault == 'Maharashtra')
                            <option value="Mumbai" {{ $cityDefault == 'Mumbai' ? 'selected' : '' }}>Mumbai</option>
                            <option value="Pune" {{ $cityDefault == 'Pune' ? 'selected' : '' }}>Pune</option>
                            <option value="Nagpur" {{ $cityDefault == 'Nagpur' ? 'selected' : '' }}>Nagpur</option>
                        @elseif($stateDefault == 'Karnataka')
                            <option value="Bangalore" {{ $cityDefault == 'Bangalore' ? 'selected' : '' }}>Bangalore</option>
                            <option value="Mysore" {{ $cityDefault == 'Mysore' ? 'selected' : '' }}>Mysore</option>
                            <option value="Hubli" {{ $cityDefault == 'Hubli' ? 'selected' : '' }}>Hubli</option>
                        @endif
                    @endif
                </select>
            </div>
        </div>
    @endif
</div>
