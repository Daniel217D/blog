import './styles/index.scss';

import mdRender from "./js/mdRender";
import scrollTopBtn from "./js/scrollTopBtn";
import themeSwitch from "./js/themeSwitch";

addEventListener("DOMContentLoaded", (event) => {
    mdRender();
    scrollTopBtn();
    themeSwitch();
});