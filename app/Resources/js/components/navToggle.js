window.addEventListener('load', () => {
  const toggle = document.getElementById('navToggle');
  const nav = document.querySelector('header nav ul');

  toggle.addEventListener('click', () => {
    if (nav.classList.contains('hidden')) {
      nav.classList.remove('hidden')
    } else {
      nav.classList.add('hidden')
    }
  })

  document.addEventListener('click', e => {
    const isNav = nav.contains(e.target) || toggle.contains(e.target);

    if (!isNav && !nav.classList.contains('hidden')) {
      nav.classList.add('hidden')
    }
  })
})