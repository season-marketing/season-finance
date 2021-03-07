<div class="js-component -mt-16 lg:-mt-24" id="home">
  <div class="flex items-center bg-hero bg-cover text-white h-screen">
    <div class="max-w-container-xs px-4 mx-auto">
      <div class="h-16 lg:h-24"></div>
      <h1 class="hero-header relative mb-8 md:mb-12 text-3xl md:text-5xl font-hero font-bold">FINANCIAL LEAD GENERATION.</h1>
      <div class="mb-8 md:mb-12 text-lg md:text-lg">With industry recognition, we produce high volumes of top performing personal finance leads and drive outstanding results to our buyers in the UK, USA, and Australia. Through our property network, we generate a constant flow of quality traffic allowing our partners to access millions of pounds of consumer credit applications.</div>
      <div class="mb-8 md:mb-12 text-lg font-semibold orange">WE OPERATE A PROPERTY NETWORK OF 30+ LEADING LOAN AND COMPARISON BRANDS IN DIFFERENT TERRITORIES.</div>
      @include('components.scrollForMore', ['class' => 'text-white', 'link' => '#promise'])
    </div>
  </div>
  <div id="promise" class="py-16 md:py-24">
    <div class="max-w-container-sm px-4 mx-auto">
      <h2 class="mb-8 md:mb-12 text-3xl md:text-5xl font-bold text-center">OUR PROMISE</h2>
      <p class="text-lg md:text-lg mb-5">We are passionate about connecting customers with credit solutions, and our buying platform is filled with best class leads.</p>
      <p class="text-lg md:text-lg mb-5">At Season Finance we protect our buyers assuring a fair treatment of our users first. We are fully FCA licensed and GDPR compliant.</p>
      <p class="text-lg md:text-lg mb-5">We develop long-term trustworthy relationships with our partners and are available just one message or phone call away. All our systems are coded in-house so we can provide bespoke services and fix issues in no time.</p>
      <div class="md:w-1/2 my-10 ml-auto">
        @include('components.getInTouch')
      </div>
      @include('components.scrollForMore', ['class' => 'text-black', 'link' => '#services'])
    </div>
  </div>
</div>