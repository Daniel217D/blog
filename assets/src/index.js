import './styles/index.scss';

import mdRender from "./js/mdRender";
import scrollTopBtn from "./js/scrollTopBtn";
import themeSwitch from "./js/themeSwitch";
import highlight from "./js/highlight";

addEventListener("DOMContentLoaded", (event) => {
    mdRender();
    scrollTopBtn();
    themeSwitch();
    highlight()
});