@props(['variant' => 'primary', 'type' => 'button', 'size' => ''])

@php
$sizeClass = $size === 'sm' ? 'btn-sm' : ($size === 'lg' ? 'btn-lg' : '');
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "btn btn-{$variant} $sizeClass"]) }}>
  {{ $slot }}
</button>
