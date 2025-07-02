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

// address
document.addEventListener("DOMContentLoaded", function () {
  const provinceSelect = document.getElementById("province");
  const districtSelect = document.getElementById("district");
  const wardSelect = document.getElementById("ward");
  const provinceCodeInput = document.getElementById("province_code");
  const districtCodeInput = document.getElementById("district_code");

  let provinceMap = {}; // key = name, value = code
  let districtMap = {};

  // Load danh sách tỉnh
  fetch("https://provinces.open-api.vn/api/?depth=1")
    .then((res) => res.json())
    .then((data) => {
      data.forEach((province) => {
        provinceMap[province.name] = province.code;
        provinceSelect.innerHTML += `<option value="${province.name}">${province.name}</option>`;
      });
    });

  // Chọn tỉnh
  provinceSelect.addEventListener("change", () => {
    const provinceName = provinceSelect.value;
    const provinceCode = provinceMap[provinceName];
    provinceCodeInput.value = provinceCode;

    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';

    if (!provinceCode) return;

    fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
      .then((res) => res.json())
      .then((data) => {
        data.districts.forEach((district) => {
          districtMap[district.name] = district.code;
          districtSelect.innerHTML += `<option value="${district.name}">${district.name}</option>`;
        });
      });
  });

  // Chọn quận
  districtSelect.addEventListener("change", () => {
    const districtName = districtSelect.value;
    const districtCode = districtMap[districtName];
    districtCodeInput.value = districtCode;

    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    if (!districtCode) return;

    fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
      .then((res) => res.json())
      .then((data) => {
        data.wards.forEach((w) => {
          wardSelect.innerHTML += `<option value="${w.name}">${w.name}</option>`;
        });
      });
  });
});
