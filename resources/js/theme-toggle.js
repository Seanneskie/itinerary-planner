document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('button[id^="theme-toggle-"]').forEach((button) => {
        const thumb = document.getElementById(`${button.id}-thumb`);
        if (!thumb) return;

        const applyTheme = (isDark) => {
            document.documentElement.classList.toggle('dark', isDark);
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            thumb.classList.toggle('translate-x-6', isDark);
        };

        let isDark = localStorage.getItem('theme') === 'dark';
        applyTheme(isDark);

        button.addEventListener('click', () => {
            isDark = !isDark;
            applyTheme(isDark);
            document.dispatchEvent(new Event('theme-changed'));
        });
    });
});
