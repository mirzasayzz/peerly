<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary', 'style' => 'height: 44px; font-size: 14px;']) }}>
    {{ $slot }}
</button>

