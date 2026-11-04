@php
    $class ??= null;
    $name ??= '';
    $value ??= '';
    $label ??= ucfirst($name);
@endphp

<div @class(["col-12 col-lg-6 select", $class])>
    <label for="{{ $name }}">{{ $label }}</label>
    <select class="form-select" name="{{ $name }}" id="{{ $name }}">
        <option selected value="">Choisir Stock</option>
        @foreach ($produits as $k => $v)
        <option value="{{ $k }}">{{ $v }}</option>
        @endforeach
    </select>

    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>