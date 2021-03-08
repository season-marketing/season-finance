window.addEventListener('load', () => {
  const height = document.querySelector('header').offsetHeight;

  const setNavStyle = () => {
    const components = Array.from(document.querySelectorAll('.js-component'))
    const navs = Array.from(document.querySelectorAll('nav li a'));
    let currentNav = null;
    const scrollTop = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop);
    const item = components.filter((component) => {
      const elScrollTop = component.getBoundingClientRect().top + scrollTop;
      const elScrollBottom = component.getBoundingClientRect().bottom + scrollTop;

      if (scrollTop < 60 && elScrollTop < 100) return true;
      if (scrollTop > elScrollTop - height && scrollTop < elScrollBottom - height) return true;
      if (scrollTop === elScrollTop - height) return true;
    });

    if (item) {
      const newNav = document.querySelector(`nav a[href="#${item[0].id}"]`);
      if (newNav !== currentNav) {
        currentNav = newNav;
        navs.forEach((nav) => nav.classList.remove("isActive"));
        currentNav.classList.add("isActive");
      }
    }
  };


  setNavStyle();
  window.addEventListener("scroll", setNavStyle);
})
