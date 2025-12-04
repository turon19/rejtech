const productBtn = document.querySelectorAll(".admin-product-btn");
const productModal = document.querySelector(".admin-product-modal");

productBtn.forEach((btn) => {
  btn.addEventListener("click", () => {
    productModal.classList.toggle("show");
  });
});

const editBtn = document.querySelectorAll(".admin-edit-btn");
const editProductModal = document.querySelector(".admin-editProduct-modal");
const inputProductName = document.querySelector(".admin-edit-productName");
const inputProductPrice = document.querySelector(".admin-edit-productPrice");
const inputProductStock = document.querySelector(".admin-edit-productStock");
const inputProductDescription = document.querySelector(
  ".admin-edit-productDescription"
);
const selectProductCategory = document.querySelector(
  ".admin-edit-productCategory"
);
const selectProductStatus = document.querySelector(".admin-edit-productStatus");
const inputProductId = document.querySelector(".admin-edit-productId");

editBtn.forEach((btn) => {
  btn.addEventListener("click", () => {
    inputProductName.value = btn.dataset.name;
    inputProductPrice.value = btn.dataset.price;
    inputProductStock.value = btn.dataset.stock;
    inputProductDescription.value = btn.dataset.description;
    selectProductCategory.value = btn.dataset.category;
    selectProductStatus.value = btn.dataset.status;
    inputProductId.value = btn.dataset.id;

    editProductModal.classList.toggle("show");
  });
});

const selectCategory = document.querySelector(".admin-select-category");
const selectCategoryForm = document.querySelector(".admin-selectCategory-form");

selectCategory.addEventListener("change", () => {
  selectCategoryForm.submit();
});
