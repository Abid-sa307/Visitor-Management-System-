<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Floating Action Button</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
  /* FAB Container */
  .fab-container {
    position: fixed;
    bottom: 30px;
    right: 30px;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
    z-index: 1000;
  }

  /* Individual buttons */
  .fab-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    cursor: pointer;
    transition: transform 0.3s, opacity 0.3s;
    font-size: 24px;
  }

  .fab-phone { background: #25d366; }   /* green */
  .fab-whatsapp { background: #128c7e; } /* darker green */
  .fab-email { background: #ff4c4c; }  /* red */
  .fab-toggle { background: #007bff; } /* blue */

  /* Hide buttons initially */
  .fab-btn:not(.fab-toggle) {
    transform: scale(0);
    opacity: 0;
  }

  /* Show buttons when expanded */
  .fab-container.expanded .fab-btn:not(.fab-toggle) {
    transform: scale(1);
    opacity: 1;
  }
</style>
</head>
<body>

<!-- Floating Action Button -->
<div class="fab-container" id="fabContainer">
  <a href="tel:+918487080659" class="fab-btn fab-phone" title="Call">
    <i class="bi bi-telephone-fill"></i>
  </a>
  
  <!-- WhatsApp FAB opens form modal -->
  <div class="fab-btn fab-whatsapp" title="WhatsApp" data-bs-toggle="modal" data-bs-target="#whatsappModal">
    <i class="bi bi-whatsapp"></i>
  </div>
  
  <a href="mailto:" class="fab-btn fab-email" title="Email">
    <i class="bi bi-envelope-fill"></i>
  </a>
  
  <div class="fab-btn fab-toggle" id="fabToggle">+</div>
</div>

<!-- WhatsApp Form Modal -->
<div class="modal fade" id="whatsappModal" tabindex="-1" aria-labelledby="whatsappModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="whatsappForm">
        <div class="modal-header">
          <h5 class="modal-title" id="whatsappModalLabel">Send WhatsApp Message</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Your Name</label>
            <input type="text" class="form-control" id="name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Company</label>
            <input type="text" class="form-control" id="company">
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone">
          </div>
          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea class="form-control" id="message" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">
            <i class="bi bi-whatsapp"></i> Send on WhatsApp
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap + Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const fabContainer = document.getElementById('fabContainer');
  const fabToggle = document.getElementById('fabToggle');

  fabToggle.addEventListener('click', () => {
    fabContainer.classList.toggle('expanded');
    fabToggle.textContent = fabContainer.classList.contains('expanded') ? '×' : '+';
  });

  // WhatsApp form handling
  document.getElementById("whatsappForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const name = document.getElementById("name").value;
    const company = document.getElementById("company").value;
    const email = document.getElementById("email").value;
    const phone = document.getElementById("phone").value;
    const message = document.getElementById("message").value;

    const whatsappMessage = `Hi, I'm ${name} from ${company}. Here's my inquiry:\n\nEmail: ${email}\nPhone: ${phone}\nMessage: ${message}`;
    const whatsappLink = `https://wa.me/918487080659?text=${encodeURIComponent(whatsappMessage)}`;
    window.open(whatsappLink, "_blank");
  });
</script>

</body>
</html>
