<header class="flex items-center sticky top-0 z-40 h-16 bg-black text-white">
  <div class="container px-4 mx-auto">
    <div class="flex justify-between">
      <div class="">
        logo
        {{-- <img src="app/Resources/images/logo.png" alt=""> --}}
      </div>
      <nav>
        <button id="navToggle" class="md:hidden focus:outline-none">
          <img src="app/Resources/images/icons/menu-icon.png" alt="">
        </button>
        <ul class="hidden absolute md:relative z-40 top-0 left-0 md:flex flex-col md:flex-row w-full md:w-auto mt-16 md:mt-0 px-4 py-4 md:py-0 bg-black text-xl">
          <li class="md:mx-6 my-2 md:my-0 font-medium">
            <a href="#home" class="relative">HOME</a>
          </li>
          <li class="md:mx-6 my-2 md:my-0 font-medium">
            <a href="#services" class="relative">SERVICES</a>
          </li>
          <li class="md:mx-6 my-2 md:my-0 font-medium">
            <a href="#about" class="relative">ABOUT</a>
          </li>
          <li class="md:mx-6 my-2 md:my-0 font-medium">
            <a href="#contact" class="relative">CONTACT</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</header>