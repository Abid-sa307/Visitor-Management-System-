
{{-- WhatsApp Floating Button --}}
<div id="whatsappBtn">
  <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
</div>

{{-- Contact Form Popup --}}
<div id="formPopup">
  <h3>Contact Us</h3>
  <form id="contactForm">
    <label>Name:</label>
    <input type="text" name="name" required placeholder="Your Name">

    <label>Email:</label>
    <input type="email" name="email" required placeholder="Your Email">

    <label>Phone Number:</label>
    <input type="tel" name="phone" required placeholder="Your Phone Number">

    <label>Company Name:</label>
    <input type="text" name="company" required placeholder="Your Company Name">

    <label>Country:</label>
    <input type="text" name="country" required placeholder="Your Country">

    <label>Location/City:</label>
    <input type="text" name="location" required placeholder="Your City or Location">

    <label>Message:</label>
    <textarea name="message" required placeholder="Your Message"></textarea>

    <button type="submit" class="submit-button">Send Message</button>
  </form>
  <button class="close-button" id="closeForm">Close</button>
</div>

{{-- Styles for WhatsApp Button + Form --}}
<style>
  .wrapper.blurred { filter: blur(5px); pointer-events: none; }

  #formPopup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    z-index: 9998;
    width: 100%;
    max-width: 500px;
    display: none;
    animation: fadeIn 0.3s ease-out;
    overflow-y: auto;
    max-height: 90vh;
  }

  @keyframes fadeIn {
    0% { opacity: 0; transform: translate(-50%, -40%); }
    100% { opacity: 1; transform: translate(-50%, -50%); }
  }

  #formPopup h3 {
    text-align: center;
    margin-bottom: 10px;
    color: #128C7E;
  }

  #formPopup form {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  #formPopup label {
    font-weight: 600;
    font-size: 14px;
    color: #333;
  }

  #formPopup input,
  #formPopup textarea {
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 15px;
    transition: border 0.3s ease;
    width: 100%;
  }

  #formPopup input:focus,
  #formPopup textarea:focus {
    border: 1px solid #25D366;
    outline: none;
  }

  #formPopup textarea {
    resize: vertical;
    min-height: 100px;
  }

  .submit-button {
    padding: 12px;
    background-color: #25D366;
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .submit-button:hover {
    background-color: #128C7E;
  }

  .close-button {
    padding: 10px;
    background-color: #ff4d4d;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
    margin-top: 8px;
    transition: background-color 0.3s ease;
  }

  .close-button:hover {
    background-color: #e60000;
  }

  #whatsappBtn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #25D366;
    border-radius: 50%;
    padding: 15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    z-index: 9999;
    transition: transform 0.3s ease;
    cursor: pointer;
  }

  #whatsappBtn img {
    width: 35px;
    height: 35px;
  }

  #whatsappBtn:hover {
    transform: scale(1.1);
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
  const whatsappBtn = document.getElementById("whatsappBtn");
  const formPopup = document.getElementById("formPopup");
  const pageWrapper = document.getElementById("pageWrapper");
  const closeForm = document.getElementById("closeForm");
  const contactForm = document.getElementById("contactForm");

  whatsappBtn.addEventListener("click", () => {
    formPopup.style.display = "block";
    if (pageWrapper) pageWrapper.classList.add("blurred");
  });

  closeForm.addEventListener("click", () => {
    formPopup.style.display = "none";
    if (pageWrapper) pageWrapper.classList.remove("blurred");
  });

  contactForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const name = contactForm.name.value;
    const email = contactForm.email.value;
    const phone = contactForm.phone.value;
    const company = contactForm.company.value;
    const country = contactForm.country.value;
    const location = contactForm.location.value;
    const message = contactForm.message.value;

    const whatsappMessage = `Hi, I'm ${name} from ${company}, ${location}, ${country}.\n\nEmail: ${email}\nPhone: ${phone}\nMessage: ${message}`;
    const whatsappLink = `https://wa.me/+917600907288?text=${encodeURIComponent(whatsappMessage)}`;

    window.location.href = whatsappLink;
    formPopup.style.display = "none";
    if (pageWrapper) pageWrapper.classList.remove("blurred");
  });
</script>

