let lastScrollTop = 0;

export default function () {
    document.addEventListener('scroll', handleScroll)
}

function handleScroll() {
    const st = document.documentElement.scrollTop;

    Array.from(document.querySelectorAll('[data-scroll-show-up], [data-scroll-show-down], [data-scroll-hide-up], [data-scroll-hide-down]'))
        .forEach(el => {
            if (st > lastScrollTop) { //scroll down
                if (el.hasAttribute('data-scroll-show-down')) {
                    el.style.opacity = '1';
                    el.style.pointerEvents = '';
                }

                if (el.hasAttribute('data-scroll-hide-down')) {
                    el.style.opacity = '0';
                    el.style.pointerEvents = 'none';
                }
            }

            if (st < lastScrollTop) { //scroll up
                if (el.hasAttribute('data-scroll-show-up')) {
                    el.style.opacity = '1';
                    el.style.pointerEvents = '';
                }

                if (el.hasAttribute('data-scroll-hide-up')) {
                    el.style.opacity = '0';
                    el.style.pointerEvents = 'none';
                }
            }
        });


    lastScrollTop = st <= 0 ? 0 : st;
}
