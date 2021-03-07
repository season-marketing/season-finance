import SmoothScroll from "smooth-scroll";
import "./components/navScroll";
import "./components/navToggle";
import "./components/contactForm";

new SmoothScroll('a[href*="#"]', {
  offset: function() {
    return 40;
  },
});