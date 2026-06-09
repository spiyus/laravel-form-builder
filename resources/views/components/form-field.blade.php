@props(['type', 'alpine' => false])

@switch($type)
    @case('text')
        <x-form-fields.text-input :alpine="$alpine" {{ $attributes }} />
        @break
    @case('textarea')
        <x-form-fields.text-area :alpine="$alpine" {{ $attributes }} />
        @break
    @case('number')
        <x-form-fields.number-input :alpine="$alpine" {{ $attributes }} />
        @break
    @case('email')
        <x-form-fields.email-input :alpine="$alpine" {{ $attributes }} />
        @break
    @case('phone')
        <x-form-fields.phone-input :alpine="$alpine" {{ $attributes }} />
        @break
    @case('dropdown')
        <x-form-fields.dropdown :alpine="$alpine" {{ $attributes }} />
        @break
    @case('radio')
        <x-form-fields.radio-buttons :alpine="$alpine" {{ $attributes }} />
        @break
    @case('checkbox')
        <x-form-fields.checkboxes :alpine="$alpine" {{ $attributes }} />
        @break
    @case('date')
        <x-form-fields.date-picker :alpine="$alpine" {{ $attributes }} />
        @break
    @case('file')
        <x-form-fields.file-upload :alpine="$alpine" {{ $attributes }} />
        @break
    @case('title')
        <x-form-fields.title :alpine="$alpine" {{ $attributes }} />
        @break
    @case('description')
        <x-form-fields.description :alpine="$alpine" {{ $attributes }} />
        @break
    @case('newline')
        <x-form-fields.new-line :alpine="$alpine" {{ $attributes }} />
        @break
    @case('pagebreak')
        <x-form-fields.page-break :alpine="$alpine" {{ $attributes }} />
        @break
    @case('hidden')
        <x-form-fields.hidden-field :alpine="$alpine" {{ $attributes }} />
        @break
    @case('state')
        <x-form-fields.state :alpine="$alpine" {{ $attributes }} />
        @break
    @case('city')
        <x-form-fields.city :alpine="$alpine" {{ $attributes }} />
        @break
    @case('state_city')
        <x-form-fields.state-city-combined :alpine="$alpine" {{ $attributes }} />
        @break
@endswitch
