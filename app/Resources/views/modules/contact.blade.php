<div class="js-component" id="contact">
  <div class="grid grid-cols-12 text-lg md:text-lg">
    <div class="col-span-12 md:col-span-5 bg-primary px-4 md:px-10 py-12 text-white">
      <div class="flex items-start">
        <div class="w-16"></div>
        <h2 class="mb-10 text-lg md:text-4xl font-bold">CONTACT US</h2>
      </div>
      <div class="flex items-start mb-6">
        <img class="w-12 pr-4" src="app/Resources/images/icons/email-icon.png" alt="">
        <a href="mailto:hello@seasonfinance.com" class="underline">hello@seasonfinance.com</a>
      </div>
      <div class="flex items-start mb-6">
        <img class="w-12 pr-4" src="app/Resources/images/icons/telephone-icon.png" alt="">
        <div>+44 020 3026 1948</div>
      </div>
      <div class="flex items-start mb-6">
        <img class="w-12 pr-4" src="app/Resources/images/icons/location-icon.png" alt="">
        <div>71-75 Shelton Street, Covent Garden, London, WC2H 9JQ United Kingdom</div>
      </div>
    </div>
    <div class="col-span-12 md:col-span-7 px-4 md:px-10 py-12">
      <h2 class="mb-10 text-lg md:text-4xl font-bold text-center">GET IN TOUCH</h2>
      <div class="mb-8">We are always open for a conversation, say hello via the contact form below, we would love to hear from you!</div>
      <form id="contactForm" class="md:grid grid-cols-2 gap-10">
        <input style="border-bottom: 1px solid #8cc7ab" name="first_name" type="text" placeholder="First Name" class="w-full mb-5 md:mb-0 placeholder-black focus:outline-none">
        <input style="border-bottom: 1px solid #8cc7ab" name="last_name" type="text" placeholder="Last Name" class="w-full mb-5 md:mb-0 placeholder-black focus:outline-none">
        <input style="border-bottom: 1px solid #8cc7ab" required name="email" type="email" placeholder="Email" class="w-full mb-5 md:mb-0 placeholder-black focus:outline-none">
        <input style="border-bottom: 1px solid #8cc7ab" name="phone" type="number" placeholder="Phone Number" class="w-full mb-5 md:mb-0 placeholder-black focus:outline-none">
        <div class="col-span-2 flex mb-5 md:mb-0">
          <div class="mr-3">Your Market:</div>
          <div class="flex">
            <div class="flex items-center">
              <label for="UK" class="mr-2">UK</label>
              <input type="checkbox" name="markets[]" value="UK" class="mr-5">
            </div>
            <div class="flex items-center">
              <label for="US" class="mr-2">US</label>
              <input type="checkbox" name="markets[]" value="US" class="mr-5">
            </div>
            <div class="flex items-center">
              <label for="AU" class="mr-2">AU</label>
              <input type="checkbox" name="markets[]" value="AU" class="mr-5">
            </div>
          </div>
        </div>
        <div class="col-span-2">
          <textarea style="border-bottom: 1px solid #8cc7ab" class="w-full focus:outline-none" name="message" rows="1">Message</textarea>
        </div>
        <div class="flex justify-between col-span-2">
          <div id="contactRes" class="text-lg mt-8"> </div>
          <div class="flex-shrink-0 text-right mt-8">
            <button class="rounded-lg px-4 py-3 bg-primary text-white font-semibold">SEND MESSAGE</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>