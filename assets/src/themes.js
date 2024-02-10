export default function () {
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        setTheme('dark');
    } else {
        setTheme('light');
    }
}

const setTheme = (theme) => {
    document.body.setAttribute('data-bs-theme', theme);
}