<div x-data="{ show: true }"
     x-init="setTimeout(() => show = false, 3000)"
     x-show="show"
     x-transition
     {{ $attributes->class('fixed top-4 right-4 z-50 px-4 py-2 rounded shadow text-white') }}>
    {{ $slot }}
</div>
