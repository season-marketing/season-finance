window.addEventListener("load", () => {
  const postContactForm = (params) => {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if (this.responseText === "1") {
          document.getElementById("contactRes").innerText = "Thankyou, your message has been sent";
          document.getElementById("contactRes").classList.add("text-green-600");
          document.getElementById("contactRes").classList.remove("text-red-600");
          document.getElementById("contactForm").reset();
        } else {
          document.getElementById("contactRes").classList.add("text-red-600");
          document.getElementById("contactRes").classList.remove("text-green-600");
          document.getElementById("contactRes").innerText = "Sorry, your message could not be sent";
        }
      }
    };
    xmlhttp.open("GET", `Contact.php?${params}`, true);
    xmlhttp.send();
  };

  document.getElementById("contactForm").addEventListener("submit", (e) => {
    e.preventDefault();

    const form = e.target;

    const markets = Array.from(form.elements["markets[]"]).filter((item) => item.checked);

    const params = [
      ["firstName", form.elements.first_name.value],
      ["lastName", form.elements.last_name.value],
      ["phone", form.elements.phone.value],
      ["email", form.elements.email.value],
      ["markets", markets.map((item) => item.value).join(",")],
      ["message", form.elements.message.value],
    ]
      .map(([key, value]) => `${key}=${value}`)
      .join("&");

    postContactForm(params);
  });
});
