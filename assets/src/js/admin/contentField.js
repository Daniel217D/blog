import markdownit from "markdown-it/index.mjs";

const md = markdownit({
    breaks: true
})

export default () => {
    const markdownField = document.querySelector('#content');
    const htmlIframe = document.querySelector('#contentRenderer');

    if (!markdownField || !htmlIframe) {
        return;
    }

    htmlIframe.contentDocument.head.innerHTML = `
    <link rel="stylesheet" href="${window.adminJsConfig.jsPath}">
`;
    htmlIframe.contentDocument.body.setAttribute('data-bs-theme', "dark");
    htmlIframe.contentDocument.body.style.padding = '.75rem';

    const render = () => htmlIframe.contentDocument.body.innerHTML = md.render(markdownField.value);

    render();
    markdownField.addEventListener('input', () => render());

    document.querySelector('#contentToggleView').addEventListener('click', function (e) {
        e.preventDefault();

        htmlIframe.style.display = htmlIframe.style.display === 'none' ? '' : 'none';
    })
}