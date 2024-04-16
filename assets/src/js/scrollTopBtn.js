export default function () {
    document.addEventListener('click', function (e) {
        const target = e.target;

        if (!target.hasAttribute('data-scroll-top')) {
            return;
        }

        document.documentElement.scrollTo({
            top: 0,
            behavior: "smooth"
        })
    })

    document.addEventListener('scroll', handleScroll)
    handleScroll()
}

function handleScroll() {
    const st = document.documentElement.scrollTop;

    Array.from(document.querySelectorAll('[data-scroll-top]'))
        .forEach(el => {
            if (st === 0) {
                el.style.opacity = '0';
                el.style.pointerEvents = 'none';
            } else {
                el.style.opacity = '1';
                el.style.pointerEvents = '';
            }
        })
}