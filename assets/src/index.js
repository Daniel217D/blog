import './styles/index.scss';

import mdRender from "./js/mdRender";
import themes from "./js/themes";

addEventListener("DOMContentLoaded", (event) => {
    mdRender();
    themes()
});