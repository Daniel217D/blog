import markdownit from "markdown-it/index.mjs";

export default function () {
    const md = markdownit({
        breaks: true
    })
    Array.from(document.querySelectorAll('.markdown')).forEach((el) => {
        el.innerHTML = md.render(el.innerHTML).replaceAll('h1>', 'h2>').replaceAll('&amp;', '&');
        el.style.display = '';
    })
}