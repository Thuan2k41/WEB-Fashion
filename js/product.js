document.addEventListener("DOMContentLoaded", function () {
  // --- KHAI BÁO TẤT CẢ DOM ELEMENTS ---
  const quantityInput = document.querySelector(".quantity-input");
  const quantityHidden = document.querySelector(".quantity-hidden");
  const decreaseButton = document.querySelector(".quantity-decrease");
  const increaseButton = document.querySelector(".quantity-increase");
  const sizeButtons = document.querySelectorAll(".size-btn");
  const selectedSizeInput = document.getElementById("selected-size");
  const selectedSizeNameInput = document.getElementById("selected-size-name");
  const buyForm = document.querySelector("form");
  const addToCartBtn = document.getElementById("add-to-cart");

  // --- HÀM XỬ LÝ SỐ LƯỢNG ---
  const updateQuantity = (newQuantity) => {
    if (quantityInput) {
      quantityInput.value = newQuantity;
      if (quantityHidden) quantityHidden.value = newQuantity;
    }
  };

  // --- XỬ LÝ SỐ LƯỢNG ---
  if (decreaseButton && increaseButton && quantityInput) {
    decreaseButton.addEventListener("click", () => {
      let currentValue = parseInt(quantityInput.value) || 1;
      if (currentValue > 1) {
        updateQuantity(currentValue - 1);
      }
    });

    increaseButton.addEventListener("click", () => {
      let currentValue = parseInt(quantityInput.value) || 1;
      updateQuantity(currentValue + 1);
    });

    quantityInput.addEventListener("input", () => {
      let currentValue = parseInt(quantityInput.value) || 1;
      if (currentValue < 1) currentValue = 1;
      updateQuantity(currentValue);
    });
  }

  // --- HÀM XỬ LÝ CHỌN KÍCH THƯỚC ---
  const handleSizeSelection = (button) => {
    sizeButtons.forEach((btn) => btn.classList.remove("active"));
    button.classList.add("active");

    if (selectedSizeInput && selectedSizeNameInput) {
      selectedSizeInput.value = button.getAttribute("data-size-id");
      selectedSizeNameInput.value = button.textContent.trim();
      console.log("Đã chọn kích thước:", button.textContent.trim());
    }
  };

  // --- XỬ LÝ CHỌN KÍCH THƯỚC ---
  if (sizeButtons.length > 0) {
    sizeButtons.forEach((button) => {
      button.addEventListener("click", function () {
        handleSizeSelection(this);
      });
    });
  }

  // --- THÊM MODAL THÔNG BÁO ---
  const modalHTML = `
    <div class="modal fade" id="sizeModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Thông báo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Vui lòng chọn kích thước trước khi mua hàng!
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đã hiểu</button>
          </div>
        </div>
      </div>
    </div>
  `;
  document.body.insertAdjacentHTML("beforeend", modalHTML);

  // --- HÀM HIỂN THỊ MODAL ---
  const showModal = (modalId) => {
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();
  };

  // --- XỬ LÝ FORM MUA HÀNG ---
  if (buyForm) {
    buyForm.addEventListener("submit", (e) => {
      if (selectedSizeInput && !selectedSizeInput.value) {
        e.preventDefault();
        showModal("sizeModal");
      }
    });
  }

  // --- HÀM GỬI REQUEST AJAX ---
  const addToCart = (data) => {
    fetch("../handlers/add_to_cart.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: new URLSearchParams(data),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          const toast = new bootstrap.Toast(
            document.getElementById("addToCartToast")
          );
          toast.show();
        } else {
          alert("Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.");
        }
      })
      .catch((error) => {
        console.error("Lỗi:", error);
        alert("Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.");
      });
  };

  // --- XỬ LÝ NÚT THÊM VÀO GIỎ ---
  if (addToCartBtn) {
    addToCartBtn.addEventListener("click", function () {
      if (selectedSizeInput && !selectedSizeInput.value) {
        showModal("sizeModal");
        return;
      }

      const productId = this.getAttribute("data-product-id");
      const productName = this.getAttribute("data-product-name");
      const productPrice = this.getAttribute("data-product-price");
      const productImage = this.getAttribute("data-product-image");
      const sizeId = selectedSizeInput ? selectedSizeInput.value : "";
      const sizeName = selectedSizeNameInput ? selectedSizeNameInput.value : "";
      const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;

      addToCart({
        product_id: productId,
        product_name: productName,
        product_price: productPrice,
        product_image: productImage,
        size_id: sizeId,
        size_name: sizeName,
        quantity: quantity,
      });
    });
  }
});
