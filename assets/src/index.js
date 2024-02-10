import './styles/index.scss';

import markdownit from 'markdown-it'

addEventListener("DOMContentLoaded", (event) => {
    const md = markdownit()
    Array.from(document.querySelectorAll('.markdown')).forEach((el) => {
        el.innerHTML = md.render(el.innerHTML).replaceAll('h1>', 'h2>');
    })
});