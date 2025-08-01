@php
    $toggleId = 'theme-toggle-' . uniqid();
    $thumbId = $toggleId . '-thumb';
@endphp
<button id="{{ $toggleId }}" aria-label="Toggle Dark Mode"
    class="relative inline-flex h-8 w-14 items-center rounded-full bg-gray-300 dark:bg-gray-700 transition-colors focus:outline-none">
    <span class="absolute left-1 text-yellow-500 text-sm">🌞</span>
    <span class="absolute right-1 text-gray-200 text-sm">🌙</span>
    <span id="{{ $thumbId }}" class="inline-block h-6 w-6 transform rounded-full bg-white shadow transition"></span>
</button>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById(@js($toggleId));
        const thumb = document.getElementById(@js($thumbId));

        function applyTheme(isDark) {
            document.documentElement.classList.toggle('dark', isDark);
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            thumb.classList.toggle('translate-x-6', isDark);
        }

        let isDark = localStorage.getItem('theme') === 'dark';
        applyTheme(isDark);

        button.addEventListener('click', () => {
            isDark = !isDark;
            applyTheme(isDark);
            document.dispatchEvent(new Event('theme-changed'));
        });
    });
</script>
