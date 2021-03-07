<div class="js-component" id="services">
  <div class="bg-black text-white py-16 md:py-24">
    <div class="max-w-container-sm px-4 mx-auto text-lg md:text-lg">
      <p class="mb-8">We dominate the paid search landscape, create simple websites, optimise landing pages, produce fast converting application forms, customise customer journeys, generate campaigns and, much more!</p>
      <p class="mb-8">Gravitating around innovation and flexibility, we are constantly working to stay one-step ahead in the industry through the development of new marketing tactics and technologies. </p>
      <p class="mb-8">Our primary focus is to provide the best experience to our customers and ensure quality and compliance first. </p>
      <p class="mb-12 text-center font-bold orange">THROUGH OUR 30+ BRANDS WE GENERATE TONS OF LEADS BY TIRELESSLY IMPROVING OUR CUSTOMER EXPERIENCE TO GIVE OUR BUYERS ACCESS TO A WHOLE WORLD OF PURCHASE.</p>
      <div class="flex items-center -mx-4 mb-10">
        <img class="w-1/5 px-4" src="app/Resources/images/brand-logos/superpoundsloans_grey.png" alt="">
        <img class="w-1/5 px-4" src="app/Resources/images/brand-logos/swishloans_grey.png" alt="">
        <img class="w-1/5 px-4" src="app/Resources/images/brand-logos/loankite_grey.png" alt="">
        <img class="w-1/5 px-4" src="app/Resources/images/brand-logos/loanskipper_grey.png" alt="">
        <img class="w-1/5 px-4" src="app/Resources/images/brand-logos/loankite_grey.png" alt="">
      </div>
      @include('components.scrollForMore', ['class' => 'text-white', 'link' => '#lenders'])
    </div>
  </div>
  <div id="lenders" class="py-16 md:py-24">
    <div class="max-w-container-sm px-4 mx-auto text-lg md:text-lg bg-map bg-hero bg-contain bg-no-repeat">
      <div class="mb-8 md:mb-28">At Season Finance we deliver a technically advanced and flexible service to our partners in the UK, USA, and Australia.</div>
      <div class="">
        <div class="mb-3">We work with:</div>
        <ul class="list-disc pl-6 mb-3">
          <li>Lenders</li>
          <li>Credit Providers</li>
          <li>Brokers</li>
          <li>Comparison Sites</li>
          <li>Others</li>
        </ul>
        <div>Our ind</div>
      </div>
      <div class="md:w-1/2 my-10 ml-auto">
        @include('components.getInTouch')
      </div>
      @include('components.scrollForMore', ['class' => 'text-black', 'link' => '#about'])
    </div>
  </div>
</div>