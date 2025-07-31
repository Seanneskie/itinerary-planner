<button
    id="theme-toggle"
    class="absolute top-6 right-6 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white px-3 py-2 rounded-md shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition"
    aria-label="Toggle Dark Mode"
>
    <span id="theme-toggle-text">🌞 Light</span>
</button>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('theme-toggle');
        const text = document.getElementById('theme-toggle-text');

        // Apply saved theme
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
            text.textContent = '🌙 Dark';
        }

        button.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            text.textContent = isDark ? '🌙 Dark' : '🌞 Light';
        });
    });
</script>
