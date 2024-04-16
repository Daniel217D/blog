export default function () {
    document.addEventListener('click', function (e) {
        const target = e.target;

        if (!target.hasAttribute('data-theme-switch')) {
            return;
        }

        window.setSiteTheme(localStorage.getItem('theme') === 'dark' ? 'light' : 'dark');
    })
}