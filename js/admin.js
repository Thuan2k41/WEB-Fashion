function showTab(id, clickedElement) {
  // Ẩn tất cả các tab nội dung
  document.querySelectorAll(".tab-pane").forEach((el) => {
    el.classList.remove("active");
  });

  // Ẩn trạng thái active ở menu
  document.querySelectorAll(".menu-item").forEach((el) => {
    el.classList.remove("active");
  });

  // Hiện tab được chọn
  const tab = document.getElementById(id);
  if (tab) {
    tab.classList.add("active");
  }

  // Thêm class active cho menu đang click
  if (clickedElement) {
    clickedElement.classList.add("active");
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const editButtons = document.querySelectorAll(".edit-customer");
  const deleteButtons = document.querySelectorAll(".delete-customer");
  const editCustomerModal = new bootstrap.Modal(
    document.getElementById("editCustomerModal")
  );

  // Xử lý nút "Sửa"
  editButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const id = button.getAttribute("data-id");
      const name = button.getAttribute("data-name");
      const email = button.getAttribute("data-email");
      const phone = button.getAttribute("data-phone");

      // Điền thông tin vào modal
      document.getElementById("editCustomerId").value = id;
      document.getElementById("editCustomerName").value = name;
      document.getElementById("editCustomerEmail").value = email;
      document.getElementById("editCustomerPhone").value = phone;

      // Hiển thị modal
      editCustomerModal.show();
    });
  });

  // Xử lý nút "Xóa"
  deleteButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const customerId = button.getAttribute("data-id");

      if (confirm("Bạn có chắc chắn muốn xóa khách hàng này?")) {
        fetch("../handlers/admin_customer.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: new URLSearchParams({
            action: "delete",
            id: customerId,
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.status === "success") {
              alert("Khách hàng đã được xóa.");
              location.reload();
            } else {
              alert(data.message || "Có lỗi xảy ra.");
            }
          })
          .catch((error) => console.error("Lỗi:", error));
      }
    });
  });
});
// sua san pham
document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".edit-product");

  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.getAttribute("data-id");
      const name = this.getAttribute("data-name");
      const price = this.getAttribute("data-price");
      const quantity = this.getAttribute("data-quantity");
      const subcategory = this.getAttribute("data-subcategory");
      const description = this.getAttribute("data-description");
      const tag = this.getAttribute("data-tag");

      // Điền dữ liệu vào modal
      document.getElementById("editProductId").value = id;
      document.getElementById("editProductName").value = name;
      document.getElementById("editProductPrice").value = price;
      document.getElementById("editProductQuantity").value = quantity;
      document.getElementById("editProductSubcategory").value = subcategory;
      document.getElementById("editProductDescription").value = description;
      document.getElementById("editProductTag").value = tag;
    });
  });
});
