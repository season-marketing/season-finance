import SmoothScroll from "smooth-scroll";
import { setNavStyle } from './components/navScroll';
import './components/navToggle';

new SmoothScroll('a[href*="#"]', {
  offset: function() {
    return 40;
  },
});

window.addEventListener('load', () => {
  setNavStyle();
  window.addEventListener('scroll', setNavStyle)
})