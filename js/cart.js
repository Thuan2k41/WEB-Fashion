document.addEventListener("DOMContentLoaded", () => {
  // Lấy tất cả các phần tử cần thiết
  const productCheckboxes = document.querySelectorAll(".product-checkbox");
  const deleteButtons = document.querySelectorAll(".delete-product");
  const subtotalElement = document.getElementById("subtotal");
  const totalPriceElement = document.getElementById("total-price");
  const checkoutButton = document.getElementById("checkout-button");
  const useVoucherButtons = document.querySelectorAll(".use-voucher");
  const voucherInput = document.querySelector("input[name='voucher_code']");

  // Hàm tính tổng tiền (nếu cần cập nhật giao diện khi chọn/bỏ chọn sản phẩm)
  const calculateTotal = () => {
    let subtotal = 0;
    productCheckboxes.forEach((checkbox) => {
      if (checkbox.checked) {
        const price = parseInt(checkbox.getAttribute("data-price"), 10);
        const quantity =
          parseInt(checkbox.getAttribute("data-quantity"), 10) || 1;
        if (!isNaN(price) && !isNaN(quantity)) {
          subtotal += price * quantity;
        }
      }
    });

    const formattedTotal = subtotal.toLocaleString("vi-VN") + "₫";
    if (subtotalElement) subtotalElement.textContent = formattedTotal;
    if (totalPriceElement) totalPriceElement.textContent = formattedTotal;
    if (checkoutButton) checkoutButton.disabled = subtotal === 0;
  };

  // Lắng nghe sự kiện thay đổi trên các checkbox
  productCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", calculateTotal);
    checkbox.checked = true;
  });

  // Gọi hàm tính tổng ban đầu khi trang được tải
  calculateTotal();

  // Hàm xóa sản phẩm
  const deleteProduct = (productId, sizeId) => {
    fetch(`../handlers/delete_from_cart.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        product_id: productId,
        size_id: sizeId,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          location.reload(); // Tải lại trang để cập nhật giỏ hàng
        } else {
          alert(data.message || "Không thể xóa sản phẩm. Vui lòng thử lại.");
        }
      })
      .catch((error) => console.error("Error:", error));
  };

  // Lắng nghe sự kiện click trên nút xóa
  deleteButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const productId = button.getAttribute("data-product-id");
      const sizeId = button.getAttribute("data-size-id");
      deleteProduct(productId, sizeId);
    });
  });

  // Gán mã giảm giá khi nhấn nút "Sử dụng"
  useVoucherButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const voucherCode = this.getAttribute("data-code");
      voucherInput.value = voucherCode; // Gán mã giảm giá vào ô nhập
      alert(`Mã giảm giá "${voucherCode}" đã được chọn.`);
    });
  });
  document.querySelectorAll('input[name="payment"]').forEach((radio) => {
    radio.addEventListener("change", function () {
      document.getElementById("payment-method").value = this.id;
    });
  });
});
