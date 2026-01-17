@props([
    'id' => '',
    'name' => '',
    'label' => '',
    'options' => [],
    'selected' => [],
    'disabled' => false,
    'placeholder' => 'Select options'
])

<div class="col-lg-2 col-md-6">
    <label class="form-label">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $id }}" class="form-select" {{ $disabled ? 'disabled' : '' }}>
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ in_array($value, (array)$selected) ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>
</div>
