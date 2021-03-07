import SmoothScroll from "smooth-scroll";
import { setNavStyle } from "./components/navScroll";
import "./components/navToggle";
import "./components/contactForm";

new SmoothScroll('a[href*="#"]', {
  offset: function() {
    return 40;
  },
});

window.addEventListener("load", () => {
  setNavStyle();
  window.addEventListener("scroll", setNavStyle);
});
