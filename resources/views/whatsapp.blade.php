{{-- WhatsApp Floating Button --}}
<div id="whatsappBtn" title="Chat on WhatsApp" aria-label="Open WhatsApp contact form">
  <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
</div>

{{-- Dark Overlay --}}
<div id="waOverlay"></div>

{{-- Contact Form Popup --}}
<div id="formPopup" role="dialog" aria-modal="true">
  <div class="wa-header">
    <h3>Contact Us</h3>
    <button class="wa-close-icon" id="waCloseIcon" aria-label="Close">&times;</button>
  </div>

  <form id="contactForm">
    <label class="wa-label">Name:</label>
    <input class="wa-input" type="text" name="name" required placeholder="Your Name">

    <label class="wa-label">Email:</label>
    <input class="wa-input" type="email" name="email" required placeholder="Your Email">

    <label class="wa-label">Phone Number:</label>
    <input class="wa-input" type="tel" name="phone" required placeholder="Your Phone Number">

    <label class="wa-label">Company Name:</label>
    <input class="wa-input" type="text" name="company" required placeholder="Your Company Name">

    <label className="wa-label">Country:</label>
    <input class="wa-input" type="text" name="country" required placeholder="Your Country">

    <label class="wa-label">Location/City:</label>
    <input class="wa-input" type="text" name="location" required placeholder="Your City or Location">

    <label class="wa-label">Message:</label>
    <textarea class="wa-textarea" name="message" required placeholder="Your Message"></textarea>

    <button type="submit" class="submit-button">Send Message</button>
    <button type="button" class="close-button" id="closeForm">Close</button>
  </form>
</div>

{{-- Styles for WhatsApp Button + Form --}}
<style>
  /* Optional: blur wrapper if you have #pageWrapper or .wrapper */
  .wrapper.blurred,
  #pageWrapper.blurred {
    filter: blur(5px);
    pointer-events: none;
  }

  /* Floating WhatsApp Button */
  #whatsappBtn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: transparent;
    border-radius: 50%;
    padding: 0;
    z-index: 9999;
    cursor: pointer;
  }

  #whatsappBtn img {
    width: 65px;
    height: 65px;
    border-radius: 50%;
    background-color: #25d366;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    transition: transform 0.2s ease;
  }

  #whatsappBtn:hover img {
    transform: scale(1.05);
  }

  /* Dark Overlay */
  #waOverlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 9998;
  }

  /* Popup */
  #formPopup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #ffffff;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
    z-index: 9999;
    width: 100%;
    max-width: 500px;
    display: none;
    animation: fadeIn 0.3s ease-out;
    overflow-y: auto;
    max-height: 85vh;
  }

  .wa-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 12px;
    padding-bottom: 8px;
  }

  .wa-header h3 {
    margin: 0;
    color: #128C7E;
    font-size: 20px;
    font-weight: bold;
  }

  .wa-close-icon {
    background: transparent;
    border: none;
    font-size: 26px;
    color: #555;
    cursor: pointer;
    line-height: 1;
  }

  @keyframes fadeIn {
    0% { opacity: 0; transform: translate(-50%, -40%); }
    100% { opacity: 1; transform: translate(-50%, -50%); }
  }

  #formPopup form {
    display: flex;
    flex-direction: column;
  }

  .wa-label {
    font-weight: 700;
    font-size: 14px;
    color: #0f172a;
    margin-bottom: 6px;
  }

  .wa-input,
  .wa-textarea {
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    font-size: 15px;
    margin-bottom: 12px;
    transition: border 0.3s ease, box-shadow 0.3s ease;
    width: 100%;
    background: #ffffff;
    color: #0f172a;
    box-shadow: inset 0 1px 0 rgba(0,0,0,.02);
  }

  .wa-input::placeholder,
  .wa-textarea::placeholder {
    color: #64748b;
    opacity: 1;
  }

  .wa-input:focus,
  .wa-textarea:focus {
    border: 1px solid #25D366;
    outline: none;
    box-shadow: 0 0 0 3px rgba(37,214,102,.25);
  }

  .wa-textarea {
    resize: vertical;
    min-height: 90px;
  }

  .submit-button {
    padding: 12px;
    background-color: #25D366;
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 16px;
    cursor: pointer;
    margin-bottom: 10px;
    transition: background-color 0.3s ease, transform 0.1s ease;
  }

  .submit-button:hover {
    background-color: #128C7E;
    transform: translateY(-1px);
  }

  .close-button {
    padding: 10px;
    background-color: #ff4d4d;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
    font-size: 15px;
    transition: background-color 0.3s ease;
  }

  .close-button:hover {
    background-color: #e60000;
  }

  @media (max-width: 600px) {
    #formPopup {
      width: 90%;
      padding: 20px;
      max-height: 85vh;
    }
  }
</style>

{{-- Script --}}
<script>
  (function () {
    const whatsappBtn = document.getElementById("whatsappBtn");
    const formPopup = document.getElementById("formPopup");
    const waOverlay = document.getElementById("waOverlay");
    const pageWrapper = document.getElementById("pageWrapper") || document.querySelector(".wrapper");
    const closeForm = document.getElementById("closeForm");
    const closeIcon = document.getElementById("waCloseIcon");
    const contactForm = document.getElementById("contactForm");

    function openForm() {
      formPopup.style.display = "block";
      waOverlay.style.display = "block";
      if (pageWrapper) pageWrapper.classList.add("blurred");
    }

    function closeFormPopup() {
      formPopup.style.display = "none";
      waOverlay.style.display = "none";
      if (pageWrapper) pageWrapper.classList.remove("blurred");
    }

    // 🔁 TOGGLE: icon pe click → open/close
    whatsappBtn.addEventListener("click", function () {
      if (formPopup.style.display === "block") {
        closeFormPopup();
      } else {
        openForm();
      }
    });

    // Close buttons
    closeForm.addEventListener("click", closeFormPopup);
    closeIcon.addEventListener("click", closeFormPopup);
    waOverlay.addEventListener("click", closeFormPopup);

    // ESC key → close
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape") {
        closeFormPopup();
      }
    });

    // Click outside popup → close
    document.addEventListener("mousedown", function (event) {
      if (
        formPopup.style.display === "block" &&
        !formPopup.contains(event.target) &&
        !whatsappBtn.contains(event.target)
      ) {
        closeFormPopup();
      }
    });

    // Form submit → open WhatsApp chat with filled details
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const name = contactForm.name.value.trim();
      const email = contactForm.email.value.trim();
      const phone = contactForm.phone.value.trim();
      const company = contactForm.company.value.trim();
      const country = contactForm.country.value.trim();
      const location = contactForm.location.value.trim();
      const message = contactForm.message.value.trim();

      const whatsappMessage =
        `Hi, I'm ${name} from ${company}, ${location}, ${country}.\n\n` +
        `Email: ${email}\n` +
        `Phone: ${phone}\n` +
        `Message: ${message}`;

      const whatsappLink =
        "https://wa.me/917600907288?text=" + encodeURIComponent(whatsappMessage);

      window.open(whatsappLink, "_blank");

      contactForm.reset();
      closeFormPopup();
    });
  })();
</script>
