const statusBtn = document.querySelectorAll(".admin-status-btn");

statusBtn.forEach((btn) => {
  btn.addEventListener("click", () => {
    const statusList = btn.nextElementSibling;
    statusList.classList.toggle("show");
  });
});

const orderDetailsBtn = document.querySelectorAll(".admin-orderDetails-btn");
const orderDetails = document.querySelector(".admin-orderDetails");
const orderNumber = document.querySelector(".admin-orderNumber");
const orderItemsTotalPrice = document.querySelector(
  ".admin-orderItemsTotalPrice"
);
const orderDate = document.querySelector(".admin-orderDate");
const orderStatus = document.querySelector(".admin-orderStatus");
const orderCustomer = document.querySelector(".admin-orderCustomer");
const orderContact = document.querySelector(".admin-orderContact");
const orderAddress = document.querySelector(".admin-orderAddress");
const orderTotalPrice = document.querySelectorAll(".admin-orderTotalPrice");

orderDetailsBtn.forEach((btn) => {
  btn.addEventListener("click", () => {
    const orderNo = btn.dataset.id;
    orderNumber.innerHTML = `Order Number: #${orderNo}`;
    orderDate.innerHTML = btn.dataset.created;
    orderStatus.innerHTML = btn.dataset.status;
    orderCustomer.innerHTML = btn.dataset.customer;
    orderContact.innerHTML = btn.dataset.contact;
    orderAddress.innerHTML = btn.dataset.address;
    orderTotalPrice.forEach((order) => {
      order.innerHTML = btn.dataset.totalPrice;
    });

    orderDetails.classList.toggle("show");
  });
});

const selectStatus = document.querySelector(".admin-select-status");
const selectStatusForm = document.querySelector(".admin-selectStatus-form");

selectStatus.addEventListener("change", () => {
  selectStatusForm.submit();
});

orderDetailsBtn.forEach((btn) => {
  btn.addEventListener("click", async () => {
    const itemsContainer = document.querySelector(".admin-itemsContainer");
    const orderId = btn.dataset.id;

    const res = await fetch("getProductItems.php", {
      method: "POST",
      headers: { "Content-type": "application/x-www-form-urlencoded" },
      body: `orderId=${orderId}`,
    });

    const data = await res.json();

    itemsContainer.innerHTML = "";
    data.forEach((item) => {
      const totalPrice = Number(item.order_totalPrice).toLocaleString("en-PH", {
        style: "currency",
        currency: "PHP",
      });
      itemsContainer.innerHTML += `
        <div class="flex items-center gap-[12px] justify-between">
                    <div class="flex gap-[12px] items-center">
                        <div class="aspect-square">
                            <img src="assets/images/uploads/${item.product_img1}" alt="${item.product_name}" class="h-[72px] w-[72px] object-cover">
                        </div>
                        <span class="flex flex-1 max-h-[48px] overflow-hidden">${item.product_name}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="flex items-center justify-end">x${item.order_quantity}</span>
                        <span class="flex items-center justify-center">${totalPrice}</span>
                    </div>
                </div>`;
    });
  });
});
