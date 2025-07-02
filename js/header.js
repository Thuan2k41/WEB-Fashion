document.addEventListener("DOMContentLoaded", () => {
  // Lấy tất cả các link trong navbar và dropdown
  const navLinks = document.querySelectorAll(
    ".navbar-nav .nav-link, .dropdown-item"
  );

  // Kiểm tra URL hiện tại và thêm class "active" vào link tương ứng
  navLinks.forEach((link) => {
    if (link.href === window.location.href) {
      // Nếu là dropdown-item, thêm class "active" cho mục cha
      const parent = link.closest(".dropdown");
      if (parent) {
        const parentLink = parent.querySelector(".nav-link");
        if (parentLink) parentLink.classList.add("active");
      } else {
        // Nếu không phải dropdown-item, thêm class "active" trực tiếp
        link.classList.add("active");
      }
    }
  });
});
