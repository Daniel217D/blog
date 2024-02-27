import markdownit from "markdown-it/index.mjs";

export default function () {
    const md = markdownit()
    Array.from(document.querySelectorAll('.markdown')).forEach((el) => {
        const html =  md.render(el.innerHTML).replaceAll('h1>', 'h2>').replaceAll('&amp;', '&');
        el.insertAdjacentHTML('afterbegin', html);
        el.style.display = '';
    })
}