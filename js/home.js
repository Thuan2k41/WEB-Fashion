// product new arriver-------------------------
document.addEventListener("DOMContentLoaded", () => {
  const links = document.querySelectorAll(".tab-link");
  const linkss = document.querySelectorAll(".tab-linkk");

  // Tải sản phẩm mặc định (New và Sale)
  fetchProducts(2, "new", "product-new"); // Tải sản phẩm New
  fetchProducts(1, "sale", "product-sale"); // Tải sản phẩm Sale

  // Xử lý tab New Arrival
  links.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      links.forEach((l) => l.classList.remove("active"));
      this.classList.add("active");

      const categoryId = this.dataset.category; // Lấy ID category
      fetchProducts(categoryId, "new", "product-new");
    });
  });

  // Xử lý tab Sale
  linkss.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      linkss.forEach((l) => l.classList.remove("active"));
      this.classList.add("active");
      const categoryId = this.dataset.category; // Lấy ID category
      fetchProducts(categoryId, "sale", "product-sale");
    });
  });

  function fetchProducts(categoryId, tag, containerId) {
    fetch(`get_products_by_category.php?category=${categoryId}&tag=${tag}`)
      .then((response) => response.text())
      .then((html) => {
        document.getElementById(containerId).innerHTML = html;
      })
      .catch((error) => {
        console.error("Error:", error);
        document.getElementById(containerId).innerHTML =
          '<p class="text-danger">Không thể tải sản phẩm.</p>';
      });
  }
  // Hiển thị hoặc ẩn cửa sổ chat
  const chatIcon = document.getElementById("chat-icon");
  const chatWindow = document.getElementById("chat-window");
  const closeChat = document.getElementById("close-chat");

  chatIcon.onclick = function () {
    chatWindow.style.display = "block"; // Hiển thị cửa sổ chat
  };

  closeChat.onclick = function () {
    chatWindow.style.display = "none"; // Ẩn cửa sổ chat
  };

  // WebSocket logic
  const chat = document.getElementById("chat");
  const message = document.getElementById("message");
  const send = document.getElementById("send");

  const ws = new WebSocket("ws://localhost:8081/chat");

  ws.onmessage = function (event) {
    const msg = document.createElement("div");
    msg.textContent = event.data;
    chat.appendChild(msg);
    chat.scrollTop = chat.scrollHeight;
  };

  send.onclick = function () {
    if (message.value.trim() !== "") {
      // Hiển thị tin nhắn của chính mình ngay lập tức
      const msg = document.createElement("div");
      msg.textContent = "You: " + message.value;
      chat.appendChild(msg);
      chat.scrollTop = chat.scrollHeight;

      // Gửi tin nhắn đến server
      ws.send(message.value);
      message.value = "";
    }
  };
});
