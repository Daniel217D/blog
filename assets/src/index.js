import './styles/index.scss';

import mdRender from "./mdRender";
import themes from "./themes";

addEventListener("DOMContentLoaded", (event) => {
    mdRender();
    themes()
});