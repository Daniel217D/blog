import hljs from 'highlight.js/lib/core';

import php from 'highlight.js/lib/languages/php';

export default () => {
    hljs.registerLanguage('php', php);

    hljs.highlightAll();
}